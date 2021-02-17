## Subscribers Take-Home
Created by Logan Graba *February 17, 2020*

Keep track of the number of subscribers YouTube channels have, with up to one entry per day per channel.

Endpoints:
- Given a channel ID, retrieve the channel’s subscriber history and day-to-day subscriber deltas.
- Given a channel ID, make a request to Youtube for the channel’s current subscriber count.

Further:
> How would you set up a background channel subscriber refresh to minimize data discontinuities?

Use [Laravel Task Scheduling](https://laravel.com/docs/8.x/scheduling) to create a daily task. This daily task would
run a (hopefully bulk) data retrieval request to get the daily subscriber counts of all pertinent channels and write
these to the database.

**-> For a quick implementation see GetChannels Command and how it's scheduled in Kernel**

## Versions
Laravel 8
