<?php

namespace Galaxy\GameBundle\Worker;

use Laelaps\GearmanBundle\Annotation as Gearman;
use Laelaps\GearmanBundle\Worker;
use Symfony\Component\Console\Output\OutputInterface;

class QuestionsWorker extends Worker
{
    /**
     * @Gearman\PointOfEntry(name="close_question")
     * @param GearmanJob $job
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return boolean returning false means job failure
     */
    public function doExampleJob(\GearmanJob $job, OutputInterface $output)
    {
        $data = unserialize($job->workload());
        $expire = $data['expires'];
        
        $diff = $expire->diff(new \DateTime);
        if($diff->s > 0){
            $output->writeln("s {$diff->s}");
            sleep($diff->s);
        }
        
        $date = new \DateTime();
        $output->writeln("ok boss {$date->format('H:i:s')}");
        return true;
    }
}