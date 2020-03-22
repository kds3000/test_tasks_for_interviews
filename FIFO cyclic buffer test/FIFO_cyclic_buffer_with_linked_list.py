class CyclicBuffer:
    """
    Это зацикленный связный список.
    """
    def __init__(self, max_size):
        self._head = None
        self._tail = None
        self._size = 0
        self._max_size = max_size

    def add(self, value):
        new = self._Node(value, self._head)
        if self._size == 0:
            self._head = new
            self._tail = new
            self._size += 1
        else:
            self._tail.next = new
            self._tail = new
            if self._size == self._max_size:
                new.next = self._head.next
                self._head = self._head.next
            else:
                self._size += 1

    def pop(self):
        if self._size == 0:
            print('Buffer is empty')
        else:
            result = self._head.value
            self._head = self._head.next
            self._tail.next = self._head
            self._size -= 1
            return result

    class _Node:
        def __init__(self, value, next=None):
            self.value = value
            self.next = next


