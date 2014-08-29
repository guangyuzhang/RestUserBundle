<?php
namespace RestUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use RestUserBundle\Entity\User;
use RestUserBundle\Form\UserType;
use RestUserBundle\Entity\Group;
use RestUserBundle\Form\GroupType;

class UserController extends FOSRestController  implements ClassResourceInterface
{
      
    /**
     * curl -i -X OPTIONS http://localhost/api/web/v1/users/dummy
     * @ApiDoc(
     *  description="Returns a list of REST resources (with dummy parameter to support DELETE action)",
     *  parameters={
     *      {"name"="dummy", "dataType"="string", "required"=true, "format"="text", "description"="dummy parameter"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *  }
     * )
     */
    public function coptionsAction($dummy = null)
    {    
        //http://stackoverflow.com/questions/12069850
        //https://github.com/FriendsOfSymfony/FOSRestBundle/issues/142
        $response = new Response();
        $response->headers->set('Allow', 'OPTIONS, GET, PUT, DELETE, POST');
        return $response;
    }
    
    /**
     * curl -i -X OPTIONS http://localhost/api/web/v1/machines/{id}/password
     * @ApiDoc(
     *  description="Returns a list of REST resources",
     *  parameters={
     *      {"name"="Id", "dataType"="integer", "required"=true, "format"="ascii string", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *  }
     * )
     */
    public function coptionsPasswordAction($id)
    {
    	//http://stackoverflow.com/questions/12069850
    	//https://github.com/FriendsOfSymfony/FOSRestBundle/issues/142
    	$response = new Response();
    	$response->headers->set('Allow', 'OPTIONS, GET, PUT, DELETE, POST');
    	return $response;
    }
    
    /**
     * curl -i -X OPTIONS http://localhost/api/web/v1/machines/{id}/force
     * @ApiDoc(
     *  description="Returns a list of REST resources ",
     *  parameters={
     *      {"name"="Id", "dataType"="integer", "required"=true, "format"="ascii string", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *  }
     * )
     */
    public function coptionsForceAction($id)
    {
    	//http://stackoverflow.com/questions/12069850
    	//https://github.com/FriendsOfSymfony/FOSRestBundle/issues/142
    	$response = new Response();
    	$response->headers->set('Allow', 'OPTIONS, GET, PUT, DELETE, POST');
    	return $response;
    }
    
    /**
     * curl -i -X OPTIONS http://localhost/api/web/v1/machines/{username}/login
     * @ApiDoc(
     *  description="Returns a list of REST resources",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "format"="ascii string", "description"="user name"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *  }
     * )
     */
    public function coptionsLoginAction($username)
    {
    	//http://stackoverflow.com/questions/12069850
    	//https://github.com/FriendsOfSymfony/FOSRestBundle/issues/142
    	$response = new Response();
    	$response->headers->set('Allow', 'OPTIONS, GET, PUT, DELETE, POST');
    	return $response;
    }

    /**
     * curl -i -H "Accept: application/json" -X GET http://localhost/api/web/v1/users
     * @ApiDoc(
     *  description="Returns a collection of Entity",
     *  statusCodes={
     *      200="OK - Returned when successful",
     *      404="Not Found - Returned when the entity is not found"
     *  }
     * )
     *
     */
    public function cgetAction()
    {
    	$em = $this->getDoctrine()->getManager('user');
    
    	$users = $em->getRepository('RestUserBundle:User')->findAll();
    	
    	if(!is_array($users) || count($users) == 0) {
    		return null;
    	}
    	
    	$groups = $em->getRepository('RestUserBundle:Group')->findAll();
    	if(!is_array($groups) || count($groups) == 0) {
    		throw $this->createNotFoundException();
    	}
    	foreach ($users as $user) {
    		foreach ($groups as $group) {
	    		if($user->getGroupId() == $group->getId()) {
	    			$user->setGroup($group);
	    			break;
	    		}
	    	}
    	}
    	
    	return $users;
    }
    
    
    /**
     * curl -i -H "Accept: application/json" -X GET http://localhost/api/web/v1/users/{id}
     * @ApiDoc(
     *  description="Returns an existing entity",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "format"="text", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *      404="Not Found - Returned when the entity is not found"
     *  }
     * )
     */
    public function getAction($id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    
    	$user = $em->getRepository('RestUserBundle:User')->find($id);
    
    	if (! is_object($user)) {
    		throw $this->createNotFoundException();
    	}
    	
    	$group = $em->getRepository('RestUserBundle:Group')->find($user->getGroupId());
    	if (! is_object($user)) {
    		throw $this->createNotFoundException();
    	}
    	
    	$user->setGroup($group);
    	
    	return $user;
    }
    
    /**
     * curl -i -H "Content-Type: application/json" -H "Accept: application/json"
     * -X POST -d '{"user":{"groupid":1,"username":"mike","fullname":"Mike Gates", "email":"mike.gates@example.com",
     * "phone":"+861012345678","department":"Software","plainPassword":"ChangeThisPasswordNow!"}}'
     * http://localhost/api/web/v1/users/dummy
     * @ApiDoc(
     *  description="Creates a new entity ",
     *  parameters={
     *     {"name"="id", "dataType"="integer", "required"=true, "format"="text", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      201="Created - Returned when successful",
     *      400="Bad request parameter(s)"
     *  }
     * )
     */
    public function postAction(Request $request, $dummy = null)
    {
    	$user = new User();
        $encoder = new Pbkdf2PasswordEncoder();
        
    	//https://github.com/FriendsOfSymfony/FOSRestBundle/issues/433
    	$form = $this->createForm(new UserType(), $user);
    	$form->submit($request);
    
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager('user');
    		//set guid
    		$user->setGuid(self::GUID());
    		//set username canonical
    		$usernameCanonical = self::canonicalize($user->getUsername());
    		$user->setUsernameCanonical($usernameCanonical);
    		//set email canonical
    		$emailCanonical = self::canonicalize($user->getEmail());
    		$user->setEmailCanonical($emailCanonical);
    		
    		//set salt
    		$user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
    		
    		//set password
    		$password = $user->getPlainPassword();
    		if (0 !== strlen($password = $user->getPlainPassword())) {
    			$user->setPassword($encoder->encodePassword($password, $user->getSalt()));
    			$user->eraseCredentials();
    		} else {
    			throw $this->createBadRequestHttpException("The password should not be empty.");
    		}
    		
    		$em->persist($user);
    		$em->flush();
    
    		return $this->redirectView(
    				$this->generateUrl('v1_get_user',array('id' => $user->getId())),
    				Codes::HTTP_CREATED
    		);
    	}
    
    	return array('form' => $form,);
    }
    
    /**
     * curl -i -H "Accept: application/json" -X DELETE http://localhost/api/web/v1/users/{id}
     * @ApiDoc(
     *  description="Deletes an existing entity",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "format"="text", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      204="No Content - Returned when successful",
     *      404="Not Found - Returned when the given entity is not found"
     *  }
     * )
     */
    public function deleteAction($id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    
    	$entity = $em->getRepository('RestUserBundle:User')->find($id);
    
    	if (!is_object($entity)) {
    		throw $this->createNotFoundException();
    	}
    
    	$em->remove($entity);
    	$em->flush();
    
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    }
    
    /**
     * curl -i -H "Content-Type: application/json"
     * -X PUT -d '{"groupid":3,"username":"mike","fullname":"Mike Gates", "email":"mike.gates@example.com",
     * "phone":"+861087654321","department":"Building Efficiency"}'
     * http://localhost/api/web/v1/users/{id}
     * @ApiDoc(
     *  description="Updates an existing entity",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "format"="ascii string", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      204="No Content - Returned when successful",
     *      400="Bad request parameter(s)",
     *      404="Not Found - Return when the given entity is not found"
     *  }
     * )
     */
    public function putAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    	$user = new User();
    	$user = $em->getRepository('RestUserBundle:User')->find($id);
    	
    	if (!is_object($user)) {
    		throw $this->createNotFoundException();
    	}
    
    	//get parameters from the request body
    	$parameters = $request->request->all();
    	if(!array_key_exists("groupid", $parameters)) {
    		throw $this->createBadRequestHttpException("groupid does not exist in the request body");
    	}
    	if(!array_key_exists("username", $parameters)) {
    		throw $this->createBadRequestHttpException("username does not exist in the request body");
    	}
    	if(!array_key_exists("fullname", $parameters)) {
    		throw $this->createBadRequestHttpException("fullname does not exist in the request body");
    	}
    	if(!array_key_exists("email", $parameters)) {
    		throw $this->createBadRequestHttpException("email does not exist in the request body");
    	}
    	if(!array_key_exists("phone", $parameters)) {
    		throw $this->createBadRequestHttpException("phone does not exist in the request body");
    	}
    	if(!array_key_exists("department", $parameters)) {
    		throw $this->createBadRequestHttpException("department does not exist in the request body");
    	}
    	
    	$user->setGroupId($parameters["groupid"]);
    	$user->setUsername($parameters["username"]);
    	$usernameCanonical = self::canonicalize($parameters["username"]);
    	$user->setUsernameCanonical($usernameCanonical);
    	$user->setFullname($parameters["fullname"]);
    	$user->setEmail($parameters["email"]);
    	$emailCanonical = self::canonicalize($parameters["email"]);
    	$user->setEmailCanonical($emailCanonical);    		
    	$user->setPhone($parameters["phone"]);
    	$user->setDepartment($parameters["department"]);
    	
    	$em->persist($user);
    	$em->flush();
    
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    
    }
    
    /**
     * curl -i -H "Content-Type: application/json"
     * -X PUT -d '{"oldpassword":"ChangeThisPasswordNow!","newpassword":"EncodeThisPasswordNow!"}'
     * http://localhost/api/web/v1/users/{id}/password
     * @ApiDoc(
     *  description="Updates an existing entity",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "format"="ascii string", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      204="No Content - Returned when successful",
     *      400="Bad request parameter(s)",
     *      404="Not Found - Return when the given entity is not found"
     *  }
     * )
     */
    public function putPasswordAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    	$user = new User();
    	$user = $em->getRepository('RestUserBundle:User')->find($id);
    	
    	$encoder = new Pbkdf2PasswordEncoder();
    	 
    	if (!is_object($user)) {
    		throw $this->createNotFoundException();
    	}
    
    	//get parameters from the request body
    	$parameters = $request->request->all();
    	if(!array_key_exists("oldpassword", $parameters)) {
    		throw $this->createBadRequestHttpException("oldpassword does not exist in the request body");
    	}
    	
    	if(!array_key_exists("newpassword", $parameters)) {
    		throw $this->createBadRequestHttpException("newpassword does not exist in the request body");
    	}
     	$isValid = $encoder->isPasswordValid($user->getPassword(), $parameters["oldpassword"], $user->getSalt());
    	
    	if(!$isValid) {
    		throw $this->createBadRequestHttpException("oldpassword is not valid");
    	}
    	
    	//set password
    	$password = $parameters["newpassword"];
    	if (0 !== strlen($password)) {
    		$user->setPassword($encoder->encodePassword($password, $user->getSalt()));
    	} else {
    		throw $this->createBadRequestHttpException("The newpassword should not be empty.");
    	}
    	
    	$em->persist($user);
    	$em->flush();
    
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    
    }
    
    /**
     * curl -i -H "Content-Type: application/json"
     * -X PUT -d '{"password":"ChangeThisPasswordNow!"}'
     * http://localhost/api/web/v1/users/{username}/password
     * @ApiDoc(
     *  description="Updates an existing entity",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "format"="ascii string", "description"="username"}
     *  },
     *  statusCodes={
     *      200="OK - Returned when successful",
     *      400="Bad request parameter(s)",
     *      404="Not Found - Return when the given entity is not found"
     *  }
     * )
     */
    public function putLoginAction(Request $request, $username)
    {
    	$em = $this->getDoctrine()->getManager('user');
    	
    	
    	$usernameCanonical = self::canonicalize($username);
    	$user = new User();
    	$user = $em->getRepository('RestUserBundle:User')->findOneBy(array('usernameCanonical'=>$usernameCanonical));

    	if (!is_object($user)) {
    		throw $this->createNotFoundException('username is not found');
    	}
    	
    	$encoder = new Pbkdf2PasswordEncoder();
    
    	//get parameters from the request body
    	$parameters = $request->request->all();
    	if(!array_key_exists("password", $parameters)) {
    		throw $this->createBadRequestHttpException("password does not exist in the request body");
    	}
    	
     	$isValid = $encoder->isPasswordValid($user->getPassword(), $parameters["password"], $user->getSalt());
    	
    	if(!$isValid) {
    		throw $this->createBadRequestHttpException("password is not valid");
    	}
    	
    	$group = $em->getRepository('RestUserBundle:Group')->find($user->getGroupId());
    	if (!is_object($user)) {
    		throw $this->createNotFoundException('group is not found');
    	}
    	
    	$user->setGroup($group);
    	
    	return $user;
    
    }
    
    /**
     * curl -i -H "Content-Type: application/json"
     * -X PUT -d '{"newpassword":"UseThisPasswordNow!"}'
     * http://localhost/api/web/v1/users/{id}/force
     * @ApiDoc(
     *  description="Updates an existing entity",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "format"="ascii string", "description"="entity identifier"}
     *  },
     *  statusCodes={
     *      204="No Content - Returned when successful",
     *      404="Not Found - Return when the given entity is not found"
     *  }
     * )
     */
    public function putForceAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    	$user = new User();
    	$user = $em->getRepository('RestUserBundle:User')->find($id);
    	 
    	$encoder = new Pbkdf2PasswordEncoder();
    
    	if (!is_object($user)) {
    		throw $this->createNotFoundException();
    	}
    
    	//get parameters from the request body
    	$parameters = $request->request->all();
    	 
    	if(!array_key_exists("newpassword", $parameters)) {
    		throw $this->createBadRequestHttpException("password does not exist in the request body");
    	}
    	
    	//set password
    	$password = $parameters["newpassword"];
    	if (0 !== strlen($password)) {
    		$user->setPassword($encoder->encodePassword($password, $user->getSalt()));
    	} else {
    		throw $this->createBadRequestHttpException("The newpassword should not be empty.");
    	}
    	
    	$em->persist($user);
    	$em->flush();
    
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    
    }
    
    /**
     * Returns a BadRequestHttpException.
     *
     * This will result in a 400 response code. Usage example:
     *
     *     throw $this->createBadRequestHttpException('Bad request!');
     *
     * @param string    $message  A message
     * @param \Exception $previous The previous exception
     *
     * @return BadRequestHttpException
     */
    protected function createBadRequestHttpException($message = 'Bad request', \Exception $previous = null)
    {
    	return new BadRequestHttpException($message, $previous);
    }
    
    /**
     * 
     * @param string $string
     * @return Ambigous <NULL, string>
     */
    private function canonicalize($string)
    {
    	return null === $string ? null : mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));
    }
    
    
    /**
     * Generates GUID
     * http://guid.us/GUID/PHP
     */
    public static function GUID() {
    	mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
    	$charid = strtolower(md5(uniqid(rand(), true)));
    	$hyphen = chr(45);// "-"
    	$uuid = substr($charid, 0, 8).$hyphen
    	.substr($charid, 8, 4).$hyphen
    	.substr($charid,12, 4).$hyphen
    	.substr($charid,16, 4).$hyphen
    	.substr($charid,20,12);
    	return $uuid;
    }
   
}
