<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends FOSRestController
{

    private $delta;

    public function getQuestionAction($userId)
    {
        $repo = $this->getUserInfoRepository();
        $userInfo = $repo->find($userId);

        $openedQuestion = null;
        $candidateQuestion = null;
        
        foreach ($userInfo->getQuestions() as $userQuestion) {
            if (!$candidateQuestion && $userQuestion->getStatus() == 2) {
                $candidateQuestion = $userQuestion;
            }
            if ($userQuestion->getStatus() == 3) {
                $openedQuestion = $userQuestion;
                break;
            }
        }
        $question = ($openedQuestion ? $openedQuestion : $candidateQuestion);

        if ($question) {
            if ($question->getStatus() == 2) {
                $seconds = $question->getSeconds() + $this->delta;
                $expires = new \DateTime();
                $interval = \DateInterval::createFromDateString("$seconds seconds");
                $expires->add($interval);
                $data = array(
                    "id" => $question->getId(),
                    "expires" => $expires,
                );
                $backClient = $this->get('laelaps.gearman.client');
                $backClient->doBackground('close_question', serialize($data));

                $question->setStatus(3);
                $question->setStarted(new \DateTime());
                $this->getDoctrine()->getManager()->flush();
            }
        } else {
            $question = array("result" => "fail");
        }
        
        $view = $this->view($question);
        return $this->handleView($view);
    }

    public function getQuestionAnswerAction($questionId, $answer)
    {
        $repo = $this->getQuestionRepository();
        $question = $repo->find($questionId);

        $questionService = $this->get("question.service");


        try {
            $variant = $answer + 1;
            if ($variant == $question->getRightAnswer()) {
                $questionService->success($question);
                $result = array(
                    "result" => "success",
                    "userAnswer" => $variant,
                    "question" => $question, 
                    );
            } else {
                $questionService->fail($question);
                $result = $result = array(
                    "result" => "fail",
                    "userAnswer" => $variant,
                    "question" => $question, 
                    );
            }
        } catch (\Exception $e) {
            $result = array("
                result" => "exception {$e->getMessage()}",
                "userAnswer" => 6,        
                "question" => $question,        
                );
        }

        $view = $this->view($result);
        return $this->handleView($view);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserInfoRepository()
    {
        $namespace = "GalaxyGameBundle:UserInfo";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getQuestionRepository()
    {
        $namespace = "GalaxyGameBundle:Question";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

}