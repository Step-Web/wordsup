/*!
 *
 * Vanilla-DataTables
 * Copyright (c) 2015-2017 Karl Saunders (http://mobius.ovh)
 * Licensed under MIT (http://www.opensource.org/licenses/mit-license.php)
 *
 * Version: 1.6.16
 *
 */
(function (m, q) {
    var u = "DataTable";
    "object" == typeof exports ? module.exports = q(u) : "function" == typeof define && define.amd ? define([], q(u)) : m[u] = q(u)
})("undefined" == typeof global ? this.window || this.global : global, function () {
    "use strict";
    var q = window, u = document, z = u.body, A = {
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25],
        sortable: !0,
        searchable: !0,
        nextPrev: !0,
        firstLast: !1,
        prevText: "&lsaquo;",
        nextText: "&rsaquo;",
        firstText: "&laquo;",
        lastText: "&raquo;",
        ellipsisText: "&hellip;",
        ascText: "\u25B4",
        descText: "\u25BE",
        truncatePager: !0,
        pagerDelta: 2,
        fixedColumns: !0,
        fixedHeight: !1,
        header: !0,
        footer: !1,
        labels: {
            placeholder: "Search...",
            perPage: "{select} entries per page",
            noRows: "No entries found",
            info: "Showing {start} to {end} of {rows} entries"
        },
        layout: {top: "{select}{search}", bottom: "{info}{pager}"}
    }, B = function (T) {
        return "[object Object]" === Object.prototype.toString.call(T)
    }, C = function (T) {
        return Array.isArray(T)
    }, D = function (T) {
        var U = !1;
        try {
            U = JSON.parse(T)
        } catch (V) {
            return !1
        }
        return null !== U && (C(U) || B(U)) && U
    }, E = function (T, U) {
        for (var V in U) if (U.hasOwnProperty(V)) {
            var W = U[V];
            W && B(W) ? (T[V] = T[V] || {}, E(T[V], W)) : T[V] = W
        }
        return T
    }, F = function (T, U, V) {
        if (B(T)) for (var W in T) Object.prototype.hasOwnProperty.call(T, W) && U.call(V, T[W], W); else for (W = 0; W < T.length; W++) U.call(V, T[W], W)
    }, G = function (T, U, V) {
        T.addEventListener(U, V, !1)
    }, H = function (T, U) {
        var V = u.createElement(T);
        if (U && "object" == typeof U) {
            for (var W in U) "html" === W ? V.innerHTML = U[W] : V.setAttribute(W, U[W])
        }
        return V
    }, I = function (T, U) {
        if (T instanceof NodeList) F(T, function (V) {
            I(V, U)
        }); else if (U) for (; T.hasChildNodes();) T.removeChild(T.firstChild); else T.innerHTML = ""
    }, J = function (T, U, V) {
        return H("li", {class: T, html: "<a href=\"#\" data-page=\"" + U + "\">" + V + "</a>"})
    }, K = {
        add: function (T, U) {
            T.classList ? T.classList.add(U) : !K.contains(T, U) && (T.className = T.className.trim() + " " + U)
        }, remove: function (T, U) {
            T.classList ? T.classList.remove(U) : K.contains(T, U) && (T.className = T.className.replace(new RegExp("(^|\\s)" + U.split(" ").join("|") + "(\\s|$)", "gi"), " "))
        }, contains: function (T, U) {
            if (T) return T.classList ? T.classList.contains(U) : !!T.className && !!T.className.match(new RegExp("(\\s|^)" + U + "(\\s|$)"))
        }
    }, L = function (T, U) {
        var V, W;
        1 === U ? (V = 0, W = T.length) : -1 == U && (V = T.length - 1, W = -1);
        for (var X = !0; X;) {
            X = !1;
            for (var Y = V; Y != W; Y += U) if (T[Y + U] && T[Y].value > T[Y + U].value) {
                var Z = T[Y], $ = T[Y + U];
                T[Y] = $, T[Y + U] = Z, X = !0
            }
        }
        return T
    }, M = function (T, U, V, W, X) {
        W = W || 2;
        var Y, Z = 2 * W, $ = U - W, _ = U + W, aa = [], ba = [];
        U < 4 - W + Z ? _ = 3 + Z : U > V - (3 - W + Z) && ($ = V - (2 + Z));
        for (var ca = 1; ca <= V; ca++) if (1 == ca || ca == V || ca >= $ && ca <= _) {
            var da = T[ca - 1];
            K.remove(da, "active"), aa.push(da)
        }
        return F(aa, function (ea) {
            var fa = ea.children[0].getAttribute("data-page");
            if (Y) {
                var ga = Y.children[0].getAttribute("data-page");
                if (2 == fa - ga) ba.push(T[ga]); else if (1 != fa - ga) {
                    var ha = H("li", {class: "ellipsis", html: "<a href=\"#\">" + X + "</a>"});
                    ba.push(ha)
                }
            }
            ba.push(ea), Y = ea
        }), ba
    }, N = function (T) {
        var U = !1, V = !1;
        if (T = T || this.options.data, T.headings) {
            U = H("thead");
            var W = H("tr");
            F(T.headings, function (X) {
                var Y = H("th", {html: X});
                W.appendChild(Y)
            }), U.appendChild(W)
        }
        T.data && T.data.length && (V = H("tbody"), F(T.data, function (X) {
            if (T.headings && T.headings.length !== X.length) throw new Error("The number of rows do not match the number of headings.");
            var Y = H("tr");
            F(X, function (Z) {
                var $ = H("td", {html: Z});
                Y.appendChild($)
            }), V.appendChild(Y)
        })), U && (null !== this.table.tHead && this.table.removeChild(this.table.tHead), this.table.appendChild(U)), V && (this.table.tBodies.length && this.table.removeChild(this.table.tBodies[0]), this.table.appendChild(V))
    }, O = function (T, U) {
        var V = !1;
        return U && (V = "ISO_8601" === U ? moment(T, moment.ISO_8601).format("YYYYMMDD") : "RFC_2822" === U ? moment(T, "ddd, MM MMM YYYY HH:mm:ss ZZ").format("YYYYMMDD") : "MYSQL" === U ? moment(T, "YYYY-MM-DD hh:mm:ss").format("YYYYMMDD") : "UNIX" === U ? moment(T).unix() : moment(T, U).format("YYYYMMDD")), V
    }, P = function (T) {
        return this.dt = T, this
    };
    P.prototype.swap = function (T) {
        if (T.length && 2 === T.length) {
            var U = [];
            F(this.dt.headings, function (Y, Z) {
                U.push(Z)
            });
            var V = T[0], W = T[1], X = U[W];
            U[W] = U[V], U[V] = X, this.order(U)
        }
    }, P.prototype.order = function (T) {
        var U, V, W, X, Y, Z, $, _ = [[], [], [], []], aa = this.dt;
        F(T, function (ba, ca) {
            Y = aa.headings[ba], Z = "false" !== Y.getAttribute("data-sortable"), U = Y.cloneNode(!0), U.originalCellIndex = ca, U.sortable = Z, _[0].push(U), 0 > aa.hiddenColumns.indexOf(ba) && (V = Y.cloneNode(!0), V.originalCellIndex = ca, V.sortable = Z, _[1].push(V))
        }), F(aa.data, function (ba, ca) {
            W = ba.cloneNode(), X = ba.cloneNode(), W.dataIndex = X.dataIndex = ca, null !== ba.searchIndex && void 0 !== ba.searchIndex && (W.searchIndex = X.searchIndex = ba.searchIndex), F(T, function (da) {
                $ = ba.cells[da].cloneNode(!0), $.data = ba.cells[da].data, W.appendChild($), 0 > aa.hiddenColumns.indexOf(da) && ($ = ba.cells[da].cloneNode(!0), $.data = ba.cells[da].data, X.appendChild($))
            }), _[2].push(W), _[3].push(X)
        }), aa.headings = _[0], aa.activeHeadings = _[1], aa.data = _[2], aa.activeRows = _[3], aa.update()
    }, P.prototype.hide = function (T) {
        if (T.length) {
            var U = this.dt;
            F(T, function (V) {
                0 > U.hiddenColumns.indexOf(V) && U.hiddenColumns.push(V)
            }), this.rebuild()
        }
    }, P.prototype.show = function (T) {
        if (T.length) {
            var U, V = this.dt;
            F(T, function (W) {
                U = V.hiddenColumns.indexOf(W), -1 < U && V.hiddenColumns.splice(U, 1)
            }), this.rebuild()
        }
    }, P.prototype.visible = function (T) {
        var U, V = this.dt;
        return T = T || V.headings.map(function (W) {
            return W.originalCellIndex
        }), isNaN(T) ? C(T) && (U = [], F(T, function (W) {
            U.push(0 > V.hiddenColumns.indexOf(W))
        })) : U = 0 > V.hiddenColumns.indexOf(T), U
    }, P.prototype.add = function (T) {
        var V, U = this, W = document.createElement("th");
        return this.dt.headings.length ? void (this.dt.hiddenHeader ? W.innerHTML = "" : T.heading.nodeName ? W.appendChild(T.heading) : W.innerHTML = T.heading, this.dt.headings.push(W), F(this.dt.data, function (X, Y) {
            T.data[Y] && (V = document.createElement("td"), T.data[Y].nodeName ? V.appendChild(T.data[Y]) : V.innerHTML = T.data[Y], V.data = V.innerHTML, T.render && (V.innerHTML = T.render.call(U, V.data, V, X)), X.appendChild(V))
        }), T.type && W.setAttribute("data-type", T.type), T.format && W.setAttribute("data-format", T.format), T.hasOwnProperty("sortable") && (W.sortable = T.sortable, W.setAttribute("data-sortable", !0 === T.sortable ? "true" : "false")), this.rebuild(), this.dt.renderHeader()) : (this.dt.insert({
            headings: [T.heading],
            data: T.data.map(function (X) {
                return [X]
            })
        }), void this.rebuild())
    }, P.prototype.remove = function (T) {
        C(T) ? (T.sort(function (U, V) {
            return V - U
        }), F(T, function (U) {
            this.remove(U)
        }, this)) : (this.dt.headings.splice(T, 1), F(this.dt.data, function (U) {
            U.removeChild(U.cells[T])
        })), this.rebuild()
    }, P.prototype.sort = function (T, U, V) {
        var W = this.dt;
        if (W.hasHeadings && (1 > T || T > W.activeHeadings.length)) return !1;
        W.sorting = !0, --T;
        var X, Y = W.data, Z = [], $ = [], _ = 0, aa = 0, ba = W.activeHeadings[T];
        T = ba.originalCellIndex, F(Y, function (fa) {
            var ga = fa.cells[T], ha = ga.hasAttribute("data-content") ? ga.getAttribute("data-content") : ga.data,
                ia = ha.replace(/(\$|\,|\s|%)/g, "");
            if ("date" === ba.getAttribute("data-type") && q.moment) {
                var ja = !1, ka = ba.hasAttribute("data-format");
                ka && (ja = ba.getAttribute("data-format")), ia = O(ha, ja)
            }
            parseFloat(ia) == ia ? $[aa++] = {value: +ia, row: fa} : Z[_++] = {value: ha, row: fa}
        });
        var ca, da;
        K.contains(ba, "asc") || "asc" == U ? (ca = L(Z, -1), da = L($, -1), X = "descending", K.remove(ba, "asc"), K.add(ba, "desc")) : (ca = L($, 1), da = L(Z, 1), X = "ascending", K.remove(ba, "desc"), K.add(ba, "asc")), W.lastTh && ba != W.lastTh && (K.remove(W.lastTh, "desc"), K.remove(W.lastTh, "asc")), W.lastTh = ba, Y = ca.concat(da), W.data = [];
        var ea = [];
        F(Y, function (fa, ga) {
            W.data.push(fa.row), null !== fa.row.searchIndex && void 0 !== fa.row.searchIndex && ea.push(ga)
        }, W), W.searchData = ea, this.rebuild(), W.update(), V || W.emit("datatable.sort", T, X)
    }, P.prototype.rebuild = function () {
        var T, U, V, W, X = this.dt, Y = [];
        X.activeRows = [], X.activeHeadings = [], F(X.headings, function (Z, $) {
            Z.originalCellIndex = $, Z.sortable = "false" !== Z.getAttribute("data-sortable"), 0 > X.hiddenColumns.indexOf($) && X.activeHeadings.push(Z)
        }, this), F(X.data, function (Z, $) {
            T = Z.cloneNode(), U = Z.cloneNode(), T.dataIndex = U.dataIndex = $, null !== Z.searchIndex && void 0 !== Z.searchIndex && (T.searchIndex = U.searchIndex = Z.searchIndex), F(Z.cells, function (_) {
                V = _.cloneNode(!0), V.data = _.data, T.appendChild(V), 0 > X.hiddenColumns.indexOf(_.cellIndex) && (W = _.cloneNode(!0), W.data = _.data, U.appendChild(W))
            }), Y.push(T), X.activeRows.push(U)
        }), X.data = Y, X.update()
    };
    var Q = function (T, U) {
        return this.dt = T, this.rows = U, this
    };
    Q.prototype.build = function (T) {
        var U, V = H("tr"), W = this.dt.headings;
        return W.length || (W = T.map(function () {
            return ""
        })), F(W, function (X, Y) {
            U = H("td"), T[Y] || T[Y].length || (T[Y] = ""), U.innerHTML = T[Y], U.data = T[Y], V.appendChild(U)
        }), V
    }, Q.prototype.render = function (T) {
        return T
    }, Q.prototype.add = function (T) {
        if (C(T)) {//alert(Добавление);
            var U = this.dt;
            C(T[0]) ? F(T, function (V) {
                U.data.unshift(this.build(V))
            }, this) : U.data.unshift(this.build(T)), U.data.length && (U.hasRows = !0), this.update(), U.columns().rebuild()
        }
    }, Q.prototype.remove = function (T) {
        var U = this.dt;
        C(T) ? (T.sort(function (V, W) {
            return W - V
        }), F(T, function (V) {
            U.data.splice(V, 1)
        })) : U.data.splice(T, 1), this.update(), U.columns().rebuild()
    }, Q.prototype.update = function () {
        F(this.dt.data, function (T, U) {
            T.dataIndex = U
        })
    };
    var R = function (T, U) {
        if (this.initialized = !1, this.options = E(A, U), "string" == typeof T && (T = document.querySelector(T)), this.initialLayout = T.innerHTML, this.initialSortable = this.options.sortable, this.options.header || (this.options.sortable = !1), null !== T.tHead || this.options.data && (!this.options.data || this.options.data.headings) || (this.options.sortable = !1), T.tBodies.length && !T.tBodies[0].rows.length && this.options.data && !this.options.data.data) throw new Error("You seem to be using the data option, but you've not defined any rows.");
        this.table = T, this.init()
    };
    R.extend = function (T, U) {
        "function" == typeof U ? R.prototype[T] = U : R[T] = U
    };
    var S = R.prototype;
    return S.init = function (T) {
        if (this.initialized || K.contains(this.table, "dataTable-table")) return !1;
        var U = this;
        this.options = E(this.options, T || {}), this.isIE = !!/(msie|trident)/i.test(navigator.userAgent), this.currentPage = 1, this.onFirstPage = !0, this.hiddenColumns = [], this.columnRenderers = [], this.selectedColumns = [], this.render(), setTimeout(function () {
            U.emit("datatable.init"), U.initialized = !0, U.options.plugins && F(U.options.plugins, function (V, W) {
                U[W] && "function" == typeof U[W] && (U[W] = U[W](V, {
                    each: F,
                    extend: E,
                    classList: K,
                    createElement: H
                }), V.enabled && U[W].init && "function" == typeof U[W].init && U[W].init())
            })
        }, 10)
    }, S.render = function (T) {
        if (T) return "page" === T ? this.renderPage() : "pager" === T ? this.renderPager() : "header" === T ? this.renderHeader() : void 0, !1;
        var U = this, V = U.options, W = "";
        if (V.data && N.call(U), V.ajax) {
            var X = V.ajax, Y = new XMLHttpRequest, Z = function (ia) {
                U.emit("datatable.ajax.progress", ia, Y)
            }, $ = function (ia) {
                if (4 === Y.readyState) if (U.emit("datatable.ajax.loaded", ia, Y), 200 === Y.status) {
                    var ja = {};
                    ja.data = X.load ? X.load.call(U, Y) : Y.responseText, ja.type = "json", X.content && X.content.type && (ja.type = X.content.type, ja = E(ja, X.content)), U.import(ja), U.setColumns(!0), U.emit("datatable.ajax.success", ia, Y)
                } else U.emit("datatable.ajax.error", ia, Y)
            }, _ = function (ia) {
                U.emit("datatable.ajax.error", ia, Y)
            }, aa = function (ia) {
                U.emit("datatable.ajax.abort", ia, Y)
            };
            G(Y, "progress", Z), G(Y, "load", $), G(Y, "error", _), G(Y, "abort", aa), U.emit("datatable.ajax.loading", Y), Y.open("GET", "string" == typeof X ? V.ajax : V.ajax.url), Y.send()
        }
        if (U.body = U.table.tBodies[0], U.head = U.table.tHead, U.foot = U.table.tFoot, U.body || (U.body = H("tbody"), U.table.appendChild(U.body)), U.hasRows = 0 < U.body.rows.length, !U.head) {
            var ba = H("thead"), ca = H("tr");
            U.hasRows && (F(U.body.rows[0].cells, function () {
                ca.appendChild(H("th"))
            }), ba.appendChild(ca)), U.head = ba, U.table.insertBefore(U.head, U.body), U.hiddenHeader = !V.ajax
        }
        if (U.headings = [], U.hasHeadings = 0 < U.head.rows.length, U.hasHeadings && (U.header = U.head.rows[0], U.headings = [].slice.call(U.header.cells)), !V.header && U.head && U.table.removeChild(U.table.tHead), V.footer ? U.head && !U.foot && (U.foot = H("tfoot", {html: U.head.innerHTML}), U.table.appendChild(U.foot)) : U.foot && U.table.removeChild(U.table.tFoot), U.wrapper = H("div", {class: "dataTable-wrapper dataTable-loading"}), W += "<div class='dataTable-top'>", W += V.layout.top, W += "</div>", W += "<div class='dataTable-container'></div>", W += "<div class='dataTable-bottom'>", W += V.layout.bottom, W += "</div>", W = W.replace("{info}", "<div class='dataTable-info'></div>"), V.perPageSelect) {
            var da = "<div class='dataTable-dropdown'><label>";
            da += V.labels.perPage, da += "</label></div>";
            var ea = H("select", {class: "dataTable-selector"});
            F(V.perPageSelect, function (ia) {
                var ja = ia === V.perPage, ka = new Option(ia, ia, ja, ja);
                ea.add(ka)
            }), da = da.replace("{select}", ea.outerHTML), W = W.replace("{select}", da)
        } else W = W.replace("{select}", "");
        if (V.searchable) {
            var fa = "<div class='dataTable-search'><input class='dataTable-input' placeholder='" + V.labels.placeholder + "' type='text'></div>";
            W = W.replace("{search}", fa)
        } else W = W.replace("{search}", "");
        U.hasHeadings && this.render("header"), K.add(U.table, "dataTable-table");
        var ga = H("div", {class: "dataTable-pagination"}), ha = H("ul");
        ga.appendChild(ha), W = W.replace(/\{pager\}/g, ga.outerHTML), U.wrapper.innerHTML = W, U.container = U.wrapper.querySelector(".dataTable-container"), U.pagers = U.wrapper.querySelectorAll(".dataTable-pagination"), U.label = U.wrapper.querySelector(".dataTable-info"), U.table.parentNode.replaceChild(U.wrapper, U.table), U.container.appendChild(U.table), U.rect = U.table.getBoundingClientRect(), U.data = [].slice.call(U.body.rows), U.activeRows = U.data.slice(), U.activeHeadings = U.headings.slice(), U.update(), V.ajax || U.setColumns(), this.fixHeight(), U.fixColumns(), V.header || K.add(U.wrapper, "no-header"), V.footer || K.add(U.wrapper, "no-footer"), V.sortable && K.add(U.wrapper, "sortable"), V.searchable && K.add(U.wrapper, "searchable"), V.fixedHeight && K.add(U.wrapper, "fixed-height"), V.fixedColumns && K.add(U.wrapper, "fixed-columns"), U.bindEvents()
    }, S.renderPage = function () {
        if (this.hasRows && this.totalPages) {
            this.currentPage > this.totalPages && (this.currentPage = 1);
            var T = this.currentPage - 1, U = u.createDocumentFragment();
            this.hasHeadings && (I(this.header, this.isIE), F(this.activeHeadings, function ($) {
                this.header.appendChild($)
            }, this)), F(this.pages[T], function ($) {
                U.appendChild(this.rows().render($))
            }, this), this.clear(U), this.onFirstPage = 1 === this.currentPage, this.onLastPage = this.currentPage === this.lastPage
        } else this.clear();
        var Y, V = 0, W = 0, X = 0;
        if (this.totalPages && (V = this.currentPage - 1, W = V * this.options.perPage, X = W + this.pages[V].length, ++W, Y = this.searching ? this.searchData.length : this.data.length), this.label && this.options.labels.info.length) {
            var Z = this.options.labels.info.replace("{start}", W).replace("{end}", X).replace("{page}", this.currentPage).replace("{pages}", this.totalPages).replace("{rows}", Y);
            this.label.innerHTML = Y ? Z : ""
        }
        1 == this.currentPage && this.fixHeight()
    }, S.renderPager = function () {
        if (I(this.pagers, this.isIE), 1 < this.totalPages) {
            var T = "pager", U = u.createDocumentFragment(), V = this.onFirstPage ? 1 : this.currentPage - 1,
                W = this.onlastPage ? this.totalPages : this.currentPage + 1;
            this.options.firstLast && U.appendChild(J(T, 1, this.options.firstText)), this.options.nextPrev && U.appendChild(J(T, V, this.options.prevText));
            var X = this.links;
            this.options.truncatePager && (X = M(this.links, this.currentPage, this.pages.length, this.options.pagerDelta, this.options.ellipsisText)), K.add(this.links[this.currentPage - 1], "active"), F(X, function (Y) {
                K.remove(Y, "active"), U.appendChild(Y)
            }), K.add(this.links[this.currentPage - 1], "active"), this.options.nextPrev && U.appendChild(J(T, W, this.options.nextText)), this.options.firstLast && U.appendChild(J(T, this.totalPages, this.options.lastText)), F(this.pagers, function (Y) {
                Y.appendChild(U.cloneNode(!0))
            })
        }
    }, S.renderHeader = function () {
        var T = this;
        T.labels = [], T.headings && T.headings.length && F(T.headings, function (U, V) {
            if (T.labels[V] = U.textContent, K.contains(U.firstElementChild, "dataTable-sorter") && (U.innerHTML = U.firstElementChild.innerHTML), U.sortable = "false" !== U.getAttribute("data-sortable"), U.originalCellIndex = V, T.options.sortable && U.sortable) {
                var W = H("a", {href: "#", class: "dataTable-sorter", html: U.innerHTML});
                U.innerHTML = "", U.setAttribute("data-sortable", ""), U.appendChild(W)
            }
        }), T.fixColumns()
    }, S.bindEvents = function () {
        var T = this, U = T.options;
        if (U.perPageSelect) {
            var V = T.wrapper.querySelector(".dataTable-selector");
            V && G(V, "change", function () {
                U.perPage = parseInt(this.value, 10), T.update(), T.fixHeight(), T.emit("datatable.perpage", U.perPage)
            })
        }
        U.searchable && (T.input = T.wrapper.querySelector(".dataTable-input"), T.input && G(T.input, "keyup", function () {
            T.search(this.value)
        })), G(T.wrapper, "click", function (W) {
            var X = W.target;
            "a" === X.nodeName.toLowerCase() && (X.hasAttribute("data-page") ? (T.page(X.getAttribute("data-page")), W.preventDefault()) : U.sortable && K.contains(X, "dataTable-sorter") && "false" != X.parentNode.getAttribute("data-sortable") && (T.columns().sort(T.activeHeadings.indexOf(X.parentNode) + 1), W.preventDefault()))
        })
    }, S.setColumns = function (T) {
        var U = this;
        T || F(U.data, function (V) {
            F(V.cells, function (W) {
                W.data = W.innerHTML
            })
        }), U.options.columns && U.headings.length && F(U.options.columns, function (V) {
            C(V.select) || (V.select = [V.select]), V.hasOwnProperty("render") && "function" == typeof V.render && (U.selectedColumns = U.selectedColumns.concat(V.select), U.columnRenderers.push({
                columns: V.select,
                renderer: V.render
            })), F(V.select, function (W) {
                var X = U.headings[W];
                V.type && X.setAttribute("data-type", V.type), V.format && X.setAttribute("data-format", V.format), V.hasOwnProperty("sortable") && X.setAttribute("data-sortable", V.sortable), V.hasOwnProperty("hidden") && !1 !== V.hidden && U.columns().hide(W), V.hasOwnProperty("sort") && 1 === V.select.length && U.columns().sort(V.select[0] + 1, V.sort, !0)
            })
        }), U.hasRows && (F(U.data, function (V, W) {
            V.dataIndex = W, F(V.cells, function (X) {
                X.data = X.innerHTML
            })
        }), U.selectedColumns.length && F(U.data, function (V) {
            F(V.cells, function (W, X) {
                -1 < U.selectedColumns.indexOf(X) && F(U.columnRenderers, function (Y) {
                    -1 < Y.columns.indexOf(X) && (W.innerHTML = Y.renderer.call(U, W.data, W, V))
                })
            })
        }), U.columns().rebuild()), U.render("header")
    }, S.destroy = function () {
        this.table.innerHTML = this.initialLayout, K.remove(this.table, "dataTable-table"), this.wrapper.parentNode.replaceChild(this.table, this.wrapper), this.initialized = !1
    }, S.update = function () {
        K.remove(this.wrapper, "dataTable-empty"), this.paginate(this), this.render("page"), this.links = [];
        for (var U, T = this.pages.length; T--;) U = T + 1, this.links[T] = J(0 === T ? "active" : "", U, U);
        this.sorting = !1, this.render("pager"), this.rows().update(), this.emit("datatable.update")
    }, S.paginate = function () {
        var T = this.options.perPage, U = this.activeRows;
        return this.searching && (U = [], F(this.searchData, function (V) {
            U.push(this.activeRows[V])
        }, this)), this.pages = U.map(function (V, W) {
            return 0 == W % T ? U.slice(W, W + T) : null
        }).filter(function (V) {
            return V
        }), this.totalPages = this.lastPage = this.pages.length, this.totalPages
    }, S.fixColumns = function () {
        if (this.options.fixedColumns && this.activeHeadings && this.activeHeadings.length) {
            var T, U = !1;
            if (this.columnWidths = [], this.table.tHead) F(this.activeHeadings, function (Y) {
                Y.style.width = ""
            }, this), F(this.activeHeadings, function (Y, Z) {
                var $ = Y.offsetWidth, _ = 100 * ($ / this.rect.width);
                Y.style.width = _ + "%", this.columnWidths[Z] = $
            }, this); else {
                T = [], U = H("thead");
                var V = H("tr"), W = this.table.tBodies[0].rows[0].cells;
                F(W, function () {
                    var Y = H("th");
                    V.appendChild(Y), T.push(Y)
                }), U.appendChild(V), this.table.insertBefore(U, this.body);
                var X = [];
                F(T, function (Y, Z) {
                    var $ = Y.offsetWidth, _ = 100 * ($ / this.rect.width);
                    X.push(_), this.columnWidths[Z] = $
                }, this), F(this.data, function (Y) {
                    F(Y.cells, function (Z, $) {
                        this.columns(Z.cellIndex).visible() && (Z.style.width = X[$] + "%")
                    }, this)
                }, this), this.table.removeChild(U)
            }
        }
    }, S.fixHeight = function () {
        this.options.fixedHeight && (this.container.style.height = null, this.rect = this.container.getBoundingClientRect(), this.container.style.height = this.rect.height + "px")
    }, S.search = function (T) {
        if (!this.hasRows) return !1;
        var U = this;
        return T = T.toLowerCase(), this.currentPage = 1, this.searching = !0, this.searchData = [], T.length ? void (this.clear(), F(this.data, function (V, W) {
            var X = -1 < this.searchData.indexOf(V), Y = T.split(" ").reduce(function (Z, $) {
                for (var _ = !1, aa = null, ba = null, ca = 0; ca < V.cells.length; ca++) if (aa = V.cells[ca], ba = aa.hasAttribute("data-content") ? aa.getAttribute("data-content") : aa.textContent, -1 < ba.toLowerCase().indexOf($) && U.columns(aa.cellIndex).visible()) {
                    _ = !0;
                    break
                }
                return Z && _
            }, !0);
            Y && !X ? (V.searchIndex = W, this.searchData.push(W)) : V.searchIndex = null
        }, this), K.add(this.wrapper, "search-results"), U.searchData.length ? U.update() : (K.remove(U.wrapper, "search-results"), U.setMessage(U.options.labels.noRows)), this.emit("datatable.search", T, this.searchData)) : (this.searching = !1, this.update(), this.emit("datatable.search", T, this.searchData), K.remove(this.wrapper, "search-results"), !1)
    }, S.page = function (T) {
        return T != this.currentPage && (isNaN(T) || (this.currentPage = parseInt(T, 10)), T > this.pages.length || 0 > T ? !1 : void (this.render("page"), this.render("pager"), this.emit("datatable.page", T)))
    }, S.sortColumn = function (T, U) {
        this.columns().sort(T, U)
    }, S.insert = function (T) {
        var U = this, V = [];
        if (B(T)) {
            if (T.headings && !U.hasHeadings && !U.hasRows) {
                var X, W = H("tr");
                F(T.headings, function (Y) {
                    X = H("th", {html: Y}), W.appendChild(X)
                }), U.head.appendChild(W), U.header = W, U.headings = [].slice.call(W.cells), U.hasHeadings = !0, U.options.sortable = U.initialSortable, U.render("header")
            }
            T.data && C(T.data) && (V = T.data)
        } else C(T) && F(T, function (Y) {
            var Z = [];
            F(Y, function ($, _) {
                var aa = U.labels.indexOf(_);
                -1 < aa && (Z[aa] = $)
            }), V.push(Z)
        });
        V.length && (U.rows().add(V), U.hasRows = !0), U.update(), U.fixColumns()
    }, S.refresh = function () {
        this.options.searchable && (this.input.value = "", this.searching = !1), this.currentPage = 1, this.onFirstPage = !0, this.update(), this.emit("datatable.refresh")
    }, S.clear = function (T) {
        this.body && I(this.body, this.isIE);
        var U = this.body;
        if (this.body || (U = this.table), T) {
            if ("string" == typeof T) {
                var V = u.createDocumentFragment();
                V.innerHTML = T
            }
            U.appendChild(T)
        }
    }, S.export = function (T) {
        if (!this.hasHeadings && !this.hasRows) return !1;
        var X, Y, Z, $, U = this.activeHeadings, V = [], W = [];
        if (!B(T)) return !1;
        var aa = E({
            download: !0,
            skipColumn: [],
            lineDelimiter: "\n",
            columnDelimiter: ",",
            tableName: "myTable",
            replacer: null,
            space: 4
        }, T);
        if (aa.type) {
            if (("txt" === aa.type || "csv" === aa.type) && (V[0] = this.header), !aa.selection) V = V.concat(this.activeRows); else if (!isNaN(aa.selection)) V = V.concat(this.pages[aa.selection - 1]); else if (C(aa.selection)) for (X = 0; X < aa.selection.length; X++) V = V.concat(this.pages[aa.selection[X] - 1]);
            if (V.length) {
                if ("txt" === aa.type || "csv" === aa.type) {
                    for (Z = "", X = 0; X < V.length; X++) {
                        for (Y = 0; Y < V[X].cells.length; Y++) if (0 > aa.skipColumn.indexOf(U[Y].originalCellIndex) && this.columns(U[Y].originalCellIndex).visible()) {
                            var ba = V[X].cells[Y].textContent;
                            ba = ba.trim(), ba = ba.replace(/\s{2,}/g, " "), ba = ba.replace(/\n/g, "  "), ba = ba.replace(/"/g, "\"\""), -1 < ba.indexOf(",") && (ba = "\"" + ba + "\""), Z += ba + aa.columnDelimiter
                        }
                        Z = Z.trim().substring(0, Z.length - 1), Z += aa.lineDelimiter
                    }
                    Z = Z.trim().substring(0, Z.length - 1), aa.download && (Z = "data:text/csv;charset=utf-8," + Z)
                } else if ("sql" === aa.type) {
                    for (Z = "INSERT INTO `" + aa.tableName + "` (", X = 0; X < U.length; X++) 0 > aa.skipColumn.indexOf(U[X].originalCellIndex) && this.columns(U[X].originalCellIndex).visible() && (Z += "`" + U[X].textContent + "`,");
                    for (Z = Z.trim().substring(0, Z.length - 1), Z += ") VALUES ", X = 0; X < V.length; X++) {
                        for (Z += "(", Y = 0; Y < V[X].cells.length; Y++) 0 > aa.skipColumn.indexOf(U[Y].originalCellIndex) && this.columns(U[Y].originalCellIndex).visible() && (Z += "\"" + V[X].cells[Y].textContent + "\",");
                        Z = Z.trim().substring(0, Z.length - 1), Z += "),"
                    }
                    Z = Z.trim().substring(0, Z.length - 1), Z += ";", aa.download && (Z = "data:application/sql;charset=utf-8," + Z)
                } else if ("json" === aa.type) {
                    for (Y = 0; Y < V.length; Y++) for (W[Y] = W[Y] || {}, X = 0; X < U.length; X++) 0 > aa.skipColumn.indexOf(U[X].originalCellIndex) && this.columns(U[X].originalCellIndex).visible() && (W[Y][U[X].textContent] = V[Y].cells[X].textContent);
                    Z = JSON.stringify(W, aa.replacer, aa.space), aa.download && (Z = "data:application/json;charset=utf-8," + Z)
                }
                return aa.download && (aa.filename = aa.filename || "datatable_export", aa.filename += "." + aa.type, Z = encodeURI(Z), $ = document.createElement("a"), $.href = Z, $.download = aa.filename, z.appendChild($), $.click(), z.removeChild($)), Z
            }
        }
        return !1
    }, S.import = function (T) {
        var U = !1;
        if (!B(T)) return !1;
        if (T = E({lineDelimiter: "\n", columnDelimiter: ","}, T), T.data.length || B(T.data)) {
            if ("csv" === T.type) {
                U = {data: []};
                var W = T.data.split(T.lineDelimiter);
                W.length && (T.headings && (U.headings = W[0].split(T.columnDelimiter), W.shift()), F(W, function (Y, Z) {
                    U.data[Z] = [];
                    var $ = Y.split(T.columnDelimiter);
                    $.length && F($, function (_) {
                        U.data[Z].push(_)
                    })
                }))
            } else if ("json" === T.type) {
                var X = D(T.data);
                X ? (U = {headings: [], data: []}, F(X, function (Y, Z) {
                    U.data[Z] = [], F(Y, function ($, _) {
                        0 > U.headings.indexOf(_) && U.headings.push(_), U.data[Z].push($)
                    })
                })) : console.warn("That's not valid JSON!")
            }
            B(T.data) && (U = T.data), U && this.insert(U)
        }
        return !1
    }, S.print = function () {
        var T = this.activeHeadings, U = this.activeRows, V = H("table"), W = H("thead"), X = H("tbody"), Y = H("tr");
        F(T, function ($) {
            Y.appendChild(H("th", {html: $.textContent}))
        }), W.appendChild(Y), F(U, function ($) {
            var _ = H("tr");
            F($.cells, function (aa) {
                _.appendChild(H("td", {html: aa.textContent}))
            }), X.appendChild(_)
        }), V.appendChild(W), V.appendChild(X);
        var Z = q.open();
        Z.document.body.appendChild(V), Z.print()
    }, S.setMessage = function (T) {
        var U = 1;
        this.hasRows && (U = this.data[0].cells.length), K.add(this.wrapper, "dataTable-empty"), this.clear(H("tr", {html: "<td class=\"dataTables-empty\" colspan=\"" + U + "\">" + T + "</td>"}))
    }, S.columns = function (T) {
        return new P(this, T)
    }, S.rows = function (T) {
        return new Q(this, T)
    }, S.on = function (T, U) {
        this.events = this.events || {}, this.events[T] = this.events[T] || [], this.events[T].push(U)
    }, S.off = function (T, U) {
        this.events = this.events || {}, !1 == T in this.events || this.events[T].splice(this.events[T].indexOf(U), 1)
    }, S.emit = function (T) {
        if (this.events = this.events || {}, !1 != T in this.events) for (var U = 0; U < this.events[T].length; U++) this.events[T][U].apply(this, Array.prototype.slice.call(arguments, 1))
    }, R
});