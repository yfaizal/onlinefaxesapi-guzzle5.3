# CHANGELOG
## 1.1.0 - 2015-11-09

* Mock now supports `save_to`
* Marked `AbstractRequestEvent::getTransaction()` as public.
* Fixed a bug in which multiple headers using different casing would overwrite
  previous headers in the associative array.
* Added `Utils::getDefaultHandler()`
* Marked `GuzzleHttp\Client::getDefaultUserAgent` as deprecated.
* URL scheme is now always lowercased.