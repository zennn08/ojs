<?php

namespace APP\plugins\themes\agrobisnis;

use PKP\plugins\ThemePlugin;

class AgrobisnisThemePlugin extends ThemePlugin
{
    public function isActive()
    {
        return true;
    }

    public function init()
    {
        $this->setParent('defaultthemeplugin');
        $this->addStyle('agrobisnis-custom', 'styles/custom.css');
    }

    public function getDisplayName()
    {
        return 'Agribisnis Professional Theme';
    }

    public function getDescription()
    {
        return 'Tema portal agribisnis profesional dengan palet hijau botani dan emas panen, tipografi akademik presisi, dan layout minimalis.';
    }
}
