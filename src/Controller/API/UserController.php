<?php
namespace App\Controller\API;

use App\Entity\User;
use Matrix\Exception;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
	/**
	 * @Route("/api/getAuthToken", name="api_get_authToken")
	 */
	public function apiGetAuthTokenAction(Request $request){
		$user           = $this->getUser();
		$response       = array( 'status' => 0 );
		if( $user ){
			try{
				$apiToken           = sha1(random_bytes(12));
				$user->setApiToken($apiToken);
				$entityManager      = $this->getDoctrine()->getManager();
				$entityManager->persist( $user );
				$entityManager->flush();
				$response['status'] = 1;
				$response['data']   = array(
					'auth_token' => $apiToken
				);
			}catch ( Exception $exception ){
			}
		}else{
		}

		return new JsonResponse( $response, Response::HTTP_OK );
	}
	/**
	 * @Route("/api/userlist", name="api_get_userlist")
	 */
	public function apiGetUsersAction(Request $request){
		$users = $this->getDoctrine()->getRepository('App\Entity\User')->findAll(Query::HYDRATE_ARRAY);
//		$query = $this->getDoctrine()
//		              ->getRepository('App\Entity\User')
//		              ->createQueryBuilder('c')
//		              ->getQuery();
//		$users = $query->getResult(Query::HYDRATE_ARRAY);

		$usersSanitizedArr = array();
		foreach ( $users AS $user ){
			$userSanitizedArr = array(
				'id' => $user->getId(),
				'name' => $user->getName(),
				'username' => $user->getUsername(),
				'password' => base64_decode( $user->getPassword() ),
				'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
				'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
			);
			$usersSanitizedArr[] = $userSanitizedArr;
		}

		return new JsonResponse( array( 'total' => sizeof( $users ), 'users' => $usersSanitizedArr ), Response::HTTP_OK );
	}
//	/**
//	 * @Route("/api/user/{user_id}", name="api_get_user")
//	 */
//	public function apiGetUserAction(Request $request, $user_id){
//		$user = $this->getDoctrine()->getRepository('App\Entity\User')->find($user_id);
//	}
	/**
	 * @Route("/api/user/update/{user_id}", name="api_update_user")
	 */
	public function apiUpdateUserAction(Request $request, $user_id){
		$manager = $this->getDoctrine()->getManager();
		$user = $this->getDoctrine()->getRepository('App\Entity\User')->find($user_id);
		$user->setName($request->get('name'));
		$user->setUsername($request->get('username'));
		$user->setPassword( base64_encode($request->get('password')) );
		$user->setCreatedAt( new \DateTime() );
		$user->setUpdatedAt( new \DateTime() );
		$manager->persist( $user );
		$manager->flush();
		$userInfo = array(
			'id' => $user->getId(),
			'name' => $user->getName(),
			'username' => $user->getUsername(),
			'password' => base64_decode( $user->getPassword() ),
			'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
			'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
		);
		return new JsonResponse( array( 'status' => 1, 'data' => array( 'user' => $userInfo ) ), Response::HTTP_OK );
	}
	/**
	 * @Route("/api/user/create", name="api_create_user")
	 */
	public function apiInsertUserAction(Request $request){
		$manager = $this->getDoctrine()->getManager();
		$user = new User();
		$user->setName($request->get('name'));
		$user->setUsername($request->get('username'));
		$user->setPassword( base64_encode($request->get('password')) );
		$user->setCreatedAt( new \DateTime() );
		$user->setUpdatedAt( new \DateTime() );
		$manager->persist( $user );
		$manager->flush();
		$userInfo = array(
			'id' => $user->getId(),
			'name' => $user->getName(),
			'username' => $user->getUsername(),
			'password' => base64_decode( $user->getPassword() ),
			'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
			'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
		);
		return new JsonResponse( array( 'status' => 1, 'data' => array( 'user' => $userInfo ) ), Response::HTTP_OK );
	}
	/**
	 * @Route("/api/user/login", name="api_login_user")
	 */
	public function apiLoginUserAction(Request $request){
		$manager = $this->getDoctrine()->getManager();
		$user = $this->getDoctrine()->getRepository('App\Entity\User')->findOneBy( array( 'username' => $request->get('username') ));

		if( $user ){
			if( $user->getPassword() == base64_encode($request->get('password')) ){
				return new JsonResponse( array( 'authorized' => true,  ), Response::HTTP_OK );
			}else{
				return new JsonResponse( array( 'authorized' => false,  ), Response::HTTP_OK );
			}
		}else{
			return new JsonResponse( array( 'authorized' => false,  ), Response::HTTP_OK );
		}
	}
}