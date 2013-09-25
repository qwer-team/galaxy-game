<?php

namespace Galaxy\GameBundle\Worker;

use Laelaps\GearmanBundle\Annotation as Gearman;
use Laelaps\GearmanBundle\Worker;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

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
        $output->writeln("start {$data['id']}");
        $expire = $data['expires'];

        $diff = $expire->diff(new \DateTime);
        $seconds = $diff->s + $diff->i * 60;
        $output->writeln("слип {$seconds}");
        if ($seconds > 0) {
            sleep(1);
        }
        
        try {
            $service  = $this->getQuestionService();
            $question = $this->getQuestion($data['id']);
            $service->fail($question);
            $output->writeln("fail {$data['id']} {$seconds}");
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln("closed {$data['id']}");
        }
        return true;
    }

    /**
     * 
     * @return \Galaxy\GameBundle\Service\QuestionService
     */
    private function getQuestionService()
    {
        return $this->get("question.service");
    }

    private function getQuestion($id)
    {
        $entityName = "GalaxyGameBundle:Question";
        $repo = $this->getEntityManager()->getRepository($entityName);

        $question = $repo->find($id);
        return $question;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        return $em;
    }

}