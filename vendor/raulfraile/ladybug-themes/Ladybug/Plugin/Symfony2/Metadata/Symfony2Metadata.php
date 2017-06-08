<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Processor / Standard Object
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Symfony2\Metadata;

use Ladybug\Metadata\MetadataInterface;
use Ladybug\Metadata\AbstractMetadata;
use Ladybug\Model\VariableWrapper;

class Symfony2Metadata extends AbstractMetadata
{

    const ICON = 'symfony2';
    const URL = 'http://api.symfony.com/%version%/index.html?q=%class%';

    public function __construct()
    {
        $this->version = '2.3';

        // determine symfony version
        if (class_exists('Symfony\\Component\\HttpKernel\\Kernel')) {
            $this->version = sprintf('%s.%s', \Symfony\Component\HttpKernel\Kernel::MAJOR_VERSION, \Symfony\Component\HttpKernel\Kernel::MINOR_VERSION);
        }
    }

    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS === $data->getType() && $this->isNamespace($data->getId(), 'Symfony');
    }

    public function get(VariableWrapper $data)
    {
        if ($this->supports($data)) {
            return array(
                'help_link' => $this->generateHelpLinkUrl(self::URL, array(
                    '%version%' => $this->version,
                    '%class%' => urlencode($data->getId())
                )),
                'icon' => self::ICON,
                'version' => $this->version
            );
        }

        return array();
    }

}
