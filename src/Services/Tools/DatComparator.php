<?php
namespace App\Services\Tools;

class DatComparator
{


    public function  convert($datx)
    {
        if($datx == null): 
            $datx = new \DateTime();
        endif;
       return (date($datx->format('Ymd')));   
    }

    public function  dayOfWeek($dat0)
    {
        return date('w' , $dat0->getTimestamp() ) ;
    }

    public function  isEqual($dat = null, $datref= null)
    {
        $dat0 = $this->convert($dat);
        $datr = $this->convert($datref);

        if($dat0 == $datr):
            return true;
        else:
            return false;
        endif;
    }


   public function  isHigherOrEqual($dat, $datref)
    {
        $dat0 = $this->convert($dat);
        $datr = $this->convert($datref);

        if($dat0 >= $datr):
            return true;
        else:
            return false;
        endif;
    }

    public function  isHigher($dat, $datref)
    {
        $dat0 = $this->convert($dat);
        $datr = $this->convert($datref);

        if($dat0 > $datr):
            return true;
        else:
            return false;
        endif;
    }

    public function  isLowerOrEqual($dat= null, $datref= null)
    {
        $dat0 = $this->convert($dat);
        $datr = $this->convert($datref);

        if($dat0 <= $datr):
            return true;
        else:
            return false;
        endif;
    }

    public function  isLower($dat= null, $datref= null)
    {
        $dat0 = $this->convert($dat);
        $datr = $this->convert($datref);

        if($dat0 < $datr):
            return true;
        else:
            return false;
        endif;
    }

        
}