CS<?php
namespace CSD\Marketo\Response;

use CSD\Marketo\Response as Response;

/**
 * Response for the getLead and getLeadByFilterType API method.
 *
 * @author Daniel Chesterton <daniel@chestertondevelopment.com>
 */
class GetLeadResponse extends Response
{
    /**
     * @return array|null
     */
    public function getLead()
    {
        return $this->isSuccess()? $this->getResult()[0]: null;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->isSuccess()? $this->getLead()['id']: null;
    }

    /**
     * Override success function as Marketo incorrectly responds 'success'
     * even if the lead ID does not exist. Overriding it makes it consistent
     * with other API methods such as getList.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return parent::isSuccess()? count($this->getResult()) > 0: false;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        // if it's successful, don't return an error message
        if ($this->isSuccess()) {
            return null;
        }

        // if an error has been returned by Marketo, return that
        if ($error = parent::getError()) {
            return $error;
        }

        // if it's not successful and there's no error from Marketo, create one
        return [
            'code' => '',
            'message' => 'Lead not found'
        ];
    }
}