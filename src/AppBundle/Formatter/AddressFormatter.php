<?php

namespace AppBundle\Formatter;

/**
 * Formats addresses.
 */
class AddressFormatter
{
    /**
     * Returns the full formatted (new lines, etc) version
     * of the address from the entity
     *
     * @return string
     */
    public static function full($entity, $hasPostalCode = true) {
        $address = '';

        $addressData = [
            'address_line_1' => $entity->getAddressLine1(),
            'address_line_2' => $entity->getAddressLine2(),
            'municipality' => $entity->getMunicipality(),
            'state' => $entity->getState(),
        ];
        if ($hasPostalCode) {
            $addressData['postal_code'] = $entity->getPostalCode();
        }

        if ( ! empty($addressData['address_line_1'])) {
            $address .= $entity->getAddressLine1();
        }
        if ( ! empty($addressData['address_line_2'])) {
            if ( ! empty($address)) {
                $address .= PHP_EOL;
            }
            $address .= $entity->getAddressLine2();
        }
        if ( ! empty($addressData['municipality'])) {
            if ( ! empty($address)) {
                $address .= PHP_EOL;
            }
            $address .= $entity->getMunicipality();
        }
        if ( ! empty($addressData['state'])) {
            if ( ! empty($address) && empty($addressData['municipality'])) {
                $address .= PHP_EOL;
            }
            if ( ! empty($addressData['municipality'])) {
                $address .= ', ';
            }
            $address .= $entity->getState();
        }
        if ( ! empty($addressData['postal_code'])) {
            if ( ! empty($addressData['municipality']) ||  ! empty($addressData['state'])) {
                $address .= '  ';
            }
            $address .= $entity->getPostalCode();
        }

        return $address;
    }
}