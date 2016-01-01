<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SchoolManagement\Controller;

use Database\Service\EntityManagerService;
use DateTime;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use SchoolManagement\Entity\StudentClass;
use SchoolManagement\Form\StudentClassFilter;
use SchoolManagement\Form\StudentClassForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Description of StudentClassController
 *
 * @author Márcio Dias <marciojr91@gmail.com>
 */
class StudentClassController extends AbstractActionController
{

    use EntityManagerService;

    /**
     * Busca por todas as turmas cadastradas
     * 
     * @return ViewModel (classes, message)
     */
    public function indexAction()
    {


        try {
            $em = $this->getEntityManager();
            $classes = $em->getRepository('SchoolManagement\Entity\StudentClass')->findAll();
            $message = null;
        } catch (Exception $ex) {
            $message = 'Erro inesperado. Entre com contato com o administrador do sistema.<br>' .
                    'Erro: ' . $ex->getMessage();
            $classes = null;
        }


        $this->layout()->toolbar = array(
            'menu' => array(
                array(
                    'url' => '/school-management/student-class/delete/$id',
                    'title' => 'Remover',
                    'description' => 'Permite remover uma turma que ainda não possua alunos',
                    'class' => 'fa fa-trash-o bg-red',
                    'fntype' => 'selectedAjaxClick',
                ),
            ),
        );

        return new ViewModel(array(
            'message' => $message,
            'classes' => $classes,
        ));
    }

    /**
     * Cria uma nova turma
     * @return ViewModel (form, message)
     */
    public function createAction()
    {
        $request = $this->getRequest();
        $message = null;
        $form = new StudentClassForm('Student Class');

        if ($request->isPost()) {

            $data = $request->getPost();
            $form->setData($data);
            $form->setInputFilter(new StudentClassFilter());

            if ($form->isValid()) {
                $data = $form->getData();

                try {
                    $em = $this->getEntityManager();

                    $class = new StudentClass();

                    $class->setClassName($data['class_name'])
                            ->setClassBeginDate(new DateTime($data['class_begin_date']))
                            ->setClassEndDate(new DateTime($data['class_end_date']));

                    $em->persist($class);
                    $em->flush();

                    $this->redirect()->toRoute('school-management/student-class', array('action' => 'index'));
                } catch (Exception $ex) {
                    if ($ex instanceof UniqueConstraintViolationException) {
                        $message = 'já existe uma turma com este nome. Por favor utilize outro.';
                    } else {
                        $message = 'Erro inesperado. Entre com contato com o administrador do sistema.<br>' .
                                'Erro: ' . $ex->getMessage();
                    }
                }
            }
        }

        return new ViewModel(array(
            'message' => $message,
            'form' => $form,
        ));
    }

    /**
     * Remove uma turma
     * 
     * Apenas turmas que ainda não iniciaram as aulas podem ser removidas
     * 
     * @return JsonModel message, mensagem de sucesso ou motivo da falha na remoção
     */
    public function deleteAction()
    {
        $id = $this->params('id', false);

        if ($id) {
            try {
                $em = $this->getEntityManager();

                $today = new DateTime('now');

                $class = $em->getReference('SchoolManagement\Entity\StudentClass', $id);

                if ($today < $class->getClassBeginDate()) {
                    $em->remove($class);
                    $em->flush();
                    $message = 'Turma removida com sucesso.';
                } else {
                    $message = 'Não é possivel remover esta turma. ' .
                            'A data de início das aulas é menor que a data atual.';
                }
            } catch (Exception $ex) {
                if ($ex instanceof ConstraintViolationException) {
                    $message = 'Não é possível remover turmas que possuam alunos associados.';
                } else {
                    $message = 'Erro inesperado. Entre com contato com o administrador do sistema.<br>' .
                            'Erro: ' . $ex->getMessage();
                }
            }
        } else {
            $message = 'Nenhuma turma selecionda.';
        }

        return new JsonModel(array(
            'message' => $message
        ));
    }

}