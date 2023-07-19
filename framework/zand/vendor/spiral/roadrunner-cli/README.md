<p align="center">
 <img src="https://user-images.githubusercontent.com/796136/50286124-6f7f3780-046f-11e9-9f45-e8fedd4f786d.png" height="75px" alt="RoadRunner">
</p>
<p align="center">
 <a href="https://packagist.org/packages/spiral/roadrunner"><img src="https://poser.pugx.org/spiral/roadrunner/version"></a>
	<a href="https://pkg.go.dev/github.com/spiral/roadrunner?tab=doc"><img src="https://godoc.org/github.com/spiral/roadrunner?status.svg"></a>
	<a href="https://github.com/spiral/roadrunner/actions"><img src="https://github.com/spiral/roadrunner/workflows/CI/badge.svg" alt=""></a>
	<a href="https://goreportcard.com/report/github.com/spiral/roadrunner"><img src="https://goreportcard.com/badge/github.com/spiral/roadrunner"></a>
	<a href="https://scrutinizer-ci.com/g/spiral/roadrunner/?branch=master"><img src="https://scrutinizer-ci.com/g/spiral/roadrunner/badges/quality-score.png"></a>
	<a href="https://codecov.io/gh/spiral/roadrunner/"><img src="https://codecov.io/gh/spiral/roadrunner/branch/master/graph/badge.svg"></a>
	<a href="https://lgtm.com/projects/g/spiral/roadrunner/alerts/"><img alt="Total alerts" src="https://img.shields.io/lgtm/alerts/g/spiral/roadrunner.svg?logo=lgtm&logoWidth=18"/></a>
	<a href="https://discord.gg/TFeEmCs"><img src="https://img.shields.io/badge/discord-chat-magenta.svg"></a>
	<a href="https://packagist.org/packages/spiral/roadrunner"><img src="https://img.shields.io/packagist/dd/spiral/roadrunner?style=flat-square"></a>
</p>

RoadRunner is an open-source (MIT licensed) high-performance PHP application server, load balancer, and process manager.
It supports running as a service with the ability to extend its functionality on a per-project basis.

RoadRunner includes PSR-7/PSR-17 compatible HTTP and HTTP/2 server and can be used to replace classic Nginx+FPM setup with much greater performance and flexibility.

<p align="center">
	<a href="https://roadrunner.dev/"><b>Official Website</b></a> | 
	<a href="https://roadrunner.dev/docs"><b>Documentation</b></a>
</p>

## RoadRunner CLI

This repository contains commands to help you work with the RoadRunner, such as:

- `get-binary` (or `get`) - allows to install the latest version of the RoadRunner compatible with 
  your environment (operating system, processor architecture, runtime, etc...).
  Also, this command creates an example `.rr.yaml` configuration file. If don't use the command without additional options 
  `plugin` and `preset`, an example with a complete configuration file will be created. 
  Using the `plugin` option (shortcut `p`) can create an example configuration file with only plugins needed. 
  For example, with http plugin only: `get-binary -p http`, http and jobs: `get-binary -p http -p jobs`. 
  Available plugins: `amqp`, `beanstalk`, `boltdb`, `broadcast`, `endure`, `fileserver`, `grpc`, `http`, `jobs`, `kv`,
  `logs`, `metrics`, `nats`, `redis`, `reload`, `rpc`, `server`, `service`, `sqs`, `status`, `tcp`, `temporal`, `websockets`.
  Using the `preset` option can create an example configuration file with popular plugins for different typical tasks. 
  For example, with web preset: `get-binary --preset web`.
  Available presets: `web` (contains plugins `http`, `jobs`).
- `download-protoc-binary` - allows to install the latest version of the `protoc-gen-php-grpc` file compatible with
  your environment (operating system, processor architecture, runtime, etc...).
- `versions` - displays a list of available RoadRunner binary versions.

Testing:
--------

This codebase is automatically tested via host repository - [spiral/roadrunner](https://github.com/spiral/roadrunner).

License:
--------

The MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. 
Maintained by [Spiral Scout](https://spiralscout.com).
