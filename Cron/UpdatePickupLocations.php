<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2022 Hippiemonkeys Web Intelligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ShippingTrackCronjob
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ShippingTrackCronjob\Cron;

    use Hippiemonkeys\Core\Api\Helper\ConfigInterface,
        Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface;

    class UpdatePickupLocations
    {
        /**
         * Constructor
         *
         * @access public
         *
         * @param \Hippiemonkeys\Core\Api\Helper\ConfigInterface $config
         * @param \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface $pickupLocationManagement
         */
        public function __construct(
            ConfigInterface $config,
            PickupLocationManagementInterface $pickupLocationManagement
        )
        {
            $this->_config = $config;
            $this->_pickupLocationManagement = $pickupLocationManagement;
        }

        /**
         * @inheritdoc
         */
        public function execute(): void
        {
            if($this->getIsActive())
            {
                $this->getPickupLocationManagement()->updatePickupLocations();
            }
        }

        /**
         * Shipment Track Management property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface $_pickupLocationManagement
         */
        private $_pickupLocationManagement;

        /**
         * Gets Update Histories
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface
         */
        protected function getPickupLocationManagement(): PickupLocationManagementInterface
        {
            return $this->_pickupLocationManagement;
        }

        /**
         * Gets Is Active Flag
         *
         * @access protected
         *
         * @return bool
         */
        protected function getIsActive(): bool
        {
            return $this->getConfig()->getIsActive();
        }

        /**
         * Config property
         *
         * @access private
         *
         * @var \Hippiemonkeys\Core\Api\Helper\ConfigInterface $_config
         */
        private $_config;

        /**
         * Gets Config
         *
         * @access protected
         *
         * @return \Hippiemonkeys\Core\Api\Helper\ConfigInterface
         */
        protected function getConfig(): ConfigInterface
        {
            return $this->_config;
        }
    }
?>