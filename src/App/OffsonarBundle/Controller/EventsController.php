<?php

namespace App\OffsonarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

/**
 * Rest controller for events
 * 
 * @package App\OffsonarBundle\Controller
 * @author Nadir Zenith <2cb.md2@gmail.com>
 */
class EventsController extends FOSRestController
{

    /**
     * List all events.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing events.")
     * Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many events to return.")
     * @Annotations\QueryParam(name="day", description="How many events to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getEventsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        /* $repository = $this->getDoctrine()->getRepository('AppOffsonarBundle:Event'); */
        /* $events = $repository->findAll(); */
        $day = $paramFetcher->get('day');

        if ($day) {
            $events = $this->getAllEventsByDay($day);
        } else {

            $events = $this->getAllEvents();
        }

        /* d($events); */
        $view = new View($events);

        return $view;
    }

    /**
     * Get a single event.
     *
     * @ApiDoc(
     *   output = "App/OffsonarBundle/Entity/Event",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="note")
     *
     * @param Request $request the request object
     * @param int     $id      the note id
     *
     * @return array
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function getEventAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppOffsonarBundle:Event');

        $event = $this->getRestEvent($repository->find($id));

        if (false === $event) {
            throw $this->createNotFoundException("Event does not exist.");
        }

        $view = new View($event);

        return $view;
    }

    private function getAllEvents()
    {
        $repository = $this->getDoctrine()->getRepository('AppOffsonarBundle:Event');
        $events = $repository->findAll();

        return $this->getRestEvents($events);
    }

    private function getAllEventsByDay($day)
    {
        $repository = $this->getDoctrine()->getRepository('AppOffsonarBundle:Event');

        $begin_date = \DateTime::CreateFromFormat("d/m/Y", $day);

        if (!$begin_date) {
            return array();
        }

        $begin_date->setTime(0, 0, 0);

        $end_date = clone $begin_date;
        $end_date->modify('+1 day');

        /* $end_date->modify('+5 hour'); */
        /*
          d($begin_date);
          d($end_date);
         */


        $query = $repository->createQueryBuilder('e')
            ->where('e.begin_date BETWEEN :begin_date AND :end_date')
            ->setParameter('begin_date', $begin_date)
            ->setParameter('end_date', $end_date)
            ->orderBy('e.begin_date', 'ASC')
            ->getQuery();

        $events = $query->getResult();

        return $this->getRestEvents($events);
    }

    private function getRestEvents($events)
    {

        foreach ($events as $event) {
            $event = $this->getRestEvent($event);
        }

        return $events;
    }

    private function getRestEvent($event)
    {
        if (!$event) {
            return null;
        }

        $flyer = $event->getFlyer();
        if ($flyer) {
            /*
              d($flyer);
              d($flyer->getContext());
              d($flyer->getBinaryContent());
             */
            $provider_name = $flyer->getProviderName();
            /* d($provider_name); */
            $provider = $this->get($provider_name);
            /* d($provider); */
            $url = [];
            $url['small'] = $provider->generatePublicUrl($flyer, 'default_small');
            $url['big'] = $provider->generatePublicUrl($flyer, 'default_big');
            $event->setFlyerUrls($url);
            /* d($event); */
            /* break; */
        }
        $event->setContent(strip_tags($event->getContent()));

        return $event;
    }
}
