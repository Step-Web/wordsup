 var multiItemSlider = (function () {
        return function (selector, config={}) {
            //alert(config.setMax);
            const delayAnswer = (config.delay)?config.delay:500;
            const indicators = (config.indicators)?config.indicators:true;
            const finishSlide = (config.finishSlide)?config.finishSlide:1;


            let
                _mainElement = document.querySelector(selector), // основный элемент блока
                _sliderWrapper = _mainElement.querySelector('.slider__wrapper'), // обертка для .slider-item
                _sliderItems = _mainElement.querySelectorAll('.slider__item'), // элементы (.slider-item)
                _sliderControls = _mainElement.querySelectorAll('.slider__control'), // элементы управления
                _sliderControlLeft = _mainElement.querySelector('.slider__control_left'), // кнопка "LEFT"
                _sliderControlRight = _mainElement.querySelector('.slider__control_right'), // кнопка "RIGHT"
                _wrapperWidth = parseFloat(getComputedStyle(_sliderWrapper).width), // ширина обёртки
                _itemWidth = parseFloat(getComputedStyle(_sliderItems[0]).width), // ширина одного элемента
                _positionLeftItem = 0, // позиция левого активного элемента
                _transform = 0, // значение транфсофрмации .slider_wrapper
                _step = _itemWidth / _wrapperWidth * 100, // величина шага (для трансформации)
                _items = [];// наполнение массива _items

            let li = '';
            let cur = '';
            let ev = '';
            _sliderItems.forEach(function (item, index) {
                if(indicators) {

                    ev = (config.indicatorClick) ? ' onclick="slider.toSlide(' + index + ')"':'';
                    cur = (index === 0) ? ' data-active="true"' : '';
                    li += '<li id="dot' + index + '" class="dot"' + cur + ev+'></li>';
                }
                 _items.push({ item: item, position: index, transform: 0 });

            });

            let setMax = (finishSlide > 0)? _items.length :_items.length - finishSlide; // показывать ли страницу с результатоми; // массив элементов
            let position = {
                getMin: 0,
                getMax: setMax, // Если не нужен finish - 1;
            };

          if(li)  document.querySelector('ul.indicators').innerHTML = li;



            let _setIndicators = function () {
                _mainElement.querySelectorAll('.indicators li').forEach(function (d) {
                    if(d.hasAttribute('data-active'))d.removeAttribute('data-active');
                });

          if(document.getElementById('dot'+(_positionLeftItem)))   document.getElementById('dot'+(_positionLeftItem)).dataset.active='true';

            };


            var _transformToItem = function (n) {

              if(n == 0){
                  _mainElement.querySelector('.slider__control_left').classList.remove('slider__control_show');
                  _mainElement.querySelector('.slider__control_right').classList.add('slider__control_show');
                  _transform = Number(0);
              } else {
                  _mainElement.querySelector('.slider__control_left').classList.add('slider__control_show');
                  _transform = Number(-n+'00');
              }
                _transform = Number(-n+'00');
                _positionLeftItem = n;
                _sliderWrapper.style.transform = 'translateX(' + _transform + '%)';
                _setIndicators()
            };

            var _transformItem = function (direction) {

                if (direction === 'right') {
                    if ((_positionLeftItem + _wrapperWidth / _itemWidth - 1) >= position.getMax) {
                        return;
                    }
                    if (!_sliderControlLeft.classList.contains('slider__control_show')) {
                        _sliderControlLeft.classList.add('slider__control_show');
                    }
                    if (_sliderControlRight.classList.contains('slider__control_show') && (_positionLeftItem + _wrapperWidth / _itemWidth) >= position.getMax) {
                        _sliderControlRight.classList.remove('slider__control_show');
                    }
                    _positionLeftItem++;
                    _transform -= _step;
                }
                if (direction === 'left') {
                    if (_positionLeftItem <= position.getMin) {
                        return;
                    }
                    if (!_sliderControlRight.classList.contains('slider__control_show')) {
                        _sliderControlRight.classList.add('slider__control_show');
                    }
                    if (_sliderControlLeft.classList.contains('slider__control_show') && _positionLeftItem - 1 <= position.getMin) {
                        _sliderControlLeft.classList.remove('slider__control_show');
                    }
                    _positionLeftItem--;
                    _transform += _step;
                }
                _sliderWrapper.style.transform = 'translateX(' + _transform + '%)';
                _setIndicators()
            };

            // обработчик события click для кнопок "назад" и "вперед"
            var _controlClick = function (e) {
                let direction = this.classList.contains('slider__control_right') ? 'right' : 'left';
                e.preventDefault();
                _transformItem(direction);
            };
            var _setUpListeners = function () {
                // добавление к кнопкам "назад" и "вперед" обрботчика _controlClick для событя click
                _sliderControls.forEach(function (item) {
                    item.addEventListener('click', _controlClick);
                });
            };
            // инициализация
            _setUpListeners();
            return {
                right: function () { // метод right
                   setTimeout(function() {
                        _transformItem('right');
                  },delayAnswer);

                },
                left: function () { // метод left
                    _transformItem('left');

                },
                toSlide: function (n) { // метод left
                    _transformToItem(n);
                },
                currentSlide: function () { // метод left
                    return _positionLeftItem;
                },
                total: function () {
                    return _items.length;
                },
                items: function () {
                    return _items;
                }
            }
        }
    }());