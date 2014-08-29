<?php
namespace RestUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use RestUserBundle\Entity\User;
use RestUserBundle\Form\UserType;
use RestUserBundle\Entity\Group;
use RestUserBundle\Form\GroupType;

class GroupController extends FOSRestController  implements ClassResourceInterface
{
      
    /**
     * curl -i -X OPTIONS http://localhost/api/web/v1/groups/dummy
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
     * curl -i -H "Accept: application/json" -X GET http://localhost/api/web/v1/groups
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
    
    	$entities = $em->getRepository('RestUserBundle:Group')->findAll();

    	return $entities;
    }
    
    /**
     * curl -i -H "Accept: application/json" -X GET http://localhost/api/web/v1/groups/{id}
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
    
    	$entity = $em->getRepository('RestUserBundle:Group')->find($id);
    
    	if (! is_object($entity)) {
    		throw $this->createNotFoundException();
    	}
    	return $entity;
    }
    
    /**
     * curl -i -H "Content-Type: application/json" -H "Accept: application/json" 
     * -X POST -d '{"group":{"name":"Building #1 Users ","description":"Rest users group",
     * "roles":"ROLE_USER,ROLE_DASHBOARD"}}'
     * http://localhost/api/web/v1/groups/dummy
     * @ApiDoc(
     *  description="Creates a new entity ",
     *  parameters={
     *      
     *  },
     *  statusCodes={
     *      201="Created - Returned when successful",
     *  }
     * )
     */
    public function postAction(Request $request, $dummy = null)
    {
    	$entity = new Group();
    
    	//https://github.com/FriendsOfSymfony/FOSRestBundle/issues/433
    	$form = $this->createForm(new GroupType(), $entity);
    	$form->submit($request);
    
    	if ($form->isValid()) {
    		$em = $this->getDoctrine()->getManager('user');
    		$entity->setGuid(self::GUID());
    		
    		$em->persist($entity);
    		$em->flush();
    
    		return $this->redirectView(
    				$this->generateUrl('v1_get_group',array('id' => $entity->getId())),
    				Codes::HTTP_CREATED
    		);
    	}
    
    	return array('form' => $form,);
    }
    
    /**
     * curl -i -H "Accept: application/json" -X DELETE http://localhost/api/web/v1/groups/{id}
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
    	//TODO 
    	//check if there are any users is this group
    	
    	$em = $this->getDoctrine()->getManager('user');

    	$entity = $em->getRepository('RestUserBundle:Group')->find($id);

    	if (!is_object($entity)) {
    		throw $this->createNotFoundException();
    	}
    
    	$em->remove($entity);
    	$em->flush();
    
    	return $this->view(null, Codes::HTTP_NO_CONTENT);
    }
    
    /**
     * curl -i -H "Content-Type: application/json" 
     * -X PUT -d '{"group":{"name":"Rest #1 users ","description":"Rest #1 users group", 
     * "roles":"ROLE_USER,ROLE_DASHBOARD,ROLE_PRIMARY"}}' 
     * http://localhost/api/web/v1/groups/{id}
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
    public function putAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager('user');
    
    	$entity = $em->getRepository('RestUserBundle:Group')->find($id);
    
    	if (!is_object($entity)) {
    		throw $this->createNotFoundException();
    	}
    
    	$form = $this->createForm(new GroupType(), $entity);
    	$form->submit($request);
    
    	if ($form->isValid()) {
    		$em->persist($entity);
    		$em->flush();
    
    		return $this->view(null, Codes::HTTP_NO_CONTENT);
    	}
    
    	return array('form' => $form,);
    	 
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
