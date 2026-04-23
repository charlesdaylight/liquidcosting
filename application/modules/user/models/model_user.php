<?php
/**
 * User model
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: $Id: model_user.php, v2.00 2013-11-30 02:52:40 Softdiscover $
 * @link      https://php-cost-estimator.zigaform.com/
 */

/**
 * User model
 *
 * @category  PHP
 * @package   PHP_Form_Builder
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://php-cost-estimator.zigaform.com/
 */
class model_user extends CI_Model
{


    public $table = '';

    /**
     * model_user::__construct()
     *
     * @return
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->dbprefix . 'cest_uiform_user';
    }

    /**
     * model_user::getList()
     * Get list user
     *
     * @return array
     */
    public function getList()
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        return $this->db->get()->result();
    }

    /**
     * model_user::getUserById()
     * Get user by id
     *
     * @param int $id_user id of user
     *
     * @return array
     */
    public function getUserById($id_user)
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->order_by('c.use_id', 'desc');
        $this->db->where(array( 'c.use_id' => $id_user ), 1);
        return $this->db->get()->row();
    }

    /**
     * model_user::getTotalForms()
     * List total rows
     *
     * @return array
     */
    public function getTotalList()
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('{PRE}cest_uiform_user');
        return $this->db->get()->row();
    }

      /**
       * model_user::getUserById()
       * Get user by id
       *
       * @param int $id_user id of user
       *
       * @return array
       */
    public function getFirstUser()
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->order_by('c.use_id', 'desc');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    /**
     * model_user::getUserById()
     * Get user by id
     *
     * @param int $id_user id of user
     *
     * @return array
     */
    public function getPasswordToken($token)
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->order_by('c.use_id', 'desc');
        $this->db->where(array( 'c.use_password_token' => $token ));
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function getUserByUsername($username)
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->where(array('c.use_login' => $username));
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function getUserByEmail($email)
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->where(array('c.use_mail' => $email));
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function authenticateLiquidcpUser($username, $password)
    {
        $this->db->select('c.*');
        $this->db->from('{PRE}cest_uiform_user c');
        $this->db->where(array(
            'c.use_login' => $username,
            'c.use_password' => md5($password),
            'c.flag_status' => 1,
        ));
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    public function createLiquidcpUser($data)
    {
        $record = array(
            'use_login' => $data['use_login'],
            'use_mail' => $data['use_mail'],
            'use_password' => md5($data['use_password']),
            'use_role' => ! empty($data['use_role']) ? $data['use_role'] : 'user',
            'flag_status' => 1,
            'created_ip' => ! empty($data['created_ip']) ? $data['created_ip'] : null,
            'updated_ip' => ! empty($data['updated_ip']) ? $data['updated_ip'] : null,
            'created_by' => ! empty($data['created_by']) ? $data['created_by'] : 'self-service',
            'updated_by' => ! empty($data['updated_by']) ? $data['updated_by'] : 'self-service',
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
        );

        $this->db->insert($this->table, $record);

        return $this->getUserById((int) $this->db->insert_id());
    }
}
