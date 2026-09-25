<?php

namespace App\Enums;

enum Estado: string
{
    case ABIERTO = 'abierto';
    case EN_CURSO = 'en_curso';
    case FINALIZADO = 'finalizado';
}