<?php

namespace App\Enums;

enum Estado: string
{
    case ABIERTA = 'abierta';
    case EN_CURSO = 'en_curso';
    case FINALIZADO = 'finalizado';
}