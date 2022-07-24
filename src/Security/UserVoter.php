<?php

namespace App\Security;

use App\Entity\CouponDiscount;
use App\Entity\Customer;
use App\Entity\Order;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * It grants or denies permissions for actions related to blog posts (such as
 * showing, editing and deleting posts).
 *
 * See https://symfony.com/doc/current/security/voters.html
 *
 * @author Yunior Pantoja <ypguerrero123@gmail.com>
 */
class UserVoter extends Voter
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"

    /** @var string CREATE */
    public const CREATE = 'CREATE';
    /** @var string READ */
    public const READ = 'READ';
    /** @var string UPDATE */
    public const UPDATE = 'UPDATE';
    /** @var string DELETE */
    public const DELETE = 'DELETE';

    /** @var Security $security */
    private $security;

    /**
     * UserVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        // this voter is only executed for three specific permissions
        return \in_array($attribute, [self::CREATE, self::READ, self::UPDATE, self::DELETE], true);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if ($this->security->isGranted('ROLE_CUSTOMER')
            && ($subject instanceof Order
                && $subject->getCustomerId()->getUserIdentifier() == $user->getUserIdentifier())) {
            return true;
        }

        if ($this->security->isGranted('ROLE_CUSTOMER') && $attribute == self::CREATE
            && $subject instanceof CouponDiscount) {
            /** @var Customer $user */
            if (!$user->existCouponDiscount($subject->getNro())) {
                return true;
            }
        }

        return false;

    }
}
