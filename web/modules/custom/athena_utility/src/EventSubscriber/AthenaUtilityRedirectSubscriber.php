<?php

namespace Drupal\athena_utility\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\athena_utility\AthenaUtility;
use Drupal\athena_utility\AthenaLeagueUtility;

/**
 * Class AthenaUtilityRedirectSubscriber.
 *
 * @package Drupal\athena_utility\EventSubscriber
 */
class AthenaUtilityRedirectSubscriber implements EventSubscriberInterface {

  /**
   * An instance of the Athena Utility.
   *
   * @var \Drupal\athena_utility\AthenaUtility
   */
  protected $athenaUtility;

  /**
   * An instance of the Athena League Utility.
   *
   * @var \Drupal\athena_utility\AthenaLeagueUtility
   */
  protected $leagueUtility;

  /**
   * AthenaUtilityRedirectSubscriber constructor.
   *
   * @param \Drupal\athena_utility\AthenaUtility $athenaUtility
   *   An instance of the Athena Utility.
   * @param \Drupal\athena_utility\AthenaLeagueUtility $leagueUtility
   *   An instance of the Athena League Utility.
   */
  public function __construct(AthenaUtility $athenaUtility, AthenaLeagueUtility $leagueUtility) {
    $this->athenaUtility = $athenaUtility;
    $this->leagueUtility = $leagueUtility;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('athena_utility.utility'),
      $container->get('athena_utility.league_utility')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return([
      KernelEvents::REQUEST => [
        ['redirectAuthenticatedUsers'],
      ],
    ]);
  }

  /**
   * Redirects any unauthorized requests by authenticated users.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The event.
   */
  public function redirectAuthenticatedUsers(GetResponseEvent $event) {
    $request = $event->getRequest();
    $current_route = $request->attributes->get('_route');
    $current_user = $this->athenaUtility->loadUser($this->athenaUtility->getCurrentUser()->id());
    $summoner_name = $this->leagueUtility->getSummonerName($current_user->id());

    $authenticated_no_summoner_allowed_routes = [
      'system.403',
      'system.404',
      'user.logout',
      'user.page',
      'user.reset',
      'user.reset.login',
      'user.reset.form',
      'user.pass',
      'entity.user.canonical',
      'entity.user.edit_form',
      'athena_utility.force_summoner_name',
    ];

    $authenticated_allowed_routes = [
      'athena_utility.front_page',
    ];

    $roles = $current_user->getRoles();

    if (!in_array('administrator', $roles) && $current_user->isAuthenticated()) {
      if ($summoner_name != NULL && $current_route == 'athena_utility.force_summoner_name') {
        $redirect_url = $this->athenaUtility->getUrlFromRoute('<front>');
        $response = new RedirectResponse($redirect_url, 301);
        $event->setResponse($response);
        return;
      }

      if ($summoner_name == NULL && !in_array($current_route, $authenticated_no_summoner_allowed_routes)) {
        $redirect_url = $this->athenaUtility->getUrlFromRoute('athena_utility.force_summoner_name');
        $response = new RedirectResponse($redirect_url, 301);
        $event->setResponse($response);
        return;
      }

      if ($summoner_name != NULL && (in_array($current_route, $authenticated_allowed_routes) || in_array($current_route, $authenticated_no_summoner_allowed_routes))) {
        return;
      }
      elseif ($summoner_name != NULL && (!in_array($current_route, $authenticated_allowed_routes) && !in_array($current_route, $authenticated_no_summoner_allowed_routes))) {
        $redirect_url = $this->athenaUtility->getUrlFromRoute('<front>');
        $response = new RedirectResponse($redirect_url, 301);
        $event->setResponse($response);
        return;
      }
    }
    else {
      return;
    }
  }

}
