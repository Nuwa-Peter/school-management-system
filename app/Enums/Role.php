<?php

namespace App\Enums;

enum Role: string
{
    case ROOT = 'root';
    case HEADTEACHER = 'headteacher';
    case BURSAR = 'bursar';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
    case PARENT = 'parent';
}
