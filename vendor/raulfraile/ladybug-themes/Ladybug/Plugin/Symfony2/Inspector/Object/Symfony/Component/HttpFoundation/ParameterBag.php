<?php
/*
 * Ladybug: Simple and Extensible PHP Dumper
 *
 * Object/DomDocument dumper
 *
 * (c) Raúl Fraile Beneyto <raulfraile@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ladybug\Plugin\Symfony2\Inspector\Object\Symfony\Component\HttpFoundation;

use Ladybug\Inspector\AbstractInspector;
use Ladybug\Inspector\InspectorInterface;
use Ladybug\Model\VariableWrapper;
use Ladybug\Plugin\Extra\Type\CollectionType;

class ParameterBag extends AbstractInspector
{
    public function supports(VariableWrapper $data)
    {
        return VariableWrapper::TYPE_CLASS == $data->getType() && 'Symfony\Component\HttpFoundation\ParameterBag' === $data->getId();
    }

    public function get(VariableWrapper $data)
    {
        /** @var $var \Symfony\Component\HttpFoundation\ParameterBag */
        $var = $data->getData();

        /** @var $collection CollectionType */
        $collection = $this->extendedTypeFactory->factory('collection', $this->level);

        $collection->setTitle('Bag');

        foreach ($var->all() as $item) {
            $collection->add($this->typeFactory->factory($item, $this->level + 1));
        }

        return $collection;
    }

}
