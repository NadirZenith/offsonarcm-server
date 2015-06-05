<?php

namespace App\OffsonarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller
{
    private $mem;

    public function __construct()
    {
        $this->mem = new \Memcached();
        $this->mem->addServer("127.0.0.1", 11211);
    }

    public function indexAction($name)
    {
        /*
          d($this->mem->get('events_all_martes'));
          d($this->mem->get('events_all_miercoles'));
          d($this->mem->get('events_all_jueves'));
          d($this->mem->get('events_all_viernes'));
          d($this->mem->get('events_all_sabado'));
          d($this->mem->get('events_all_domingo'));
         */

        /*$day = 'domingo';*/
        $martes_events = $this->mem->get('events_all_' . $day);
        $media_manager = $this->get('sonata.media.manager.media');
        $em = $this->getDoctrine()->getManager();
        $evts = [];
        //event day
        foreach ($martes_events as $ev) {
            $ev = json_decode($ev);
            if (isset($ev->post_title)) {
                $event = new \App\OffsonarBundle\Entity\Event();

                $event->setTitle($ev->post_title);
                $event->setContent($ev->post_content);

                //event flyer
                if (isset($ev->image)) {
                    $url_image = $ev->image;
                    $temp_file = tempnam(sys_get_temp_dir(), 'sonar_2015_event_');


                    $file_content = @file_get_contents($url_image);
                    if ($file_content) {
                        file_put_contents($temp_file, $file_content);

                        $file = new \Symfony\Component\HttpFoundation\File\File($temp_file);

                        $media = new \Application\Sonata\MediaBundle\Entity\Media();
                        $media->setBinaryContent($file);
                        $media->setContext('default');
                        $media->setProviderName('sonata.media.provider.image');

                        $media_manager->save($media);
                        $event->setFlyer($media);
                    }
                }

                //event meta
                $meta = $ev->meta;

                $event->setPrice($this->get_value('wpcf-event_price', $meta));
                $event->setPriceConditions($this->get_value('price_conditions', $meta));
                $event->setPromoter($this->get_value('wpcf-event_promoter', $meta));

                $DateTime = new \DateTime();
                $DateTime->setTimestamp($this->get_value('wpcf-event_begin_date', $meta));
                $event->setBeginDate($DateTime);

                $DateTime = new \DateTime();
                $DateTime->setTimestamp($this->get_value('wpcf-event_end_date', $meta));
                $event->setEndDate($DateTime);

                //event place
                if (isset($ev->place)) {

                    $place = $ev->place;
                    $place = json_decode($place);
                    $event->setPlaceName($place->post_title);

                    //event place meta
                    if (isset($place->meta)) {
                        $place_meta = $place->meta;
                        $event->setPlaceAddress($this->get_value('coolplace_mapaddress', $place_meta));

                        $url_image = $place_meta->image;
                        $temp_file = tempnam(sys_get_temp_dir(), 'sonar_2015_place_');
                        $file_content = @file_get_contents($url_image);
                        if ($file_content) {

                            file_put_contents($temp_file, $file_content);
                            $file = new \Symfony\Component\HttpFoundation\File\File($temp_file);

                            $media = new \Application\Sonata\MediaBundle\Entity\Media();
                            $media->setBinaryContent($file);
                            $media->setContext('default');
                            $media->setProviderName('sonata.media.provider.image');

                            $media_manager->save($media);
                            $event->setPlaceImage($media);
                        }
                    } else {
                        $event->setPlaceAddress('see event id => ' . $ev->ID);
                    }
                } else {
                    $event->setPlaceAddress('see event id => ' . $ev->ID);
                    $event->setPlaceName('see event id => ' . $ev->ID);
                }

                /*
                  d($ev);
                  d($meta);
                  d($place);
                  d($event);
                 */


                $em->persist($event);
            }
        }
        //event day

        $em->flush();

        return $this->render('AppOffsonarBundle:Default:index.html.twig', array('name' => $name));
    }

    private function get_value($key, $meta)
    {
        if (isset($meta->$key)) {
            if (is_array($meta->$key)) {
                return array_pop($meta->$key);
                /* return $meta->$key; */
            } else {
                return $meta->$key;
            }
        }
    }

    private function get_site($url, $name)
    {

        $result = $this->mem->get($name);

        if (!$result) {
            $result = file_get_contents($url);
            $this->mem->set($name, $result) or die("Couldn't save anything to memcached...");
        }

        return $result;
    }
}
