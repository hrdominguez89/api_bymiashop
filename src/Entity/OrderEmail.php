<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderEmailRepository")
 * @ORM\Table("mia_order_email")
 */
class OrderEmail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $orderId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="send_error", type="boolean")
     */
    private $sendError;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_message", type="text", nullable=true)
     */
    private $errorMessage;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->orderId = $order;
        $this->date = new \DateTime();
        $this->sendError = false;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Order
     */
    public function getOrderId(): Order
    {
        return $this->orderId;
    }

    /**
     * @param Order $orderId
     * @return $this
     */
    public function setOrderId(Order $orderId): OrderEmail
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): OrderEmail
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSendError(): ?bool
    {
        return $this->sendError;
    }

    /**
     * @param bool $sendError
     * @return $this
     */
    public function setSendError(bool $sendError): OrderEmail
    {
        $this->sendError = $sendError;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $errorMessage
     * @return $this
     */
    public function setErrorMessage(?string $errorMessage): OrderEmail
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
