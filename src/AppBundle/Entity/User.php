<?php
// src/AppBundle/Entity/User.php
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;
    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    private $lastName;
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone = '';

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    /**
     * @var string
     *
     * @ORM\Column(name="function", type="string", length=255, nullable=true)
     */
    private $function = '';
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     * @Exclude
     */
    private $state = '1';
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     *
     * 1 ==> Passager
     * 2 ==> Diplomatic Shop
     * 3 ==> Manager
     * 4 ==> Super Admin
     */
    private $type = '1';
    /**
     * @var string
     *
     * @ORM\Column(name="consularCard", type="string", length=255, nullable=true)
     */
    private $consularCard = '';
    /**
     * @var boolean
     *
     * @ORM\Column(name="isBbanned", type="boolean", nullable=false)
     * @Exclude
     */

    private $isBanned =false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFake", type="boolean", nullable=false)
     * @Exclude
     */
    private $isFake = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribed", type="boolean", nullable=false)
     * @Exclude
     */
    private $subscribed = false;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=false)
     * @Exclude
     */
    private $createdOn;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true)
     * @Exclude
     */
    private $updatedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastActivityAt", type="datetime", nullable=true)
     * @Exclude
     */
    private $lastActivityAt;

    public function __construct(array $data = null)
    {
        parent::__construct();
        $this->setCreatedOn(new \DateTime('now'));
        if(!is_null($data)){
            $this->hydrate($data);
            $this->setEnabled(true);
            $this->setEmailCanonical($this->getEmail());
            $this->setUsernameCanonical($this->getUsername());
            $this->setPassword($this->getPlainPassword());
            $this->setSubscribed(1);
        }
        $this->groups=new ArrayCollection();
    }
    public function hydrate(array $data){
        foreach ($data as $key => $value){
            if($key == "password") $key = "PlainPassword";
            $method = $this->formatMethodName($key);
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }
    public function formatMethodname($name){
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        $name = 'set'.$name;
        return $name;
    }
    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }
    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }
    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * Set function
     *
     * @param string $function
     *
     * @return User
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }
    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }
    /**
     * Set state
     *
     * @param string $state
     *
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Set consularCard
     *
     * @param string $consularCard
     *
     * @return User
     */
    public function setConsularCard($consularCard)
    {
        $this->consularCard = $consularCard;
        return $this;
    }
    /**
     * Get consularCard
     *
     * @return string
     */
    public function getConsularCard()
    {
        return $this->consularCard;
    }
    /**
     * Set isBanned
     *
     * @param boolean $isBanned
     *
     * @return User
     */
    public function setIsBanned($isBanned)
    {
        $this->isBanned = $isBanned;
        return $this;
    }
    /**
     * Get isBanned
     *
     * @return boolean
     */
    public function getIsBanned()
    {
        return $this->isBanned;
    }
    /**
     * Set isFake
     *
     * @param boolean $isFake
     *
     * @return User
     */
    public function setIsFake($isFake)
    {
        $this->isFake = $isFake;
        return $this;
    }
    /**
     * Get isFake
     *
     * @return boolean
     */
    public function getIsFake()
    {
        return $this->isFake;
    }
    /**
     * Set subscribed
     *
     * @param boolean $isFake
     *
     * @return User
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;
        return $this;
    }
    /**
     * Get subscribed
     *
     * @return boolean
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return User
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }
    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }
    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return User
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }
    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @return mixed
     */
    public function getLastActivityAt()
    {
        return $this->lastActivityAt;
    }

    /**
     * @param mixed $lastActivityAt
     */
    public function setLastActivityAt($lastActivityAt)
    {
        $this->lastActivityAt = $lastActivityAt;
    }

    /**
     * @return Bool Whether the user is active or not
     */
    public function isActiveNow()
    {
        // Delay during wich the user will be considered as still active
        $delay = new \DateTime('1440 minutes ago');

        return ( $this->getLastActivityAt() > $delay );
    }


    /**
     * @param Collection|null $groups
     */
    function setGroups(Collection $groups = null) {
        if ($groups !== null)
            $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function getFamilies()
    {

        $families=array();
        foreach ($this->getGroups() as $group) {
            $families = array_merge($families, $group->getFamilies());
        }
        // we need to make sure to have at least one role
        return array_unique($families);
    }
    /**
     * @return string
     */
    public function __toString() {
        return $this->username;
    }



}