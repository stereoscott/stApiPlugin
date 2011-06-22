<?php

interface stApiSubscriber
{
  public function updateMemberUsingCustomer();
  
  public function requiresAuth();
  
  public function upgrade($shoppingCart);
}