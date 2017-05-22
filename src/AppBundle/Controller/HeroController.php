<?php
/**
 * Created by PhpStorm.
 * User: ramkumar
 * Date: 18/5/17
 * Time: 5:56 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Hero;

class HeroController extends FOSRestController
{
    /**
     * @Rest\Get("/hero")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Hero')->findAll();
        if ($restresult === null) {
            return new View("there are no heroes exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/hero/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:Hero')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/hero/")
     */
    public function postAction(Request $request)
    {
        $data = new Hero;
        $name = $request->get('name');
        if(empty($name))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setName($name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new View("Hero Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/hero/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $name = $request->get('name');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:Hero')->find($id);
        if (empty($user)) {
            return new View("Hero not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($name)){
            $user->setName($name);
            $sn->flush();
            return new View("Hero Updated Successfully", Response::HTTP_OK);
        }
        else return new View("Hero name cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }
}