[![Build Status](https://travis-ci.org/smartsupp/chat-code-generator.svg)](https://travis-ci.org/smartsupp/chat-code-generator)
[![Coverage Status](https://coveralls.io/repos/smartsupp/chat-code-generator/badge.svg?branch=master&service=github)](https://coveralls.io/github/smartsupp/chat-code-generator?branch=master)

smartsupp/chat-code-generator
===============

## Introduction

This is simple PHP class for SmartSupp.com chat API which helps you to generate chat JavaScript code.

* [More info about Smartsupp CHAT API](https://developers.smartsupp.com/chat/configuration) This is "Get started" doc for chat API.
* [More info about Smartsupp CHAT API - Overview](https://developers.smartsupp.com/chat/overview) This is full documentation for chat API. Note, that not all properties are possible to be set using this class.

## Get Started

Here is an example on how to use it:

```php
  $chat = new Smartsupp\ChatGenerator;

  $chat->setKey('XYZ123456');
  $chat->disableSendEmailTranscript();
  $chat->enableRating('advanced', true);
  $chat->setBoxPosition('left', 'side', 20, 120);
  $chat->setName('Johny Depp');
  $chat->setEmail('johny@depp.com');
  $chat->setVariable('orderTotal', 'Total orders', 150);
  $chat->setVariable('lastOrder', 'Last ordered', '2015-07-09');
  $chat->setGoogleAnalytics('UA-123456');
  $data = $chat->render();
```
