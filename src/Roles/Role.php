<?php

namespace App\Roles;

enum Role: string
{
	case USER = "ROLE_USER";
	case ADMIN = "ROLE_ADMIN";
}