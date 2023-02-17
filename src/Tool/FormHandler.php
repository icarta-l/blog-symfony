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
	public function formIsValidAndSubmitted(Request $request): bool
	{
		$this->form = $this->createForm(PostType::class)->handleRequest($request);

		return $this->form->isSubmitted() && $this->form->isValid();
	}
}