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

    // Si el comando contiene alguna de estas palabras, se marca riesgo ALTO.
    'palabras_riesgo_alto' => [
        'rm ',
        'del ',
        'erase ',
        'rd /s',
        'rmdir',
        'remove-item',
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

    // Patrones que se rechazan siempre: permiten ejecutar código arbitrario
    // aunque el primer token esté en la lista blanca.
    'patrones_prohibidos' => [
        'php -r',
        'python -c',
        'docker run',
        'docker exec',
        'git -c',
        'git config',
    ],

];
