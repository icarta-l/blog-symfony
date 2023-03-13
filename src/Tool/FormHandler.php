<?php

namespace App\Tool;

use Symfony\Component\Form\FormInterface;
use App\Form\Type\PostType;
use Symfony\Component\HttpFoundation\Request;

trait FormHandler
{
    private FormInterface $form;

    /**
	 * Handle form creation, submission and validation in controller
	 */
	public function isFormValidAndSubmitted(Request $request, string $form_class = PostType::class): bool
	{
		$this->form = $this->createForm($form_class)->handleRequest($request);

		return $this->form->isSubmitted() && $this->form->isValid();
	}
}