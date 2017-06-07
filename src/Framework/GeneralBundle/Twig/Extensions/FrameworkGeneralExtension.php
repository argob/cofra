<?php
namespace Framework\GeneralBundle\Twig\Extensions;

class FrameworkGeneralExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'str_pad' => new \Twig_Filter_Function('str_pad'),
        );
    }
	
//    public function strPad(array('needs_environment' => true))
//    {
//        $delta = time() - $dateTime->getTimestamp();
//        if ($delta < 0)
//            throw new \Exception("createdAgo is unable to handle dates in the future");
//
//        $duration = "";
//        if ($delta < 60)
//        {
//            // Segundos
//            $time = $delta;
//            $duration = $time . " second" . (($time > 1) ? "s" : "") . " ago";
//        }
//        else if ($delta <= 3600)
//        {
//            // Minutos
//            $time = floor($delta / 60);
//            $duration = $time . " minute" . (($time > 1) ? "s" : "") . " ago";
//        }
//        else if ($delta <= 86400)
//        {
//            // Horas
//            $time = floor($delta / 3600);
//            $duration = $time . " hour" . (($time > 1) ? "s" : "") . " ago";
//        }
//        else
//        {
//            // Días
//            $time = floor($delta / 86400);
//            $duration = $time . " day" . (($time > 1) ? "s" : "") . " ago";
//        }
//
//        return $duration;
//    }

    public function getName()
    {
        return 'framework_general_extension';
    }
}