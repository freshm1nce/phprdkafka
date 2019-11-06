<?php
$conf = new Conf();
$conf->set('log_level', LOG_LEVEL);
$conf->set('debug', 'all');
$rk = new RdKafka\Consumer($conf);
$rk->addBrokers("10.170.171.21:9093");
$topic = $rk->newTopic("misp_event");

// The first argument is the partition to consume from.
// The second argument is the offset at which to start consumption. Valid values
// are: RD_KAFKA_OFFSET_BEGINNING, RD_KAFKA_OFFSET_END, RD_KAFKA_OFFSET_STORED.
$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

while (true) {
    // The first argument is the partition (again).
    // The second argument is the timeout.
    $msg = $topic->consume(0, 1000);
    if (null === $msg) {
        continue;
    } elseif ($msg->err) {
        echo $msg->errstr(), "\n";
        break;
    } else {
        echo $msg->payload, "\n";
    }
}
?>