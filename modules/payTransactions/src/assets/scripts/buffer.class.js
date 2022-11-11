// Класс буфера
class Buffer {
    // Буфер
    buffer = [];
    // Текущий индекс буфера
    bufferIndex = -1;
    // Длина буфера
    bufferLimit = 25;
    // Массив методов для игнорирования
    methodsIgnored = [];

    // Конструктор
    constructor() {
        // ничего
    }

    // Записать запись в буфер
    set(pageDomObject, tabName, methodName, properties) {
        if (!this.methodsIgnored.includes(methodName)) {
            if (this.buffer.length > 0) {
                var buffer = [];

                for (var i = 0; i <= this.bufferIndex; i++) {
                    buffer.push(this.buffer[i]);
                }

                this.buffer = buffer;
            }

            this.bufferIndex++;

            this.buffer.push({
                pageDomObject: pageDomObject,
                tabName: tabName,
                methodName: methodName,
                properties: properties,
            });

            if (this.buffer.length > this.bufferLimit) {
                var limitBuffer = [];

                for (var i = (this.buffer.length - this.bufferLimit); i < this.buffer.length; i++) {
                    limitBuffer.push(this.buffer[i]);
                }

                this.buffer = limitBuffer;

                this.bufferIndex = this.bufferLimit - 1;
            }

            return this.bufferIndex;
        }

        return false;
    }

    // Прочитать запись из буфера
    get(index) {
        if (!this.hasIndex(index)) {
            return false;
        }

        console.log(this.buffer[index]);
        console.log(this.bufferIndex);

        return this.buffer[index];
    }

    // Проверить наличие записи в буфере
    hasIndex(index) {
        if (this.buffer[index] === undefined) {
            return false;
        }

        return true;
    }

    // Список всех записей в буфере
    list() {
        return this.buffer;
    }

    // Получение текущего индекса
    index() {
        return this.bufferIndex;
    }

    // Получение длины буфера
    limit() {
        return this.bufferLimit;
    }

    // Получение предыдущей записи из буфера
    prev() {
        if (this.hasIndex(this.bufferIndex - 1) === false) {
            return false;
        }

        this.bufferIndex--;

        return this.get(this.bufferIndex);
    }

    // Получение следующей записи из буфера
    next() {
        if (this.hasIndex(this.bufferIndex + 1) === false) {
            return false;
        }

        this.bufferIndex++;

        return this.get(this.bufferIndex);
    }

    // Получение текущей записи из буфера
    current() {
        if (this.hasIndex(this.bufferIndex) === false) {
            return false;
        }

        return this.get(this.bufferIndex);
    }
}