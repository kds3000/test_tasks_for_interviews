class CyclicBuffer:
    """Реализация стандартного функционала циклического буфера.
    При заполнении буфера старые данные переписываются
    новыми. Буфер не пустеет, если из него получить равное длине буфера
    количетсво элементов. Но даннные, которые в нем остаются, теряют
    свою актуальность."""
    def __init__(self, max_size):
        self._max_size = max_size
        self._content = [None] * max_size
        self._head = 0
        self._tail = 0
        self._is_full = False

    def add(self, value):
        self._content[self._tail] = value

        # Проверяем, насколько близко указатель конца буфера приблизился к указателю начала
        diff = self._head - self._tail

        # Вводим переменную max_diff для проверки ситуации, в которой конец буфера на последнем индексе,
        # а начало - на первом. В таком случае буфер почти полон
        max_diff = - (self._max_size - 1)

        # Если буфер хаполнен, мы перемещаем оба указателя при добавлении элемента
        if self._is_full:
            self._head = self._increment(self._head, self._max_size)
        self._tail = self._increment(self._tail, self._max_size)

        # расчет переменной diff производился до перемещения указателей,
        # значит после добавления элемента и перемещения указателей буфер будет полон
        if diff == 1 or diff == max_diff:
            self._is_full = True

    def pop(self):
        if self._head == self._tail and not self._is_full:
            print('There is no useful data in buffer')
        else:
            self._is_full = False
            head = self._content[self._head]
            self._head = self._increment(self._head, self._max_size)
            print(self._content)
            return head

    @staticmethod
    def _increment(var, max_size):
        """Статический метод, который увеличивает значение указателя на единицу,
        если указатель находится не на границе массива """
        if var < max_size - 1:
            var += 1
        else:
            var = 0
        return var


class CyclicBufferFull:
    """В данной реализации при заполнении буфера данные не записываются,
    а пользователь получает сообщения о том, что буфер полон. При запросе данных
    из буфера ячейки буфера опустошаются. При опустошении буфера он остается пустым
    в прямом смысле"""
    def __init__(self, max_size):
        self._max_size = max_size
        self._size = 0
        self._content = [None] * max_size
        self._head = 0
        self._tail = 0

    def add(self, value):
        if self._max_size == self._size:
            print('Buffer is full')
        else:
            self._content[self._tail] = value
            if self._tail < self._max_size:
                self._tail += 1
            else:
                self._tail = 0
            self._size += 1

    def pop(self):
        if self._size == 0:
            print('Buffer is empty')
        else:
            head = self._content[self._head]
            self._content[self._head] = None
            if self._head < self._max_size:
                self._head += 1
            else:
                self._head = 0
            self._size -= 1
            return head


