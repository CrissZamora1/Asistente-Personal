<?php

return [

    // Comandos permitidos. Se valida por el primer "token" del comando.
    'permitidos' => [
        'git',
        'composer',
        'php',
        'npm',
        'npx',
        'docker',
        'python',
        'code', // abrir VS Code
    ],

    // Carpetas donde SÍ se puede ejecutar algo. Todo lo de afuera se rechaza.
    'carpetas_permitidas' => [
        'D:\\proyectos',
    ],

    // Si el comando contiene alguna de estas palabras, se marca riesgo ALTO
    // y exige doble confirmación (aprobar + ejecutar por separado).
    'palabras_riesgo_alto' => [
        'rm ',
        'del ',
        'rmdir',
        'DROP',
        'drop table',
        'migrate:fresh',
        'migrate:reset',
        '--force',
        'git push --force',
        'git reset --hard',
        'format',
        'shutdown',
        'taskkill',
    ],

];
