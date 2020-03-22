import csv


class Exchange:
    """
    Instance contains info about one certain exchange
    """
    def __init__(self, name, first_timestamp):
        self._name = name
        # max trades in one minute. Initializing an instance means there is at least one trade exists.
        self._max_trades = 1
        self._timestamps = [first_timestamp]
        # index that points to the start of 1-minute interval
        self._start_index = 0
        # index that points to the end of 1-minute interval
        self._end_index = 0
        self._max_time_between_trades = 60

    def add_timestamp(self, _timestamp):
        self._timestamps.append(_timestamp)

    def end_index_increment(self, value):
        self._end_index += value

    def start_index_increment(self, value):
        self._start_index += value

    def get_start_time(self):
        return self._timestamps[self._start_index]

    def get_end_time(self):
        return self._timestamps[self._end_index]

    def get_max_trades(self):
        return self._max_trades

    def refresh_max_trades(self):
        """
        If time window between "start" and "end" trades are less or equal then max time
        compares current number of trades to max number of trades stored. Refreshes
        max trades number if found new maximum
        """
        while True:
            start_time = self.get_start_time()
            finish_time = self.get_end_time()

            # if time window is too large we decrease current window by increasing start index
            if finish_time - start_time >= self._max_time_between_trades:
                self.start_index_increment(1)
            else:
                break

        cur_trades = self._end_index - self._start_index + 1
        if self._max_trades < cur_trades:
            self._max_trades = cur_trades


class SetOfTrades:
    def __init__(self):
        # contains only names
        self._exchange_names = []
        # containts Exchange instances
        self._exchanges = {}

    def add_trade(self, _exchange_name, _timestamp):
        if _exchange_name not in self._exchanges:
            self._exchange_names.append(_exchange_name)
            self._exchanges[_exchange_name] = Exchange(_exchange_name, _timestamp)
        else:
            self._exchanges[_exchange_name].add_timestamp(_timestamp)
            self._exchanges[_exchange_name].end_index_increment(1)

    def print_max_number_of_trades(self):
        self._exchange_names.sort()
        for name in self._exchange_names:
            max_trades = self._exchanges[name].get_max_trades()
            print(max_trades)

    def get_exchange_instance(self, name):
        return self._exchanges[name]


def main():
    set_of_trades = SetOfTrades()
    with open('trades.csv') as csvfile:
        file = csv.reader(csvfile)

        for row in file:
            # convert to float for simple calculations.
            # in our case "increasing" minute size from 60 sec to 100 sec is not an issue
            timestamp = float(row[0].replace(':', ''))
            exchange_name = row[3]
            set_of_trades.add_trade(exchange_name, timestamp)
            exchange_instance = set_of_trades.get_exchange_instance(exchange_name)
            exchange_instance.refresh_max_trades()

    set_of_trades.print_max_number_of_trades()


if __name__ == '__main__':
    main()
