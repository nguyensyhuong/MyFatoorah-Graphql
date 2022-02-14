<?php
namespace Simi\MyFatoorahGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use MyFatoorah\MyFatoorahPaymentGateway\APi\MFOrderManagementInterface;

class MyFatoorahCheckout implements ResolverInterface
{
    
    /**
     * @var MFOrderManagementInterface
     */
    protected $MFOrderManagementInterface;

    public function __construct(
        MFOrderManagementInterface $MFOrderManagementInterface
    )
    {
        $this->MFOrderManagementInterface = $MFOrderManagementInterface;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['cartId']) || !isset($args['billingAddressId']) || !isset($args['gateway'])||
            empty($args['cartId']) || empty($args['billingAddressId']) || empty($args['gateway']))
        {
            throw new GraphQlInputException(__('Invalid parameter list.'));
        }
        $redirectUrl = $this->MFOrderManagementInterface->checkout($args['cartId'], $args['billingAddressId'], $args['gateway']);
        $output = [];
        $output['urlRedirect'] = $redirectUrl;
        return $output ;
    }
}