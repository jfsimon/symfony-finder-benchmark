Benchmark for Symfony2 Finder component
=======================================


How to run
----------

1.  Clone repository: `git clone https://github.com/jfsimon/symfony-finder-benchmark.git`
3.  Go into application source: `cd symfony-finder-benchmark`
2.  Install composer: `curl -s http://getcomposer.org/installer | php`
4.  Install vendors with composer: `php composer.phar install`
5.  Run the benchmark: `./bin/benchmark run`


Add bench case
--------------

1.  Cases are declared in `FinderBench\Console\Application` constructor
2.  A case must implement `FinderBench\BenchCase\CaseInterface`
3.  Cases can be composed with `FinderBench\BenchCase\ComposedCase`
