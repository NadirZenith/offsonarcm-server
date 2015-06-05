<?php

namespace App\OffsonarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AppController extends Controller
{

    /**
     * @Route("/", name="app_home")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }

    /**
     * @Route("/timechange", name="app_home")
     * @Template()
     */
    public function timeChangeAction()
    {
        /* $repository = $this->getDoctrine()->getRepository('AppOffsonarBundle:Event'); */
        /* $events = $repository->findAll(); */

        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('AppOffsonarBundle:Event');
        $events = $rep->findAll();
        foreach ($events as $event) {
            d($event);

            $interval = new \DateInterval('PT2H');
            /* $event->setTitle('Form Music Showcase@Techno OFF Week'); */

            //begin date
            $begin_date = $event->getBeginDate();
            $new_begin_date = $begin_date->sub($interval);


            d($new_begin_date);
            $event->setBeginDate(clone $new_begin_date);

            //end date
            /*
             */
            $end_date = $event->getEndDate();
            $new_end_date = $end_date->sub($interval);
            $event->setEndDate(clone $new_end_date);

            /* $em->merge($event); */
            $em->persist($event);
            d($event);
            /*break;*/
        }
         /*$em->flush(); */
        d($events);

        return new \Symfony\Component\HttpFoundation\Response('<html><head></head><body>done</body></html>');
    }
}
