var Aa = Object.defineProperty;
var Na = (e, n, t) => n in e ? Aa(e, n, { enumerable: !0, configurable: !0, writable: !0, value: t }) : e[n] = t;
var w = (e, n, t) => (Na(e, typeof n != "symbol" ? n + "" : n, t), t), Ji = (e, n, t) => {
  if (!n.has(e))
    throw TypeError("Cannot " + t);
};
var m = (e, n, t) => (Ji(e, n, "read from private field"), t ? t.call(e) : n.get(e)), x = (e, n, t) => {
  if (n.has(e))
    throw TypeError("Cannot add the same private member more than once");
  n instanceof WeakSet ? n.add(e) : n.set(e, t);
}, R = (e, n, t, s) => (Ji(e, n, "write to private field"), s ? s.call(e, t) : n.set(e, t), t), ir = (e, n, t, s) => ({
  set _(i) {
    R(e, n, i, t);
  },
  get _() {
    return m(e, n, s);
  }
}), N = (e, n, t) => (Ji(e, n, "access private method"), t);
var rs, z, cl, it, ye, or, al, fo, ul, ks = {}, hl = [], La = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i;
function Bt(e, n) {
  for (var t in n)
    e[t] = n[t];
  return e;
}
function fl(e) {
  var n = e.parentNode;
  n && n.removeChild(e);
}
function E(e, n, t) {
  var s, i, o, r = {};
  for (o in n)
    o == "key" ? s = n[o] : o == "ref" ? i = n[o] : r[o] = n[o];
  if (arguments.length > 2 && (r.children = arguments.length > 3 ? rs.call(arguments, 2) : t), typeof e == "function" && e.defaultProps != null)
    for (o in e.defaultProps)
      r[o] === void 0 && (r[o] = e.defaultProps[o]);
  return bn(e, r, s, i, null);
}
function bn(e, n, t, s, i) {
  var o = { type: e, props: n, key: t, ref: s, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++cl };
  return i == null && z.vnode != null && z.vnode(o), o;
}
function cn() {
  return { current: null };
}
function ls(e) {
  return e.children;
}
function U(e, n) {
  this.props = e, this.context = n;
}
function Tn(e, n) {
  if (n == null)
    return e.__ ? Tn(e.__, e.__.__k.indexOf(e) + 1) : null;
  for (var t; n < e.__k.length; n++)
    if ((t = e.__k[n]) != null && t.__e != null)
      return t.__e;
  return typeof e.type == "function" ? Tn(e) : null;
}
function dl(e) {
  var n, t;
  if ((e = e.__) != null && e.__c != null) {
    for (e.__e = e.__c.base = null, n = 0; n < e.__k.length; n++)
      if ((t = e.__k[n]) != null && t.__e != null) {
        e.__e = e.__c.base = t.__e;
        break;
      }
    return dl(e);
  }
}
function po(e) {
  (!e.__d && (e.__d = !0) && ye.push(e) && !Ts.__r++ || or !== z.debounceRendering) && ((or = z.debounceRendering) || al)(Ts);
}
function Ts() {
  var e, n, t, s, i, o, r, l;
  for (ye.sort(fo); e = ye.shift(); )
    e.__d && (n = ye.length, s = void 0, i = void 0, r = (o = (t = e).__v).__e, (l = t.__P) && (s = [], (i = Bt({}, o)).__v = o.__v + 1, To(l, o, i, t.__n, l.ownerSVGElement !== void 0, o.__h != null ? [r] : null, s, r ?? Tn(o), o.__h), bl(s, o), o.__e != r && dl(o)), ye.length > n && ye.sort(fo));
  Ts.__r = 0;
}
function pl(e, n, t, s, i, o, r, l, a, h) {
  var c, u, d, f, p, g, y, _ = s && s.__k || hl, v = _.length;
  for (t.__k = [], c = 0; c < n.length; c++)
    if ((f = t.__k[c] = (f = n[c]) == null || typeof f == "boolean" || typeof f == "function" ? null : typeof f == "string" || typeof f == "number" || typeof f == "bigint" ? bn(null, f, null, null, f) : Array.isArray(f) ? bn(ls, { children: f }, null, null, null) : f.__b > 0 ? bn(f.type, f.props, f.key, f.ref ? f.ref : null, f.__v) : f) != null) {
      if (f.__ = t, f.__b = t.__b + 1, (d = _[c]) === null || d && f.key == d.key && f.type === d.type)
        _[c] = void 0;
      else
        for (u = 0; u < v; u++) {
          if ((d = _[u]) && f.key == d.key && f.type === d.type) {
            _[u] = void 0;
            break;
          }
          d = null;
        }
      To(e, f, d = d || ks, i, o, r, l, a, h), p = f.__e, (u = f.ref) && d.ref != u && (y || (y = []), d.ref && y.push(d.ref, null, f), y.push(u, f.__c || p, f)), p != null ? (g == null && (g = p), typeof f.type == "function" && f.__k === d.__k ? f.__d = a = ml(f, a, e) : a = yl(e, f, d, _, p, a), typeof t.type == "function" && (t.__d = a)) : a && d.__e == a && a.parentNode != e && (a = Tn(d));
    }
  for (t.__e = g, c = v; c--; )
    _[c] != null && (typeof t.type == "function" && _[c].__e != null && _[c].__e == t.__d && (t.__d = _l(s).nextSibling), vl(_[c], _[c]));
  if (y)
    for (c = 0; c < y.length; c++)
      wl(y[c], y[++c], y[++c]);
}
function ml(e, n, t) {
  for (var s, i = e.__k, o = 0; i && o < i.length; o++)
    (s = i[o]) && (s.__ = e, n = typeof s.type == "function" ? ml(s, n, t) : yl(t, s, s, i, s.__e, n));
  return n;
}
function gl(e, n) {
  return n = n || [], e == null || typeof e == "boolean" || (Array.isArray(e) ? e.some(function(t) {
    gl(t, n);
  }) : n.push(e)), n;
}
function yl(e, n, t, s, i, o) {
  var r, l, a;
  if (n.__d !== void 0)
    r = n.__d, n.__d = void 0;
  else if (t == null || i != o || i.parentNode == null)
    t:
      if (o == null || o.parentNode !== e)
        e.appendChild(i), r = null;
      else {
        for (l = o, a = 0; (l = l.nextSibling) && a < s.length; a += 1)
          if (l == i)
            break t;
        e.insertBefore(i, o), r = o;
      }
  return r !== void 0 ? r : i.nextSibling;
}
function _l(e) {
  var n, t, s;
  if (e.type == null || typeof e.type == "string")
    return e.__e;
  if (e.__k) {
    for (n = e.__k.length - 1; n >= 0; n--)
      if ((t = e.__k[n]) && (s = _l(t)))
        return s;
  }
  return null;
}
function Ma(e, n, t, s, i) {
  var o;
  for (o in t)
    o === "children" || o === "key" || o in n || As(e, o, null, t[o], s);
  for (o in n)
    i && typeof n[o] != "function" || o === "children" || o === "key" || o === "value" || o === "checked" || t[o] === n[o] || As(e, o, n[o], t[o], s);
}
function rr(e, n, t) {
  n[0] === "-" ? e.setProperty(n, t ?? "") : e[n] = t == null ? "" : typeof t != "number" || La.test(n) ? t : t + "px";
}
function As(e, n, t, s, i) {
  var o;
  t:
    if (n === "style")
      if (typeof t == "string")
        e.style.cssText = t;
      else {
        if (typeof s == "string" && (e.style.cssText = s = ""), s)
          for (n in s)
            t && n in t || rr(e.style, n, "");
        if (t)
          for (n in t)
            s && t[n] === s[n] || rr(e.style, n, t[n]);
      }
    else if (n[0] === "o" && n[1] === "n")
      o = n !== (n = n.replace(/Capture$/, "")), n = n.toLowerCase() in e ? n.toLowerCase().slice(2) : n.slice(2), e.l || (e.l = {}), e.l[n + o] = t, t ? s || e.addEventListener(n, o ? cr : lr, o) : e.removeEventListener(n, o ? cr : lr, o);
    else if (n !== "dangerouslySetInnerHTML") {
      if (i)
        n = n.replace(/xlink(H|:h)/, "h").replace(/sName$/, "s");
      else if (n !== "width" && n !== "height" && n !== "href" && n !== "list" && n !== "form" && n !== "tabIndex" && n !== "download" && n in e)
        try {
          e[n] = t ?? "";
          break t;
        } catch {
        }
      typeof t == "function" || (t == null || t === !1 && n[4] !== "-" ? e.removeAttribute(n) : e.setAttribute(n, t));
    }
}
function lr(e) {
  return this.l[e.type + !1](z.event ? z.event(e) : e);
}
function cr(e) {
  return this.l[e.type + !0](z.event ? z.event(e) : e);
}
function To(e, n, t, s, i, o, r, l, a) {
  var h, c, u, d, f, p, g, y, _, v, S, $, T, D, L, O = n.type;
  if (n.constructor !== void 0)
    return null;
  t.__h != null && (a = t.__h, l = n.__e = t.__e, n.__h = null, o = [l]), (h = z.__b) && h(n);
  try {
    t:
      if (typeof O == "function") {
        if (y = n.props, _ = (h = O.contextType) && s[h.__c], v = h ? _ ? _.props.value : h.__ : s, t.__c ? g = (c = n.__c = t.__c).__ = c.__E : ("prototype" in O && O.prototype.render ? n.__c = c = new O(y, v) : (n.__c = c = new U(y, v), c.constructor = O, c.render = Pa), _ && _.sub(c), c.props = y, c.state || (c.state = {}), c.context = v, c.__n = s, u = c.__d = !0, c.__h = [], c._sb = []), c.__s == null && (c.__s = c.state), O.getDerivedStateFromProps != null && (c.__s == c.state && (c.__s = Bt({}, c.__s)), Bt(c.__s, O.getDerivedStateFromProps(y, c.__s))), d = c.props, f = c.state, c.__v = n, u)
          O.getDerivedStateFromProps == null && c.componentWillMount != null && c.componentWillMount(), c.componentDidMount != null && c.__h.push(c.componentDidMount);
        else {
          if (O.getDerivedStateFromProps == null && y !== d && c.componentWillReceiveProps != null && c.componentWillReceiveProps(y, v), !c.__e && c.shouldComponentUpdate != null && c.shouldComponentUpdate(y, c.__s, v) === !1 || n.__v === t.__v) {
            for (n.__v !== t.__v && (c.props = y, c.state = c.__s, c.__d = !1), c.__e = !1, n.__e = t.__e, n.__k = t.__k, n.__k.forEach(function(k) {
              k && (k.__ = n);
            }), S = 0; S < c._sb.length; S++)
              c.__h.push(c._sb[S]);
            c._sb = [], c.__h.length && r.push(c);
            break t;
          }
          c.componentWillUpdate != null && c.componentWillUpdate(y, c.__s, v), c.componentDidUpdate != null && c.__h.push(function() {
            c.componentDidUpdate(d, f, p);
          });
        }
        if (c.context = v, c.props = y, c.__P = e, $ = z.__r, T = 0, "prototype" in O && O.prototype.render) {
          for (c.state = c.__s, c.__d = !1, $ && $(n), h = c.render(c.props, c.state, c.context), D = 0; D < c._sb.length; D++)
            c.__h.push(c._sb[D]);
          c._sb = [];
        } else
          do
            c.__d = !1, $ && $(n), h = c.render(c.props, c.state, c.context), c.state = c.__s;
          while (c.__d && ++T < 25);
        c.state = c.__s, c.getChildContext != null && (s = Bt(Bt({}, s), c.getChildContext())), u || c.getSnapshotBeforeUpdate == null || (p = c.getSnapshotBeforeUpdate(d, f)), L = h != null && h.type === ls && h.key == null ? h.props.children : h, pl(e, Array.isArray(L) ? L : [L], n, t, s, i, o, r, l, a), c.base = n.__e, n.__h = null, c.__h.length && r.push(c), g && (c.__E = c.__ = null), c.__e = !1;
      } else
        o == null && n.__v === t.__v ? (n.__k = t.__k, n.__e = t.__e) : n.__e = Oa(t.__e, n, t, s, i, o, r, a);
    (h = z.diffed) && h(n);
  } catch (k) {
    n.__v = null, (a || o != null) && (n.__e = l, n.__h = !!a, o[o.indexOf(l)] = null), z.__e(k, n, t);
  }
}
function bl(e, n) {
  z.__c && z.__c(n, e), e.some(function(t) {
    try {
      e = t.__h, t.__h = [], e.some(function(s) {
        s.call(t);
      });
    } catch (s) {
      z.__e(s, t.__v);
    }
  });
}
function Oa(e, n, t, s, i, o, r, l) {
  var a, h, c, u = t.props, d = n.props, f = n.type, p = 0;
  if (f === "svg" && (i = !0), o != null) {
    for (; p < o.length; p++)
      if ((a = o[p]) && "setAttribute" in a == !!f && (f ? a.localName === f : a.nodeType === 3)) {
        e = a, o[p] = null;
        break;
      }
  }
  if (e == null) {
    if (f === null)
      return document.createTextNode(d);
    e = i ? document.createElementNS("http://www.w3.org/2000/svg", f) : document.createElement(f, d.is && d), o = null, l = !1;
  }
  if (f === null)
    u === d || l && e.data === d || (e.data = d);
  else {
    if (o = o && rs.call(e.childNodes), h = (u = t.props || ks).dangerouslySetInnerHTML, c = d.dangerouslySetInnerHTML, !l) {
      if (o != null)
        for (u = {}, p = 0; p < e.attributes.length; p++)
          u[e.attributes[p].name] = e.attributes[p].value;
      (c || h) && (c && (h && c.__html == h.__html || c.__html === e.innerHTML) || (e.innerHTML = c && c.__html || ""));
    }
    if (Ma(e, d, u, i, l), c)
      n.__k = [];
    else if (p = n.props.children, pl(e, Array.isArray(p) ? p : [p], n, t, s, i && f !== "foreignObject", o, r, o ? o[0] : t.__k && Tn(t, 0), l), o != null)
      for (p = o.length; p--; )
        o[p] != null && fl(o[p]);
    l || ("value" in d && (p = d.value) !== void 0 && (p !== e.value || f === "progress" && !p || f === "option" && p !== u.value) && As(e, "value", p, u.value, !1), "checked" in d && (p = d.checked) !== void 0 && p !== e.checked && As(e, "checked", p, u.checked, !1));
  }
  return e;
}
function wl(e, n, t) {
  try {
    typeof e == "function" ? e(n) : e.current = n;
  } catch (s) {
    z.__e(s, t);
  }
}
function vl(e, n, t) {
  var s, i;
  if (z.unmount && z.unmount(e), (s = e.ref) && (s.current && s.current !== e.__e || wl(s, null, n)), (s = e.__c) != null) {
    if (s.componentWillUnmount)
      try {
        s.componentWillUnmount();
      } catch (o) {
        z.__e(o, n);
      }
    s.base = s.__P = null, e.__c = void 0;
  }
  if (s = e.__k)
    for (i = 0; i < s.length; i++)
      s[i] && vl(s[i], n, t || typeof e.type != "function");
  t || e.__e == null || fl(e.__e), e.__ = e.__e = e.__d = void 0;
}
function Pa(e, n, t) {
  return this.constructor(e, t);
}
function cs(e, n, t) {
  var s, i, o;
  z.__ && z.__(e, n), i = (s = typeof t == "function") ? null : t && t.__k || n.__k, o = [], To(n, e = (!s && t || n).__k = E(ls, null, [e]), i || ks, ks, n.ownerSVGElement !== void 0, !s && t ? [t] : i ? null : n.firstChild ? rs.call(n.childNodes) : null, o, !s && t ? t : i ? i.__e : n.firstChild, s), bl(o, e);
}
function xl(e, n) {
  cs(e, n, xl);
}
function Da(e, n, t) {
  var s, i, o, r = Bt({}, e.props);
  for (o in n)
    o == "key" ? s = n[o] : o == "ref" ? i = n[o] : r[o] = n[o];
  return arguments.length > 2 && (r.children = arguments.length > 3 ? rs.call(arguments, 2) : t), bn(e.type, r, s || e.key, i || e.ref, null);
}
function Ha(e, n) {
  var t = { __c: n = "__cC" + ul++, __: e, Consumer: function(s, i) {
    return s.children(i);
  }, Provider: function(s) {
    var i, o;
    return this.getChildContext || (i = [], (o = {})[n] = this, this.getChildContext = function() {
      return o;
    }, this.shouldComponentUpdate = function(r) {
      this.props.value !== r.value && i.some(function(l) {
        l.__e = !0, po(l);
      });
    }, this.sub = function(r) {
      i.push(r);
      var l = r.componentWillUnmount;
      r.componentWillUnmount = function() {
        i.splice(i.indexOf(r), 1), l && l.call(r);
      };
    }), s.children;
  } };
  return t.Provider.__ = t.Consumer.contextType = t;
}
rs = hl.slice, z = { __e: function(e, n, t, s) {
  for (var i, o, r; n = n.__; )
    if ((i = n.__c) && !i.__)
      try {
        if ((o = i.constructor) && o.getDerivedStateFromError != null && (i.setState(o.getDerivedStateFromError(e)), r = i.__d), i.componentDidCatch != null && (i.componentDidCatch(e, s || {}), r = i.__d), r)
          return i.__E = i;
      } catch (l) {
        e = l;
      }
  throw e;
} }, cl = 0, it = function(e) {
  return e != null && e.constructor === void 0;
}, U.prototype.setState = function(e, n) {
  var t;
  t = this.__s != null && this.__s !== this.state ? this.__s : this.__s = Bt({}, this.state), typeof e == "function" && (e = e(Bt({}, t), this.props)), e && Bt(t, e), e != null && this.__v && (n && this._sb.push(n), po(this));
}, U.prototype.forceUpdate = function(e) {
  this.__v && (this.__e = !0, e && this.__h.push(e), po(this));
}, U.prototype.render = ls, ye = [], al = typeof Promise == "function" ? Promise.prototype.then.bind(Promise.resolve()) : setTimeout, fo = function(e, n) {
  return e.__v.__b - n.__v.__b;
}, Ts.__r = 0, ul = 0;
const Ia = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  Component: U,
  Fragment: ls,
  cloneElement: Da,
  createContext: Ha,
  createElement: E,
  createRef: cn,
  h: E,
  hydrate: xl,
  get isValidElement() {
    return it;
  },
  get options() {
    return z;
  },
  render: cs,
  toChildArray: gl
}, Symbol.toStringTag, { value: "Module" }));
var ja = 0;
function b(e, n, t, s, i, o) {
  var r, l, a = {};
  for (l in n)
    l == "ref" ? r = n[l] : a[l] = n[l];
  var h = { type: e, props: a, key: t, ref: r, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --ja, __source: i, __self: o };
  if (typeof e == "function" && (r = e.defaultProps))
    for (l in r)
      a[l] === void 0 && (a[l] = r[l]);
  return z.vnode && z.vnode(h), h;
}
var Dt;
class Wa {
  constructor(n = "") {
    x(this, Dt, void 0);
    typeof n == "object" ? R(this, Dt, n) : R(this, Dt, document.appendChild(document.createComment(n)));
  }
  on(n, t, s) {
    m(this, Dt).addEventListener(n, t, s);
  }
  once(n, t, s) {
    m(this, Dt).addEventListener(n, t, { once: !0, ...s });
  }
  off(n, t, s) {
    m(this, Dt).removeEventListener(n, t, s);
  }
  emit(n) {
    return m(this, Dt).dispatchEvent(n), n;
  }
}
Dt = new WeakMap();
const mo = /* @__PURE__ */ new Set([
  "click",
  "dblclick",
  "mouseup",
  "mousedown",
  "contextmenu",
  "mousewheel",
  "DOMMouseScroll",
  "mouseover",
  "mouseout",
  "mousemove",
  "selectstart",
  "selectend",
  "keydown",
  "keypress",
  "keyup",
  "orientationchange",
  "touchstart",
  "touchmove",
  "touchend",
  "touchcancel",
  "pointerdown",
  "pointermove",
  "pointerup",
  "pointerleave",
  "pointercancel",
  "gesturestart",
  "gesturechange",
  "gestureend",
  "focus",
  "blur",
  "change",
  "reset",
  "select",
  "submit",
  "focusin",
  "focusout",
  "load",
  "unload",
  "beforeunload",
  "resize",
  "move",
  "DOMContentLoaded",
  "readystatechange",
  "error",
  "abort",
  "scroll"
]);
class Hi extends Wa {
  on(n, t, s) {
    super.on(n, t, s);
  }
  off(n, t, s) {
    super.off(n, t, s);
  }
  once(n, t, s) {
    super.once(n, t, s);
  }
  emit(n, t) {
    return typeof n == "string" && (mo.has(n) ? (n = new Event(n), Object.assign(n, { detail: t })) : n = new CustomEvent(n, { detail: t })), super.emit(Hi.createEvent(n, t));
  }
  static createEvent(n, t) {
    return typeof n == "string" && (mo.has(n) ? (n = new Event(n), Object.assign(n, { detail: t })) : n = new CustomEvent(n, { detail: t })), n;
  }
}
var Ht, Pn, be, pn;
class ar extends Hi {
  constructor(t = "", s) {
    super(t);
    x(this, be);
    x(this, Ht, /* @__PURE__ */ new Map());
    x(this, Pn, void 0);
    R(this, Pn, s == null ? void 0 : s.customEventSuffix);
  }
  on(t, s, i) {
    t = N(this, be, pn).call(this, t), super.on(t, s, i), m(this, Ht).set(s, [t, i]);
  }
  off(t, s, i) {
    t = N(this, be, pn).call(this, t), super.off(t, s, i), m(this, Ht).delete(s);
  }
  once(t, s, i) {
    t = N(this, be, pn).call(this, t);
    const o = (r) => {
      s(r), m(this, Ht).delete(o);
    };
    super.once(t, o, i), m(this, Ht).set(o, [t, i]);
  }
  emit(t, s) {
    return typeof t == "string" && (t = N(this, be, pn).call(this, t)), super.emit(t, s);
  }
  offAll() {
    Array.from(m(this, Ht).entries()).forEach(([t, [s, i]]) => {
      super.off(s, t, i);
    }), m(this, Ht).clear();
  }
}
Ht = new WeakMap(), Pn = new WeakMap(), be = new WeakSet(), pn = function(t) {
  const s = m(this, Pn);
  return mo.has(t) || typeof s != "string" || t.endsWith(s) ? t : `${t}${s}`;
};
function Fa(e, n) {
  if (e == null)
    return [e, void 0];
  typeof n == "string" && (n = n.split("."));
  const t = n.join(".");
  let s = e;
  const i = [s];
  for (; typeof s == "object" && s !== null && n.length; ) {
    let o = n.shift(), r;
    const l = o.indexOf("[");
    if (l > 0 && l < o.length - 1 && o.endsWith("]") && (r = o.substring(l + 1, o.length - 1), o = o.substring(0, l)), s = s[o], i.push(s), r !== void 0)
      if (typeof s == "object" && s !== null)
        s instanceof Map ? s = s.get(r) : s = s[r], i.push(s);
      else
        throw new Error(`Cannot access property "${o}[${r}]", the full path is "${t}".`);
  }
  if (n.length)
    throw new Error(`Cannot access property with rest path "${n.join(".")}", the full path is "${t}".`);
  return i;
}
function Ba(e, n, t) {
  const s = Fa(e, n), i = s[s.length - 1];
  return i === void 0 ? t : i;
}
function Qi(e) {
  return !!e && typeof e == "object" && !Array.isArray(e);
}
function go(e, ...n) {
  if (!n.length)
    return e;
  const t = n.shift();
  if (Qi(e) && Qi(t))
    for (const s in t)
      Qi(t[s]) ? (e[s] || Object.assign(e, { [s]: {} }), go(e[s], t[s])) : Object.assign(e, { [s]: t[s] });
  return go(e, ...n);
}
function tt(e, ...n) {
  if (n.length === 0)
    return e;
  if (n.length === 1 && typeof n[0] == "object" && n[0]) {
    const t = n[0];
    return Object.keys(t).forEach((s) => {
      const i = t[s] ?? 0;
      e = e.replace(new RegExp(`\\{${s}\\}`, "g"), `${i}`);
    }), e;
  }
  for (let t = 0; t < n.length; t++) {
    const s = n[t] ?? "";
    e = e.replace(new RegExp(`\\{${t}\\}`, "g"), `${s}`);
  }
  return e;
}
var Ao = /* @__PURE__ */ ((e) => (e[e.B = 1] = "B", e[e.KB = 1024] = "KB", e[e.MB = 1048576] = "MB", e[e.GB = 1073741824] = "GB", e[e.TB = 1099511627776] = "TB", e))(Ao || {});
function dd(e, n = 2, t = "") {
  return Number.isNaN(e) ? "?KB" : (t || (e < 1024 ? t = "B" : e < 1048576 ? t = "KB" : e < 1073741824 ? t = "MB" : e < 1099511627776 ? t = "GB" : t = "TB"), (e / Ao[t]).toFixed(n) + t);
}
const pd = (e) => {
  const n = /^[0-9]*(B|KB|MB|GB|TB)$/;
  e = e.toUpperCase();
  const t = e.match(n);
  if (!t)
    return 0;
  const s = t[1];
  return e = e.replace(s, ""), Number.parseInt(e, 10) * Ao[s];
};
var ll;
let No = ((ll = document.documentElement.getAttribute("lang")) == null ? void 0 : ll.toLowerCase()) ?? "zh_cn", Xt;
function za() {
  return No;
}
function Ua(e) {
  No = e.toLowerCase();
}
function Va(e, n) {
  Xt || (Xt = {}), typeof e == "string" && (e = { [e]: n ?? {} }), go(Xt, e);
}
function as(e, n, t, s, i, o) {
  Array.isArray(e) ? Xt && e.unshift(Xt) : e = Xt ? [Xt, e] : [e], typeof t == "string" && (o = i, i = s, s = t, t = void 0);
  const r = i || No;
  let l;
  for (const a of e) {
    if (!a)
      continue;
    const h = a[r];
    if (!h)
      continue;
    const c = o && a === Xt ? `${o}.${n}` : n;
    if (l = Ba(h, c), l !== void 0)
      break;
  }
  return l === void 0 ? s : t ? tt(l, ...Array.isArray(t) ? t : [t]) : l;
}
as.addLang = Va;
as.getCode = za;
as.setCode = Ua;
function qa(e) {
  return Object.fromEntries(Object.entries(e).map(([n, t]) => {
    if (typeof t == "string")
      try {
        t = JSON.parse(t);
      } catch {
      }
    return [n, t];
  }));
}
const Zi = /* @__PURE__ */ new Map();
var It, De, mt;
class kt {
  constructor(n, t) {
    x(this, It, void 0);
    x(this, De, void 0);
    x(this, mt, void 0);
    n = typeof n == "string" ? document.querySelector(n) : n, this.constructor.EVENTS && R(this, mt, new ar(n, { customEventSuffix: `.${this.constructor.KEY}` })), R(this, It, { ...this.constructor.DEFAULT }), this.setOptions({ ...n instanceof HTMLElement ? qa(n.dataset) : null, ...t }), this.constructor.all.set(n, this), R(this, De, n), this.init(), requestAnimationFrame(() => {
      this.afterInit(), this.emit("inited", this);
    });
  }
  get options() {
    return m(this, It);
  }
  get element() {
    return m(this, De);
  }
  get events() {
    return m(this, mt);
  }
  init() {
  }
  afterInit() {
  }
  setOptions(n) {
    return n && Object.assign(m(this, It), n), m(this, It);
  }
  render(n) {
    this.setOptions(n);
  }
  destroy() {
    this.constructor.all.delete(m(this, De)), m(this, mt) && (this.emit("destroyed", this), m(this, mt).offAll());
  }
  on(n, t, s) {
    var i;
    (i = m(this, mt)) == null || i.on(n, t, s);
  }
  once(n, t, s) {
    var i;
    (i = m(this, mt)) == null || i.once(n, t, s);
  }
  off(n, t, s) {
    var i;
    (i = m(this, mt)) == null || i.off(n, t, s);
  }
  emit(n, t, s) {
    var o;
    let i = ar.createEvent(n, t);
    if (s !== !1) {
      const r = s || `on${n[0].toUpperCase()}${n.substring(1)}`, l = m(this, It)[r];
      l && l(i) === !1 && (i.preventDefault(), i.stopPropagation());
    }
    return i = (o = m(this, mt)) == null ? void 0 : o.emit(n, t), i;
  }
  i18n(n, t, s) {
    return as(m(this, It).i18n, n, t, s, this.options.lang, this.constructor.NAME) ?? `{i18n:${n}}`;
  }
  /**
   * Component internal name, like "Menu"
   */
  static get NAME() {
    throw new Error(`static NAME should be override in class ${this.name}`);
  }
  /**
   * Component data key, like "zui.menu"
   */
  static get KEY() {
    return `zui.${this.NAME}`;
  }
  static get all() {
    const n = this.NAME;
    if (Zi.has(n))
      return Zi.get(n);
    const t = /* @__PURE__ */ new Map();
    return Zi.set(n, t), t;
  }
  static getAll() {
    return this.all;
  }
  static get(n) {
    return this.all.get(n);
  }
  static ensure(n, t) {
    return this.get(n) || new this(n, t);
  }
}
It = new WeakMap(), De = new WeakMap(), mt = new WeakMap(), w(kt, "EVENTS", !1), w(kt, "DEFAULT", {});
class J extends kt {
  constructor() {
    super(...arguments);
    w(this, "ref", cn());
  }
  get $() {
    return this.ref.current;
  }
  init() {
    requestAnimationFrame(() => this.render());
  }
  destroy() {
    super.destroy(), this.element.innerHTML = "";
  }
  render(t) {
    const s = this.constructor.Component;
    cs(/* @__PURE__ */ b(s, { ref: this.ref, ...this.setOptions(t) }), this.element);
  }
}
w(J, "Component");
var Lo, q, Sl, El, wn, ur, Cl = {}, $l = [], Ga = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i;
function ie(e, n) {
  for (var t in n)
    e[t] = n[t];
  return e;
}
function Rl(e) {
  var n = e.parentNode;
  n && n.removeChild(e);
}
function an(e, n, t) {
  var s, i, o, r = {};
  for (o in n)
    o == "key" ? s = n[o] : o == "ref" ? i = n[o] : r[o] = n[o];
  if (arguments.length > 2 && (r.children = arguments.length > 3 ? Lo.call(arguments, 2) : t), typeof e == "function" && e.defaultProps != null)
    for (o in e.defaultProps)
      r[o] === void 0 && (r[o] = e.defaultProps[o]);
  return vs(e, r, s, i, null);
}
function vs(e, n, t, s, i) {
  var o = { type: e, props: n, key: t, ref: s, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++Sl };
  return i == null && q.vnode != null && q.vnode(o), o;
}
function Ka() {
  return { current: null };
}
function Mo(e) {
  return e.children;
}
function vn(e, n) {
  this.props = e, this.context = n;
}
function An(e, n) {
  if (n == null)
    return e.__ ? An(e.__, e.__.__k.indexOf(e) + 1) : null;
  for (var t; n < e.__k.length; n++)
    if ((t = e.__k[n]) != null && t.__e != null)
      return t.__e;
  return typeof e.type == "function" ? An(e) : null;
}
function kl(e) {
  var n, t;
  if ((e = e.__) != null && e.__c != null) {
    for (e.__e = e.__c.base = null, n = 0; n < e.__k.length; n++)
      if ((t = e.__k[n]) != null && t.__e != null) {
        e.__e = e.__c.base = t.__e;
        break;
      }
    return kl(e);
  }
}
function hr(e) {
  (!e.__d && (e.__d = !0) && wn.push(e) && !Ns.__r++ || ur !== q.debounceRendering) && ((ur = q.debounceRendering) || setTimeout)(Ns);
}
function Ns() {
  for (var e; Ns.__r = wn.length; )
    e = wn.sort(function(n, t) {
      return n.__v.__b - t.__v.__b;
    }), wn = [], e.some(function(n) {
      var t, s, i, o, r, l;
      n.__d && (r = (o = (t = n).__v).__e, (l = t.__P) && (s = [], (i = ie({}, o)).__v = o.__v + 1, Ll(l, o, i, t.__n, l.ownerSVGElement !== void 0, o.__h != null ? [r] : null, s, r ?? An(o), o.__h), Xa(s, o), o.__e != r && kl(o)));
    });
}
function Tl(e, n, t, s, i, o, r, l, a, h) {
  var c, u, d, f, p, g, y, _ = s && s.__k || $l, v = _.length;
  for (t.__k = [], c = 0; c < n.length; c++)
    if ((f = t.__k[c] = (f = n[c]) == null || typeof f == "boolean" ? null : typeof f == "string" || typeof f == "number" || typeof f == "bigint" ? vs(null, f, null, null, f) : Array.isArray(f) ? vs(Mo, { children: f }, null, null, null) : f.__b > 0 ? vs(f.type, f.props, f.key, f.ref ? f.ref : null, f.__v) : f) != null) {
      if (f.__ = t, f.__b = t.__b + 1, (d = _[c]) === null || d && f.key == d.key && f.type === d.type)
        _[c] = void 0;
      else
        for (u = 0; u < v; u++) {
          if ((d = _[u]) && f.key == d.key && f.type === d.type) {
            _[u] = void 0;
            break;
          }
          d = null;
        }
      Ll(e, f, d = d || Cl, i, o, r, l, a, h), p = f.__e, (u = f.ref) && d.ref != u && (y || (y = []), d.ref && y.push(d.ref, null, f), y.push(u, f.__c || p, f)), p != null ? (g == null && (g = p), typeof f.type == "function" && f.__k === d.__k ? f.__d = a = Al(f, a, e) : a = Nl(e, f, d, _, p, a), typeof t.type == "function" && (t.__d = a)) : a && d.__e == a && a.parentNode != e && (a = An(d));
    }
  for (t.__e = g, c = v; c--; )
    _[c] != null && Ol(_[c], _[c]);
  if (y)
    for (c = 0; c < y.length; c++)
      Ml(y[c], y[++c], y[++c]);
}
function Al(e, n, t) {
  for (var s, i = e.__k, o = 0; i && o < i.length; o++)
    (s = i[o]) && (s.__ = e, n = typeof s.type == "function" ? Al(s, n, t) : Nl(t, s, s, i, s.__e, n));
  return n;
}
function Nl(e, n, t, s, i, o) {
  var r, l, a;
  if (n.__d !== void 0)
    r = n.__d, n.__d = void 0;
  else if (t == null || i != o || i.parentNode == null)
    t:
      if (o == null || o.parentNode !== e)
        e.appendChild(i), r = null;
      else {
        for (l = o, a = 0; (l = l.nextSibling) && a < s.length; a += 2)
          if (l == i)
            break t;
        e.insertBefore(i, o), r = o;
      }
  return r !== void 0 ? r : i.nextSibling;
}
function Ya(e, n, t, s, i) {
  var o;
  for (o in t)
    o === "children" || o === "key" || o in n || Ls(e, o, null, t[o], s);
  for (o in n)
    i && typeof n[o] != "function" || o === "children" || o === "key" || o === "value" || o === "checked" || t[o] === n[o] || Ls(e, o, n[o], t[o], s);
}
function fr(e, n, t) {
  n[0] === "-" ? e.setProperty(n, t) : e[n] = t == null ? "" : typeof t != "number" || Ga.test(n) ? t : t + "px";
}
function Ls(e, n, t, s, i) {
  var o;
  t:
    if (n === "style")
      if (typeof t == "string")
        e.style.cssText = t;
      else {
        if (typeof s == "string" && (e.style.cssText = s = ""), s)
          for (n in s)
            t && n in t || fr(e.style, n, "");
        if (t)
          for (n in t)
            s && t[n] === s[n] || fr(e.style, n, t[n]);
      }
    else if (n[0] === "o" && n[1] === "n")
      o = n !== (n = n.replace(/Capture$/, "")), n = n.toLowerCase() in e ? n.toLowerCase().slice(2) : n.slice(2), e.l || (e.l = {}), e.l[n + o] = t, t ? s || e.addEventListener(n, o ? pr : dr, o) : e.removeEventListener(n, o ? pr : dr, o);
    else if (n !== "dangerouslySetInnerHTML") {
      if (i)
        n = n.replace(/xlink(H|:h)/, "h").replace(/sName$/, "s");
      else if (n !== "href" && n !== "list" && n !== "form" && n !== "tabIndex" && n !== "download" && n in e)
        try {
          e[n] = t ?? "";
          break t;
        } catch {
        }
      typeof t == "function" || (t == null || t === !1 && n.indexOf("-") == -1 ? e.removeAttribute(n) : e.setAttribute(n, t));
    }
}
function dr(e) {
  this.l[e.type + !1](q.event ? q.event(e) : e);
}
function pr(e) {
  this.l[e.type + !0](q.event ? q.event(e) : e);
}
function Ll(e, n, t, s, i, o, r, l, a) {
  var h, c, u, d, f, p, g, y, _, v, S, $, T, D, L, O = n.type;
  if (n.constructor !== void 0)
    return null;
  t.__h != null && (a = t.__h, l = n.__e = t.__e, n.__h = null, o = [l]), (h = q.__b) && h(n);
  try {
    t:
      if (typeof O == "function") {
        if (y = n.props, _ = (h = O.contextType) && s[h.__c], v = h ? _ ? _.props.value : h.__ : s, t.__c ? g = (c = n.__c = t.__c).__ = c.__E : ("prototype" in O && O.prototype.render ? n.__c = c = new O(y, v) : (n.__c = c = new vn(y, v), c.constructor = O, c.render = Qa), _ && _.sub(c), c.props = y, c.state || (c.state = {}), c.context = v, c.__n = s, u = c.__d = !0, c.__h = [], c._sb = []), c.__s == null && (c.__s = c.state), O.getDerivedStateFromProps != null && (c.__s == c.state && (c.__s = ie({}, c.__s)), ie(c.__s, O.getDerivedStateFromProps(y, c.__s))), d = c.props, f = c.state, u)
          O.getDerivedStateFromProps == null && c.componentWillMount != null && c.componentWillMount(), c.componentDidMount != null && c.__h.push(c.componentDidMount);
        else {
          if (O.getDerivedStateFromProps == null && y !== d && c.componentWillReceiveProps != null && c.componentWillReceiveProps(y, v), !c.__e && c.shouldComponentUpdate != null && c.shouldComponentUpdate(y, c.__s, v) === !1 || n.__v === t.__v) {
            for (c.props = y, c.state = c.__s, n.__v !== t.__v && (c.__d = !1), c.__v = n, n.__e = t.__e, n.__k = t.__k, n.__k.forEach(function(k) {
              k && (k.__ = n);
            }), S = 0; S < c._sb.length; S++)
              c.__h.push(c._sb[S]);
            c._sb = [], c.__h.length && r.push(c);
            break t;
          }
          c.componentWillUpdate != null && c.componentWillUpdate(y, c.__s, v), c.componentDidUpdate != null && c.__h.push(function() {
            c.componentDidUpdate(d, f, p);
          });
        }
        if (c.context = v, c.props = y, c.__v = n, c.__P = e, $ = q.__r, T = 0, "prototype" in O && O.prototype.render) {
          for (c.state = c.__s, c.__d = !1, $ && $(n), h = c.render(c.props, c.state, c.context), D = 0; D < c._sb.length; D++)
            c.__h.push(c._sb[D]);
          c._sb = [];
        } else
          do
            c.__d = !1, $ && $(n), h = c.render(c.props, c.state, c.context), c.state = c.__s;
          while (c.__d && ++T < 25);
        c.state = c.__s, c.getChildContext != null && (s = ie(ie({}, s), c.getChildContext())), u || c.getSnapshotBeforeUpdate == null || (p = c.getSnapshotBeforeUpdate(d, f)), L = h != null && h.type === Mo && h.key == null ? h.props.children : h, Tl(e, Array.isArray(L) ? L : [L], n, t, s, i, o, r, l, a), c.base = n.__e, n.__h = null, c.__h.length && r.push(c), g && (c.__E = c.__ = null), c.__e = !1;
      } else
        o == null && n.__v === t.__v ? (n.__k = t.__k, n.__e = t.__e) : n.__e = Ja(t.__e, n, t, s, i, o, r, a);
    (h = q.diffed) && h(n);
  } catch (k) {
    n.__v = null, (a || o != null) && (n.__e = l, n.__h = !!a, o[o.indexOf(l)] = null), q.__e(k, n, t);
  }
}
function Xa(e, n) {
  q.__c && q.__c(n, e), e.some(function(t) {
    try {
      e = t.__h, t.__h = [], e.some(function(s) {
        s.call(t);
      });
    } catch (s) {
      q.__e(s, t.__v);
    }
  });
}
function Ja(e, n, t, s, i, o, r, l) {
  var a, h, c, u = t.props, d = n.props, f = n.type, p = 0;
  if (f === "svg" && (i = !0), o != null) {
    for (; p < o.length; p++)
      if ((a = o[p]) && "setAttribute" in a == !!f && (f ? a.localName === f : a.nodeType === 3)) {
        e = a, o[p] = null;
        break;
      }
  }
  if (e == null) {
    if (f === null)
      return document.createTextNode(d);
    e = i ? document.createElementNS("http://www.w3.org/2000/svg", f) : document.createElement(f, d.is && d), o = null, l = !1;
  }
  if (f === null)
    u === d || l && e.data === d || (e.data = d);
  else {
    if (o = o && Lo.call(e.childNodes), h = (u = t.props || Cl).dangerouslySetInnerHTML, c = d.dangerouslySetInnerHTML, !l) {
      if (o != null)
        for (u = {}, p = 0; p < e.attributes.length; p++)
          u[e.attributes[p].name] = e.attributes[p].value;
      (c || h) && (c && (h && c.__html == h.__html || c.__html === e.innerHTML) || (e.innerHTML = c && c.__html || ""));
    }
    if (Ya(e, d, u, i, l), c)
      n.__k = [];
    else if (p = n.props.children, Tl(e, Array.isArray(p) ? p : [p], n, t, s, i && f !== "foreignObject", o, r, o ? o[0] : t.__k && An(t, 0), l), o != null)
      for (p = o.length; p--; )
        o[p] != null && Rl(o[p]);
    l || ("value" in d && (p = d.value) !== void 0 && (p !== e.value || f === "progress" && !p || f === "option" && p !== u.value) && Ls(e, "value", p, u.value, !1), "checked" in d && (p = d.checked) !== void 0 && p !== e.checked && Ls(e, "checked", p, u.checked, !1));
  }
  return e;
}
function Ml(e, n, t) {
  try {
    typeof e == "function" ? e(n) : e.current = n;
  } catch (s) {
    q.__e(s, t);
  }
}
function Ol(e, n, t) {
  var s, i;
  if (q.unmount && q.unmount(e), (s = e.ref) && (s.current && s.current !== e.__e || Ml(s, null, n)), (s = e.__c) != null) {
    if (s.componentWillUnmount)
      try {
        s.componentWillUnmount();
      } catch (o) {
        q.__e(o, n);
      }
    s.base = s.__P = null, e.__c = void 0;
  }
  if (s = e.__k)
    for (i = 0; i < s.length; i++)
      s[i] && Ol(s[i], n, t || typeof e.type != "function");
  t || e.__e == null || Rl(e.__e), e.__ = e.__e = e.__d = void 0;
}
function Qa(e, n, t) {
  return this.constructor(e, t);
}
Lo = $l.slice, q = { __e: function(e, n, t, s) {
  for (var i, o, r; n = n.__; )
    if ((i = n.__c) && !i.__)
      try {
        if ((o = i.constructor) && o.getDerivedStateFromError != null && (i.setState(o.getDerivedStateFromError(e)), r = i.__d), i.componentDidCatch != null && (i.componentDidCatch(e, s || {}), r = i.__d), r)
          return i.__E = i;
      } catch (l) {
        e = l;
      }
  throw e;
} }, Sl = 0, El = function(e) {
  return e != null && e.constructor === void 0;
}, vn.prototype.setState = function(e, n) {
  var t;
  t = this.__s != null && this.__s !== this.state ? this.__s : this.__s = ie({}, this.state), typeof e == "function" && (e = e(ie({}, t), this.props)), e && ie(t, e), e != null && this.__v && (n && this._sb.push(n), hr(this));
}, vn.prototype.forceUpdate = function(e) {
  this.__v && (this.__e = !0, e && this.__h.push(e), hr(this));
}, vn.prototype.render = Mo, wn = [], Ns.__r = 0;
var Za = 0;
function ft(e, n, t, s, i) {
  var o, r, l = {};
  for (r in n)
    r == "ref" ? o = n[r] : l[r] = n[r];
  var a = { type: e, props: l, key: t, ref: o, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --Za, __source: i, __self: s };
  if (typeof e == "function" && (o = e.defaultProps))
    for (r in o)
      l[r] === void 0 && (l[r] = o[r]);
  return q.vnode && q.vnode(a), a;
}
function Ii(...e) {
  const n = [], t = /* @__PURE__ */ new Map(), s = (i, o) => {
    if (Array.isArray(i) && (o = i[1], i = i[0]), !i.length)
      return;
    const r = t.get(i);
    typeof r == "number" ? n[r][1] = !!o : (t.set(i, n.length), n.push([i, !!o]));
  };
  return e.forEach((i) => {
    typeof i == "function" && (i = i()), Array.isArray(i) ? Ii(...i).forEach(s) : i && typeof i == "object" ? Object.entries(i).forEach(s) : typeof i == "string" && i.split(" ").forEach((o) => s(o, !0));
  }), n.sort((i, o) => (t.get(i[0]) || 0) - (t.get(o[0]) || 0));
}
const M = (...e) => Ii(...e).reduce((n, [t, s]) => (s && n.push(t), n), []).join(" ");
function tu({
  component: e = "div",
  className: n,
  children: t,
  style: s,
  attrs: i
}) {
  return an(e, {
    className: M(n),
    style: s,
    ...i
  }, t);
}
function Pl({
  component: e = "a",
  className: n,
  children: t,
  attrs: s,
  url: i,
  disabled: o,
  active: r,
  icon: l,
  text: a,
  target: h,
  trailingIcon: c,
  hint: u,
  onClick: d,
  ...f
}) {
  const p = [
    typeof l == "string" ? /* @__PURE__ */ ft("i", { class: `icon ${l}` }) : l,
    /* @__PURE__ */ ft("span", { className: "text", children: a }),
    typeof t == "function" ? t() : t,
    typeof c == "string" ? /* @__PURE__ */ ft("i", { class: `icon ${c}` }) : c
  ];
  return an(e, {
    className: M(n, { disabled: o, active: r }),
    title: u,
    [e === "a" ? "href" : "data-url"]: i,
    [e === "a" ? "target" : "data-target"]: h,
    onClick: d,
    ...f,
    ...s
  }, ...p);
}
function eu({
  component: e = "div",
  className: n,
  text: t,
  attrs: s,
  children: i,
  style: o,
  onClick: r
}) {
  return an(e, {
    className: M(n),
    style: o,
    onClick: r,
    ...s
  }, t, typeof i == "function" ? i() : i);
}
function nu({
  component: e = "div",
  className: n,
  style: t,
  space: s,
  flex: i,
  attrs: o,
  onClick: r,
  children: l
}) {
  return an(e, {
    className: M(n),
    style: { width: s, height: s, flex: i, ...t },
    onClick: r,
    ...o
  }, l);
}
function su(e) {
  const {
    tag: n,
    className: t,
    style: s,
    renders: i,
    generateArgs: o = [],
    generatorThis: r,
    generators: l,
    onGenerate: a,
    onRenderItem: h,
    ...c
  } = e, u = [t], d = { ...s }, f = [], p = [];
  return i.forEach((g) => {
    const y = [];
    typeof g == "string" && l && l[g] && (g = l[g]), typeof g == "function" ? a ? y.push(...a.call(r, g, f, ...o)) : y.push(...g.call(r, f, ...o) ?? []) : y.push(g), y.forEach((_) => {
      _ != null && (typeof _ == "object" && !it(_) && ("html" in _ || "__html" in _ || "className" in _ || "style" in _ || "attrs" in _ || "children" in _) ? _.html ? f.push(
        /* @__PURE__ */ b("div", { className: M(_.className), style: _.style, dangerouslySetInnerHTML: { __html: _.html }, ..._.attrs ?? {} })
      ) : _.__html ? p.push(_.__html) : (_.style && Object.assign(d, _.style), _.className && u.push(_.className), _.children && f.push(_.children), _.attrs && Object.assign(c, _.attrs)) : f.push(_));
    });
  }), p.length && Object.assign(c, { dangerouslySetInnerHTML: { __html: p } }), [{
    className: M(u),
    style: d,
    ...c
  }, f];
}
function yo({
  tag: e = "div",
  ...n
}) {
  const [t, s] = su(n);
  return E(e, t, ...s);
}
function iu({ type: e, ...n }) {
  return /* @__PURE__ */ ft(yo, { ...n });
}
function Dl({
  component: e = "div",
  className: n,
  children: t,
  style: s,
  attrs: i
}) {
  return an(e, {
    className: M(n),
    style: s,
    ...i
  }, t);
}
var Ft;
let ji = (Ft = class extends vn {
  constructor() {
    super(...arguments);
    w(this, "ref", Ka());
  }
  get name() {
    return this.props.name ?? this.constructor.NAME;
  }
  componentDidMount() {
    this.afterRender(!0);
  }
  componentDidUpdate() {
    this.afterRender(!1);
  }
  componentWillUnmount() {
    var t, s;
    (s = (t = this.props).beforeDestroy) == null || s.call(t, { menu: this });
  }
  afterRender(t) {
    var s, i;
    (i = (s = this.props).afterRender) == null || i.call(s, { menu: this, firstRender: t });
  }
  handleItemClick(t, s, i, o) {
    i && i.call(o.target, o);
    const { onClickItem: r } = this.props;
    r && r({ menu: this, item: t, index: s, event: o });
  }
  beforeRender() {
    var i;
    const t = { ...this.props };
    typeof t.items == "function" && (t.items = t.items(this));
    const s = (i = t.beforeRender) == null ? void 0 : i.call(t, { menu: this, options: t });
    return s && Object.assign(t, s), t;
  }
  getItemRenderProps(t, s, i) {
    const { commonItemProps: o, onClickItem: r } = t, l = { key: i, ...s };
    return o && Object.assign(l, o[s.type || "item"]), (r || s.onClick) && (l.onClick = this.handleItemClick.bind(this, l, i, s.onClick)), l.className = M(l.className), l;
  }
  renderItem(t, s, i) {
    const o = this.getItemRenderProps(t, s, i), { itemRender: r } = t;
    if (r) {
      if (typeof r == "object") {
        const y = r[s.type || "item"];
        if (y)
          return /* @__PURE__ */ ft(y, { ...o });
      } else if (typeof r == "function") {
        const y = r.call(this, o, an);
        if (El(y))
          return y;
        typeof y == "object" && Object.assign(o, y);
      }
    }
    const { type: l = "item", component: a, key: h = i, rootAttrs: c, rootClass: u, rootStyle: d, rootChildren: f, ...p } = o;
    if (l === "html")
      return /* @__PURE__ */ ft(
        "li",
        {
          className: M("action-menu-item", `${this.name}-html`, u, p.className),
          ...c,
          style: d || p.style,
          dangerouslySetInnerHTML: { __html: p.html }
        },
        h
      );
    const g = !a || typeof a == "string" ? this.constructor.ItemComponents && this.constructor.ItemComponents[l] || Ft.ItemComponents[l] : a;
    return Object.assign(p, {
      type: l,
      component: typeof a == "string" ? a : void 0
    }), this.renderTypedItem(g, {
      className: M(u),
      children: f,
      style: d,
      key: h,
      ...c
    }, {
      ...p,
      type: l,
      component: typeof a == "string" ? a : void 0
    });
  }
  renderTypedItem(t, s, i) {
    const { children: o, className: r, key: l, ...a } = s, { activeClass: h = "", activeKey: c, activeIcon: u } = this.props, d = u && c === l ? /* @__PURE__ */ ft("i", { className: `checked icon icon-${u}` }) : null, f = c === l;
    return /* @__PURE__ */ ft(
      "li",
      {
        className: M("action-menu-item", `${this.name}-${i.type}`, r, { [h]: f }),
        ...a,
        children: [
          /* @__PURE__ */ ft(t, { ...i }),
          d,
          typeof o == "function" ? o() : o
        ]
      },
      l
    );
  }
  render() {
    const t = this.beforeRender(), {
      name: s,
      style: i,
      commonItemProps: o,
      className: r,
      items: l,
      children: a,
      itemRender: h,
      onClickItem: c,
      beforeRender: u,
      afterRender: d,
      beforeDestroy: f,
      activeClass: p,
      activeKey: g,
      ...y
    } = t, _ = this.constructor.ROOT_TAG;
    return /* @__PURE__ */ ft(_, { class: M(this.name, r), style: i, ...y, ref: this.ref, children: [
      l && l.map(this.renderItem.bind(this, t)),
      a
    ] });
  }
}, w(Ft, "ItemComponents", {
  divider: tu,
  item: Pl,
  heading: eu,
  space: nu,
  custom: iu,
  basic: Dl
}), w(Ft, "ROOT_TAG", "menu"), w(Ft, "NAME", "action-menu"), Ft);
class mr extends J {
}
w(mr, "NAME", "actionmenu"), w(mr, "Component", ji);
function gr({
  ...e
}) {
  return /* @__PURE__ */ ft(Pl, { ...e });
}
var lo, Dn, wt, He;
let Hl = (lo = class extends ji {
  constructor(t) {
    super(t);
    x(this, Dn, /* @__PURE__ */ new Set());
    x(this, wt, void 0);
    x(this, He, (t, s, i) => {
      this.toggleNestedMenu(t, s), i.preventDefault();
    });
    R(this, wt, t.nestedShow === void 0), m(this, wt) && (this.state = { nestedShow: t.defaultNestedShow ?? {} });
  }
  get nestedTrigger() {
    return this.props.nestedTrigger;
  }
  beforeRender() {
    const t = super.beforeRender(), { nestedShow: s, nestedTrigger: i, defaultNestedShow: o, controlledMenu: r, ...l } = t;
    return l;
  }
  renderNestedMenu(t) {
    let { items: s } = t;
    if (!s || (typeof s == "function" && (s = s(t, this)), !s.length))
      return;
    const i = this.constructor, { name: o, controlledMenu: r, nestedShow: l, beforeDestroy: a, beforeRender: h, itemRender: c, activeClass: u, activeKey: d, onClickItem: f, afterRender: p, commonItemProps: g, activeIcon: y } = this.props;
    return /* @__PURE__ */ ft(
      i,
      {
        items: s,
        name: o,
        nestedShow: m(this, wt) ? this.state.nestedShow : l,
        nestedTrigger: this.nestedTrigger,
        controlledMenu: r || this,
        commonItemProps: g,
        onClickItem: f,
        afterRender: p,
        beforeRender: h,
        beforeDestroy: a,
        itemRender: c,
        activeClass: u,
        activeKey: d,
        activeIcon: y
      }
    );
  }
  isNestedItem(t) {
    return (!t.type || t.type === "item") && !!t.items;
  }
  renderToggleIcon(t, s) {
  }
  getItemRenderProps(t, s, i) {
    const o = super.getItemRenderProps(t, s, i);
    if (!this.isNestedItem(o))
      return o;
    const r = o.key ?? i;
    m(this, Dn).add(r);
    const l = this.isNestedMenuShow(r);
    if (l && (o.rootChildren = [
      o.rootChildren,
      this.renderNestedMenu(s)
    ], o.component = gr), this.nestedTrigger === "hover")
      o.rootAttrs = {
        ...o.rootAttrs,
        onMouseEnter: m(this, He).bind(this, r, !0),
        onMouseLeave: m(this, He).bind(this, r, !1)
      };
    else if (this.nestedTrigger === "click") {
      const { onClick: h } = o;
      o.onClick = (c) => {
        m(this, He).call(this, r, void 0, c), h == null || h(c);
      };
    }
    const a = this.renderToggleIcon(l, o);
    return a && (o.children = [o.children, a]), o.rootClass = [o.rootClass, "has-nested-menu", l ? "show" : ""], o;
  }
  isNestedMenuShow(t) {
    const s = m(this, wt) ? this.state.nestedShow : this.props.nestedShow;
    return s && typeof s == "object" ? s[t] : !!s;
  }
  toggleNestedMenu(t, s) {
    const { controlledMenu: i } = this.props;
    if (i)
      return i.toggleNestedMenu(t, s);
    if (!m(this, wt))
      return !1;
    let { nestedShow: o = {} } = this.state;
    if (typeof o == "boolean" && (o === !0 ? o = [...m(this, Dn).values()].reduce((r, l) => (r[l] = !0, r), {}) : o = {}), s === void 0)
      s = !o[t];
    else if (!!o[t] == !!s)
      return !1;
    return s ? o[t] = s : delete o[t], this.setState({ nestedShow: { ...o } }), !0;
  }
  showNestedMenu(t) {
    return this.toggleNestedMenu(t, !0);
  }
  hideNestedMenu(t) {
    return this.toggleNestedMenu(t, !1);
  }
  showAllNestedMenu() {
    m(this, wt) && this.setState({ nestedShow: !0 });
  }
  hideAllNestedMenu() {
    m(this, wt) && this.setState({ nestedShow: !1 });
  }
}, Dn = new WeakMap(), wt = new WeakMap(), He = new WeakMap(), w(lo, "ItemComponents", {
  item: gr
}), lo);
class yr extends J {
}
w(yr, "NAME", "actionmenunested"), w(yr, "Component", Hl);
let Tt = class extends U {
  render() {
    const {
      component: n,
      type: t,
      btnType: s,
      size: i,
      className: o,
      children: r,
      url: l,
      target: a,
      disabled: h,
      active: c,
      loading: u,
      loadingIcon: d,
      loadingText: f,
      icon: p,
      text: g,
      trailingIcon: y,
      caret: _,
      square: v,
      hint: S,
      ...$
    } = this.props, T = n || (l ? "a" : "button"), D = g == null || typeof g == "string" && !g.length || u && !f, L = _ && D && !p && !y && !r && !u;
    return E(
      T,
      {
        className: M("btn", t, o, {
          "btn-caret": L,
          disabled: h || u,
          active: c,
          loading: u,
          square: v === void 0 ? !L && !r && D : v
        }, i ? `size-${i}` : ""),
        title: S,
        [T === "a" ? "href" : "data-url"]: l,
        [T === "a" ? "target" : "data-target"]: a,
        type: T === "button" ? s : void 0,
        ...$
      },
      u ? /* @__PURE__ */ b("i", { class: `spin icon ${d || "icon-spinner-snake"}` }) : typeof p == "string" ? /* @__PURE__ */ b("i", { class: `icon ${p}` }) : p,
      D ? null : /* @__PURE__ */ b("span", { className: "text", children: u ? f : g }),
      u ? null : r,
      u ? null : typeof y == "string" ? /* @__PURE__ */ b("i", { class: `icon ${y}` }) : y,
      u ? null : _ ? /* @__PURE__ */ b("span", { className: typeof _ == "string" ? `caret-${_}` : "caret" }) : null
    );
  }
};
class _r extends J {
}
w(_r, "NAME", "button"), w(_r, "Component", Tt);
var co;
let oe = (co = class extends Hl {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "menu-nested";
  }
  beforeRender() {
    const n = super.beforeRender();
    let { hasIcons: t } = n;
    return t === void 0 && (t = n.items.some((s) => s.icon)), n.className = M(n.className, this.menuName, {
      "has-icons": t,
      "has-nested-items": n.items.some((s) => this.isNestedItem(s)),
      "menu-popup": n.popup
    }), n;
  }
  renderToggleIcon(n) {
    return /* @__PURE__ */ b("span", { class: `${this.name}-toggle-icon caret-${n ? "down" : "right"}` });
  }
}, w(co, "NAME", "menu"), co);
class br extends J {
}
w(br, "NAME", "menu"), w(br, "Component", oe);
let us = (e = 21) => crypto.getRandomValues(new Uint8Array(e)).reduce((n, t) => (t &= 63, t < 36 ? n += t.toString(36) : t < 62 ? n += (t - 26).toString(36).toUpperCase() : t > 62 ? n += "-" : n += "_", n), "");
const Ut = document, Ms = window, Il = Ut.documentElement, Te = Ut.createElement.bind(Ut), jl = Te("div"), to = Te("table"), ou = Te("tbody"), wr = Te("tr"), { isArray: Wi, prototype: Wl } = Array, { concat: ru, filter: Oo, indexOf: Fl, map: Bl, push: lu, slice: zl, some: Po, splice: cu } = Wl, au = /^#(?:[\w-]|\\.|[^\x00-\xa0])*$/, uu = /^\.(?:[\w-]|\\.|[^\x00-\xa0])*$/, hu = /<.+>/, fu = /^\w+$/;
function Do(e, n) {
  const t = du(n);
  return !e || !t && !ln(n) && !X(n) ? [] : !t && uu.test(e) ? n.getElementsByClassName(e.slice(1).replace(/\\/g, "")) : !t && fu.test(e) ? n.getElementsByTagName(e) : n.querySelectorAll(e);
}
class Fi {
  constructor(n, t) {
    if (!n)
      return;
    if (_o(n))
      return n;
    let s = n;
    if (ot(n)) {
      const i = (_o(t) ? t[0] : t) || Ut;
      if (s = au.test(n) && "getElementById" in i ? i.getElementById(n.slice(1).replace(/\\/g, "")) : hu.test(n) ? ql(n) : Do(n, i), !s)
        return;
    } else if (Ae(n))
      return this.ready(n);
    (s.nodeType || s === Ms) && (s = [s]), this.length = s.length;
    for (let i = 0, o = this.length; i < o; i++)
      this[i] = s[i];
  }
  init(n, t) {
    return new Fi(n, t);
  }
}
const C = Fi.prototype, A = C.init;
A.fn = A.prototype = C;
C.length = 0;
C.splice = cu;
typeof Symbol == "function" && (C[Symbol.iterator] = Wl[Symbol.iterator]);
function _o(e) {
  return e instanceof Fi;
}
function rn(e) {
  return !!e && e === e.window;
}
function ln(e) {
  return !!e && e.nodeType === 9;
}
function du(e) {
  return !!e && e.nodeType === 11;
}
function X(e) {
  return !!e && e.nodeType === 1;
}
function pu(e) {
  return !!e && e.nodeType === 3;
}
function mu(e) {
  return typeof e == "boolean";
}
function Ae(e) {
  return typeof e == "function";
}
function ot(e) {
  return typeof e == "string";
}
function ct(e) {
  return e === void 0;
}
function Nn(e) {
  return e === null;
}
function Ul(e) {
  return !isNaN(parseFloat(e)) && isFinite(e);
}
function Ho(e) {
  if (typeof e != "object" || e === null)
    return !1;
  const n = Object.getPrototypeOf(e);
  return n === null || n === Object.prototype;
}
A.isWindow = rn;
A.isFunction = Ae;
A.isArray = Wi;
A.isNumeric = Ul;
A.isPlainObject = Ho;
function Z(e, n, t) {
  if (t) {
    let s = e.length;
    for (; s--; )
      if (n.call(e[s], s, e[s]) === !1)
        return e;
  } else if (Ho(e)) {
    const s = Object.keys(e);
    for (let i = 0, o = s.length; i < o; i++) {
      const r = s[i];
      if (n.call(e[r], r, e[r]) === !1)
        return e;
    }
  } else
    for (let s = 0, i = e.length; s < i; s++)
      if (n.call(e[s], s, e[s]) === !1)
        return e;
  return e;
}
A.each = Z;
C.each = function(e) {
  return Z(this, e);
};
C.empty = function() {
  return this.each((e, n) => {
    for (; n.firstChild; )
      n.removeChild(n.firstChild);
  });
};
function Os(...e) {
  const n = mu(e[0]) ? e.shift() : !1, t = e.shift(), s = e.length;
  if (!t)
    return {};
  if (!s)
    return Os(n, A, t);
  for (let i = 0; i < s; i++) {
    const o = e[i];
    for (const r in o)
      n && (Wi(o[r]) || Ho(o[r])) ? ((!t[r] || t[r].constructor !== o[r].constructor) && (t[r] = new o[r].constructor()), Os(n, t[r], o[r])) : t[r] = o[r];
  }
  return t;
}
A.extend = Os;
C.extend = function(e) {
  return Os(C, e);
};
const gu = /\S+/g;
function Bi(e) {
  return ot(e) ? e.match(gu) || [] : [];
}
C.toggleClass = function(e, n) {
  const t = Bi(e), s = !ct(n);
  return this.each((i, o) => {
    X(o) && Z(t, (r, l) => {
      s ? n ? o.classList.add(l) : o.classList.remove(l) : o.classList.toggle(l);
    });
  });
};
C.addClass = function(e) {
  return this.toggleClass(e, !0);
};
C.removeAttr = function(e) {
  const n = Bi(e);
  return this.each((t, s) => {
    X(s) && Z(n, (i, o) => {
      s.removeAttribute(o);
    });
  });
};
function yu(e, n) {
  if (e) {
    if (ot(e)) {
      if (arguments.length < 2) {
        if (!this[0] || !X(this[0]))
          return;
        const t = this[0].getAttribute(e);
        return Nn(t) ? void 0 : t;
      }
      return ct(n) ? this : Nn(n) ? this.removeAttr(e) : this.each((t, s) => {
        X(s) && s.setAttribute(e, n);
      });
    }
    for (const t in e)
      this.attr(t, e[t]);
    return this;
  }
}
C.attr = yu;
C.removeClass = function(e) {
  return arguments.length ? this.toggleClass(e, !1) : this.attr("class", "");
};
C.hasClass = function(e) {
  return !!e && Po.call(this, (n) => X(n) && n.classList.contains(e));
};
C.get = function(e) {
  return ct(e) ? zl.call(this) : (e = Number(e), this[e < 0 ? e + this.length : e]);
};
C.eq = function(e) {
  return A(this.get(e));
};
C.first = function() {
  return this.eq(0);
};
C.last = function() {
  return this.eq(-1);
};
function _u(e) {
  return ct(e) ? this.get().map((n) => X(n) || pu(n) ? n.textContent : "").join("") : this.each((n, t) => {
    X(t) && (t.textContent = e);
  });
}
C.text = _u;
function Vt(e, n, t) {
  if (!X(e))
    return;
  const s = Ms.getComputedStyle(e, null);
  return t ? s.getPropertyValue(n) || void 0 : s[n] || e.style[n];
}
function Ct(e, n) {
  return parseInt(Vt(e, n), 10) || 0;
}
function vr(e, n) {
  return Ct(e, `border${n ? "Left" : "Top"}Width`) + Ct(e, `padding${n ? "Left" : "Top"}`) + Ct(e, `padding${n ? "Right" : "Bottom"}`) + Ct(e, `border${n ? "Right" : "Bottom"}Width`);
}
const eo = {};
function bu(e) {
  if (eo[e])
    return eo[e];
  const n = Te(e);
  Ut.body.insertBefore(n, null);
  const t = Vt(n, "display");
  return Ut.body.removeChild(n), eo[e] = t !== "none" ? t : "block";
}
function xr(e) {
  return Vt(e, "display") === "none";
}
function Vl(e, n) {
  const t = e && (e.matches || e.webkitMatchesSelector || e.msMatchesSelector);
  return !!t && !!n && t.call(e, n);
}
function zi(e) {
  return ot(e) ? (n, t) => Vl(t, e) : Ae(e) ? e : _o(e) ? (n, t) => e.is(t) : e ? (n, t) => t === e : () => !1;
}
C.filter = function(e) {
  const n = zi(e);
  return A(Oo.call(this, (t, s) => n.call(t, s, t)));
};
function he(e, n) {
  return n ? e.filter(n) : e;
}
C.detach = function(e) {
  return he(this, e).each((n, t) => {
    t.parentNode && t.parentNode.removeChild(t);
  }), this;
};
const wu = /^\s*<(\w+)[^>]*>/, vu = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, Sr = {
  "*": jl,
  tr: ou,
  td: wr,
  th: wr,
  thead: to,
  tbody: to,
  tfoot: to
};
function ql(e) {
  if (!ot(e))
    return [];
  if (vu.test(e))
    return [Te(RegExp.$1)];
  const n = wu.test(e) && RegExp.$1, t = Sr[n] || Sr["*"];
  return t.innerHTML = e, A(t.childNodes).detach().get();
}
A.parseHTML = ql;
C.has = function(e) {
  const n = ot(e) ? (t, s) => Do(e, s).length : (t, s) => s.contains(e);
  return this.filter(n);
};
C.not = function(e) {
  const n = zi(e);
  return this.filter((t, s) => (!ot(e) || X(s)) && !n.call(s, t, s));
};
function Kt(e, n, t, s) {
  const i = [], o = Ae(n), r = s && zi(s);
  for (let l = 0, a = e.length; l < a; l++)
    if (o) {
      const h = n(e[l]);
      h.length && lu.apply(i, h);
    } else {
      let h = e[l][n];
      for (; h != null && !(s && r(-1, h)); )
        i.push(h), h = t ? h[n] : null;
    }
  return i;
}
function Gl(e) {
  return e.multiple && e.options ? Kt(Oo.call(e.options, (n) => n.selected && !n.disabled && !n.parentNode.disabled), "value") : e.value || "";
}
function xu(e) {
  return arguments.length ? this.each((n, t) => {
    const s = t.multiple && t.options;
    if (s || ec.test(t.type)) {
      const i = Wi(e) ? Bl.call(e, String) : Nn(e) ? [] : [String(e)];
      s ? Z(t.options, (o, r) => {
        r.selected = i.indexOf(r.value) >= 0;
      }, !0) : t.checked = i.indexOf(t.value) >= 0;
    } else
      t.value = ct(e) || Nn(e) ? "" : e;
  }) : this[0] && Gl(this[0]);
}
C.val = xu;
C.is = function(e) {
  const n = zi(e);
  return Po.call(this, (t, s) => n.call(t, s, t));
};
A.guid = 1;
function At(e) {
  return e.length > 1 ? Oo.call(e, (n, t, s) => Fl.call(s, n) === t) : e;
}
A.unique = At;
C.add = function(e, n) {
  return A(At(this.get().concat(A(e, n).get())));
};
C.children = function(e) {
  return he(A(At(Kt(this, (n) => n.children))), e);
};
C.parent = function(e) {
  return he(A(At(Kt(this, "parentNode"))), e);
};
C.index = function(e) {
  const n = e ? A(e)[0] : this[0], t = e ? this : A(n).parent().children();
  return Fl.call(t, n);
};
C.closest = function(e) {
  const n = this.filter(e);
  if (n.length)
    return n;
  const t = this.parent();
  return t.length ? t.closest(e) : n;
};
C.siblings = function(e) {
  return he(A(At(Kt(this, (n) => A(n).parent().children().not(n)))), e);
};
C.find = function(e) {
  return A(At(Kt(this, (n) => Do(e, n))));
};
const Su = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, Eu = /^$|^module$|\/(java|ecma)script/i, Cu = ["type", "src", "nonce", "noModule"];
function $u(e, n) {
  const t = A(e);
  t.filter("script").add(t.find("script")).each((s, i) => {
    if (Eu.test(i.type) && Il.contains(i)) {
      const o = Te("script");
      o.text = i.textContent.replace(Su, ""), Z(Cu, (r, l) => {
        i[l] && (o[l] = i[l]);
      }), n.head.insertBefore(o, null), n.head.removeChild(o);
    }
  });
}
function Ru(e, n, t, s, i) {
  s ? e.insertBefore(n, t ? e.firstChild : null) : e.nodeName === "HTML" ? e.parentNode.replaceChild(n, e) : e.parentNode.insertBefore(n, t ? e : e.nextSibling), i && $u(n, e.ownerDocument);
}
function fe(e, n, t, s, i, o, r, l) {
  return Z(e, (a, h) => {
    Z(A(h), (c, u) => {
      Z(A(n), (d, f) => {
        const p = t ? u : f, g = t ? f : u, y = t ? c : d;
        Ru(p, y ? g.cloneNode(!0) : g, s, i, !y);
      }, l);
    }, r);
  }, o), n;
}
C.after = function() {
  return fe(arguments, this, !1, !1, !1, !0, !0);
};
C.append = function() {
  return fe(arguments, this, !1, !1, !0);
};
function ku(e) {
  if (!arguments.length)
    return this[0] && this[0].innerHTML;
  if (ct(e))
    return this;
  const n = /<script[\s>]/.test(e);
  return this.each((t, s) => {
    X(s) && (n ? A(s).empty().append(e) : s.innerHTML = e);
  });
}
C.html = ku;
C.appendTo = function(e) {
  return fe(arguments, this, !0, !1, !0);
};
C.wrapInner = function(e) {
  return this.each((n, t) => {
    const s = A(t), i = s.contents();
    i.length ? i.wrapAll(e) : s.append(e);
  });
};
C.before = function() {
  return fe(arguments, this, !1, !0);
};
C.wrapAll = function(e) {
  let n = A(e), t = n[0];
  for (; t.children.length; )
    t = t.firstElementChild;
  return this.first().before(n), this.appendTo(t);
};
C.wrap = function(e) {
  return this.each((n, t) => {
    const s = A(e)[0];
    A(t).wrapAll(n ? s.cloneNode(!0) : s);
  });
};
C.insertAfter = function(e) {
  return fe(arguments, this, !0, !1, !1, !1, !1, !0);
};
C.insertBefore = function(e) {
  return fe(arguments, this, !0, !0);
};
C.prepend = function() {
  return fe(arguments, this, !1, !0, !0, !0, !0);
};
C.prependTo = function(e) {
  return fe(arguments, this, !0, !0, !0, !1, !1, !0);
};
C.contents = function() {
  return A(At(Kt(this, (e) => e.tagName === "IFRAME" ? [e.contentDocument] : e.tagName === "TEMPLATE" ? e.content.childNodes : e.childNodes)));
};
C.next = function(e, n, t) {
  return he(A(At(Kt(this, "nextElementSibling", n, t))), e);
};
C.nextAll = function(e) {
  return this.next(e, !0);
};
C.nextUntil = function(e, n) {
  return this.next(n, !0, e);
};
C.parents = function(e, n) {
  return he(A(At(Kt(this, "parentElement", !0, n))), e);
};
C.parentsUntil = function(e, n) {
  return this.parents(n, e);
};
C.prev = function(e, n, t) {
  return he(A(At(Kt(this, "previousElementSibling", n, t))), e);
};
C.prevAll = function(e) {
  return this.prev(e, !0);
};
C.prevUntil = function(e, n) {
  return this.prev(n, !0, e);
};
C.map = function(e) {
  return A(ru.apply([], Bl.call(this, (n, t) => e.call(n, t, n))));
};
C.clone = function() {
  return this.map((e, n) => n.cloneNode(!0));
};
C.offsetParent = function() {
  return this.map((e, n) => {
    let t = n.offsetParent;
    for (; t && Vt(t, "position") === "static"; )
      t = t.offsetParent;
    return t || Il;
  });
};
C.slice = function(e, n) {
  return A(zl.call(this, e, n));
};
const Tu = /-([a-z])/g;
function Io(e) {
  return e.replace(Tu, (n, t) => t.toUpperCase());
}
C.ready = function(e) {
  const n = () => setTimeout(e, 0, A);
  return Ut.readyState !== "loading" ? n() : Ut.addEventListener("DOMContentLoaded", n), this;
};
C.unwrap = function() {
  return this.parent().each((e, n) => {
    if (n.tagName === "BODY")
      return;
    const t = A(n);
    t.replaceWith(t.children());
  }), this;
};
C.offset = function() {
  const e = this[0];
  if (!e)
    return;
  const n = e.getBoundingClientRect();
  return {
    top: n.top + Ms.pageYOffset,
    left: n.left + Ms.pageXOffset
  };
};
C.position = function() {
  const e = this[0];
  if (!e)
    return;
  const n = Vt(e, "position") === "fixed", t = n ? e.getBoundingClientRect() : this.offset();
  if (!n) {
    const s = e.ownerDocument;
    let i = e.offsetParent || s.documentElement;
    for (; (i === s.body || i === s.documentElement) && Vt(i, "position") === "static"; )
      i = i.parentNode;
    if (i !== e && X(i)) {
      const o = A(i).offset();
      t.top -= o.top + Ct(i, "borderTopWidth"), t.left -= o.left + Ct(i, "borderLeftWidth");
    }
  }
  return {
    top: t.top - Ct(e, "marginTop"),
    left: t.left - Ct(e, "marginLeft")
  };
};
const Kl = {
  /* GENERAL */
  class: "className",
  contenteditable: "contentEditable",
  /* LABEL */
  for: "htmlFor",
  /* INPUT */
  readonly: "readOnly",
  maxlength: "maxLength",
  tabindex: "tabIndex",
  /* TABLE */
  colspan: "colSpan",
  rowspan: "rowSpan",
  /* IMAGE */
  usemap: "useMap"
};
C.prop = function(e, n) {
  if (e) {
    if (ot(e))
      return e = Kl[e] || e, arguments.length < 2 ? this[0] && this[0][e] : this.each((t, s) => {
        s[e] = n;
      });
    for (const t in e)
      this.prop(t, e[t]);
    return this;
  }
};
C.removeProp = function(e) {
  return this.each((n, t) => {
    delete t[Kl[e] || e];
  });
};
const Au = /^--/;
function jo(e) {
  return Au.test(e);
}
const no = {}, { style: Nu } = jl, Lu = ["webkit", "moz", "ms"];
function Mu(e, n = jo(e)) {
  if (n)
    return e;
  if (!no[e]) {
    const t = Io(e), s = `${t[0].toUpperCase()}${t.slice(1)}`, i = `${t} ${Lu.join(`${s} `)}${s}`.split(" ");
    Z(i, (o, r) => {
      if (r in Nu)
        return no[e] = r, !1;
    });
  }
  return no[e];
}
const Ou = {
  animationIterationCount: !0,
  columnCount: !0,
  flexGrow: !0,
  flexShrink: !0,
  fontWeight: !0,
  gridArea: !0,
  gridColumn: !0,
  gridColumnEnd: !0,
  gridColumnStart: !0,
  gridRow: !0,
  gridRowEnd: !0,
  gridRowStart: !0,
  lineHeight: !0,
  opacity: !0,
  order: !0,
  orphans: !0,
  widows: !0,
  zIndex: !0
};
function Yl(e, n, t = jo(e)) {
  return !t && !Ou[e] && Ul(n) ? `${n}px` : n;
}
function Pu(e, n) {
  if (ot(e)) {
    const t = jo(e);
    return e = Mu(e, t), arguments.length < 2 ? this[0] && Vt(this[0], e, t) : e ? (n = Yl(e, n, t), this.each((s, i) => {
      X(i) && (t ? i.style.setProperty(e, n) : i.style[e] = n);
    })) : this;
  }
  for (const t in e)
    this.css(t, e[t]);
  return this;
}
C.css = Pu;
function Xl(e, n) {
  try {
    return e(n);
  } catch {
    return n;
  }
}
const Du = /^\s+|\s+$/;
function Er(e, n) {
  const t = e.dataset[n] || e.dataset[Io(n)];
  return Du.test(t) ? t : Xl(JSON.parse, t);
}
function Hu(e, n, t) {
  t = Xl(JSON.stringify, t), e.dataset[Io(n)] = t;
}
function Iu(e, n) {
  if (!e) {
    if (!this[0])
      return;
    const t = {};
    for (const s in this[0].dataset)
      t[s] = Er(this[0], s);
    return t;
  }
  if (ot(e))
    return arguments.length < 2 ? this[0] && Er(this[0], e) : ct(n) ? this : this.each((t, s) => {
      Hu(s, e, n);
    });
  for (const t in e)
    this.data(t, e[t]);
  return this;
}
C.data = Iu;
function Jl(e, n) {
  const t = e.documentElement;
  return Math.max(e.body[`scroll${n}`], t[`scroll${n}`], e.body[`offset${n}`], t[`offset${n}`], t[`client${n}`]);
}
Z([!0, !1], (e, n) => {
  Z(["Width", "Height"], (t, s) => {
    const i = `${n ? "outer" : "inner"}${s}`;
    C[i] = function(o) {
      if (this[0])
        return rn(this[0]) ? n ? this[0][`inner${s}`] : this[0].document.documentElement[`client${s}`] : ln(this[0]) ? Jl(this[0], s) : this[0][`${n ? "offset" : "client"}${s}`] + (o && n ? Ct(this[0], `margin${t ? "Top" : "Left"}`) + Ct(this[0], `margin${t ? "Bottom" : "Right"}`) : 0);
    };
  });
});
Z(["Width", "Height"], (e, n) => {
  const t = n.toLowerCase();
  C[t] = function(s) {
    if (!this[0])
      return ct(s) ? void 0 : this;
    if (!arguments.length)
      return rn(this[0]) ? this[0].document.documentElement[`client${n}`] : ln(this[0]) ? Jl(this[0], n) : this[0].getBoundingClientRect()[t] - vr(this[0], !e);
    const i = parseInt(s, 10);
    return this.each((o, r) => {
      if (!X(r))
        return;
      const l = Vt(r, "boxSizing");
      r.style[t] = Yl(t, i + (l === "border-box" ? vr(r, !e) : 0));
    });
  };
});
const Cr = "___cd";
C.toggle = function(e) {
  return this.each((n, t) => {
    if (!X(t))
      return;
    (ct(e) ? xr(t) : e) ? (t.style.display = t[Cr] || "", xr(t) && (t.style.display = bu(t.tagName))) : (t[Cr] = Vt(t, "display"), t.style.display = "none");
  });
};
C.hide = function() {
  return this.toggle(!1);
};
C.show = function() {
  return this.toggle(!0);
};
const $r = "___ce", Wo = ".", Fo = { focus: "focusin", blur: "focusout" }, Ql = { mouseenter: "mouseover", mouseleave: "mouseout" }, ju = /^(mouse|pointer|contextmenu|drag|drop|click|dblclick)/i;
function Bo(e) {
  return Ql[e] || Fo[e] || e;
}
function zo(e) {
  const n = e.split(Wo);
  return [n[0], n.slice(1).sort()];
}
C.trigger = function(e, n) {
  if (ot(e)) {
    const [s, i] = zo(e), o = Bo(s);
    if (!o)
      return this;
    const r = ju.test(o) ? "MouseEvents" : "HTMLEvents";
    e = Ut.createEvent(r), e.initEvent(o, !0, !0), e.namespace = i.join(Wo), e.___ot = s;
  }
  e.___td = n;
  const t = e.___ot in Fo;
  return this.each((s, i) => {
    t && Ae(i[e.___ot]) && (i[`___i${e.type}`] = !0, i[e.___ot](), i[`___i${e.type}`] = !1), i.dispatchEvent(e);
  });
};
function Zl(e) {
  return e[$r] = e[$r] || {};
}
function Wu(e, n, t, s, i) {
  const o = Zl(e);
  o[n] = o[n] || [], o[n].push([t, s, i]), e.addEventListener(n, i);
}
function tc(e, n) {
  return !n || !Po.call(n, (t) => e.indexOf(t) < 0);
}
function Ps(e, n, t, s, i) {
  const o = Zl(e);
  if (n)
    o[n] && (o[n] = o[n].filter(([r, l, a]) => {
      if (i && a.guid !== i.guid || !tc(r, t) || s && s !== l)
        return !0;
      e.removeEventListener(n, a);
    }));
  else
    for (n in o)
      Ps(e, n, t, s, i);
}
C.off = function(e, n, t) {
  if (ct(e))
    this.each((s, i) => {
      !X(i) && !ln(i) && !rn(i) || Ps(i);
    });
  else if (ot(e))
    Ae(n) && (t = n, n = ""), Z(Bi(e), (s, i) => {
      const [o, r] = zo(i), l = Bo(o);
      this.each((a, h) => {
        !X(h) && !ln(h) && !rn(h) || Ps(h, l, r, n, t);
      });
    });
  else
    for (const s in e)
      this.off(s, e[s]);
  return this;
};
C.remove = function(e) {
  return he(this, e).detach().off(), this;
};
C.replaceWith = function(e) {
  return this.before(e).remove();
};
C.replaceAll = function(e) {
  return A(e).replaceWith(this), this;
};
function Fu(e, n, t, s, i) {
  if (!ot(e)) {
    for (const o in e)
      this.on(o, n, t, e[o], i);
    return this;
  }
  return ot(n) || (ct(n) || Nn(n) ? n = "" : ct(t) ? (t = n, n = "") : (s = t, t = n, n = "")), Ae(s) || (s = t, t = void 0), s ? (Z(Bi(e), (o, r) => {
    const [l, a] = zo(r), h = Bo(l), c = l in Ql, u = l in Fo;
    h && this.each((d, f) => {
      if (!X(f) && !ln(f) && !rn(f))
        return;
      const p = function(g) {
        if (g.target[`___i${g.type}`])
          return g.stopImmediatePropagation();
        if (g.namespace && !tc(a, g.namespace.split(Wo)) || !n && (u && (g.target !== f || g.___ot === h) || c && g.relatedTarget && f.contains(g.relatedTarget)))
          return;
        let y = f;
        if (n) {
          let v = g.target;
          for (; !Vl(v, n); )
            if (v === f || (v = v.parentNode, !v))
              return;
          y = v;
        }
        Object.defineProperty(g, "currentTarget", {
          configurable: !0,
          get() {
            return y;
          }
        }), Object.defineProperty(g, "delegateTarget", {
          configurable: !0,
          get() {
            return f;
          }
        }), Object.defineProperty(g, "data", {
          configurable: !0,
          get() {
            return t;
          }
        });
        const _ = s.call(y, g, g.___td);
        i && Ps(f, h, a, n, p), _ === !1 && (g.preventDefault(), g.stopPropagation());
      };
      p.guid = s.guid = s.guid || A.guid++, Wu(f, h, a, n, p);
    });
  }), this) : this;
}
C.on = Fu;
function Bu(e, n, t, s) {
  return this.on(e, n, t, s, !0);
}
C.one = Bu;
const zu = /\r?\n/g;
function Uu(e, n) {
  return `&${encodeURIComponent(e)}=${encodeURIComponent(n.replace(zu, `\r
`))}`;
}
const Vu = /file|reset|submit|button|image/i, ec = /radio|checkbox/i;
C.serialize = function() {
  let e = "";
  return this.each((n, t) => {
    Z(t.elements || [t], (s, i) => {
      if (i.disabled || !i.name || i.tagName === "FIELDSET" || Vu.test(i.type) || ec.test(i.type) && !i.checked)
        return;
      const o = Gl(i);
      if (!ct(o)) {
        const r = Wi(o) ? o : [o];
        Z(r, (l, a) => {
          e += Uu(i.name, a);
        });
      }
    });
  }), e.slice(1);
};
window.$ = A;
const qu = A;
function Gu({
  key: e,
  type: n,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ b(Tt, { type: t, ...s });
}
function Ku(e) {
  return e.button === 2;
}
function Uo(e) {
  return e.split("-")[1];
}
function nc(e) {
  return e === "y" ? "height" : "width";
}
function xn(e) {
  return e.split("-")[0];
}
function sc(e) {
  return ["top", "bottom"].includes(xn(e)) ? "x" : "y";
}
function Rr(e, n, t) {
  let { reference: s, floating: i } = e;
  const o = s.x + s.width / 2 - i.width / 2, r = s.y + s.height / 2 - i.height / 2, l = sc(n), a = nc(l), h = s[a] / 2 - i[a] / 2, c = l === "x";
  let u;
  switch (xn(n)) {
    case "top":
      u = { x: o, y: s.y - i.height };
      break;
    case "bottom":
      u = { x: o, y: s.y + s.height };
      break;
    case "right":
      u = { x: s.x + s.width, y: r };
      break;
    case "left":
      u = { x: s.x - i.width, y: r };
      break;
    default:
      u = { x: s.x, y: s.y };
  }
  switch (Uo(n)) {
    case "start":
      u[l] -= h * (t && c ? -1 : 1);
      break;
    case "end":
      u[l] += h * (t && c ? -1 : 1);
  }
  return u;
}
const Yu = async (e, n, t) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: o = [], platform: r } = t, l = o.filter(Boolean), a = await (r.isRTL == null ? void 0 : r.isRTL(n));
  let h = await r.getElementRects({ reference: e, floating: n, strategy: i }), { x: c, y: u } = Rr(h, s, a), d = s, f = {}, p = 0;
  for (let g = 0; g < l.length; g++) {
    const { name: y, fn: _ } = l[g], { x: v, y: S, data: $, reset: T } = await _({ x: c, y: u, initialPlacement: s, placement: d, strategy: i, middlewareData: f, rects: h, platform: r, elements: { reference: e, floating: n } });
    c = v ?? c, u = S ?? u, f = { ...f, [y]: { ...f[y], ...$ } }, T && p <= 50 && (p++, typeof T == "object" && (T.placement && (d = T.placement), T.rects && (h = T.rects === !0 ? await r.getElementRects({ reference: e, floating: n, strategy: i }) : T.rects), { x: c, y: u } = Rr(h, d, a)), g = -1);
  }
  return { x: c, y: u, placement: d, strategy: i, middlewareData: f };
};
function Xu(e) {
  return typeof e != "number" ? function(n) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...n };
  }(e) : { top: e, right: e, bottom: e, left: e };
}
function Ds(e) {
  return { ...e, top: e.y, left: e.x, right: e.x + e.width, bottom: e.y + e.height };
}
async function Ju(e, n) {
  var t;
  n === void 0 && (n = {});
  const { x: s, y: i, platform: o, rects: r, elements: l, strategy: a } = e, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: u = "floating", altBoundary: d = !1, padding: f = 0 } = n, p = Xu(f), g = l[d ? u === "floating" ? "reference" : "floating" : u], y = Ds(await o.getClippingRect({ element: (t = await (o.isElement == null ? void 0 : o.isElement(g))) == null || t ? g : g.contextElement || await (o.getDocumentElement == null ? void 0 : o.getDocumentElement(l.floating)), boundary: h, rootBoundary: c, strategy: a })), _ = u === "floating" ? { ...r.floating, x: s, y: i } : r.reference, v = await (o.getOffsetParent == null ? void 0 : o.getOffsetParent(l.floating)), S = await (o.isElement == null ? void 0 : o.isElement(v)) && await (o.getScale == null ? void 0 : o.getScale(v)) || { x: 1, y: 1 }, $ = Ds(o.convertOffsetParentRelativeRectToViewportRelativeRect ? await o.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: _, offsetParent: v, strategy: a }) : _);
  return { top: (y.top - $.top + p.top) / S.y, bottom: ($.bottom - y.bottom + p.bottom) / S.y, left: (y.left - $.left + p.left) / S.x, right: ($.right - y.right + p.right) / S.x };
}
const Qu = ["top", "right", "bottom", "left"];
Qu.reduce((e, n) => e.concat(n, n + "-start", n + "-end"), []);
const Zu = { left: "right", right: "left", bottom: "top", top: "bottom" };
function Hs(e) {
  return e.replace(/left|right|bottom|top/g, (n) => Zu[n]);
}
function th(e, n, t) {
  t === void 0 && (t = !1);
  const s = Uo(e), i = sc(e), o = nc(i);
  let r = i === "x" ? s === (t ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return n.reference[o] > n.floating[o] && (r = Hs(r)), { main: r, cross: Hs(r) };
}
const eh = { start: "end", end: "start" };
function so(e) {
  return e.replace(/start|end/g, (n) => eh[n]);
}
const ic = function(e) {
  return e === void 0 && (e = {}), { name: "flip", options: e, async fn(n) {
    var t;
    const { placement: s, middlewareData: i, rects: o, initialPlacement: r, platform: l, elements: a } = n, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: u, fallbackStrategy: d = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...g } = e, y = xn(s), _ = xn(r) === r, v = await (l.isRTL == null ? void 0 : l.isRTL(a.floating)), S = u || (_ || !p ? [Hs(r)] : function(j) {
      const P = Hs(j);
      return [so(j), P, so(P)];
    }(r));
    u || f === "none" || S.push(...function(j, P, V, F) {
      const G = Uo(j);
      let I = function(K, bt, de) {
        const pe = ["left", "right"], me = ["right", "left"], Lt = ["top", "bottom"], Ne = ["bottom", "top"];
        switch (K) {
          case "top":
          case "bottom":
            return de ? bt ? me : pe : bt ? pe : me;
          case "left":
          case "right":
            return bt ? Lt : Ne;
          default:
            return [];
        }
      }(xn(j), V === "start", F);
      return G && (I = I.map((K) => K + "-" + G), P && (I = I.concat(I.map(so)))), I;
    }(r, p, f, v));
    const $ = [r, ...S], T = await Ju(n, g), D = [];
    let L = ((t = i.flip) == null ? void 0 : t.overflows) || [];
    if (h && D.push(T[y]), c) {
      const { main: j, cross: P } = th(s, o, v);
      D.push(T[j], T[P]);
    }
    if (L = [...L, { placement: s, overflows: D }], !D.every((j) => j <= 0)) {
      var O;
      const j = (((O = i.flip) == null ? void 0 : O.index) || 0) + 1, P = $[j];
      if (P)
        return { data: { index: j, overflows: L }, reset: { placement: P } };
      let V = "bottom";
      switch (d) {
        case "bestFit": {
          var k;
          const F = (k = L.map((G) => [G, G.overflows.filter((I) => I > 0).reduce((I, K) => I + K, 0)]).sort((G, I) => G[1] - I[1])[0]) == null ? void 0 : k[0].placement;
          F && (V = F);
          break;
        }
        case "initialPlacement":
          V = r;
      }
      if (s !== V)
        return { reset: { placement: V } };
    }
    return {};
  } };
};
function dt(e) {
  var n;
  return ((n = e.ownerDocument) == null ? void 0 : n.defaultView) || window;
}
function $t(e) {
  return dt(e).getComputedStyle(e);
}
function ce(e) {
  return rc(e) ? (e.nodeName || "").toLowerCase() : "";
}
let ps;
function oc() {
  if (ps)
    return ps;
  const e = navigator.userAgentData;
  return e && Array.isArray(e.brands) ? (ps = e.brands.map((n) => n.brand + "/" + n.version).join(" "), ps) : navigator.userAgent;
}
function qt(e) {
  return e instanceof dt(e).HTMLElement;
}
function yt(e) {
  return e instanceof dt(e).Element;
}
function rc(e) {
  return e instanceof dt(e).Node;
}
function kr(e) {
  return typeof ShadowRoot > "u" ? !1 : e instanceof dt(e).ShadowRoot || e instanceof ShadowRoot;
}
function Ui(e) {
  const { overflow: n, overflowX: t, overflowY: s, display: i } = $t(e);
  return /auto|scroll|overlay|hidden|clip/.test(n + s + t) && !["inline", "contents"].includes(i);
}
function nh(e) {
  return ["table", "td", "th"].includes(ce(e));
}
function bo(e) {
  const n = /firefox/i.test(oc()), t = $t(e), s = t.backdropFilter || t.WebkitBackdropFilter;
  return t.transform !== "none" || t.perspective !== "none" || !!s && s !== "none" || n && t.willChange === "filter" || n && !!t.filter && t.filter !== "none" || ["transform", "perspective"].some((i) => t.willChange.includes(i)) || ["paint", "layout", "strict", "content"].some((i) => {
    const o = t.contain;
    return o != null && o.includes(i);
  });
}
function lc() {
  return !/^((?!chrome|android).)*safari/i.test(oc());
}
function Vo(e) {
  return ["html", "body", "#document"].includes(ce(e));
}
const Tr = Math.min, Sn = Math.max, Is = Math.round;
function cc(e) {
  const n = $t(e);
  let t = parseFloat(n.width), s = parseFloat(n.height);
  const i = e.offsetWidth, o = e.offsetHeight, r = Is(t) !== i || Is(s) !== o;
  return r && (t = i, s = o), { width: t, height: s, fallback: r };
}
function ac(e) {
  return yt(e) ? e : e.contextElement;
}
const uc = { x: 1, y: 1 };
function Me(e) {
  const n = ac(e);
  if (!qt(n))
    return uc;
  const t = n.getBoundingClientRect(), { width: s, height: i, fallback: o } = cc(n);
  let r = (o ? Is(t.width) : t.width) / s, l = (o ? Is(t.height) : t.height) / i;
  return r && Number.isFinite(r) || (r = 1), l && Number.isFinite(l) || (l = 1), { x: r, y: l };
}
function $e(e, n, t, s) {
  var i, o;
  n === void 0 && (n = !1), t === void 0 && (t = !1);
  const r = e.getBoundingClientRect(), l = ac(e);
  let a = uc;
  n && (s ? yt(s) && (a = Me(s)) : a = Me(e));
  const h = l ? dt(l) : window, c = !lc() && t;
  let u = (r.left + (c && ((i = h.visualViewport) == null ? void 0 : i.offsetLeft) || 0)) / a.x, d = (r.top + (c && ((o = h.visualViewport) == null ? void 0 : o.offsetTop) || 0)) / a.y, f = r.width / a.x, p = r.height / a.y;
  if (l) {
    const g = dt(l), y = s && yt(s) ? dt(s) : s;
    let _ = g.frameElement;
    for (; _ && s && y !== g; ) {
      const v = Me(_), S = _.getBoundingClientRect(), $ = getComputedStyle(_);
      S.x += (_.clientLeft + parseFloat($.paddingLeft)) * v.x, S.y += (_.clientTop + parseFloat($.paddingTop)) * v.y, u *= v.x, d *= v.y, f *= v.x, p *= v.y, u += S.x, d += S.y, _ = dt(_).frameElement;
    }
  }
  return { width: f, height: p, top: d, right: u + f, bottom: d + p, left: u, x: u, y: d };
}
function re(e) {
  return ((rc(e) ? e.ownerDocument : e.document) || window.document).documentElement;
}
function Vi(e) {
  return yt(e) ? { scrollLeft: e.scrollLeft, scrollTop: e.scrollTop } : { scrollLeft: e.pageXOffset, scrollTop: e.pageYOffset };
}
function hc(e) {
  return $e(re(e)).left + Vi(e).scrollLeft;
}
function sh(e, n, t) {
  const s = qt(n), i = re(n), o = $e(e, !0, t === "fixed", n);
  let r = { scrollLeft: 0, scrollTop: 0 };
  const l = { x: 0, y: 0 };
  if (s || !s && t !== "fixed")
    if ((ce(n) !== "body" || Ui(i)) && (r = Vi(n)), qt(n)) {
      const a = $e(n, !0);
      l.x = a.x + n.clientLeft, l.y = a.y + n.clientTop;
    } else
      i && (l.x = hc(i));
  return { x: o.left + r.scrollLeft - l.x, y: o.top + r.scrollTop - l.y, width: o.width, height: o.height };
}
function Ln(e) {
  if (ce(e) === "html")
    return e;
  const n = e.assignedSlot || e.parentNode || (kr(e) ? e.host : null) || re(e);
  return kr(n) ? n.host : n;
}
function Ar(e) {
  return qt(e) && $t(e).position !== "fixed" ? e.offsetParent : null;
}
function Nr(e) {
  const n = dt(e);
  let t = Ar(e);
  for (; t && nh(t) && $t(t).position === "static"; )
    t = Ar(t);
  return t && (ce(t) === "html" || ce(t) === "body" && $t(t).position === "static" && !bo(t)) ? n : t || function(s) {
    let i = Ln(s);
    for (; qt(i) && !Vo(i); ) {
      if (bo(i))
        return i;
      i = Ln(i);
    }
    return null;
  }(e) || n;
}
function fc(e) {
  const n = Ln(e);
  return Vo(n) ? e.ownerDocument.body : qt(n) && Ui(n) ? n : fc(n);
}
function En(e, n) {
  var t;
  n === void 0 && (n = []);
  const s = fc(e), i = s === ((t = e.ownerDocument) == null ? void 0 : t.body), o = dt(s);
  return i ? n.concat(o, o.visualViewport || [], Ui(s) ? s : []) : n.concat(s, En(s));
}
function Lr(e, n, t) {
  return n === "viewport" ? Ds(function(s, i) {
    const o = dt(s), r = re(s), l = o.visualViewport;
    let a = r.clientWidth, h = r.clientHeight, c = 0, u = 0;
    if (l) {
      a = l.width, h = l.height;
      const d = lc();
      (d || !d && i === "fixed") && (c = l.offsetLeft, u = l.offsetTop);
    }
    return { width: a, height: h, x: c, y: u };
  }(e, t)) : yt(n) ? function(s, i) {
    const o = $e(s, !0, i === "fixed"), r = o.top + s.clientTop, l = o.left + s.clientLeft, a = qt(s) ? Me(s) : { x: 1, y: 1 }, h = s.clientWidth * a.x, c = s.clientHeight * a.y, u = l * a.x, d = r * a.y;
    return { top: d, left: u, right: u + h, bottom: d + c, x: u, y: d, width: h, height: c };
  }(n, t) : Ds(function(s) {
    var i;
    const o = re(s), r = Vi(s), l = (i = s.ownerDocument) == null ? void 0 : i.body, a = Sn(o.scrollWidth, o.clientWidth, l ? l.scrollWidth : 0, l ? l.clientWidth : 0), h = Sn(o.scrollHeight, o.clientHeight, l ? l.scrollHeight : 0, l ? l.clientHeight : 0);
    let c = -r.scrollLeft + hc(s);
    const u = -r.scrollTop;
    return $t(l || o).direction === "rtl" && (c += Sn(o.clientWidth, l ? l.clientWidth : 0) - a), { width: a, height: h, x: c, y: u };
  }(re(e)));
}
const ih = { getClippingRect: function(e) {
  let { element: n, boundary: t, rootBoundary: s, strategy: i } = e;
  const o = t === "clippingAncestors" ? function(h, c) {
    const u = c.get(h);
    if (u)
      return u;
    let d = En(h).filter((y) => yt(y) && ce(y) !== "body"), f = null;
    const p = $t(h).position === "fixed";
    let g = p ? Ln(h) : h;
    for (; yt(g) && !Vo(g); ) {
      const y = $t(g), _ = bo(g);
      (p ? _ || f : _ || y.position !== "static" || !f || !["absolute", "fixed"].includes(f.position)) ? f = y : d = d.filter((v) => v !== g), g = Ln(g);
    }
    return c.set(h, d), d;
  }(n, this._c) : [].concat(t), r = [...o, s], l = r[0], a = r.reduce((h, c) => {
    const u = Lr(n, c, i);
    return h.top = Sn(u.top, h.top), h.right = Tr(u.right, h.right), h.bottom = Tr(u.bottom, h.bottom), h.left = Sn(u.left, h.left), h;
  }, Lr(n, l, i));
  return { width: a.right - a.left, height: a.bottom - a.top, x: a.left, y: a.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(e) {
  let { rect: n, offsetParent: t, strategy: s } = e;
  const i = qt(t), o = re(t);
  if (t === o)
    return n;
  let r = { scrollLeft: 0, scrollTop: 0 }, l = { x: 1, y: 1 };
  const a = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((ce(t) !== "body" || Ui(o)) && (r = Vi(t)), qt(t))) {
    const h = $e(t);
    l = Me(t), a.x = h.x + t.clientLeft, a.y = h.y + t.clientTop;
  }
  return { width: n.width * l.x, height: n.height * l.y, x: n.x * l.x - r.scrollLeft * l.x + a.x, y: n.y * l.y - r.scrollTop * l.y + a.y };
}, isElement: yt, getDimensions: function(e) {
  return cc(e);
}, getOffsetParent: Nr, getDocumentElement: re, getScale: Me, async getElementRects(e) {
  let { reference: n, floating: t, strategy: s } = e;
  const i = this.getOffsetParent || Nr, o = this.getDimensions;
  return { reference: sh(n, await i(t), s), floating: { x: 0, y: 0, ...await o(t) } };
}, getClientRects: (e) => Array.from(e.getClientRects()), isRTL: (e) => $t(e).direction === "rtl" };
function oh(e, n, t, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: o = !0, elementResize: r = !0, animationFrame: l = !1 } = s, a = i && !l, h = a || o ? [...yt(e) ? En(e) : e.contextElement ? En(e.contextElement) : [], ...En(n)] : [];
  h.forEach((f) => {
    a && f.addEventListener("scroll", t, { passive: !0 }), o && f.addEventListener("resize", t);
  });
  let c, u = null;
  if (r) {
    let f = !0;
    u = new ResizeObserver(() => {
      f || t(), f = !1;
    }), yt(e) && !l && u.observe(e), yt(e) || !e.contextElement || l || u.observe(e.contextElement), u.observe(n);
  }
  let d = l ? $e(e) : null;
  return l && function f() {
    const p = $e(e);
    !d || p.x === d.x && p.y === d.y && p.width === d.width && p.height === d.height || t(), d = p, c = requestAnimationFrame(f);
  }(), t(), () => {
    var f;
    h.forEach((p) => {
      a && p.removeEventListener("scroll", t), o && p.removeEventListener("resize", t);
    }), (f = u) == null || f.disconnect(), u = null, l && cancelAnimationFrame(c);
  };
}
const dc = (e, n, t) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: ih, ...t }, o = { ...i.platform, _c: s };
  return Yu(e, n, { ...i, platform: o });
};
let rh = class extends oe {
  get nestedTrigger() {
    return this.props.nestedTrigger || "hover";
  }
  get name() {
    return "menu";
  }
  get menuName() {
    return "menu-context";
  }
  componentWillUnmount() {
    super.componentWillUnmount();
  }
  _getPopperOptions() {
    return {
      middleware: [ic()],
      placement: "right-start"
    };
  }
  _getPopperElement() {
    var n;
    return (n = this.ref.current) == null ? void 0 : n.parentElement;
  }
  _createPopper() {
    const n = this._getPopperOptions();
    this.ref.current && dc(this._getPopperElement(), this.ref.current, n).then(({ x: t, y: s }) => {
      Object.assign(this.ref.current.style, {
        left: `${t}px`,
        top: `${s}px`,
        position: "absolute"
      });
    });
  }
  afterRender(n) {
    super.afterRender(n), this.props.controlledMenu && this._createPopper();
  }
  beforeRender() {
    const n = super.beforeRender();
    return n.className = M(n.className, "menu-popup"), n;
  }
  renderToggleIcon() {
    return /* @__PURE__ */ b("span", { class: "contextmenu-toggle-icon caret-right" });
  }
};
var Qt, Ie, Hn, In, qs, pc, Gs, mc;
class lt extends kt {
  constructor() {
    super(...arguments);
    x(this, qs);
    x(this, Gs);
    x(this, Qt, void 0);
    x(this, Ie, void 0);
    x(this, Hn, void 0);
    w(this, "arrowEl");
    x(this, In, void 0);
  }
  get isShown() {
    var t;
    return (t = m(this, Qt)) == null ? void 0 : t.classList.contains(this.constructor.CLASS_SHOW);
  }
  get menu() {
    return m(this, Qt) || this._ensureMenu();
  }
  get trigger() {
    return m(this, Hn) || this.element;
  }
  get isDynamic() {
    return this.options.items || this.options.menu;
  }
  init() {
    const { element: t } = this;
    t !== document.body && !t.hasAttribute("data-toggle") && t.setAttribute("data-toggle", "contextmenu");
  }
  show(t) {
    return R(this, Hn, t), this.emit("show", { menu: this, trigger: this.trigger }).defaultPrevented || this.isDynamic && !this._renderMenu() ? !1 : (this.menu.classList.add(this.constructor.CLASS_SHOW), this._createPopper(), this.emit("shown", this), !0);
  }
  hide() {
    var s, i;
    return (s = m(this, In)) == null || s.call(this), this.emit("hide", this).defaultPrevented ? !1 : ((i = m(this, Qt)) == null || i.classList.remove(this.constructor.CLASS_SHOW), this.emit("hidden", this), !0);
  }
  toggle(t) {
    return this.isShown ? this.hide() : this.show(t);
  }
  destroy() {
    var t;
    super.destroy(), (t = m(this, Qt)) == null || t.remove();
  }
  _ensureMenu() {
    var o;
    const { element: t } = this, s = this.constructor.MENU_CLASS;
    let i;
    if (this.isDynamic)
      i = document.createElement("div"), i.classList.add(s), document.body.appendChild(i);
    else if (t) {
      const r = t.getAttribute("href") ?? t.dataset.target;
      if ((r == null ? void 0 : r[0]) === "#" && (i = document.querySelector(r)), !i) {
        const l = t.nextElementSibling;
        l != null && l.classList.contains(s) ? i = l : i = (o = t.parentNode) == null ? void 0 : o.querySelector(`.${s}`);
      }
      i && i.classList.add("menu-popup");
    }
    if (!i)
      throw new Error("ContextMenu: Cannot find menu element");
    return i.style.width = "max-content", i.style.position = this.options.strategy, i.style.top = "0", i.style.left = "0", R(this, Qt, i), i;
  }
  _getPopperOptions() {
    var o;
    const { placement: t, strategy: s } = this.options, i = {
      middleware: [],
      placement: t,
      strategy: s
    };
    return this.options.flip && ((o = i.middleware) == null || o.push(ic())), i;
  }
  _createPopper() {
    const t = this._getPopperOptions(), s = this._getPopperElement();
    R(this, In, oh(s, this.menu, () => {
      dc(s, this.menu, t).then(({ x: i, y: o, middlewareData: r, placement: l }) => {
        Object.assign(this.menu.style, {
          left: `${i}px`,
          top: `${o}px`
        });
        const a = l.split("-")[0], h = N(this, qs, pc).call(this, a);
        if (r.arrow && this.arrowEl) {
          const { x: c, y: u } = r.arrow;
          Object.assign(this.arrowEl.style, {
            left: c != null ? `${c}px` : "",
            top: u != null ? `${u}px` : "",
            [h]: `${-this.arrowEl.offsetWidth / 2}px`,
            background: "inherit",
            border: "inherit",
            ...N(this, Gs, mc).call(this, a)
          });
        }
      });
    }));
  }
  _getMenuOptions() {
    const { menu: t, items: s } = this.options;
    let i = s || (t == null ? void 0 : t.items);
    if (i)
      return typeof i == "function" && (i = i(this)), {
        nestedTrigger: "hover",
        ...t,
        items: i
      };
  }
  _renderMenu() {
    const t = this._getMenuOptions();
    return !t || this.emit("updateMenu", { menu: t, trigger: this.trigger, contextmenu: this }).defaultPrevented ? !1 : (cs(E(rh, t), this.menu), !0);
  }
  _getPopperElement() {
    return m(this, Ie) || R(this, Ie, {
      getBoundingClientRect: () => {
        const { trigger: t } = this;
        if (t instanceof MouseEvent) {
          const { clientX: s, clientY: i } = t;
          return {
            width: 0,
            height: 0,
            top: i,
            right: s,
            bottom: i,
            left: s
          };
        }
        return t instanceof HTMLElement ? t.getBoundingClientRect() : t;
      },
      contextElement: this.element
    }), m(this, Ie);
  }
  static clear(t) {
    var a, h;
    t instanceof Event && (t = { event: t });
    const { event: s, exclude: i, ignoreSelector: o = ".not-hide-menu" } = t || {};
    if (s && o && ((h = (a = s.target).closest) != null && h.call(a, o)) || s && Ku(s))
      return;
    const r = this.getAll().entries(), l = new Set(i || []);
    for (const [c, u] of r)
      l.has(c) || u.hide();
  }
  static show(t) {
    const { event: s, ...i } = t, o = this.ensure(document.body);
    return Object.keys(i).length && o.setOptions(i), o.show(s), s instanceof Event && s.stopPropagation(), o;
  }
  static hide() {
    const t = this.get(document.body);
    return t == null || t.hide(), t;
  }
}
Qt = new WeakMap(), Ie = new WeakMap(), Hn = new WeakMap(), In = new WeakMap(), qs = new WeakSet(), pc = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
}, Gs = new WeakSet(), mc = function(t) {
  return t === "bottom" ? {
    borderBottomStyle: "none",
    borderRightStyle: "none"
  } : t === "top" ? {
    borderTopStyle: "none",
    borderLeftStyle: "none"
  } : t === "left" ? {
    borderBottomStyle: "none",
    borderLeftStyle: "none"
  } : {
    borderTopStyle: "none",
    borderRightStyle: "none"
  };
}, w(lt, "NAME", "contextmenu"), w(lt, "EVENTS", !0), w(lt, "DEFAULT", {
  placement: "bottom-start",
  strategy: "fixed",
  flip: !0,
  preventOverflow: !0
}), w(lt, "MENU_CLASS", "contextmenu"), w(lt, "CLASS_SHOW", "show"), w(lt, "MENU_SELECTOR", '[data-toggle="contextmenu"]:not(.disabled):not(:disabled)');
document.addEventListener("contextmenu", (e) => {
  var s;
  const n = e.target;
  if ((s = n.closest) != null && s.call(n, `.${lt.MENU_CLASS}`))
    return;
  const t = n.closest(lt.MENU_SELECTOR);
  t && (lt.ensure(t).show(e), e.preventDefault());
});
document.addEventListener("click", lt.clear.bind(lt));
function gc(e) {
  return e.split("-")[1];
}
function lh(e) {
  return e === "y" ? "height" : "width";
}
function yc(e) {
  return e.split("-")[0];
}
function _c(e) {
  return ["top", "bottom"].includes(yc(e)) ? "x" : "y";
}
function ch(e) {
  return typeof e != "number" ? function(n) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...n };
  }(e) : { top: e, right: e, bottom: e, left: e };
}
const ah = Math.min, uh = Math.max;
function hh(e, n, t) {
  return uh(e, ah(n, t));
}
const fh = (e) => ({ name: "arrow", options: e, async fn(n) {
  const { element: t, padding: s = 0 } = e || {}, { x: i, y: o, placement: r, rects: l, platform: a } = n;
  if (t == null)
    return {};
  const h = ch(s), c = { x: i, y: o }, u = _c(r), d = lh(u), f = await a.getDimensions(t), p = u === "y" ? "top" : "left", g = u === "y" ? "bottom" : "right", y = l.reference[d] + l.reference[u] - c[u] - l.floating[d], _ = c[u] - l.reference[u], v = await (a.getOffsetParent == null ? void 0 : a.getOffsetParent(t));
  let S = v ? u === "y" ? v.clientHeight || 0 : v.clientWidth || 0 : 0;
  S === 0 && (S = l.floating[d]);
  const $ = y / 2 - _ / 2, T = h[p], D = S - f[d] - h[g], L = S / 2 - f[d] / 2 + $, O = hh(T, L, D), k = gc(r) != null && L != O && l.reference[d] / 2 - (L < T ? h[p] : h[g]) - f[d] / 2 < 0;
  return { [u]: c[u] - (k ? L < T ? T - L : D - L : 0), data: { [u]: O, centerOffset: L - O } };
} }), dh = ["top", "right", "bottom", "left"];
dh.reduce((e, n) => e.concat(n, n + "-start", n + "-end"), []);
const ph = function(e) {
  return e === void 0 && (e = 0), { name: "offset", options: e, async fn(n) {
    const { x: t, y: s } = n, i = await async function(o, r) {
      const { placement: l, platform: a, elements: h } = o, c = await (a.isRTL == null ? void 0 : a.isRTL(h.floating)), u = yc(l), d = gc(l), f = _c(l) === "x", p = ["left", "top"].includes(u) ? -1 : 1, g = c && f ? -1 : 1, y = typeof r == "function" ? r(o) : r;
      let { mainAxis: _, crossAxis: v, alignmentAxis: S } = typeof y == "number" ? { mainAxis: y, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...y };
      return d && typeof S == "number" && (v = d === "end" ? -1 * S : S), f ? { x: v * g, y: _ * p } : { x: _ * p, y: v * g };
    }(n, e);
    return { x: t + i.x, y: s + i.y, data: i };
  } };
};
var je, We, Fe, Ks, bc;
const Yo = class extends lt {
  constructor() {
    super(...arguments);
    x(this, Ks);
    x(this, je, !1);
    x(this, We, 0);
    w(this, "hideLater", () => {
      m(this, Fe).call(this), R(this, We, window.setTimeout(this.hide.bind(this), 100));
    });
    x(this, Fe, () => {
      clearTimeout(m(this, We)), R(this, We, 0);
    });
  }
  get isHover() {
    return this.options.trigger === "hover";
  }
  get elementShowClass() {
    return `with-${this.constructor.NAME}-show`;
  }
  show(t, s) {
    (s == null ? void 0 : s.clearOthers) !== !1 && Yo.clear({ event: s == null ? void 0 : s.event, exclude: [this.element] });
    const i = super.show(t);
    return i && (!m(this, je) && this.isHover && N(this, Ks, bc).call(this), this.element.classList.add(this.elementShowClass)), i;
  }
  hide() {
    const t = super.hide();
    return t && this.element.classList.remove(this.elementShowClass), t;
  }
  toggle(t, s) {
    return this.isShown ? this.hide() : this.show(t, { event: t, ...s });
  }
  destroy() {
    m(this, je) && (this.element.removeEventListener("mouseleave", this.hideLater), this.menu.removeEventListener("mouseenter", m(this, Fe)), this.menu.removeEventListener("mouseleave", this.hideLater)), super.destroy();
  }
  _getArrowSize() {
    const { arrow: t } = this.options;
    return t ? typeof t == "number" ? t : 8 : 0;
  }
  _getPopperOptions() {
    var i, o;
    const t = super._getPopperOptions(), s = this._getArrowSize();
    return s && this.arrowEl && ((i = t.middleware) == null || i.push(ph(s)), (o = t.middleware) == null || o.push(fh({ element: this.arrowEl }))), t;
  }
  _ensureMenu() {
    const t = super._ensureMenu();
    if (this.options.arrow) {
      const s = this._getArrowSize();
      this.arrowEl = document.createElement("div"), this.arrowEl.style.position = "absolute", this.arrowEl.style.width = `${s}px`, this.arrowEl.style.height = `${s}px`, this.arrowEl.style.transform = "rotate(45deg)", t.append(this.arrowEl);
    }
    return t;
  }
  _getMenuOptions() {
    const t = super._getMenuOptions();
    if (t && this.options.arrow) {
      const { afterRender: s } = t;
      t.afterRender = (...i) => {
        var o;
        this.arrowEl && ((o = this.menu.querySelector(".menu")) == null || o.appendChild(this.arrowEl)), s == null || s(...i);
      };
    }
    return t;
  }
};
let st = Yo;
je = new WeakMap(), We = new WeakMap(), Fe = new WeakMap(), Ks = new WeakSet(), bc = function() {
  const { menu: t } = this;
  t.addEventListener("mouseenter", m(this, Fe)), t.addEventListener("mouseleave", this.hideLater), this.element.addEventListener("mouseleave", this.hideLater), R(this, je, !0);
}, w(st, "NAME", "dropdown"), w(st, "MENU_CLASS", "dropdown-menu"), w(st, "MENU_SELECTOR", '[data-toggle="dropdown"]:not(.disabled):not(:disabled)'), w(st, "DEFAULT", {
  ...lt.DEFAULT,
  strategy: "fixed",
  trigger: "click"
});
document.addEventListener("click", function(e) {
  var s;
  const n = e.target, t = (s = n.closest) == null ? void 0 : s.call(n, st.MENU_SELECTOR);
  if (t) {
    const i = st.ensure(t);
    i.options.trigger === "click" && i.toggle();
  } else
    st.clear({ event: e });
});
document.addEventListener("mouseover", function(e) {
  var i;
  const n = e.target, t = (i = n.closest) == null ? void 0 : i.call(n, st.MENU_SELECTOR);
  if (!t)
    return;
  const s = st.ensure(t);
  s.isHover && s.show();
});
const mh = (e) => {
  const n = document.getElementsByClassName("with-dropdown-show")[0];
  if (!n)
    return;
  const t = typeof n.closest == "function" ? n.closest(st.MENU_SELECTOR) : null;
  !t || !e.target.contains(t) || st.clear({ event: e });
};
window.addEventListener("scroll", mh, !0);
var jn, Be;
class gh extends U {
  constructor(t) {
    var s;
    super(t);
    x(this, jn, void 0);
    x(this, Be, cn());
    this.state = { placement: ((s = t.dropdown) == null ? void 0 : s.placement) || "", show: !1 };
  }
  get ref() {
    return m(this, Be);
  }
  get triggerElement() {
    return m(this, Be).current;
  }
  componentDidMount() {
    const { modifiers: t = [], ...s } = this.props.dropdown || {};
    t.push({
      name: "dropdown-trigger",
      enabled: !0,
      phase: "beforeMain",
      fn: ({ state: i }) => {
        var r;
        const o = ((r = i.placement) == null ? void 0 : r.split("-").shift()) || "";
        this.setState({ placement: o });
      }
    }), R(this, jn, st.ensure(this.triggerElement, {
      ...s,
      modifiers: t,
      onShow: () => {
        this.setState({ show: !0 });
      },
      onHide: () => {
        this.setState({ show: !0 });
      }
    }));
  }
  componentWillUnmount() {
    var t;
    (t = m(this, jn)) == null || t.destroy();
  }
  beforeRender() {
    const { className: t, children: s, dropdown: i, ...o } = this.props;
    return {
      className: M("dropdown", t),
      children: typeof s == "function" ? s(this.state) : s,
      ...o,
      "data-toggle": "dropdown",
      "data-dropdown-placement": this.state.placement,
      ref: m(this, Be)
    };
  }
  render() {
    const { children: t, ...s } = this.beforeRender();
    return /* @__PURE__ */ b("div", { ...s, children: t });
  }
}
jn = new WeakMap(), Be = new WeakMap();
class yh extends gh {
  get triggerElement() {
    return this.ref.current.base;
  }
  render() {
    var o;
    const { placement: n, show: t } = this.state, s = this.beforeRender();
    let { caret: i = !0 } = s;
    if (i !== !1 && (t || i === !0)) {
      const r = t ? n : (o = this.props.dropdown) == null ? void 0 : o.placement;
      i = (r === "top" ? "up" : r === "bottom" ? "down" : r) || (typeof i == "string" ? i : "") || "down";
    }
    return s.caret = i, /* @__PURE__ */ b(Tt, { ...s });
  }
}
function wc({
  key: e,
  type: n,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ b(yh, { type: t, ...s });
}
let vc = class extends U {
  componentDidMount() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var n;
    (n = this.props.beforeDestroy) == null || n.call(this);
  }
  handleItemClick(n, t, s, i) {
    s && s.call(i.target, i);
    const { onClickItem: o } = this.props;
    o && o.call(this, { item: n, index: t, event: i });
  }
  beforeRender() {
    var s;
    const n = { ...this.props }, t = (s = n.beforeRender) == null ? void 0 : s.call(this, n);
    return t && Object.assign(n, t), typeof n.items == "function" && (n.items = n.items.call(this)), n;
  }
  onRenderItem(n, t) {
    const { key: s = t, ...i } = n;
    return /* @__PURE__ */ b(Tt, { ...i }, s);
  }
  renderItem(n, t, s) {
    const { itemRender: i, defaultBtnProps: o, onClickItem: r } = n, l = { key: s, ...t };
    if (o && Object.assign(l, o), r && (l.onClick = this.handleItemClick.bind(this, l, s, t.onClick)), i) {
      const a = i.call(this, l, E);
      if (it(a))
        return a;
      typeof a == "object" && Object.assign(l, a);
    }
    return this.onRenderItem(l, s);
  }
  render() {
    const n = this.beforeRender(), {
      className: t,
      items: s,
      size: i,
      type: o,
      defaultBtnProps: r,
      children: l,
      itemRender: a,
      onClickItem: h,
      beforeRender: c,
      afterRender: u,
      beforeDestroy: d,
      ...f
    } = n;
    return /* @__PURE__ */ b(
      "div",
      {
        className: M("btn-group", i ? `size-${i}` : "", t),
        ...f,
        children: [
          s && s.map(this.renderItem.bind(this, n)),
          l
        ]
      }
    );
  }
};
function _h({
  key: e,
  type: n,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ b(vc, { type: t, ...s });
}
var Le;
let ae = (Le = class extends ji {
  beforeRender() {
    const { gap: n, btnProps: t, wrap: s, ...i } = super.beforeRender();
    return i.className = M(i.className, s ? "flex-wrap" : "", typeof n == "number" ? `gap-${n}` : ""), typeof n == "string" && (i.style ? i.style.gap = n : i.style = { gap: n }), i;
  }
  isBtnItem(n) {
    return n === "item" || n === "dropdown";
  }
  renderTypedItem(n, t, s) {
    const i = this.isBtnItem(s.type) ? { btnType: "ghost", ...this.props.btnProps } : {}, o = {
      ...t,
      ...i,
      ...s,
      className: M(`${this.name}-${s.type}`, t.className, i.className, s.className),
      style: Object.assign({}, t.style, i.style, s.style)
    };
    return /* @__PURE__ */ b(n, { ...o });
  }
}, w(Le, "ItemComponents", {
  item: Gu,
  dropdown: wc,
  "btn-group": _h
}), w(Le, "ROOT_TAG", "nav"), w(Le, "NAME", "toolbar"), w(Le, "defaultProps", {
  btnProps: {
    btnType: "ghost"
  }
}), Le);
function bh({
  className: e,
  style: n,
  actions: t,
  heading: s,
  content: i,
  contentClass: o,
  children: r,
  close: l,
  onClose: a,
  icon: h,
  ...c
}) {
  let u;
  l === !0 ? u = /* @__PURE__ */ b(Tt, { className: "alert-close btn ghost", square: !0, onClick: a, children: /* @__PURE__ */ b("span", { class: "close" }) }) : it(l) ? u = l : typeof l == "object" && (u = /* @__PURE__ */ b(Tt, { ...l, onClick: a }));
  const d = it(t) ? t : t ? /* @__PURE__ */ b(ae, { ...t }) : null;
  return /* @__PURE__ */ b("div", { className: M("alert", e), style: n, ...c, children: [
    it(h) ? h : typeof h == "string" ? /* @__PURE__ */ b("i", { className: `icon ${h}` }) : null,
    it(i) ? i : /* @__PURE__ */ b("div", { className: M("alert-content", o), children: [
      it(s) ? s : s && /* @__PURE__ */ b("div", { className: "alert-heading", children: s }),
      /* @__PURE__ */ b("div", { className: "alert-text", children: i }),
      s ? d : null
    ] }),
    s ? null : d,
    u,
    r
  ] });
}
function wh(e) {
  if (e === "center")
    return "fade-from-center";
  if (e) {
    if (e.includes("top"))
      return "fade-from-top";
    if (e.includes("bottom"))
      return "fade-from-bottom";
  }
  return "fade";
}
let vh = class extends U {
  componentDidMount() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var n;
    (n = this.props.beforeDestroy) == null || n.call(this);
  }
  render() {
    const {
      afterRender: n,
      beforeDestroy: t,
      margin: s,
      type: i,
      placement: o,
      animation: r,
      show: l,
      className: a,
      time: h,
      ...c
    } = this.props;
    return /* @__PURE__ */ b(
      bh,
      {
        className: M("messager", a, i, r === !0 ? wh(o) : r, l ? "in" : ""),
        ...c
      }
    );
  }
};
var ze, Ss;
class xs extends J {
  constructor() {
    super(...arguments);
    x(this, ze);
    w(this, "_show", !1);
    w(this, "_showTimer", 0);
    w(this, "_afterRender", ({ firstRender: t }) => {
      t && this.show();
      const { margin: s } = this.options;
      s && (this.element.style.margin = `${s}px`);
    });
  }
  get isShown() {
    return this._show;
  }
  afterInit() {
    this.on("click", (t) => {
      t.target.closest('.alert-close,[data-dismiss="messager"]') && (t.preventDefault(), t.stopPropagation(), this.hide());
    });
  }
  setOptions(t) {
    return t = super.setOptions(t), {
      ...t,
      show: this._show,
      afterRender: this._afterRender
    };
  }
  show() {
    this._show || (this.emit("show"), this.render(), this._show = !0, N(this, ze, Ss).call(this, () => {
      this.emit("shown");
      const { time: t } = this.options;
      t && N(this, ze, Ss).call(this, () => this.hide(), t);
    }));
  }
  hide() {
    this._show && (this._show = !1, this.emit("hide"), this.render(), N(this, ze, Ss).call(this, () => {
      this.emit("hidden");
    }));
  }
}
ze = new WeakSet(), Ss = function(t, s = 200) {
  this._showTimer && clearTimeout(this._showTimer), this._showTimer = window.setTimeout(() => {
    t(), this._showTimer = 0;
  }, s);
}, w(xs, "NAME", "MessagerItem"), w(xs, "EVENTS", !0), w(xs, "Component", vh);
var we, Ue, jt, Ys, xc, Xs, Sc;
const Xo = class extends kt {
  constructor() {
    super(...arguments);
    x(this, Ys);
    x(this, Xs);
    x(this, we, void 0);
    x(this, Ue, us(6));
    x(this, jt, void 0);
  }
  get id() {
    return m(this, Ue);
  }
  get isShown() {
    var t;
    return !!((t = m(this, jt)) != null && t.isShown);
  }
  show(t) {
    this.setOptions(t), N(this, Ys, xc).call(this).show();
  }
  hide() {
    var t;
    (t = m(this, jt)) == null || t.hide();
  }
  static show(t) {
    typeof t == "string" && (t = { content: t });
    const { container: s, ...i } = t, o = new Xo(s || "body", i);
    return o.show(), o;
  }
};
let mn = Xo;
we = new WeakMap(), Ue = new WeakMap(), jt = new WeakMap(), Ys = new WeakSet(), xc = function() {
  if (m(this, jt))
    m(this, jt).setOptions(this.options);
  else {
    const t = N(this, Xs, Sc).call(this), s = new xs(t, this.options);
    s.on("hidden", () => {
      s.destroy(), t.remove(), R(this, we, void 0);
    }), R(this, jt, s);
  }
  return m(this, jt);
}, Xs = new WeakSet(), Sc = function() {
  if (m(this, we))
    return m(this, we);
  const { placement: t = "top" } = this.options;
  let s = this.element.querySelector(`.messagers-${t}`);
  s || (s = document.createElement("div"), s.className = `messagers messagers-${t}`, this.element.appendChild(s));
  let i = s.querySelector(`#messager-${m(this, Ue)}`);
  return i || (i = document.createElement("div"), i.className = "messager-holder", i.id = `messager-${m(this, Ue)}`, s.appendChild(i), R(this, we, i)), i;
}, w(mn, "NAME", "messager"), w(mn, "DEFAULT", {
  placement: "top",
  animation: !0,
  close: !0,
  margin: 6,
  time: 5e3
});
A(document).on("zui.messager.show", (e, n) => {
  n && mn.show(n);
});
var bs;
let xh = (bs = class extends U {
  render() {
    const { percent: n, circleSize: t, circleBorderSize: s, circleBgColor: i, circleColor: o } = this.props, r = (t - s) / 2, l = t / 2;
    return /* @__PURE__ */ b("svg", { width: t, height: t, class: "progress-circle", children: [
      /* @__PURE__ */ b("circle", { cx: l, cy: l, r, stroke: i, "stroke-width": s }),
      /* @__PURE__ */ b("circle", { cx: l, cy: l, r, stroke: o, "stroke-dasharray": Math.PI * r * 2, "stroke-dashoffset": Math.PI * r * 2 * (100 - n) / 100, "stroke-width": s }),
      /* @__PURE__ */ b("text", { x: l, y: l + s / 4, "dominant-baseline": "middle", style: { fontSize: `${r}px` }, children: Math.round(n) })
    ] });
  }
}, w(bs, "NAME", "zui.progress-circle"), w(bs, "defaultProps", {
  circleSize: 24,
  circleBorderSize: 2,
  circleBgColor: "var(--progress-circle-bg)",
  circleColor: "var(--progress-circle-bar-color)"
}), bs);
class Mr extends J {
}
w(Mr, "NAME", "table-sorter"), w(Mr, "Component", xh);
let Sh = class extends U {
  constructor() {
    super(...arguments);
    w(this, "state", { checked: !1 });
    w(this, "handleOnClick", () => {
      this.setState({ checked: !this.state.checked });
    });
  }
  componentDidMount() {
    this.setState({ checked: this.props.defaultChecked ?? !1 });
  }
  render() {
    const {
      component: t,
      className: s,
      children: i,
      text: o,
      icon: r,
      surffixIcon: l,
      disabled: a,
      defaultChecked: h,
      onChange: c,
      ...u
    } = this.props, d = this.state.checked ? 1 : 0, f = t || "div", p = typeof r == "string" ? /* @__PURE__ */ b("i", { class: `icon ${r}` }) : r, g = typeof l == "string" ? /* @__PURE__ */ b("i", { class: `icon ${l}` }) : l, y = [
      /* @__PURE__ */ b("input", { onChange: c, type: "checkbox", value: d, checked: !!this.state.checked }),
      /* @__PURE__ */ b("label", { children: [
        p,
        o,
        g
      ] })
    ];
    return E(
      f,
      {
        className: M("switch", s, { disabled: a }),
        onClick: this.handleOnClick,
        ...u
      },
      ...y,
      i
    );
  }
};
class Or extends J {
}
w(Or, "NAME", "switch"), w(Or, "Component", Sh);
function Eh(e) {
  const n = typeof e == "string" ? document.querySelector(e) : e;
  if (!n)
    return !1;
  if (n instanceof HTMLInputElement || n instanceof HTMLTextAreaElement)
    return n.select(), !0;
  if (window.getSelection) {
    const t = window.getSelection();
    if (t) {
      const s = document.createRange();
      return s.selectNodeContents(n), t.removeAllRanges(), t.addRange(s), !0;
    }
  }
  return !1;
}
function Ch(e, n) {
  const t = typeof e == "string" ? document.querySelector(e) : e;
  if (!t)
    return !1;
  const s = t.getBoundingClientRect(), i = window.innerHeight || document.documentElement.clientHeight, o = window.innerWidth || document.documentElement.clientWidth;
  if (n != null && n.fullyCheck)
    return s.left >= 0 && s.top >= 0 && s.left + s.width <= o && s.top + s.height <= i;
  const r = s.top <= i && s.top + s.height >= 0, l = s.left <= o && s.left + s.width >= 0;
  return r && l;
}
const Ed = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  classes: M,
  getClassList: Ii,
  isElementVisible: Ch,
  selectText: Eh
}, Symbol.toStringTag, { value: "Module" }));
/*! js-cookie v3.0.1 | MIT */
function ms(e) {
  for (var n = 1; n < arguments.length; n++) {
    var t = arguments[n];
    for (var s in t)
      e[s] = t[s];
  }
  return e;
}
var $h = {
  read: function(e) {
    return e[0] === '"' && (e = e.slice(1, -1)), e.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
  },
  write: function(e) {
    return encodeURIComponent(e).replace(
      /%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,
      decodeURIComponent
    );
  }
};
function wo(e, n) {
  function t(i, o, r) {
    if (!(typeof document > "u")) {
      r = ms({}, n, r), typeof r.expires == "number" && (r.expires = new Date(Date.now() + r.expires * 864e5)), r.expires && (r.expires = r.expires.toUTCString()), i = encodeURIComponent(i).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
      var l = "";
      for (var a in r)
        r[a] && (l += "; " + a, r[a] !== !0 && (l += "=" + r[a].split(";")[0]));
      return document.cookie = i + "=" + e.write(o, i) + l;
    }
  }
  function s(i) {
    if (!(typeof document > "u" || arguments.length && !i)) {
      for (var o = document.cookie ? document.cookie.split("; ") : [], r = {}, l = 0; l < o.length; l++) {
        var a = o[l].split("="), h = a.slice(1).join("=");
        try {
          var c = decodeURIComponent(a[0]);
          if (r[c] = e.read(h, c), i === c)
            break;
        } catch {
        }
      }
      return i ? r[i] : r;
    }
  }
  return Object.create(
    {
      set: t,
      get: s,
      remove: function(i, o) {
        t(
          i,
          "",
          ms({}, o, {
            expires: -1
          })
        );
      },
      withAttributes: function(i) {
        return wo(this.converter, ms({}, this.attributes, i));
      },
      withConverter: function(i) {
        return wo(ms({}, this.converter, i), this.attributes);
      }
    },
    {
      attributes: { value: Object.freeze(n) },
      converter: { value: Object.freeze(e) }
    }
  );
}
var Rh = wo($h, { path: "/" });
window.$ && Object.assign(window.$, { cookie: Rh });
var Ec = function(e, n, t, s) {
  var i;
  n[0] = 0;
  for (var o = 1; o < n.length; o++) {
    var r = n[o++], l = n[o] ? (n[0] |= r ? 1 : 2, t[n[o++]]) : n[++o];
    r === 3 ? s[0] = l : r === 4 ? s[1] = Object.assign(s[1] || {}, l) : r === 5 ? (s[1] = s[1] || {})[n[++o]] = l : r === 6 ? s[1][n[++o]] += l + "" : r ? (i = e.apply(l, Ec(e, l, t, ["", null])), s.push(i), l[0] ? n[0] |= 2 : (n[o - 2] = 0, n[o] = i)) : s.push(l);
  }
  return s;
}, Pr = /* @__PURE__ */ new Map();
function Cc(e) {
  var n = Pr.get(this);
  return n || (n = /* @__PURE__ */ new Map(), Pr.set(this, n)), (n = Ec(this, n.get(e) || (n.set(e, n = function(t) {
    for (var s, i, o = 1, r = "", l = "", a = [0], h = function(d) {
      o === 1 && (d || (r = r.replace(/^\s*\n\s*|\s*\n\s*$/g, ""))) ? a.push(0, d, r) : o === 3 && (d || r) ? (a.push(3, d, r), o = 2) : o === 2 && r === "..." && d ? a.push(4, d, 0) : o === 2 && r && !d ? a.push(5, 0, !0, r) : o >= 5 && ((r || !d && o === 5) && (a.push(o, 0, r, i), o = 6), d && (a.push(o, d, 0, i), o = 6)), r = "";
    }, c = 0; c < t.length; c++) {
      c && (o === 1 && h(), h(c));
      for (var u = 0; u < t[c].length; u++)
        s = t[c][u], o === 1 ? s === "<" ? (h(), a = [a], o = 3) : r += s : o === 4 ? r === "--" && s === ">" ? (o = 1, r = "") : r = s + r[0] : l ? s === l ? l = "" : r += s : s === '"' || s === "'" ? l = s : s === ">" ? (h(), o = 1) : o && (s === "=" ? (o = 5, i = r, r = "") : s === "/" && (o < 5 || t[c][u + 1] === ">") ? (h(), o === 3 && (a = a[0]), o = a, (a = a[0]).push(2, 0, o), o = 0) : s === " " || s === "	" || s === `
` || s === "\r" ? (h(), o = 2) : r += s), o === 3 && r === "!--" && (o = 4, a = a[0]);
    }
    return h(), a;
  }(e)), n), arguments, [])).length > 1 ? n : n[0];
}
var kh = Cc.bind(E);
Object.assign(window, { htm: Cc, html: kh, preact: Ia });
var Wn, Zt, vt, Ve, qe, Es;
const Jo = class {
  /**
   * Create new store instance
   * @param name Name of store
   * @param type Store type
   */
  constructor(n, t = "local") {
    x(this, qe);
    x(this, Wn, void 0);
    x(this, Zt, void 0);
    x(this, vt, void 0);
    x(this, Ve, void 0);
    R(this, Wn, t), R(this, Zt, `ZUI_STORE:${n ?? us()}`), R(this, vt, t === "local" ? localStorage : sessionStorage);
  }
  /**
   * Get store type
   */
  get type() {
    return m(this, Wn);
  }
  /**
   * Get session type store instance
   */
  get session() {
    return this.type === "session" ? this : (m(this, Ve) || R(this, Ve, new Jo(m(this, Zt), "session")), m(this, Ve));
  }
  /**
   * Get value from store
   * @param key Key to get
   * @param defaultValue default value to return if key is not found
   * @returns Value of key or defaultValue if key is not found
   */
  get(n, t) {
    const s = m(this, vt).getItem(N(this, qe, Es).call(this, n));
    return typeof s == "string" ? JSON.parse(s) : s ?? t;
  }
  /**
   * Set key-value pair in store
   * @param key Key to set
   * @param value Value to set
   */
  set(n, t) {
    if (t == null)
      return this.remove(n);
    m(this, vt).setItem(N(this, qe, Es).call(this, n), JSON.stringify(t));
  }
  /**
   * Remove key-value pair from store
   * @param key Key to remove
   */
  remove(n) {
    m(this, vt).removeItem(N(this, qe, Es).call(this, n));
  }
  /**
   * Iterate all key-value pairs in store
   * @param callback Callback function to call for each key-value pair in the store
   */
  each(n) {
    for (let t = 0; t < m(this, vt).length; t++) {
      const s = m(this, vt).key(t);
      if (s != null && s.startsWith(m(this, Zt))) {
        const i = m(this, vt).getItem(s);
        typeof i == "string" && n(s.substring(m(this, Zt).length + 1), JSON.parse(i));
      }
    }
  }
  /**
   * Get all key values in store
   * @returns All key-value pairs in the store
   */
  getAll() {
    const n = {};
    return this.each((t, s) => {
      n[t] = s;
    }), n;
  }
};
let js = Jo;
Wn = new WeakMap(), Zt = new WeakMap(), vt = new WeakMap(), Ve = new WeakMap(), qe = new WeakSet(), Es = function(n) {
  return `${m(this, Zt)}:${n}`;
};
const Th = new js("DEFAULT");
function Ah(e, n = "local") {
  return new js(e, n);
}
Object.assign(Th, { create: Ah });
const W = qu, qo = window.document;
let gs, Yt;
const Nh = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, Lh = /^(?:text|application)\/javascript/i, Mh = /^(?:text|application)\/xml/i, $c = "application/json", Rc = "text/html", Oh = /^\s*$/, vo = qo.createElement("a");
vo.href = window.location.href;
function Ph(e, n, t) {
  const s = new CustomEvent(n, { detail: t });
  return W(e).trigger(s, t), !s.defaultPrevented;
}
function Re(e, n, t, s) {
  if (e.global)
    return Ph(n || qo, t, s);
}
W.active = 0;
function Dh(e) {
  e.global && W.active++ === 0 && Re(e, null, "ajaxStart");
}
function Hh(e) {
  e.global && !--W.active && Re(e, null, "ajaxStop");
}
function Ih(e, n) {
  const t = n.context;
  if (n.beforeSend.call(t, e, n) === !1 || Re(n, t, "ajaxBeforeSend", [e, n]) === !1)
    return !1;
  Re(n, t, "ajaxSend", [e, n]);
}
function jh(e, n, t) {
  const s = t.context, i = "success";
  t.success.call(s, e, i, n), Re(t, s, "ajaxSuccess", [n, t, e]), kc(i, n, t);
}
function ys(e, n, t, s) {
  const i = s.context;
  s.error.call(i, t, n, e), Re(s, i, "ajaxError", [t, s, e || n]), kc(n, t, s);
}
function kc(e, n, t) {
  const s = t.context;
  t.complete.call(s, n, e), Re(t, s, "ajaxComplete", [n, t]), Hh(t);
}
function Wh(e, n, t) {
  if (t.dataFilter == Jt)
    return e;
  const s = t.context;
  return t.dataFilter.call(s, e, n);
}
function Jt() {
}
W.ajaxSettings = {
  // Default type of request
  type: "GET",
  // Callback that is executed before request
  beforeSend: Jt,
  // Callback that is executed if the request succeeds
  success: Jt,
  // Callback that is executed the the server drops error
  error: Jt,
  // Callback that is executed on request complete (both: error and success)
  complete: Jt,
  // The context for the callbacks
  context: null,
  // Whether to trigger "global" Ajax events
  global: !0,
  // Transport
  xhr: function() {
    return new window.XMLHttpRequest();
  },
  // MIME types mapping
  // IIS returns Javascript as "application/x-javascript"
  accepts: {
    script: "text/javascript, application/javascript, application/x-javascript",
    json: $c,
    xml: "application/xml, text/xml",
    html: Rc,
    text: "text/plain"
  },
  // Whether the request is to another domain
  crossDomain: !1,
  // Default timeout
  timeout: 0,
  // Whether data should be serialized to string
  processData: !0,
  // Whether the browser should be allowed to cache GET responses
  cache: !0,
  //Used to handle the raw response data of XMLHttpRequest.
  //This is a pre-filtering function to sanitize the response.
  //The sanitized response should be returned
  dataFilter: Jt
};
function Fh(e) {
  return e && (e = e.split(";", 2)[0]), e && (e == Rc ? "html" : e == $c ? "json" : Lh.test(e) ? "script" : Mh.test(e) && "xml") || "text";
}
function Tc(e, n) {
  return n == "" ? e : (e + "&" + n).replace(/[&?]{1,2}/, "?");
}
function Bh(e) {
  e.processData && e.data && typeof e.data != "string" && (e.data = W.param(e.data, e.traditional)), e.data && (!e.type || e.type.toUpperCase() == "GET" || e.dataType == "jsonp") && (e.url = Tc(e.url, e.data), e.data = void 0);
}
W.ajax = function(e) {
  var p;
  const n = W.extend({}, e || {});
  let t, s;
  for (gs in W.ajaxSettings)
    n[gs] === void 0 && (n[gs] = W.ajaxSettings[gs]);
  Dh(n), n.crossDomain || (t = qo.createElement("a"), t.href = n.url, t.href = t.href, n.crossDomain = vo.protocol + "//" + vo.host != t.protocol + "//" + t.host), n.url || (n.url = window.location.toString()), (s = n.url.indexOf("#")) > -1 && (n.url = n.url.slice(0, s)), Bh(n);
  let i = n.dataType;
  /\?.+=\?/.test(n.url) && (i = "jsonp"), (n.cache === !1 || (!e || e.cache !== !0) && (i == "script" || i == "jsonp")) && (n.url = Tc(n.url, "_=" + Date.now()));
  let r = n.accepts[i];
  const l = {}, a = function(g, y) {
    l[g.toLowerCase()] = [g, y];
  }, h = /^([\w-]+:)\/\//.test(n.url) ? RegExp.$1 : window.location.protocol, c = n.xhr(), u = c.setRequestHeader;
  let d;
  if (n.crossDomain || a("X-Requested-With", "XMLHttpRequest"), a("Accept", r || "*/*"), r = n.mimeType, r && (r.indexOf(",") > -1 && (r = r.split(",", 2)[0]), (p = c.overrideMimeType) == null || p.call(c, r)), (n.contentType || n.contentType !== !1 && n.data && n.type.toUpperCase() != "GET") && a("Content-Type", n.contentType || "application/x-www-form-urlencoded"), n.headers)
    for (Yt in n.headers)
      a(Yt, n.headers[Yt]);
  if (c.setRequestHeader = a, c.onreadystatechange = function() {
    if (c.readyState == 4) {
      c.onreadystatechange = Jt, clearTimeout(d);
      let g, y = !1;
      if (c.status >= 200 && c.status < 300 || c.status == 304 || c.status == 0 && h == "file:") {
        if (i = i || Fh(n.mimeType || c.getResponseHeader("content-type")), c.responseType == "arraybuffer" || c.responseType == "blob")
          g = c.response;
        else {
          g = c.responseText;
          try {
            g = Wh(g, i, n), i == "xml" ? g = c.responseXML : i == "json" && (g = Oh.test(g) ? null : JSON.parse(g));
          } catch (_) {
            y = _;
          }
          if (y)
            return ys(y, "parsererror", c, n);
        }
        jh(g, c, n);
      } else
        ys(c.statusText || null, c.status ? "error" : "abort", c, n);
    }
  }, Ih(c, n) === !1)
    return c.abort(), ys(null, "abort", c, n), c;
  const f = "async" in n ? n.async : !0;
  if (c.open(n.type, n.url, f, n.username, n.password), n.xhrFields)
    for (Yt in n.xhrFields)
      c[Yt] = n.xhrFields[Yt];
  for (Yt in l)
    u.apply(c, l[Yt]);
  return n.timeout > 0 && (d = setTimeout(function() {
    c.onreadystatechange = Jt, c.abort(), ys(null, "timeout", c, n);
  }, n.timeout)), c.send(n.data ? n.data : null), c;
};
function qi(e, n, t, s) {
  return W.isFunction(n) && (s = t, t = n, n = void 0), W.isFunction(t) || (s = t, t = void 0), {
    url: e,
    data: n,
    success: t,
    dataType: s
  };
}
W.get = function(e, n, t, s) {
  return W.ajax(qi(e, n, t, s));
};
W.post = function(e, n, t, s) {
  const i = qi(e, n, t, s);
  return W.ajax(Object.assign(i, { type: "POST" }));
};
W.getJSON = function(e, n, t, s) {
  const i = qi(e, n, t, s);
  return i.dataType = "json", W.ajax(i);
};
W.fn.load = function(e, n, t) {
  if (!this.length)
    return this;
  const s = e.split(/\s/);
  let i;
  const o = qi(e, n, t), r = o.success;
  return s.length > 1 && (o.url = s[0], i = s[1]), o.success = (l, ...a) => {
    this.html(i ? W("<div>").html(l.replace(Nh, "")).find(i) : l), r == null || r.call(this, l, ...a);
  }, W.ajax(o), this;
};
const Dr = encodeURIComponent;
function Ac(e, n, t, s) {
  const i = W.isArray(n), o = W.isPlainObject(n);
  W.each(n, function(r, l) {
    const a = Array.isArray(l) ? "array" : typeof l;
    s && (r = t ? s : s + "[" + (o || a == "object" || a == "array" ? r : "") + "]"), !s && i ? e.add(l.name, l.value) : a == "array" || !t && a == "object" ? Ac(e, l, t, r) : e.add(r, l);
  });
}
W.param = function(e, n) {
  const t = [];
  return t.add = function(s, i) {
    W.isFunction(i) && (i = i()), i == null && (i = ""), this.push(Dr(s) + "=" + Dr(i));
  }, Ac(t, e, n), t.join("&").replace(/%20/g, "+");
};
const Cd = Object.assign(W.ajax, {
  get: W.get,
  post: W.post,
  getJSON: W.getJSON,
  param: W.param,
  ajaxSettings: W.ajaxSettings
}), $d = new Hi();
function zh(e) {
  if (e.indexOf("#") === 0 && (e = e.slice(1)), e.length === 3 && (e = e[0] + e[0] + e[1] + e[1] + e[2] + e[2]), e.length !== 6)
    throw new Error(`Invalid HEX color "${e}".`);
  return [
    parseInt(e.slice(0, 2), 16),
    // r
    parseInt(e.slice(2, 4), 16),
    // g
    parseInt(e.slice(4, 6), 16)
    // b
  ];
}
function Uh(e) {
  const [n, t, s] = typeof e == "string" ? zh(e) : e;
  return n * 0.299 + t * 0.587 + s * 0.114 > 186;
}
function Hr(e, n) {
  return Uh(e) ? (n == null ? void 0 : n.dark) ?? "#333333" : (n == null ? void 0 : n.light) ?? "#ffffff";
}
function Ir(e, n = 255) {
  return Math.min(Math.max(e, 0), n);
}
function Vh(e, n, t) {
  e = e % 360 / 360, n = Ir(n), t = Ir(t);
  const s = t <= 0.5 ? t * (n + 1) : t + n - t * n, i = t * 2 - s, o = (r) => (r = r < 0 ? r + 1 : r > 1 ? r - 1 : r, r * 6 < 1 ? i + (s - i) * r * 6 : r * 2 < 1 ? s : r * 3 < 2 ? i + (s - i) * (2 / 3 - r) * 6 : i);
  return [
    o(e + 1 / 3) * 255,
    o(e) * 255,
    o(e - 1 / 3) * 255
  ];
}
function qh(e) {
  let n = 0;
  if (typeof e != "string" && (e = String(e)), e && e.length)
    for (let t = 0; t < e.length; ++t)
      n += (t + 1) * e.charCodeAt(t);
  return n;
}
function Gh(e, n) {
  return /^[\u4e00-\u9fa5\s]+$/.test(e) ? e = e.length <= n ? e : e.substring(e.length - n) : /^[A-Za-z\d\s]+$/.test(e) ? e = e[0].toUpperCase() : e = e.length <= n ? e : e.substring(0, n), e;
}
let Nc = class extends U {
  render() {
    const {
      className: n,
      style: t,
      size: s = "",
      circle: i,
      rounded: o,
      background: r,
      foreColor: l,
      text: a,
      code: h,
      maxTextLength: c = 2,
      src: u,
      hueDistance: d = 43,
      saturation: f = 0.4,
      lightness: p = 0.6,
      children: g,
      ...y
    } = this.props, _ = ["avatar", n], v = { ...t, background: r, color: l };
    let S = 32;
    s && (typeof s == "number" ? (v.width = `${s}px`, v.height = `${s}px`, v.fontSize = `${Math.max(12, Math.round(s / 2))}px`, S = s) : (_.push(`size-${s}`), S = { xs: 20, sm: 24, lg: 48, xl: 80 }[s])), i ? _.push("circle") : o && (typeof o == "number" ? v.borderRadius = `${o}px` : _.push(`rounded-${o}`));
    let $;
    if (u)
      _.push("has-img"), $ = /* @__PURE__ */ b("img", { className: "avatar-img", src: u, alt: a });
    else if (a != null && a.length) {
      const T = Gh(a, c);
      if (_.push("has-text", `has-text-${T.length}`), r)
        !l && r && (v.color = Hr(r));
      else {
        const L = h ?? a, O = (typeof L == "number" ? L : qh(L)) * d % 360;
        if (v.background = `hsl(${O},${f * 100}%,${p * 100}%)`, !l) {
          const k = Vh(O, f, p);
          v.color = Hr(k);
        }
      }
      let D;
      S && S < 14 * T.length && (D = { transform: `scale(${S / (14 * T.length)})`, whiteSpace: "nowrap" }), $ = /* @__PURE__ */ b("div", { "data-actualSize": S, className: "avatar-text", style: D, children: T });
    }
    return /* @__PURE__ */ b(
      "div",
      {
        className: M(_),
        style: v,
        ...y,
        children: [
          $,
          g
        ]
      }
    );
  }
};
class jr extends J {
}
w(jr, "NAME", "avatar"), w(jr, "Component", Nc);
class Wr extends J {
}
w(Wr, "NAME", "btngroup"), w(Wr, "Component", vc);
function Lc(e, n, t) {
  if (t) {
    e.setAttribute("class", M(n));
    return;
  }
  Ii(e.getAttribute("class"), n).forEach(([s, i]) => {
    e.classList.toggle(s, i);
  });
}
function gn(e, n, t) {
  if (typeof n == "object")
    return Object.entries(n).forEach(([s, i]) => {
      gn(e, s, i);
    });
  t !== void 0 && (e.style[n] = typeof t == "number" ? `${t}px` : t);
}
function Ws(e, n, t) {
  if (typeof n == "object")
    return Object.entries(n).forEach(([s, i]) => {
      Ws(e, s, i);
    });
  t !== void 0 && (t === null ? e.removeAttribute(n) : e.setAttribute(n, t));
}
var ve, Fn, te, Js, Ge, Cs;
const rt = class extends kt {
  constructor() {
    super(...arguments);
    x(this, Ge);
    x(this, ve, 0);
    x(this, Fn, void 0);
    x(this, te, void 0);
    x(this, Js, (t) => {
      const s = t.target;
      (s.closest(rt.DISMISS_SELECTOR) || this.options.backdrop === !0 && !s.closest(".modal-dialog") && s.closest(".modal")) && this.hide();
    });
  }
  get modalElement() {
    return this.element;
  }
  get isShown() {
    return this.modalElement.classList.contains(rt.CLASS_SHOW);
  }
  get dialog() {
    return this.modalElement.querySelector(".modal-dialog");
  }
  afterInit() {
    if (this.on("click", m(this, Js)), this.options.responsive && typeof ResizeObserver < "u") {
      const { dialog: t } = this;
      if (t) {
        const s = new ResizeObserver(() => {
          if (!this.isShown)
            return;
          const i = t.clientWidth, o = t.clientHeight;
          (!m(this, te) || m(this, te)[0] !== i || m(this, te)[1] !== o) && (R(this, te, [i, o]), this.layout());
        });
        s.observe(t), R(this, Fn, s);
      }
    }
    this.options.show && this.show();
  }
  destroy() {
    var t;
    super.destroy(), (t = m(this, Fn)) == null || t.disconnect();
  }
  show(t) {
    if (this.isShown)
      return !1;
    this.setOptions(t);
    const { modalElement: s } = this, { animation: i, backdrop: o, className: r, style: l } = this.options;
    return Lc(s, [{
      "modal-trans": i,
      "modal-no-backdrop": !o
    }, rt.CLASS_SHOW, r]), gn(s, {
      zIndex: `${rt.zIndex++}`,
      ...l
    }), this.layout(), this.emit("show", this), N(this, Ge, Cs).call(this, () => {
      s.classList.add(rt.CLASS_SHOWN), N(this, Ge, Cs).call(this, () => {
        this.emit("shown", this);
      });
    }, 50), !0;
  }
  hide() {
    return this.isShown ? (this.modalElement.classList.remove(rt.CLASS_SHOWN), this.emit("hide", this), N(this, Ge, Cs).call(this, () => {
      this.modalElement.classList.remove(rt.CLASS_SHOW), this.emit("hidden", this);
    }), !0) : !1;
  }
  layout(t, s) {
    if (!this.isShown)
      return;
    const { dialog: i } = this;
    if (!i)
      return;
    s = s ?? this.options.size, Ws(i, "data-size", null);
    const o = { width: null, height: null };
    typeof s == "object" ? (o.width = s.width, o.height = s.height) : typeof s == "string" && ["md", "sm", "lg", "full"].includes(s) ? Ws(i, "data-size", s) : s && (o.width = s), gn(i, o), t = t ?? this.options.position ?? "fit";
    const r = i.clientWidth, l = i.clientHeight;
    R(this, te, [r, l]), typeof t == "function" && (t = t({ width: r, height: l }));
    const a = {
      top: null,
      left: null,
      bottom: null,
      right: null,
      alignSelf: "center"
    };
    typeof t == "number" ? (a.alignSelf = "flex-start", a.top = t) : typeof t == "object" && t ? (a.alignSelf = "flex-start", Object.assign(a, t)) : t === "fit" ? (a.alignSelf = "flex-start", a.top = `${Math.max(0, Math.floor((window.innerHeight - l) / 3))}px`) : t === "bottom" ? a.alignSelf = "flex-end" : t === "top" ? a.alignSelf = "flex-start" : t !== "center" && typeof t == "string" && (a.alignSelf = "flex-start", a.top = t), gn(i, a), gn(this.modalElement, "justifyContent", a.left ? "flex-start" : "center");
  }
  static query(t) {
    if (t === void 0 ? t = document.querySelector(`.modal.${rt.CLASS_SHOW}`) : typeof t == "string" && (t = document.querySelector(t)), !!t)
      return rt.get(t);
  }
  static hide(t) {
    var s;
    (s = rt.query(t)) == null || s.hide();
  }
  static show(t) {
    var s;
    (s = rt.query(t)) == null || s.show();
  }
};
let nt = rt;
ve = new WeakMap(), Fn = new WeakMap(), te = new WeakMap(), Js = new WeakMap(), Ge = new WeakSet(), Cs = function(t, s) {
  m(this, ve) && (clearTimeout(m(this, ve)), R(this, ve, 0)), t && (this.options.animation ? R(this, ve, window.setTimeout(t, s ?? this.options.transTime)) : t());
}, w(nt, "NAME", "Modal"), w(nt, "EVENTS", !0), w(nt, "DEFAULT", {
  position: "fit",
  show: !0,
  keyboard: !0,
  animation: !0,
  backdrop: !0,
  responsive: !0,
  transTime: 300
}), w(nt, "CLASS_SHOW", "show"), w(nt, "CLASS_SHOWN", "in"), w(nt, "DISMISS_SELECTOR", '[data-dismiss="modal"]'), w(nt, "zIndex", 2e3);
A(window).on("resize", () => {
  nt.all.forEach((e) => {
    const n = e;
    n.isShown && n.options.responsive && n.layout();
  });
});
A(document).on("zui.modal.hide", (e, n) => {
  nt.hide(n == null ? void 0 : n.target);
});
class Mc extends U {
  componentDidMount() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var n;
    (n = this.props.beforeDestroy) == null || n.call(this);
  }
  renderHeader() {
    const {
      header: n,
      title: t
    } = this.props;
    return it(n) ? n : n === !1 || !t ? null : /* @__PURE__ */ b("div", { className: "modal-header", children: /* @__PURE__ */ b("div", { className: "modal-title", children: t }) });
  }
  renderActions() {
    const {
      actions: n,
      closeBtn: t
    } = this.props;
    return !t && !n ? null : it(n) ? n : /* @__PURE__ */ b("div", { className: "modal-actions", children: [
      n ? /* @__PURE__ */ b(ae, { ...n }) : null,
      t ? /* @__PURE__ */ b("button", { type: "button", class: "btn square ghost", "data-dismiss": "modal", children: /* @__PURE__ */ b("span", { class: "close" }) }) : null
    ] });
  }
  renderBody() {
    const {
      body: n
    } = this.props;
    return n ? it(n) ? n : /* @__PURE__ */ b("div", { className: "modal-body", children: n }) : null;
  }
  renderFooter() {
    const {
      footer: n,
      footerActions: t
    } = this.props;
    return it(n) ? n : n === !1 || !t ? null : /* @__PURE__ */ b("div", { className: "modal-footer", children: t ? /* @__PURE__ */ b(ae, { ...t }) : null });
  }
  render() {
    const {
      className: n,
      style: t,
      children: s
    } = this.props;
    return /* @__PURE__ */ b("div", { className: M("modal-dialog", n), style: t, children: /* @__PURE__ */ b("div", { className: "modal-content", children: [
      this.renderHeader(),
      this.renderActions(),
      this.renderBody(),
      s,
      this.renderFooter()
    ] }) });
  }
}
w(Mc, "defaultProps", { closeBtn: !0 });
var Bn, Ke, zn;
class Kh extends U {
  constructor() {
    super(...arguments);
    x(this, Bn, cn());
    x(this, Ke, void 0);
    w(this, "state", {});
    x(this, zn, () => {
      var i, o;
      const t = (o = (i = m(this, Bn).current) == null ? void 0 : i.contentWindow) == null ? void 0 : o.document;
      if (!t)
        return;
      let s = m(this, Ke);
      s == null || s.disconnect(), s = new ResizeObserver(() => {
        const r = t.body, l = t.documentElement, a = Math.ceil(Math.max(r.scrollHeight, r.offsetHeight, l.offsetHeight));
        this.setState({ height: a });
      }), s.observe(t.body), s.observe(t.documentElement), R(this, Ke, s);
    });
  }
  componentDidMount() {
    m(this, zn).call(this);
  }
  componentWillUnmount() {
    var t;
    (t = m(this, Ke)) == null || t.disconnect();
  }
  render() {
    const { url: t } = this.props;
    return /* @__PURE__ */ b(
      "iframe",
      {
        className: "modal-iframe",
        style: this.state,
        src: t,
        ref: m(this, Bn),
        onLoad: m(this, zn)
      }
    );
  }
}
Bn = new WeakMap(), Ke = new WeakMap(), zn = new WeakMap();
function Yh(e, n) {
  const { custom: t, title: s, content: i } = n;
  return {
    body: i,
    title: s,
    ...typeof t == "function" ? t() : t
  };
}
async function Xh(e, n) {
  const { dataType: t = "html", url: s, request: i, custom: o, title: r, replace: l = !0 } = n, h = await (await fetch(s, i)).text();
  if (t !== "html")
    try {
      const c = JSON.parse(h);
      return {
        title: r,
        ...o,
        ...c
      };
    } catch {
    }
  return n.replace !== !1 && t === "html" ? [h] : {
    title: r,
    ...o,
    body: t === "html" ? /* @__PURE__ */ b("div", { className: "modal-body", dangerouslySetInnerHTML: { __html: h } }) : h
  };
}
async function Jh(e, n) {
  const { url: t, custom: s, title: i } = n;
  return {
    title: i,
    ...s,
    body: /* @__PURE__ */ b(Kh, { url: t })
  };
}
const Qh = {
  custom: Yh,
  ajax: Xh,
  iframe: Jh
};
var Un, Vn, xt, Ye, $s, Qs, Oc, qn, xo;
const kn = class extends nt {
  constructor() {
    super(...arguments);
    x(this, Ye);
    x(this, Qs);
    x(this, qn);
    x(this, Un, void 0);
    x(this, Vn, void 0);
    x(this, xt, void 0);
  }
  get id() {
    return m(this, Vn);
  }
  get loading() {
    return this.modalElement.classList.contains(kn.LOADING_CLASS);
  }
  get modalElement() {
    let t = m(this, Un);
    if (!t) {
      const { id: s } = this;
      t = this.element.querySelector(`#${s}`), t || (t = document.createElement("div"), Ws(t, {
        id: s,
        style: this.options.style
      }), Lc(t, ["modal modal-async", this.options.className]), this.element.appendChild(t)), R(this, Un, t);
    }
    return t;
  }
  afterInit() {
    super.afterInit(), R(this, Vn, this.options.id || `modal-${us()}`);
  }
  show(t) {
    return super.show(t) ? (this.buildDialog(), !0) : !1;
  }
  render(t) {
    super.render(t), this.buildDialog();
  }
  async buildDialog() {
    if (this.loading)
      return !1;
    m(this, xt) && clearTimeout(m(this, xt));
    const { modalElement: t, options: s } = this, { type: i, loadTimeout: o } = s, r = Qh[i];
    if (!r)
      return console.warn(`Modal: Cannot build modal with type "${i}"`), !1;
    t.classList.add(kn.LOADING_CLASS), await N(this, Qs, Oc).call(this), o && R(this, xt, window.setTimeout(() => {
      R(this, xt, 0), N(this, qn, xo).call(this, this.options.timeoutTip);
    }, o));
    const l = await r(t, s);
    return l === !1 ? await N(this, qn, xo).call(this, this.options.failedTip) : l && typeof l == "object" && await N(this, Ye, $s).call(this, l), m(this, xt) && (clearTimeout(m(this, xt)), R(this, xt, 0)), t.classList.remove(kn.LOADING_CLASS), !0;
  }
};
let yn = kn;
Un = new WeakMap(), Vn = new WeakMap(), xt = new WeakMap(), Ye = new WeakSet(), $s = function(t) {
  return new Promise((s) => {
    if (Array.isArray(t))
      return this.modalElement.innerHTML = t[0], s();
    const { afterRender: i, ...o } = t;
    t = {
      afterRender: (r) => {
        this.layout(), i == null || i(r), s();
      },
      ...o
    }, cs(
      /* @__PURE__ */ b(Mc, { ...t }),
      this.modalElement
    );
  });
}, Qs = new WeakSet(), Oc = function() {
  const { loadingText: t } = this.options;
  return N(this, Ye, $s).call(this, {
    body: /* @__PURE__ */ b("div", { className: "modal-loading-indicator", children: [
      /* @__PURE__ */ b("span", { className: "spinner" }),
      t ? /* @__PURE__ */ b("span", { className: "modal-loading-text", children: t }) : null
    ] })
  });
}, qn = new WeakSet(), xo = function(t) {
  if (t)
    return N(this, Ye, $s).call(this, {
      body: /* @__PURE__ */ b("div", { className: "modal-load-failed", children: t })
    });
}, w(yn, "LOADING_CLASS", "loading"), w(yn, "DEFAULT", {
  ...nt.DEFAULT,
  loadTimeout: 1e4
});
var ee, Zs, Pc, ti, Dc, ei, Hc;
class Cn extends kt {
  constructor() {
    super(...arguments);
    x(this, Zs);
    x(this, ti);
    x(this, ei);
    x(this, ee, void 0);
  }
  get modal() {
    return m(this, ee);
  }
  get container() {
    const { container: t } = this.options;
    return typeof t == "string" ? document.querySelector(t) : t instanceof HTMLElement ? t : document.body;
  }
  show() {
    return N(this, ti, Dc).call(this).show();
  }
  hide() {
    var t;
    (t = m(this, ee)) == null || t.hide();
  }
}
ee = new WeakMap(), Zs = new WeakSet(), Pc = function() {
  const {
    container: t,
    ...s
  } = this.options, i = s, o = this.element.getAttribute("href") || "";
  return i.type || (i.target || o[0] === "#" ? i.type = "static" : i.type = i.type || (i.url || o ? "ajax" : "custom")), !i.url && (i.type === "iframe" || i.type === "ajax") && o[0] !== "#" && (i.url = o), i;
}, ti = new WeakSet(), Dc = function() {
  const t = N(this, Zs, Pc).call(this);
  let s = m(this, ee);
  return s ? s.setOptions(t) : t.type === "static" ? (s = new nt(N(this, ei, Hc).call(this), t), R(this, ee, s)) : (s = new yn(this.container, t), R(this, ee, s)), s;
}, ei = new WeakSet(), Hc = function() {
  let t = this.options.target;
  if (!t) {
    const { element: s } = this;
    if (s.tagName === "A") {
      const i = s.getAttribute("href");
      i != null && i.startsWith("#") && (t = i);
    }
  }
  return this.container.querySelector(t || ".modal");
}, w(Cn, "NAME", "ModalTrigger"), w(Cn, "EVENTS", !0), w(Cn, "TOGGLE_SELECTOR", '[data-toggle="modal"]');
window.addEventListener("click", (e) => {
  var s;
  const n = e.target, t = (s = n.closest) == null ? void 0 : s.call(n, Cn.TOGGLE_SELECTOR);
  if (t) {
    const i = Cn.ensure(t);
    i && i.show();
  }
});
var ao;
let Zh = (ao = class extends ji {
  beforeRender() {
    const n = super.beforeRender();
    return n.className = M(n.className, n.type ? `nav-${n.type}` : "", {
      "nav-stacked": n.stacked
    }), n;
  }
}, w(ao, "NAME", "nav"), ao);
class Fr extends J {
}
w(Fr, "NAME", "nav"), w(Fr, "Component", Zh);
function Mn(e, n) {
  const t = e.pageTotal || Math.ceil(e.recTotal / e.recPerPage);
  return typeof n == "string" && (n === "first" ? n = 1 : n === "last" ? n = t : n === "prev" ? n = e.page - 1 : n === "next" ? n = e.page + 1 : n === "current" ? n = e.page : n = Number.parseInt(n, 10)), n = n !== void 0 ? Math.max(1, Math.min(n < 0 ? t + n : n, t)) : e.page, {
    ...e,
    pageTotal: t,
    page: n
  };
}
function tf({
  key: e,
  type: n,
  btnType: t,
  page: s,
  format: i,
  pagerInfo: o,
  linkCreator: r,
  ...l
}) {
  const a = Mn(o, s);
  return l.text === void 0 && !l.icon && i && (l.text = typeof i == "function" ? i(a) : tt(i, a)), l.url === void 0 && r && (l.url = typeof r == "function" ? r(a) : tt(r, a)), l.disabled === void 0 && (l.disabled = s !== void 0 && a.page === o.page), /* @__PURE__ */ b(Tt, { type: t, ...l });
}
const Pt = 24 * 60 * 60 * 1e3, at = (e) => e ? (e instanceof Date || (typeof e == "string" && (e = e.trim(), /^\d+$/.test(e) && (e = Number.parseInt(e, 10))), typeof e == "number" && e < 1e10 && (e *= 1e3), e = new Date(e)), e) : /* @__PURE__ */ new Date(), hs = (e, n = /* @__PURE__ */ new Date()) => (e = at(e), n = at(n), e.getFullYear() === n.getFullYear() && e.getMonth() === n.getMonth() && e.getDate() === n.getDate()), Br = (e, n = /* @__PURE__ */ new Date()) => at(e).getFullYear() === at(n).getFullYear(), ef = (e, n = /* @__PURE__ */ new Date()) => (e = at(e), n = at(n), e.getFullYear() === n.getFullYear() && e.getMonth() === n.getMonth()), Td = (e, n = /* @__PURE__ */ new Date()) => {
  e = at(e), n = at(n);
  const t = 1e3 * 60 * 60 * 24, s = Math.floor(e.getTime() / t), i = Math.floor(n.getTime() / t);
  return Math.floor((s + 4) / 7) === Math.floor((i + 4) / 7);
}, Ad = (e, n) => hs(at(n), e), Nd = (e, n) => hs(at(n).getTime() - Pt, e), Ld = (e, n) => hs(at(n).getTime() + Pt, e), Md = (e, n) => hs(at(n).getTime() - 2 * Pt, e), So = (e, n = "yyyy-MM-dd hh:mm") => {
  e = at(e);
  const t = {
    "M+": e.getMonth() + 1,
    "d+": e.getDate(),
    "h+": e.getHours(),
    "H+": e.getHours() % 12,
    "m+": e.getMinutes(),
    "s+": e.getSeconds(),
    "S+": e.getMilliseconds()
  };
  return /(y+)/i.test(n) && (n = n.replace(RegExp.$1, `${e.getFullYear()}`.substring(4 - RegExp.$1.length))), Object.keys(t).forEach((s) => {
    if (new RegExp(`(${s})`).test(n)) {
      const i = `${t[s]}`;
      n = n.replace(RegExp.$1, RegExp.$1.length === 1 ? i : `00${i}`.substring(i.length));
    }
  }), n;
}, Od = (e, n, t) => {
  const s = {
    full: "yyyy-M-d",
    month: "M-d",
    day: "d",
    str: "{0} ~ {1}",
    ...t
  }, i = So(e, Br(e) ? s.month : s.full);
  if (hs(e, n))
    return i;
  const o = So(n, Br(e, n) ? ef(e, n) ? s.day : s.month : s.full);
  return s.str.replace("{0}", i).replace("{1}", o);
}, Pd = (e) => {
  const n = (/* @__PURE__ */ new Date()).getTime();
  switch (e) {
    case "oneWeek":
      return n - Pt * 7;
    case "oneMonth":
      return n - Pt * 31;
    case "threeMonth":
      return n - Pt * 31 * 3;
    case "halfYear":
      return n - Pt * 183;
    case "oneYear":
      return n - Pt * 365;
    case "twoYear":
      return n - 2 * (Pt * 365);
    default:
      return 0;
  }
}, zr = (e, n, t = !0, s = Date.now()) => {
  switch (n) {
    case "year":
      return e *= 365, zr(e, "day", t, s);
    case "quarter":
      e *= 3;
      break;
    case "month":
      return e *= 30, zr(e, "day", t, s);
    case "week":
      e *= 7;
      break;
    case "day":
      e *= 24;
      break;
    case "hour":
      e *= 60;
      break;
    case "minute":
      e *= 6e4;
      break;
    default:
      e = 0;
  }
  return t ? s + e : s - e;
};
function nf({
  key: e,
  type: n,
  page: t,
  text: s = "",
  pagerInfo: i,
  children: o,
  ...r
}) {
  const l = Mn(i, t);
  return s = typeof s == "function" ? s(l) : tt(s, l), /* @__PURE__ */ b(Dl, { ...r, children: [
    o,
    s
  ] });
}
function sf({
  key: e,
  type: n,
  btnType: t,
  count: s = 12,
  pagerInfo: i,
  onClick: o,
  linkCreator: r,
  ...l
}) {
  if (!i.pageTotal)
    return;
  const a = { ...l, square: !0 }, h = () => (a.text = "", a.icon = "icon-ellipsis-h", a.disabled = !0, /* @__PURE__ */ b(Tt, { type: t, ...a })), c = (d, f) => {
    const p = [];
    for (let g = d; g <= f; g++) {
      a.text = g, delete a.icon, a.disabled = !1;
      const y = Mn(i, g);
      r && (a.url = typeof r == "function" ? r(y) : tt(r, y)), p.push(/* @__PURE__ */ b(Tt, { type: t, ...a, onClick: o }));
    }
    return p;
  };
  let u = [];
  return u = [...c(1, 1)], i.pageTotal <= 1 || (i.pageTotal <= s ? u = [...u, ...c(2, i.pageTotal)] : i.page < s - 2 ? u = [...u, ...c(2, s - 2), h(), ...c(i.pageTotal, i.pageTotal)] : i.page > i.pageTotal - s + 3 ? u = [...u, h(), ...c(i.pageTotal - s + 3, i.pageTotal)] : u = [...u, h(), ...c(i.page - Math.ceil((s - 4) / 2), i.page + Math.floor((s - 4) / 2)), h(), ...c(i.pageTotal, i.pageTotal)]), u;
}
function of({
  type: e,
  pagerInfo: n,
  linkCreator: t,
  items: s = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1e3, 2e3],
  dropdown: i = {},
  ...o
}) {
  var l;
  i.items = i.items ?? s.map((a) => {
    const h = { ...n, recPerPage: a };
    return {
      text: `${a}`,
      url: typeof t == "function" ? t(h) : tt(t, h)
    };
  });
  const { text: r = "" } = o;
  return o.text = typeof r == "function" ? r(n) : tt(r, n), i.menu = { ...i.menu, className: M((l = i.menu) == null ? void 0 : l.className, "pager-size-menu") }, /* @__PURE__ */ b(wc, { type: "dropdown", dropdown: i, ...o });
}
function rf({
  key: e,
  page: n,
  type: t,
  btnType: s,
  pagerInfo: i,
  size: o,
  onClick: r,
  onChange: l,
  linkCreator: a,
  ...h
}) {
  const c = { ...h };
  let u;
  const d = (g) => {
    var y;
    u = Number((y = g.target) == null ? void 0 : y.value) || 1, u = u > i.pageTotal ? i.pageTotal : u;
  }, f = (g) => {
    if (!(g != null && g.target))
      return;
    u = u <= i.pageTotal ? u : i.pageTotal;
    const y = Mn(i, u);
    l && !l({ info: y, event: g }) || (g.target.href = c.url = typeof a == "function" ? a(y) : tt(a, y));
  }, p = Mn(i, n || 0);
  return c.url = typeof a == "function" ? a(p) : tt(a, p), /* @__PURE__ */ b("div", { className: M("input-group", "pager-goto-group", o ? `size-${o}` : ""), children: [
    /* @__PURE__ */ b("input", { type: "number", class: "form-control", max: i.pageTotal, min: "1", onInput: d }),
    /* @__PURE__ */ b(Tt, { type: s, ...c, onClick: f })
  ] });
}
var dn;
let Ic = (dn = class extends ae {
  get pagerInfo() {
    const { page: n = 1, recTotal: t = 0, recPerPage: s = 10 } = this.props;
    return { page: n, recTotal: t, recPerPage: s, pageTotal: s ? Math.ceil(t / s) : 0 };
  }
  isBtnItem(n) {
    return n === "link" || n === "nav" || n === "size-menu" || n === "goto" || super.isBtnItem(n);
  }
  getItemRenderProps(n, t, s) {
    const i = super.getItemRenderProps(n, t, s), o = t.type || "item";
    return o === "info" ? Object.assign(i, { pagerInfo: this.pagerInfo }) : (o === "link" || o === "size-menu" || o === "nav" || o === "goto") && Object.assign(i, { pagerInfo: this.pagerInfo, linkCreator: n.linkCreator }), i;
  }
}, w(dn, "NAME", "pager"), w(dn, "defaultProps", {
  gap: 1,
  btnProps: {
    btnType: "ghost",
    size: "sm"
  }
}), w(dn, "ItemComponents", {
  ...ae.ItemComponents,
  link: tf,
  info: nf,
  nav: sf,
  "size-menu": of,
  goto: rf
}), dn);
class Ur extends J {
}
w(Ur, "NAME", "pager"), w(Ur, "Component", Ic);
var ni;
class lf extends U {
  constructor() {
    super(...arguments);
    x(this, ni, (t) => {
      var r;
      const { onDeselect: s, selections: i } = this.props, o = (r = t.target.closest(".picker-deselect-btn")) == null ? void 0 : r.dataset.idx;
      o && s && (i != null && i.length) && (t.stopPropagation(), s([i[+o]], t));
    });
  }
  render() {
    const {
      className: t,
      style: s,
      disabled: i,
      placeholder: o,
      focused: r,
      selections: l = [],
      onClick: a,
      children: h
    } = this.props;
    let c;
    return l.length ? c = /* @__PURE__ */ b("div", { className: "picker-multi-selections", children: l.map((u, d) => /* @__PURE__ */ b("div", { className: "picker-multi-selection", children: [
      u.text ?? u.value,
      /* @__PURE__ */ b("div", { className: "picker-deselect-btn btn", onClick: m(this, ni), "data-idx": d, children: /* @__PURE__ */ b("span", { className: "close" }) })
    ] })) }) : c = /* @__PURE__ */ b("span", { className: "picker-select-placeholder", children: o }), /* @__PURE__ */ b(
      "div",
      {
        className: M("picker-select picker-select-multi form-control", t, { disabled: i, focused: r }),
        style: s,
        onClick: a,
        children: [
          c,
          h,
          /* @__PURE__ */ b("span", { class: "caret" })
        ]
      }
    );
  }
}
ni = new WeakMap();
var si;
class cf extends U {
  constructor() {
    super(...arguments);
    x(this, si, (t) => {
      const { onDeselect: s, selections: i } = this.props;
      s && (i != null && i.length) && (t.stopPropagation(), s(i, t));
    });
  }
  render() {
    const {
      className: t,
      style: s,
      disabled: i,
      placeholder: o,
      focused: r,
      selections: l = [],
      onDeselect: a,
      onClick: h,
      children: c
    } = this.props, [u] = l, d = u ? /* @__PURE__ */ b("span", { className: "picker-single-selection", children: u.text ?? u.value }) : /* @__PURE__ */ b("span", { className: "picker-select-placeholder", children: o }), f = u && a ? /* @__PURE__ */ b("button", { type: "button", className: "btn picker-deselect-btn", onClick: m(this, si), children: /* @__PURE__ */ b("span", { className: "close" }) }) : null;
    return /* @__PURE__ */ b(
      "div",
      {
        className: M("picker-select picker-select-single form-control", t, { disabled: i, focused: r }),
        style: s,
        onClick: h,
        children: [
          d,
          c,
          f,
          /* @__PURE__ */ b("span", { class: "caret" })
        ]
      }
    );
  }
}
si = new WeakMap();
var ii, jc, Gn, oi, Kn, ri;
class af extends U {
  constructor() {
    super(...arguments);
    x(this, ii);
    w(this, "state", { keys: "", shown: !1 });
    x(this, Gn, (t) => {
      var s;
      (s = t.target) != null && s.closest(`#picker-menu-${this.props.id}`) || this.hide();
    });
    x(this, oi, ({ item: t }) => {
      const s = this.props.items.find((i) => i.value === t.key);
      s && this.props.onSelectItem(s);
    });
    x(this, Kn, (t) => {
      this.setState({ keys: t.target.value });
    });
    x(this, ri, () => {
      this.setState({ keys: "" });
    });
  }
  componentDidMount() {
    document.addEventListener("click", m(this, Gn)), this.show();
  }
  componentWillUnmount() {
    document.removeEventListener("click", m(this, Gn));
  }
  show() {
    this.state.shown || this.setState({ shown: !0 });
  }
  hide() {
    this.state.shown && this.setState({ shown: !1 }, () => {
      window.setTimeout(() => {
        var t, s;
        (s = (t = this.props).onRequestHide) == null || s.call(t);
      }, 200);
    });
  }
  render() {
    const {
      id: t,
      search: s,
      className: i,
      style: o = {},
      maxHeight: r,
      maxWidth: l,
      width: a,
      menu: h,
      searchHint: c
    } = this.props, { shown: u, keys: d } = this.state, f = d.trim().length;
    return /* @__PURE__ */ b("div", { className: M("picker-menu", i, { shown: u, "has-search": f }), id: `picker-menu-${t}`, style: { maxHeight: r, maxWidth: l, width: a, ...o }, children: [
      s ? /* @__PURE__ */ b("div", { className: "picker-menu-search", children: [
        /* @__PURE__ */ b("input", { className: "form-control picker-menu-search-input", type: "text", placeholder: c, value: d, onChange: m(this, Kn), onInput: m(this, Kn) }),
        f ? /* @__PURE__ */ b("button", { type: "button", className: "btn picker-menu-search-clear", onClick: m(this, ri), children: /* @__PURE__ */ b("span", { className: "close" }) }) : /* @__PURE__ */ b("span", { className: "magnifier" })
      ] }) : null,
      /* @__PURE__ */ b(oe, { className: "picker-menu-list", items: N(this, ii, jc).call(this), onClickItem: m(this, oi), ...h })
    ] });
  }
}
ii = new WeakSet(), jc = function() {
  const { selections: t, items: s } = this.props, i = new Set(t), o = this.state.keys.toLowerCase().split(" ").filter((r) => r.length);
  return s.reduce((r, l) => {
    const {
      value: a,
      keys: h,
      text: c,
      ...u
    } = l;
    if (!o.length || o.every((d) => a.toLowerCase().includes(d) || (h == null ? void 0 : h.toLowerCase().includes(d)) || typeof c == "string" && c.toLowerCase().includes(d))) {
      let d = c ?? a;
      typeof d == "string" && o.length && (d = /* @__PURE__ */ b("span", { dangerouslySetInnerHTML: { __html: o.reduce((f, p) => f.replace(p, `<span class="picker-menu-item-match">${p}</span>`), d) } })), r.push({
        key: a,
        active: i.has(a),
        text: d,
        ...u
      });
    }
    return r;
  }, []);
}, Gn = new WeakMap(), oi = new WeakMap(), Kn = new WeakMap(), ri = new WeakMap();
function Vr(e) {
  const n = /* @__PURE__ */ new Set();
  return e.reduce((t, s) => (n.has(s) || (n.add(s), t.push(s)), t), []);
}
var uo, Yn, Xn, Jn, Xe, Rs, Qn, Eo, li, Wc, ci, Fc, ai, ui, hi, fi, di, Bc;
let uf = (uo = class extends U {
  constructor(t) {
    super(t);
    x(this, Xe);
    x(this, Qn);
    x(this, li);
    x(this, ci);
    x(this, di);
    x(this, Yn, 0);
    x(this, Xn, us());
    x(this, Jn, cn());
    x(this, ai, (t, s) => {
      const { valueList: i } = this, o = new Set(t.map((l) => l.value)), r = i.filter((l) => !o.has(l));
      this.setState({ value: r.length ? r.join(this.props.valueSplitter ?? ",") : void 0 });
    });
    x(this, ui, (t) => {
      console.log("#handleSelectClick", t), this.setState({ open: !0 });
    });
    x(this, hi, () => {
      this.close();
    });
    x(this, fi, (t) => {
      this.props.multi ? this.toggleValue(t.value) : this.setState({ value: t.value }, () => {
        var s;
        (s = m(this, Jn).current) == null || s.hide();
      });
    });
    this.state = {
      value: N(this, li, Wc).call(this, t.defaultValue) ?? "",
      open: !1,
      loading: !1,
      search: "",
      items: Array.isArray(t.items) ? t.items : []
    };
  }
  get value() {
    return this.state.value;
  }
  get valueList() {
    return N(this, Qn, Eo).call(this, this.state.value);
  }
  componentDidMount() {
    var t;
    (t = this.props.afterRender) == null || t.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var t;
    (t = this.props.afterRender) == null || t.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var t;
    (t = this.props.beforeDestroy) == null || t.call(this);
  }
  async loadItemList() {
    let { items: t } = this.props;
    if (typeof t == "function") {
      const i = ++ir(this, Yn)._;
      if (await N(this, Xe, Rs).call(this, { loading: !0, items: [] }), t = await t(), m(this, Yn) !== i)
        return [];
    }
    const s = {};
    return Array.isArray(t) && this.state.items !== t && (s.items = t), this.state.loading && (s.loading = !1), Object.keys(s).length && await N(this, Xe, Rs).call(this, s), t;
  }
  getItemList() {
    return this.state.items;
  }
  getItemMap() {
    return this.getItemList().reduce((t, s) => (t[s.value] = s, t), {});
  }
  getItemByValue(t) {
    return this.getItemList().find((s) => s.value === t);
  }
  getSelections() {
    const t = this.getItemMap();
    return this.valueList.map((s) => t[s] || { value: s });
  }
  async toggle(t) {
    if (t === void 0)
      t = !this.state.open;
    else if (t === this.state.open)
      return;
    await N(this, Xe, Rs).call(this, { open: t }), t && this.loadItemList();
  }
  open() {
    return this.toggle(!0);
  }
  close() {
    return this.toggle(!1);
  }
  toggleValue(t, s) {
    const { valueList: i } = this, o = i.indexOf(t);
    s !== !!o && (o > -1 ? i.splice(o, 1) : i.push(t), this.setState({ value: i.join(this.props.valueSplitter ?? ",") }));
  }
  render() {
    const {
      className: t,
      style: s,
      children: i,
      multi: o
    } = this.props, r = o ? lf : cf;
    return /* @__PURE__ */ b("div", { className: M("picker", t), style: s, id: `picker-${m(this, Xn)}`, children: [
      /* @__PURE__ */ b(r, { ...N(this, ci, Fc).call(this) }),
      i,
      this.state.open ? /* @__PURE__ */ b(af, { ...N(this, di, Bc).call(this), ref: m(this, Jn) }) : null
    ] });
  }
}, Yn = new WeakMap(), Xn = new WeakMap(), Jn = new WeakMap(), Xe = new WeakSet(), Rs = function(t) {
  return new Promise((s) => {
    this.setState(t, s);
  });
}, Qn = new WeakSet(), Eo = function(t) {
  return typeof t == "string" ? Vr(t.split(this.props.valueSplitter ?? ",")) : Array.isArray(t) ? Vr(t) : [];
}, li = new WeakSet(), Wc = function(t) {
  const s = N(this, Qn, Eo).call(this, t);
  return s.length ? s.join(this.props.valueSplitter ?? ",") : void 0;
}, ci = new WeakSet(), Fc = function() {
  const { placeholder: t, disabled: s } = this.props, { open: i } = this.state;
  return {
    focused: i,
    placeholder: t,
    disabled: s,
    selections: this.getSelections(),
    onClick: m(this, ui),
    onDeselect: m(this, ai)
  };
}, ai = new WeakMap(), ui = new WeakMap(), hi = new WeakMap(), fi = new WeakMap(), di = new WeakSet(), Bc = function() {
  const { search: t, menuClass: s, menuWidth: i, menuStyle: o, menuMaxHeight: r, menuMaxWidth: l } = this.props, { items: a } = this.state;
  return {
    id: m(this, Xn),
    items: a,
    selections: this.valueList,
    search: t === !0 || typeof t == "number" && t <= a.length,
    style: o,
    className: s,
    width: i,
    maxHeight: r,
    maxWidth: l,
    onRequestHide: m(this, hi),
    onSelectItem: m(this, fi)
  };
}, w(uo, "defaultProps", {
  container: "body",
  valueSplitter: ",",
  search: !0,
  menuWidth: "auto",
  menuMaxHeight: 400
}), uo);
class qr extends J {
}
w(qr, "NAME", "picker"), w(qr, "Component", uf);
class Gr extends J {
}
w(Gr, "NAME", "toolbar"), w(Gr, "Component", ae);
function fs(e) {
  return e.split("-")[1];
}
function Go(e) {
  return e === "y" ? "height" : "width";
}
function Oe(e) {
  return e.split("-")[0];
}
function Gi(e) {
  return ["top", "bottom"].includes(Oe(e)) ? "x" : "y";
}
function Kr(e, n, t) {
  let { reference: s, floating: i } = e;
  const o = s.x + s.width / 2 - i.width / 2, r = s.y + s.height / 2 - i.height / 2, l = Gi(n), a = Go(l), h = s[a] / 2 - i[a] / 2, c = l === "x";
  let u;
  switch (Oe(n)) {
    case "top":
      u = { x: o, y: s.y - i.height };
      break;
    case "bottom":
      u = { x: o, y: s.y + s.height };
      break;
    case "right":
      u = { x: s.x + s.width, y: r };
      break;
    case "left":
      u = { x: s.x - i.width, y: r };
      break;
    default:
      u = { x: s.x, y: s.y };
  }
  switch (fs(n)) {
    case "start":
      u[l] -= h * (t && c ? -1 : 1);
      break;
    case "end":
      u[l] += h * (t && c ? -1 : 1);
  }
  return u;
}
const hf = async (e, n, t) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: o = [], platform: r } = t, l = o.filter(Boolean), a = await (r.isRTL == null ? void 0 : r.isRTL(n));
  let h = await r.getElementRects({ reference: e, floating: n, strategy: i }), { x: c, y: u } = Kr(h, s, a), d = s, f = {}, p = 0;
  for (let g = 0; g < l.length; g++) {
    const { name: y, fn: _ } = l[g], { x: v, y: S, data: $, reset: T } = await _({ x: c, y: u, initialPlacement: s, placement: d, strategy: i, middlewareData: f, rects: h, platform: r, elements: { reference: e, floating: n } });
    c = v ?? c, u = S ?? u, f = { ...f, [y]: { ...f[y], ...$ } }, T && p <= 50 && (p++, typeof T == "object" && (T.placement && (d = T.placement), T.rects && (h = T.rects === !0 ? await r.getElementRects({ reference: e, floating: n, strategy: i }) : T.rects), { x: c, y: u } = Kr(h, d, a)), g = -1);
  }
  return { x: c, y: u, placement: d, strategy: i, middlewareData: f };
};
function zc(e) {
  return typeof e != "number" ? function(n) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...n };
  }(e) : { top: e, right: e, bottom: e, left: e };
}
function Fs(e) {
  return { ...e, top: e.y, left: e.x, right: e.x + e.width, bottom: e.y + e.height };
}
async function ff(e, n) {
  var t;
  n === void 0 && (n = {});
  const { x: s, y: i, platform: o, rects: r, elements: l, strategy: a } = e, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: u = "floating", altBoundary: d = !1, padding: f = 0 } = n, p = zc(f), g = l[d ? u === "floating" ? "reference" : "floating" : u], y = Fs(await o.getClippingRect({ element: (t = await (o.isElement == null ? void 0 : o.isElement(g))) == null || t ? g : g.contextElement || await (o.getDocumentElement == null ? void 0 : o.getDocumentElement(l.floating)), boundary: h, rootBoundary: c, strategy: a })), _ = u === "floating" ? { ...r.floating, x: s, y: i } : r.reference, v = await (o.getOffsetParent == null ? void 0 : o.getOffsetParent(l.floating)), S = await (o.isElement == null ? void 0 : o.isElement(v)) && await (o.getScale == null ? void 0 : o.getScale(v)) || { x: 1, y: 1 }, $ = Fs(o.convertOffsetParentRelativeRectToViewportRelativeRect ? await o.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: _, offsetParent: v, strategy: a }) : _);
  return { top: (y.top - $.top + p.top) / S.y, bottom: ($.bottom - y.bottom + p.bottom) / S.y, left: (y.left - $.left + p.left) / S.x, right: ($.right - y.right + p.right) / S.x };
}
const df = Math.min, pf = Math.max;
function mf(e, n, t) {
  return pf(e, df(n, t));
}
const gf = (e) => ({ name: "arrow", options: e, async fn(n) {
  const { element: t, padding: s = 0 } = e || {}, { x: i, y: o, placement: r, rects: l, platform: a } = n;
  if (t == null)
    return {};
  const h = zc(s), c = { x: i, y: o }, u = Gi(r), d = Go(u), f = await a.getDimensions(t), p = u === "y" ? "top" : "left", g = u === "y" ? "bottom" : "right", y = l.reference[d] + l.reference[u] - c[u] - l.floating[d], _ = c[u] - l.reference[u], v = await (a.getOffsetParent == null ? void 0 : a.getOffsetParent(t));
  let S = v ? u === "y" ? v.clientHeight || 0 : v.clientWidth || 0 : 0;
  S === 0 && (S = l.floating[d]);
  const $ = y / 2 - _ / 2, T = h[p], D = S - f[d] - h[g], L = S / 2 - f[d] / 2 + $, O = mf(T, L, D), k = fs(r) != null && L != O && l.reference[d] / 2 - (L < T ? h[p] : h[g]) - f[d] / 2 < 0;
  return { [u]: c[u] - (k ? L < T ? T - L : D - L : 0), data: { [u]: O, centerOffset: L - O } };
} }), yf = ["top", "right", "bottom", "left"];
yf.reduce((e, n) => e.concat(n, n + "-start", n + "-end"), []);
const _f = { left: "right", right: "left", bottom: "top", top: "bottom" };
function Bs(e) {
  return e.replace(/left|right|bottom|top/g, (n) => _f[n]);
}
function bf(e, n, t) {
  t === void 0 && (t = !1);
  const s = fs(e), i = Gi(e), o = Go(i);
  let r = i === "x" ? s === (t ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return n.reference[o] > n.floating[o] && (r = Bs(r)), { main: r, cross: Bs(r) };
}
const wf = { start: "end", end: "start" };
function io(e) {
  return e.replace(/start|end/g, (n) => wf[n]);
}
const vf = function(e) {
  return e === void 0 && (e = {}), { name: "flip", options: e, async fn(n) {
    var t;
    const { placement: s, middlewareData: i, rects: o, initialPlacement: r, platform: l, elements: a } = n, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: u, fallbackStrategy: d = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...g } = e, y = Oe(s), _ = Oe(r) === r, v = await (l.isRTL == null ? void 0 : l.isRTL(a.floating)), S = u || (_ || !p ? [Bs(r)] : function(j) {
      const P = Bs(j);
      return [io(j), P, io(P)];
    }(r));
    u || f === "none" || S.push(...function(j, P, V, F) {
      const G = fs(j);
      let I = function(K, bt, de) {
        const pe = ["left", "right"], me = ["right", "left"], Lt = ["top", "bottom"], Ne = ["bottom", "top"];
        switch (K) {
          case "top":
          case "bottom":
            return de ? bt ? me : pe : bt ? pe : me;
          case "left":
          case "right":
            return bt ? Lt : Ne;
          default:
            return [];
        }
      }(Oe(j), V === "start", F);
      return G && (I = I.map((K) => K + "-" + G), P && (I = I.concat(I.map(io)))), I;
    }(r, p, f, v));
    const $ = [r, ...S], T = await ff(n, g), D = [];
    let L = ((t = i.flip) == null ? void 0 : t.overflows) || [];
    if (h && D.push(T[y]), c) {
      const { main: j, cross: P } = bf(s, o, v);
      D.push(T[j], T[P]);
    }
    if (L = [...L, { placement: s, overflows: D }], !D.every((j) => j <= 0)) {
      var O;
      const j = (((O = i.flip) == null ? void 0 : O.index) || 0) + 1, P = $[j];
      if (P)
        return { data: { index: j, overflows: L }, reset: { placement: P } };
      let V = "bottom";
      switch (d) {
        case "bestFit": {
          var k;
          const F = (k = L.map((G) => [G, G.overflows.filter((I) => I > 0).reduce((I, K) => I + K, 0)]).sort((G, I) => G[1] - I[1])[0]) == null ? void 0 : k[0].placement;
          F && (V = F);
          break;
        }
        case "initialPlacement":
          V = r;
      }
      if (s !== V)
        return { reset: { placement: V } };
    }
    return {};
  } };
}, xf = function(e) {
  return e === void 0 && (e = 0), { name: "offset", options: e, async fn(n) {
    const { x: t, y: s } = n, i = await async function(o, r) {
      const { placement: l, platform: a, elements: h } = o, c = await (a.isRTL == null ? void 0 : a.isRTL(h.floating)), u = Oe(l), d = fs(l), f = Gi(l) === "x", p = ["left", "top"].includes(u) ? -1 : 1, g = c && f ? -1 : 1, y = typeof r == "function" ? r(o) : r;
      let { mainAxis: _, crossAxis: v, alignmentAxis: S } = typeof y == "number" ? { mainAxis: y, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...y };
      return d && typeof S == "number" && (v = d === "end" ? -1 * S : S), f ? { x: v * g, y: _ * p } : { x: _ * p, y: v * g };
    }(n, e);
    return { x: t + i.x, y: s + i.y, data: i };
  } };
};
function pt(e) {
  var n;
  return ((n = e.ownerDocument) == null ? void 0 : n.defaultView) || window;
}
function Rt(e) {
  return pt(e).getComputedStyle(e);
}
function ue(e) {
  return Vc(e) ? (e.nodeName || "").toLowerCase() : "";
}
let _s;
function Uc() {
  if (_s)
    return _s;
  const e = navigator.userAgentData;
  return e && Array.isArray(e.brands) ? (_s = e.brands.map((n) => n.brand + "/" + n.version).join(" "), _s) : navigator.userAgent;
}
function Gt(e) {
  return e instanceof pt(e).HTMLElement;
}
function _t(e) {
  return e instanceof pt(e).Element;
}
function Vc(e) {
  return e instanceof pt(e).Node;
}
function Yr(e) {
  return typeof ShadowRoot > "u" ? !1 : e instanceof pt(e).ShadowRoot || e instanceof ShadowRoot;
}
function Ki(e) {
  const { overflow: n, overflowX: t, overflowY: s, display: i } = Rt(e);
  return /auto|scroll|overlay|hidden|clip/.test(n + s + t) && !["inline", "contents"].includes(i);
}
function Sf(e) {
  return ["table", "td", "th"].includes(ue(e));
}
function Co(e) {
  const n = /firefox/i.test(Uc()), t = Rt(e), s = t.backdropFilter || t.WebkitBackdropFilter;
  return t.transform !== "none" || t.perspective !== "none" || !!s && s !== "none" || n && t.willChange === "filter" || n && !!t.filter && t.filter !== "none" || ["transform", "perspective"].some((i) => t.willChange.includes(i)) || ["paint", "layout", "strict", "content"].some((i) => {
    const o = t.contain;
    return o != null && o.includes(i);
  });
}
function qc() {
  return !/^((?!chrome|android).)*safari/i.test(Uc());
}
function Ko(e) {
  return ["html", "body", "#document"].includes(ue(e));
}
const Xr = Math.min, $n = Math.max, zs = Math.round;
function Gc(e) {
  const n = Rt(e);
  let t = parseFloat(n.width), s = parseFloat(n.height);
  const i = e.offsetWidth, o = e.offsetHeight, r = zs(t) !== i || zs(s) !== o;
  return r && (t = i, s = o), { width: t, height: s, fallback: r };
}
function Kc(e) {
  return _t(e) ? e : e.contextElement;
}
const Yc = { x: 1, y: 1 };
function Pe(e) {
  const n = Kc(e);
  if (!Gt(n))
    return Yc;
  const t = n.getBoundingClientRect(), { width: s, height: i, fallback: o } = Gc(n);
  let r = (o ? zs(t.width) : t.width) / s, l = (o ? zs(t.height) : t.height) / i;
  return r && Number.isFinite(r) || (r = 1), l && Number.isFinite(l) || (l = 1), { x: r, y: l };
}
function ke(e, n, t, s) {
  var i, o;
  n === void 0 && (n = !1), t === void 0 && (t = !1);
  const r = e.getBoundingClientRect(), l = Kc(e);
  let a = Yc;
  n && (s ? _t(s) && (a = Pe(s)) : a = Pe(e));
  const h = l ? pt(l) : window, c = !qc() && t;
  let u = (r.left + (c && ((i = h.visualViewport) == null ? void 0 : i.offsetLeft) || 0)) / a.x, d = (r.top + (c && ((o = h.visualViewport) == null ? void 0 : o.offsetTop) || 0)) / a.y, f = r.width / a.x, p = r.height / a.y;
  if (l) {
    const g = pt(l), y = s && _t(s) ? pt(s) : s;
    let _ = g.frameElement;
    for (; _ && s && y !== g; ) {
      const v = Pe(_), S = _.getBoundingClientRect(), $ = getComputedStyle(_);
      S.x += (_.clientLeft + parseFloat($.paddingLeft)) * v.x, S.y += (_.clientTop + parseFloat($.paddingTop)) * v.y, u *= v.x, d *= v.y, f *= v.x, p *= v.y, u += S.x, d += S.y, _ = pt(_).frameElement;
    }
  }
  return { width: f, height: p, top: d, right: u + f, bottom: d + p, left: u, x: u, y: d };
}
function le(e) {
  return ((Vc(e) ? e.ownerDocument : e.document) || window.document).documentElement;
}
function Yi(e) {
  return _t(e) ? { scrollLeft: e.scrollLeft, scrollTop: e.scrollTop } : { scrollLeft: e.pageXOffset, scrollTop: e.pageYOffset };
}
function Xc(e) {
  return ke(le(e)).left + Yi(e).scrollLeft;
}
function Ef(e, n, t) {
  const s = Gt(n), i = le(n), o = ke(e, !0, t === "fixed", n);
  let r = { scrollLeft: 0, scrollTop: 0 };
  const l = { x: 0, y: 0 };
  if (s || !s && t !== "fixed")
    if ((ue(n) !== "body" || Ki(i)) && (r = Yi(n)), Gt(n)) {
      const a = ke(n, !0);
      l.x = a.x + n.clientLeft, l.y = a.y + n.clientTop;
    } else
      i && (l.x = Xc(i));
  return { x: o.left + r.scrollLeft - l.x, y: o.top + r.scrollTop - l.y, width: o.width, height: o.height };
}
function On(e) {
  if (ue(e) === "html")
    return e;
  const n = e.assignedSlot || e.parentNode || (Yr(e) ? e.host : null) || le(e);
  return Yr(n) ? n.host : n;
}
function Jr(e) {
  return Gt(e) && Rt(e).position !== "fixed" ? e.offsetParent : null;
}
function Qr(e) {
  const n = pt(e);
  let t = Jr(e);
  for (; t && Sf(t) && Rt(t).position === "static"; )
    t = Jr(t);
  return t && (ue(t) === "html" || ue(t) === "body" && Rt(t).position === "static" && !Co(t)) ? n : t || function(s) {
    let i = On(s);
    for (; Gt(i) && !Ko(i); ) {
      if (Co(i))
        return i;
      i = On(i);
    }
    return null;
  }(e) || n;
}
function Jc(e) {
  const n = On(e);
  return Ko(n) ? e.ownerDocument.body : Gt(n) && Ki(n) ? n : Jc(n);
}
function Rn(e, n) {
  var t;
  n === void 0 && (n = []);
  const s = Jc(e), i = s === ((t = e.ownerDocument) == null ? void 0 : t.body), o = pt(s);
  return i ? n.concat(o, o.visualViewport || [], Ki(s) ? s : []) : n.concat(s, Rn(s));
}
function Zr(e, n, t) {
  return n === "viewport" ? Fs(function(s, i) {
    const o = pt(s), r = le(s), l = o.visualViewport;
    let a = r.clientWidth, h = r.clientHeight, c = 0, u = 0;
    if (l) {
      a = l.width, h = l.height;
      const d = qc();
      (d || !d && i === "fixed") && (c = l.offsetLeft, u = l.offsetTop);
    }
    return { width: a, height: h, x: c, y: u };
  }(e, t)) : _t(n) ? function(s, i) {
    const o = ke(s, !0, i === "fixed"), r = o.top + s.clientTop, l = o.left + s.clientLeft, a = Gt(s) ? Pe(s) : { x: 1, y: 1 }, h = s.clientWidth * a.x, c = s.clientHeight * a.y, u = l * a.x, d = r * a.y;
    return { top: d, left: u, right: u + h, bottom: d + c, x: u, y: d, width: h, height: c };
  }(n, t) : Fs(function(s) {
    var i;
    const o = le(s), r = Yi(s), l = (i = s.ownerDocument) == null ? void 0 : i.body, a = $n(o.scrollWidth, o.clientWidth, l ? l.scrollWidth : 0, l ? l.clientWidth : 0), h = $n(o.scrollHeight, o.clientHeight, l ? l.scrollHeight : 0, l ? l.clientHeight : 0);
    let c = -r.scrollLeft + Xc(s);
    const u = -r.scrollTop;
    return Rt(l || o).direction === "rtl" && (c += $n(o.clientWidth, l ? l.clientWidth : 0) - a), { width: a, height: h, x: c, y: u };
  }(le(e)));
}
const Cf = { getClippingRect: function(e) {
  let { element: n, boundary: t, rootBoundary: s, strategy: i } = e;
  const o = t === "clippingAncestors" ? function(h, c) {
    const u = c.get(h);
    if (u)
      return u;
    let d = Rn(h).filter((y) => _t(y) && ue(y) !== "body"), f = null;
    const p = Rt(h).position === "fixed";
    let g = p ? On(h) : h;
    for (; _t(g) && !Ko(g); ) {
      const y = Rt(g), _ = Co(g);
      (p ? _ || f : _ || y.position !== "static" || !f || !["absolute", "fixed"].includes(f.position)) ? f = y : d = d.filter((v) => v !== g), g = On(g);
    }
    return c.set(h, d), d;
  }(n, this._c) : [].concat(t), r = [...o, s], l = r[0], a = r.reduce((h, c) => {
    const u = Zr(n, c, i);
    return h.top = $n(u.top, h.top), h.right = Xr(u.right, h.right), h.bottom = Xr(u.bottom, h.bottom), h.left = $n(u.left, h.left), h;
  }, Zr(n, l, i));
  return { width: a.right - a.left, height: a.bottom - a.top, x: a.left, y: a.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(e) {
  let { rect: n, offsetParent: t, strategy: s } = e;
  const i = Gt(t), o = le(t);
  if (t === o)
    return n;
  let r = { scrollLeft: 0, scrollTop: 0 }, l = { x: 1, y: 1 };
  const a = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((ue(t) !== "body" || Ki(o)) && (r = Yi(t)), Gt(t))) {
    const h = ke(t);
    l = Pe(t), a.x = h.x + t.clientLeft, a.y = h.y + t.clientTop;
  }
  return { width: n.width * l.x, height: n.height * l.y, x: n.x * l.x - r.scrollLeft * l.x + a.x, y: n.y * l.y - r.scrollTop * l.y + a.y };
}, isElement: _t, getDimensions: function(e) {
  return Gc(e);
}, getOffsetParent: Qr, getDocumentElement: le, getScale: Pe, async getElementRects(e) {
  let { reference: n, floating: t, strategy: s } = e;
  const i = this.getOffsetParent || Qr, o = this.getDimensions;
  return { reference: Ef(n, await i(t), s), floating: { x: 0, y: 0, ...await o(t) } };
}, getClientRects: (e) => Array.from(e.getClientRects()), isRTL: (e) => Rt(e).direction === "rtl" };
function $f(e, n, t, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: o = !0, elementResize: r = !0, animationFrame: l = !1 } = s, a = i && !l, h = a || o ? [..._t(e) ? Rn(e) : e.contextElement ? Rn(e.contextElement) : [], ...Rn(n)] : [];
  h.forEach((f) => {
    a && f.addEventListener("scroll", t, { passive: !0 }), o && f.addEventListener("resize", t);
  });
  let c, u = null;
  if (r) {
    let f = !0;
    u = new ResizeObserver(() => {
      f || t(), f = !1;
    }), _t(e) && !l && u.observe(e), _t(e) || !e.contextElement || l || u.observe(e.contextElement), u.observe(n);
  }
  let d = l ? ke(e) : null;
  return l && function f() {
    const p = ke(e);
    !d || p.x === d.x && p.y === d.y && p.width === d.width && p.height === d.height || t(), d = p, c = requestAnimationFrame(f);
  }(), t(), () => {
    var f;
    h.forEach((p) => {
      a && p.removeEventListener("scroll", t), o && p.removeEventListener("resize", t);
    }), (f = u) == null || f.disconnect(), u = null, l && cancelAnimationFrame(c);
  };
}
const Rf = (e, n, t) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: Cf, ...t }, o = { ...i.platform, _c: s };
  return hf(e, n, { ...i, platform: o });
};
var Je, Qe, Ze, xe, et, pi, Zn, ts, $o, mi, Qc, gi, Zc, yi, ta, _i, ea, bi, na, wi, sa, vi, ia, tn, xi, oa;
const _e = class extends kt {
  constructor() {
    super(...arguments);
    x(this, ts);
    x(this, mi);
    x(this, gi);
    x(this, yi);
    x(this, _i);
    x(this, bi);
    x(this, wi);
    x(this, vi);
    x(this, xi);
    x(this, Je, !1);
    x(this, Qe, void 0);
    x(this, Ze, 0);
    x(this, xe, void 0);
    x(this, et, void 0);
    x(this, pi, void 0);
    x(this, Zn, void 0);
    w(this, "hideLater", () => {
      m(this, tn).call(this), R(this, Ze, window.setTimeout(this.hide.bind(this), 100));
    });
    x(this, tn, () => {
      clearTimeout(m(this, Ze)), R(this, Ze, 0);
    });
  }
  get isShown() {
    var t;
    return (t = m(this, xe)) == null ? void 0 : t.classList.contains(_e.CLASS_SHOW);
  }
  get tooltip() {
    return m(this, xe) || N(this, gi, Zc).call(this);
  }
  get trigger() {
    return m(this, pi) || this.element;
  }
  get isHover() {
    return this.options.trigger === "hover";
  }
  get elementShowClass() {
    return `with-${_e.NAME}-show`;
  }
  get isDynamic() {
    return this.options.title;
  }
  init() {
    const { element: t } = this;
    t !== document.body && !t.hasAttribute("data-toggle") && t.setAttribute("data-toggle", "tooltip");
  }
  show(t) {
    return this.setOptions(t), !m(this, Je) && this.isHover && N(this, xi, oa).call(this), this.options.animation && this.tooltip.classList.add("fade"), this.element.classList.add(this.elementShowClass), this.tooltip.classList.add(_e.CLASS_SHOW), N(this, wi, sa).call(this), !0;
  }
  hide() {
    var t, s;
    return (t = m(this, Zn)) == null || t.call(this), this.element.classList.remove(this.elementShowClass), (s = m(this, xe)) == null || s.classList.remove(_e.CLASS_SHOW), !0;
  }
  toggle(t) {
    return this.isShown ? this.hide() : this.show(t);
  }
  destroy() {
    m(this, Je) && (this.element.removeEventListener("mouseleave", this.hideLater), this.tooltip.removeEventListener("mouseenter", m(this, tn)), this.tooltip.removeEventListener("mouseleave", this.hideLater)), super.destroy();
  }
  static clear(t) {
    t instanceof Event && (t = { event: t });
    const { exclude: s } = t || {}, i = this.getAll().entries(), o = new Set(s || []);
    for (const [r, l] of i)
      o.has(r) || l.hide();
  }
};
let ht = _e;
Je = new WeakMap(), Qe = new WeakMap(), Ze = new WeakMap(), xe = new WeakMap(), et = new WeakMap(), pi = new WeakMap(), Zn = new WeakMap(), ts = new WeakSet(), $o = function() {
  const { arrow: t } = this.options;
  return typeof t == "number" ? t : 8;
}, mi = new WeakSet(), Qc = function() {
  const t = N(this, ts, $o).call(this);
  return R(this, et, document.createElement("div")), m(this, et).style.position = this.options.strategy, m(this, et).style.width = `${t}px`, m(this, et).style.height = `${t}px`, m(this, et).style.transform = "rotate(45deg)", m(this, et);
}, gi = new WeakSet(), Zc = function() {
  var i;
  const t = _e.TOOLTIP_CLASS;
  let s;
  if (this.isDynamic) {
    s = document.createElement("div");
    const o = this.options.className ? this.options.className.split(" ") : [];
    let r = [t, this.options.type || ""];
    r = r.concat(o), s.classList.add(...r), s[this.options.html ? "innerHTML" : "innerText"] = this.options.title || "";
  } else if (this.element) {
    const o = this.element.getAttribute("href") ?? this.element.dataset.target;
    if (o != null && o.startsWith("#") && (s = document.querySelector(o)), !s) {
      const r = this.element.nextElementSibling;
      r != null && r.classList.contains(t) ? s = r : s = (i = this.element.parentNode) == null ? void 0 : i.querySelector(`.${t}`);
    }
  }
  if (this.options.arrow && (s == null || s.append(N(this, mi, Qc).call(this))), !s)
    throw new Error("Tooltip: Cannot find tooltip element");
  return s.style.width = "max-content", s.style.position = "absolute", s.style.top = "0", s.style.left = "0", document.body.appendChild(s), R(this, xe, s), s;
}, yi = new WeakSet(), ta = function() {
  var r;
  const t = N(this, ts, $o).call(this), { strategy: s, placement: i } = this.options, o = {
    middleware: [xf(t), vf()],
    strategy: s,
    placement: i
  };
  return this.options.arrow && m(this, et) && ((r = o.middleware) == null || r.push(gf({ element: m(this, et) }))), o;
}, _i = new WeakSet(), ea = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
}, bi = new WeakSet(), na = function(t) {
  return t === "bottom" ? {
    borderBottomStyle: "none",
    borderRightStyle: "none"
  } : t === "top" ? {
    borderTopStyle: "none",
    borderLeftStyle: "none"
  } : t === "left" ? {
    borderBottomStyle: "none",
    borderLeftStyle: "none"
  } : {
    borderTopStyle: "none",
    borderRightStyle: "none"
  };
}, wi = new WeakSet(), sa = function() {
  const t = N(this, yi, ta).call(this), s = N(this, vi, ia).call(this);
  R(this, Zn, $f(s, this.tooltip, () => {
    Rf(s, this.tooltip, t).then(({ x: i, y: o, middlewareData: r, placement: l }) => {
      Object.assign(this.tooltip.style, {
        left: `${i}px`,
        top: `${o}px`
      });
      const a = l.split("-")[0], h = N(this, _i, ea).call(this, a);
      if (r.arrow && m(this, et)) {
        const { x: c, y: u } = r.arrow;
        Object.assign(m(this, et).style, {
          left: c != null ? `${c}px` : "",
          top: u != null ? `${u}px` : "",
          [h]: `${-m(this, et).offsetWidth / 2}px`,
          background: "inherit",
          border: "inherit",
          ...N(this, bi, na).call(this, a)
        });
      }
    });
  }));
}, vi = new WeakSet(), ia = function() {
  return m(this, Qe) || R(this, Qe, {
    getBoundingClientRect: () => {
      const { element: t } = this;
      if (t instanceof MouseEvent) {
        const { clientX: s, clientY: i } = t;
        return {
          width: 0,
          height: 0,
          top: i,
          right: s,
          bottom: i,
          left: s
        };
      }
      return t instanceof HTMLElement ? t.getBoundingClientRect() : t;
    },
    contextElement: this.element
  }), m(this, Qe);
}, tn = new WeakMap(), xi = new WeakSet(), oa = function() {
  const { tooltip: t } = this;
  t.addEventListener("mouseenter", m(this, tn)), t.addEventListener("mouseleave", this.hideLater), this.element.addEventListener("mouseleave", this.hideLater), R(this, Je, !0);
}, w(ht, "NAME", "tooltip"), w(ht, "TOOLTIP_CLASS", "tooltip"), w(ht, "CLASS_SHOW", "show"), w(ht, "MENU_SELECTOR", '[data-toggle="tooltip"]:not(.disabled):not(:disabled)'), w(ht, "DEFAULT", {
  animation: !0,
  placement: "top",
  strategy: "absolute",
  trigger: "hover",
  type: "darker",
  arrow: !0
});
document.addEventListener("click", function(e) {
  var s;
  const n = e.target, t = (s = n.closest) == null ? void 0 : s.call(n, ht.MENU_SELECTOR);
  if (t) {
    const i = ht.ensure(t);
    i.options.trigger === "click" && i.toggle();
  } else
    ht.clear({ event: e });
});
document.addEventListener("mouseover", function(e) {
  var i;
  const n = e.target, t = (i = n.closest) == null ? void 0 : i.call(n, ht.MENU_SELECTOR);
  if (!t)
    return;
  const s = ht.ensure(t);
  s.isHover && s.show();
});
let kf = class extends U {
  constructor() {
    super(...arguments);
    w(this, "handleItemClick", (t) => {
      const { onClickItem: s, changeActiveKey: i } = this.props;
      s && s(t);
      const { item: o } = t;
      o.items || i && i(o.key);
    });
  }
  render() {
    const { items: t, activeClass: s, activeIcon: i, activeKey: o, defaultNestedShow: r = !0, isDropdownMenu: l = !1, ...a } = this.props;
    return /* @__PURE__ */ E(
      oe,
      {
        className: l ? "dropdown-menu" : "",
        items: t,
        activeClass: s,
        activeKey: o,
        activeIcon: i,
        onClickItem: this.handleItemClick,
        defaultNestedShow: r,
        ...a
      }
    );
  }
};
class tl extends J {
}
w(tl, "NAME", "MenuTree"), w(tl, "Component", kf);
var ut;
class _n extends kt {
  constructor() {
    super(...arguments);
    x(this, ut, void 0);
  }
  init() {
    const { element: t } = this;
    t !== document.body && !t.hasAttribute("data-toggle") && t.setAttribute("data-toggle", "tab");
  }
  showTarget() {
    const t = this.element.getAttribute("href") || this.element.dataset.target || this.element.dataset.tab;
    t != null && t.startsWith("#") && R(this, ut, document.querySelector(t)), this.addActive(this.element.closest(`.${this.constructor.NAV_CLASS}`), this.element.parentElement), m(this, ut) && (this.addActive(m(this, ut).parentElement, m(this, ut)), m(this, ut).dispatchEvent(new CustomEvent("show.zui3.tab")));
  }
  show() {
    const t = this.element.getAttribute("href") || this.element.dataset.target || this.element.dataset.tab;
    t != null && t.startsWith("#") && R(this, ut, document.querySelector(t)), m(this, ut) && (this.addActive(m(this, ut).parentElement, m(this, ut)), this.addActive(this.element.closest(`.${this.constructor.NAV_CLASS}`), this.element.parentElement));
  }
  addActive(t, s) {
    const i = t.children;
    Array.from(i).forEach((r) => {
      r.classList.remove("active"), r.classList.contains("fade") && r.classList.remove("in");
    }), s.classList.add("active"), s.classList.contains("fade") && this.transition(s).then(function() {
      s.dispatchEvent(new CustomEvent("shown.zui3.tab"));
    });
  }
  transition(t) {
    return new Promise(function(s) {
      setTimeout(() => {
        t.classList.add("in"), s();
      }, 100);
    });
  }
}
ut = new WeakMap(), w(_n, "NAME", "NavTabs"), w(_n, "NAV_CLASS", "nav-tabs"), w(_n, "EVENTS", !0), w(_n, "TOGGLE_SELECTOR", '[data-toggle="tab"]');
document.addEventListener("click", (e) => {
  e.target instanceof HTMLElement && (e.target.dataset.toggle === "tab" || e.target.getAttribute("data-tab")) && (e.preventDefault(), new _n(e.target).showTarget());
});
class Tf extends U {
  constructor(t) {
    super(t);
    w(this, "handleChange", (t) => {
      this.setState({ activeKey: t });
    });
    this.state = {
      activeKey: t.activeKey ?? t.items[0].key
    };
  }
  render() {
    const { items: t, className: s, contentClass: i } = this.props, { activeKey: o } = this.state;
    return /* @__PURE__ */ b("div", { className: M("zui-tabs", s), children: [
      /* @__PURE__ */ b("ul", { className: "-flex -items-center", children: t.map(({ key: r, label: l, labelCount: a }) => /* @__PURE__ */ b("li", { className: M("-flex -items-center -gap-3", { active: o === r }), children: /* @__PURE__ */ b("a", { className: "-flex -h-8 -items-center -justify-center -gap-1 -px-4 -text-inherit", onClick: () => this.handleChange(r), children: [
        /* @__PURE__ */ b("span", { className: M({ "text-primary": o === r }), children: l }),
        o === r ? /* @__PURE__ */ b("span", { className: "label circle gray", children: a }) : null
      ] }) }, r)) }),
      t.map((r) => {
        const { key: l, content: a, isElm: h } = r;
        return h ? /* @__PURE__ */ b(
          "div",
          {
            dangerouslySetInnerHTML: { __html: a },
            className: M("-px-3", "-py-2", { "-hidden": o !== l })
          },
          l
        ) : /* @__PURE__ */ b("div", { className: M(i, { "-hidden": o !== l }), children: a }, l);
      })
    ] });
  }
}
class Af extends U {
  constructor(t) {
    super(t);
    w(this, "handleChange", (t) => {
      const s = t.target.value;
      this.setState({ value: s });
      const { onChange: i } = this.props;
      i && i(s);
    });
    w(this, "handleClear", () => {
      this.setState({ value: "" });
      const { onChange: t } = this.props;
      t && t("");
    });
    this.state = {
      value: t.defaultValue ?? ""
    };
  }
  render() {
    const { type: t = "text", icon: s } = this.props, { value: i } = this.state, o = s ? /* @__PURE__ */ b("label", { className: "input-control-prefix", children: /* @__PURE__ */ b("i", { className: `icon icon-${s}` }) }) : null;
    return /* @__PURE__ */ b("div", { className: "zui-input input-control has-prefix-icon", children: [
      o,
      /* @__PURE__ */ b("input", { className: "form-control", type: t, value: i, onChange: this.handleChange }),
      /* @__PURE__ */ b("span", { className: M("-absolute -w-8 -h-8 -right-0 -top-0 -flex -justify-center -items-center -cursor-pointer", { "-hidden": !i }), onClick: this.handleClear, children: /* @__PURE__ */ b("i", { className: "icon icon-close" }) })
    ] });
  }
}
var ho;
let Nf = (ho = class extends U {
  constructor(t) {
    super(t);
    w(this, "handleChange", (t) => {
      const { collapse: s } = this.state;
      this.setState({
        searchValue: t,
        collapse: !!t || s
      });
    });
    w(this, "acount", (t) => {
      let s = 0;
      return t.forEach((i) => {
        var o;
        s += ((o = i.items) == null ? void 0 : o.length) || 0;
      }), s;
    });
    w(this, "filter", (t) => {
      const s = [], { searchValue: i } = this.state;
      return t.forEach((o) => {
        const r = o.items.filter((l) => l.text.includes(i));
        r.length > 0 && s.push({ ...o, items: r });
      }), s;
    });
    this.state = {
      collapse: !0,
      searchValue: ""
    };
  }
  render() {
    const { involved: t, others: s, finished: i, involvedText: o, othersText: r, finishedBtnText: l, finishedText: a } = this.props, { collapse: h, searchValue: c } = this.state;
    return /* @__PURE__ */ E("div", { className: "quick-menu", style: { width: h ? 250 : 500 } }, /* @__PURE__ */ E("div", { className: "-p-2" }, /* @__PURE__ */ E(Af, { onChange: this.handleChange, icon: "search" })), /* @__PURE__ */ E("main", { className: "-flex" }, /* @__PURE__ */ E("div", { className: "-flex -max-h-[350px] -flex-col -pl-2 -py-2", style: { flexBasis: h ? "100%" : "50%" } }, /* @__PURE__ */ E(
      Tf,
      {
        className: "-flex -flex-col -max-h-full -overflow-hidden -grow",
        contentClass: "-grow -overflow-y-scroll",
        activeKey: 1,
        items: [
          {
            key: 1,
            label: o,
            labelCount: this.acount(t),
            content: /* @__PURE__ */ E(oe, { defaultNestedShow: !0, items: c ? this.filter(t) : t })
          },
          {
            key: 2,
            label: r,
            labelCount: this.acount(s),
            content: /* @__PURE__ */ E(oe, { defaultNestedShow: !0, items: c ? this.filter(s) : s })
          }
        ]
      }
    ), /* @__PURE__ */ E(
      "div",
      {
        onClick: () => this.setState({ collapse: !h }),
        className: `-py-2 -pr-2 -flex -justify-end -items-center -cursor-pointer ${c ? "-hidden" : ""}`
      },
      /* @__PURE__ */ E("span", null, l),
      /* @__PURE__ */ E("i", { className: `icon ${h ? "icon-angle-right" : "icon-angle-left"}` })
    )), h || c ? null : /* @__PURE__ */ E("div", { className: "-basis-1/2 -max-h-[350px] -overflow-y-auto -border-l-[1px] -border-solid -border-slate-200" }, /* @__PURE__ */ E(oe, { defaultNestedShow: !0, items: i }))), c ? /* @__PURE__ */ E("div", { className: "-max-h-[350px] -overflow-y-auto" }, /* @__PURE__ */ E("span", { className: "label gray size-lg -ml-2" }, a), /* @__PURE__ */ E(oe, { defaultNestedShow: !0, items: this.filter(i) })) : null);
  }
}, w(ho, "NAME", "zui.searchForm"), ho);
class el extends J {
}
w(el, "NAME", "QuickMenu"), w(el, "Component", Nf);
const Lf = ({
  formConfig: e,
  className: n,
  fields: t,
  operators: s,
  savedQuery: i,
  andOr: o,
  formSession: r,
  searchBtnText: l,
  resetBtnText: a,
  saveSearch: h,
  savedQueryTitle: c,
  onApplyQuery: u,
  onDeleteQuery: d,
  groupName: f,
  handleSelect: p,
  toggleMore: g,
  toggleHistory: y,
  resetForm: _,
  submitForm: v,
  actionURL: S,
  module: $,
  groupItems: T
}) => {
  const L = [n, ...["search-form"]], O = [1, 2, 3], k = r ? r.groupAndOr : "", j = (P) => {
    const V = r ? r[`andOr${P}`] : "";
    return /* @__PURE__ */ E("div", { class: [1, 4].includes(P) ? "search-group" : "search-group hidden", "data-id": P }, /* @__PURE__ */ E("div", { class: "group-name" }, [1, 4].includes(P) ? P === 1 ? f[0] : f[1] : /* @__PURE__ */ E("select", { class: "form-control", id: `andOr${P}`, name: `andOr${P}` }, o.map((F) => /* @__PURE__ */ E("option", { value: F.value, selected: V === F.value, title: F.value }, F.title)))), /* @__PURE__ */ E("div", { class: "group-select" }, /* @__PURE__ */ E("select", { class: "form-control field-select", id: `field${P}`, name: `field${P}`, onChange: p.bind(void 0) }, " ", t == null ? void 0 : t.map((F) => /* @__PURE__ */ E("option", { value: F.name, selected: !1, title: F.name, control: F.control }, F.label)))), /* @__PURE__ */ E("div", { class: "group-select" }, /* @__PURE__ */ E("select", { class: "form-control search-method", id: `operator${P}`, name: `operator${P}` }, s.map((F) => /* @__PURE__ */ E("option", { key: F.value, value: F.value, title: F.value }, F.title)))), /* @__PURE__ */ E("div", { class: "group-value" }, /* @__PURE__ */ E("input", { type: "text", class: "form-control value-input", value: t[P - 1].defaultValue, placeholder: t[P - 1].placeholder }), /* @__PURE__ */ E("select", { class: "form-control value-select hidden" }), /* @__PURE__ */ E("input", { type: "datetime-local", class: "form-control value-date hidden" })));
  };
  return /* @__PURE__ */ E(
    "form",
    {
      id: "searchForm",
      className: M(L),
      ...e
    },
    /* @__PURE__ */ E("div", { class: "search-form-content" }, /* @__PURE__ */ E("div", { class: "search-form-items" }, /* @__PURE__ */ E("div", { class: "search-col" }, O.map((P) => j(P))), /* @__PURE__ */ E("div", { class: "search-col" }, /* @__PURE__ */ E("select", { class: "form-control", id: "groupAndOr", name: "groupAndOr" }, o.map((P) => /* @__PURE__ */ E("option", { value: P.value, selected: k === P.value, title: P.value }, P.title)))), /* @__PURE__ */ E("div", { class: "search-col" }, O.map((P) => j(P + 3)))), /* @__PURE__ */ E("div", { class: "search-form-footer" }, /* @__PURE__ */ E("div", { class: "inline-block flex items-center justify-center" }, /* @__PURE__ */ E("button", { class: "btn primary btn-submit-form", type: "button", onClick: v }, l || "搜索"), /* @__PURE__ */ E("button", { class: "btn btn-reset-form", type: "button", onClick: _ }, a || "重置")), /* @__PURE__ */ E("div", { class: "save-bar" }, (h == null ? void 0 : h.hasPriv) && /* @__PURE__ */ E("a", { class: "btn save-query", ...h.config }, /* @__PURE__ */ E("i", { class: "icon icon-save" }), h.text || "保存搜索条件"), /* @__PURE__ */ E("a", { class: "btn toggle-more", onClick: g }, /* @__PURE__ */ E("i", { class: "icon icon-chevron-double-down" }))))),
    /* @__PURE__ */ E("div", null, /* @__PURE__ */ E("button", { class: "btn search-toggle-btn", type: "button", onClick: y }, /* @__PURE__ */ E("i", { class: "icon icon-angle-left" }))),
    /* @__PURE__ */ E("div", { class: "history-record hidden" }, /* @__PURE__ */ E("p", null, c), /* @__PURE__ */ E("div", { class: "labels" }, (i == null ? void 0 : i.length) && i.map((P) => {
      if (P)
        return /* @__PURE__ */ E("div", { class: "label-btn", "data-id": P.id }, /* @__PURE__ */ E("span", { class: "label lighter-pale bd-lighter", onClick: (V) => u(V, Number(P.id)) }, P.title, " ", P.hasPriv ? /* @__PURE__ */ E("i", { onClick: (V) => d(V, Number(P.id)), class: "icon icon-close" }) : ""));
    }))),
    S ? /* @__PURE__ */ E("input", { type: "hidden", name: "actionURL", value: S }) : "",
    $ ? /* @__PURE__ */ E("input", { type: "hidden", name: "module", value: $ }) : "",
    T ? /* @__PURE__ */ E("input", { type: "hidden", name: "groupItems", value: T }) : ""
  );
};
var zt;
let Mf = (zt = class extends U {
  componentDidMount() {
    this.initForm();
  }
  initForm() {
    const { formSession: n } = this.props;
    this.base.querySelectorAll(".search-form-content .search-group").forEach((s, i) => {
      let o = {};
      const r = s.querySelector(".field-select");
      r && (r.value = (n ? n[r.id] : null) || this.props.fields[i].name, this.props.fields.forEach((a) => {
        a.name == r.value && (o = JSON.parse(JSON.stringify(a)));
      })), o.defaultValue = n ? n["value" + (i + 1)] : "";
      const l = s.querySelector(".search-method");
      l && (l.value = (n ? n[l.id] : null) || this.props.fields[i].operator || ""), this.toggleElement(s, o);
    });
  }
  toggleAttr(n, t) {
    if (!n.classList.contains("hidden")) {
      n.setAttribute("name", t), n.setAttribute("id", t);
      return;
    }
    n.removeAttribute("name"), n.removeAttribute("id");
  }
  toggleElement(n, t) {
    const s = n.querySelector(".value-select"), i = n.querySelector(".value-input"), o = n.querySelector(".value-date"), r = n.querySelector(".search-method");
    if (t.operator, t.control === "select" && (s.innerHTML = "", t.values)) {
      for (const c in t.values) {
        const u = document.createElement("option");
        u.value = c, u.setAttribute("value", c), u.innerHTML = t.values[c], s.appendChild(u);
      }
      s.value = t.defaultValue || "";
    }
    s.classList.toggle("hidden", t.control !== "select"), i.classList.toggle("hidden", t.control !== "input"), o == null || o.classList.toggle("hidden", t.control !== "date"), i.classList.contains("hidden") || (i.value = t.defaultValue || "", i.placeholder = t.placeholder || ""), o && !o.classList.contains("hidden") && (o.value = t.defaultValue || "");
    const l = n.dataset.id, a = n.querySelector(".group-value");
    if (!a)
      return;
    a.childNodes.forEach((c) => {
      this.toggleAttr(c, `value${l}`);
    });
  }
  handleSelect(n) {
    if (!n || !n.target)
      return;
    const t = n.target, i = this.props.fields.filter((r) => r.name === t.value)[0], o = t.closest(".search-group");
    this.toggleElement(o, i);
  }
  toggleElementDisplay(n, t, s, i) {
    const o = t.classList.contains("hidden"), r = n.querySelector(".icon");
    r == null || r.classList.toggle(s, o), r == null || r.classList.toggle(i, !o);
  }
  toggleMore(n) {
    if (!(n != null && n.target))
      return;
    const t = n.target, i = t.closest(".search-form-content").querySelectorAll(".search-col .search-group + .search-group");
    i.forEach((o) => {
      o.classList.toggle("hidden", !o.classList.contains("hidden"));
    }), this.toggleElementDisplay(t, i[0], "icon-chevron-double-down", "icon-chevron-double-up");
  }
  toggleHistory(n) {
    var i;
    if (!(n != null && n.target))
      return;
    const t = n.target, s = (i = t.closest(zt.FORM_ID)) == null ? void 0 : i.querySelector(".history-record");
    s && (this.toggleElementDisplay(t, s, "icon-angle-right", "icon-angle-left"), s.classList.toggle("hidden", !s.classList.contains("hidden")));
  }
  resetForm(n) {
    if (!(n != null && n.target))
      return;
    const s = n.target.closest(zt.FORM_ID);
    if (!s)
      return;
    s.querySelectorAll('.group-value [id^="value"]:not(.hidden), #searchForm .group-value [id*=" value"]:not(.hidden)').forEach((o) => {
      o.value = "";
    });
  }
  submitForm(n) {
    if (!(n != null && n.target))
      return;
    const s = n.target.closest(zt.FORM_ID);
    s && s.submit();
  }
  onDeleteQuery(n, t) {
    !n || !n.target || t && n.stopPropagation();
  }
  onApplyQuery(n, t) {
    if (!n || !n.target || !t)
      return;
    const { applyQueryURL: s } = this.props;
    s && (location.href = s.replace("myQueryID", t.toString()));
  }
  render() {
    const { submitForm: n, onApplyQuery: t, onDeleteQuery: s } = this.props;
    return /* @__PURE__ */ E(
      Lf,
      {
        ...this.props,
        handleSelect: this.handleSelect.bind(this),
        toggleMore: this.toggleMore.bind(this),
        toggleHistory: this.toggleHistory.bind(this),
        resetForm: this.resetForm.bind(this),
        submitForm: n ? n.bind(this) : this.submitForm.bind(this),
        onDeleteQuery: s ? s.bind(this) : this.onDeleteQuery.bind(this),
        onApplyQuery: t ? t.bind(this) : this.onApplyQuery.bind(this)
      }
    );
  }
}, w(zt, "NAME", "zui.searchForm"), w(zt, "FORM_ID", "#searchForm"), zt);
class nl extends J {
}
w(nl, "NAME", "searchForm"), w(nl, "Component", Mf);
var Si, ra, Ei, la, Ci, ca;
class Of extends kt {
  constructor() {
    super(...arguments);
    x(this, Si);
    x(this, Ei);
    x(this, Ci);
  }
  init() {
    A(this.element).on("submit", this.onSubmit.bind(this)).on("input mousedown change", this.onInput.bind(this));
  }
  enable(t = !0) {
    A(this.element).toggleClass("loading", !t);
  }
  disable() {
    this.enable(!1);
  }
  onInput(t) {
    const s = A(t.target).closest(".has-error");
    s.length && (s.removeClass("has-error"), s.closest(".form-group").find(`#${s.attr("id")}Tip`).remove());
  }
  onSubmit(t) {
    var o;
    t.preventDefault();
    const { element: s } = this, i = A.extend({}, this.options);
    this.emit("before", { event: t, element: s, options: i }, !1), ((o = i.beforeSubmit) == null ? void 0 : o.call(i, t, s, i)) !== !1 && (this.disable(), N(this, Si, ra).call(this, new FormData(s)).finally(() => {
      this.enable();
    }));
  }
  submit() {
    this.element.submit();
  }
  reset() {
    this.element.reset();
  }
}
Si = new WeakSet(), ra = async function(t) {
  var h, c;
  const { element: s, options: i } = this, { beforeSend: o } = i;
  if (o) {
    const u = o(t);
    u instanceof FormData && (t = u);
  }
  this.emit("send", { formData: t }, !1);
  let r, l, a;
  try {
    const u = await fetch(i.url || s.action, {
      method: s.method || "POST",
      body: t,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    });
    l = await u.text(), u.ok ? (a = JSON.parse(l), (!a || typeof a != "object") && (r = new Error("Invalid json format"))) : r = new Error(u.statusText);
  } catch (u) {
    r = u;
  }
  r ? (this.emit("error", { error: r, responseText: l }, !1), (h = i.onError) == null || h.call(i, r, l)) : N(this, Ci, ca).call(this, a), this.emit("complete", { result: a, error: r }, !1), (c = i.onComplete) == null || c.call(i, a, r);
}, Ei = new WeakSet(), la = function(t) {
  var i;
  let s;
  Object.entries(t).forEach(([o, r]) => {
    Array.isArray(r) && (r = r.join(""));
    const l = A(this.element).find(`#${o}`);
    if (!l.length)
      return;
    l.addClass("has-error");
    const a = l.closest(".form-group");
    if (a.length) {
      let h = A(`#${o}Tip`);
      h.length || (h = A(`<div class="form-tip ajax-form-tip text-danger" id="${o}Tip"></div>`).appendTo(a)), h.empty().text(r);
    }
    s || (s = l);
  }), s && ((i = s[0]) == null || i.focus());
}, Ci = new WeakSet(), ca = function(t) {
  var o, r;
  const { options: s } = this, { message: i } = t;
  if (t.result === "success") {
    if (this.emit("success", { result: t }, !1), ((o = s.onSuccess) == null ? void 0 : o.call(s, t)) === !1)
      return;
    typeof i == "string" && i.length && A(document).trigger("zui.messager.show", { content: i, type: "success" });
    const { closeModal: l } = s;
    l && A(document).trigger("zui.modal.hide", { target: l });
    const a = t.callback || s.callback;
    if (typeof a == "string") {
      const c = a.indexOf("("), u = (c > 0 ? a.substr(0, c) : a).split(".");
      let d = window, f = u[0];
      u.length > 1 && (f = u[1], u[0] === "top" ? d = window.top : u[0] === "parent" && (d = window.parent));
      const p = d == null ? void 0 : d[f];
      if (typeof p == "function") {
        let g = [];
        return c > 0 && a[a.length - 1] == ")" && (g = JSON.parse("[" + a.substring(c + 1, a.length - 1) + "]")), g.push(t), p.apply(this, g);
      }
    } else
      a && typeof a == "object" && (a.target ? window[a.target] : window)[a.name].apply(this, Array.isArray(a.params) ? a.params : [a.params]);
    const h = t.locate || s.locate;
    h && A(document).trigger("zui.locate", h);
  } else {
    if (this.emit("fail", { result: t }, !1), ((r = s.onFail) == null ? void 0 : r.call(s, t)) === !1)
      return;
    typeof i == "string" && i.length ? A(document).trigger("zui.messager.show", { content: i }) : typeof i == "object" && i && N(this, Ei, la).call(this, i);
  }
}, w(Of, "NAME", "ajaxform");
var Se, Ee;
class sl extends U {
  constructor(t) {
    super(t);
    x(this, Se, 0);
    x(this, Ee, null);
    w(this, "_handleWheel", (t) => {
      const { wheelContainer: s } = this.props, i = t.target;
      if (!(!i || !s) && (typeof s == "string" && i.closest(s) || typeof s == "object")) {
        const o = (this.props.type === "horz" ? t.deltaX : t.deltaY) * (this.props.wheelSpeed ?? 1);
        this.scrollOffset(o) && t.preventDefault();
      }
    });
    w(this, "_handleMouseMove", (t) => {
      const { dragStart: s } = this.state;
      s && (m(this, Se) && cancelAnimationFrame(m(this, Se)), R(this, Se, requestAnimationFrame(() => {
        const i = this.props.type === "horz" ? t.clientX - s.x : t.clientY - s.y;
        this.scroll(s.offset + i * this.props.scrollSize / this.props.clientSize), R(this, Se, 0);
      })), t.preventDefault());
    });
    w(this, "_handleMouseUp", () => {
      this.state.dragStart && this.setState({
        dragStart: !1
      });
    });
    w(this, "_handleMouseDown", (t) => {
      this.state.dragStart || this.setState({ dragStart: { x: t.clientX, y: t.clientY, offset: this.scrollPos } }), t.stopPropagation();
    });
    w(this, "_handleClick", (t) => {
      const s = t.currentTarget;
      if (!s)
        return;
      const i = s.getBoundingClientRect(), { type: o, clientSize: r, scrollSize: l } = this.props, a = (o === "horz" ? t.clientX - i.left : t.clientY - i.top) - this.barSize / 2;
      this.scroll(a * l / r), t.preventDefault();
    });
    this.state = {
      scrollPos: this.props.defaultScrollPos ?? 0,
      dragStart: !1
    };
  }
  get scrollPos() {
    return this.props.scrollPos ?? this.state.scrollPos;
  }
  get controlled() {
    return this.props.scrollPos !== void 0;
  }
  get maxScrollPos() {
    const { scrollSize: t, clientSize: s } = this.props;
    return Math.max(0, t - s);
  }
  get barSize() {
    const { clientSize: t, scrollSize: s, size: i = 12, minBarSize: o = 3 * i } = this.props;
    return Math.max(Math.round(t * t / s), o);
  }
  componentDidMount() {
    document.addEventListener("mousemove", this._handleMouseMove), document.addEventListener("mouseup", this._handleMouseUp);
    const { wheelContainer: t } = this.props;
    t && (R(this, Ee, typeof t == "string" ? document : t.current), m(this, Ee).addEventListener("wheel", this._handleWheel, { passive: !1 }));
  }
  componentWillUnmount() {
    document.removeEventListener("mousemove", this._handleMouseMove), document.removeEventListener("mouseup", this._handleMouseUp), m(this, Ee) && m(this, Ee).removeEventListener("wheel", this._handleWheel);
  }
  scroll(t) {
    return t = Math.max(0, Math.min(Math.round(t), this.maxScrollPos)), t === this.scrollPos ? !1 : (this.controlled ? this._afterScroll(t) : this.setState({
      scrollPos: t
    }, this._afterScroll.bind(this, t)), !0);
  }
  scrollOffset(t) {
    return this.scroll(this.scrollPos + t);
  }
  _afterScroll(t) {
    const { onScroll: s } = this.props;
    s && s(t, this.props.type ?? "vert");
  }
  render() {
    const {
      clientSize: t,
      type: s,
      size: i = 12,
      className: o,
      style: r,
      left: l,
      top: a,
      bottom: h,
      right: c
    } = this.props, { maxScrollPos: u, scrollPos: d } = this, { dragStart: f } = this.state, p = {
      left: l,
      top: a,
      bottom: h,
      right: c,
      ...r
    }, g = {};
    return s === "horz" ? (p.height = i, p.width = t, g.width = this.barSize, g.left = Math.round(Math.min(u, d) * (t - g.width) / u)) : (p.width = i, p.height = t, g.height = this.barSize, g.top = Math.round(Math.min(u, d) * (t - g.height) / u)), /* @__PURE__ */ b(
      "div",
      {
        className: M("scrollbar", o, {
          "is-vert": s === "vert",
          "is-horz": s === "horz",
          "is-dragging": f
        }),
        style: p,
        onMouseDown: this._handleClick,
        children: /* @__PURE__ */ b(
          "div",
          {
            className: "scrollbar-bar",
            style: g,
            onMouseDown: this._handleMouseDown
          }
        )
      }
    );
  }
}
Se = new WeakMap(), Ee = new WeakMap();
function il(e, n, t) {
  return e && (n && (e = Math.max(n, e)), t && (e = Math.min(t, e))), e;
}
function aa({ col: e, className: n, height: t, row: s, onRenderCell: i, style: o, outerStyle: r, children: l, outerClass: a, ...h }) {
  var O;
  const c = {
    left: e.left,
    width: e.realWidth,
    height: t,
    ...r
  }, { align: u, border: d } = e.setting, f = {
    justifyContent: u ? u === "left" ? "start" : u === "right" ? "end" : u : void 0,
    ...e.setting.cellStyle,
    ...o
  }, p = ["dtable-cell", a, e.setting.className, {
    "has-border-left": d === !0 || d === "left",
    "has-border-right": d === !0 || d === "right"
  }], g = ["dtable-cell-content", n], y = [l ?? ((O = s.data) == null ? void 0 : O[e.name]) ?? ""], _ = i ? i(y, { row: s, col: e }, E) : y, v = [], S = [], $ = {}, T = {};
  let D = "div";
  _ == null || _.forEach((k) => {
    if (typeof k == "object" && k && !it(k) && ("html" in k || "className" in k || "style" in k || "attrs" in k || "children" in k || "tagName" in k)) {
      const j = k.outer ? v : S;
      k.html ? j.push(/* @__PURE__ */ b("div", { className: M("dtable-cell-html", k.className), style: k.style, dangerouslySetInnerHTML: { __html: k.html }, ...k.attrs ?? {} })) : (k.style && Object.assign(k.outer ? c : f, k.style), k.className && (k.outer ? p : g).push(k.className), k.children && j.push(k.children), k.attrs && Object.assign(k.outer ? $ : T, k.attrs)), k.tagName && !k.outer && (D = k.tagName);
    } else
      S.push(k);
  });
  const L = D;
  return /* @__PURE__ */ b(
    "div",
    {
      className: M(p),
      style: c,
      "data-col": e.name,
      ...h,
      ...$,
      children: [
        S.length > 0 && /* @__PURE__ */ b(L, { className: M(g), style: f, ...T, children: S }),
        v
      ]
    }
  );
}
function oo({ row: e, className: n, top: t = 0, left: s = 0, width: i, height: o, cols: r, CellComponent: l = aa, onRenderCell: a }) {
  return /* @__PURE__ */ b("div", { className: M("dtable-cells", n), style: { top: t, left: s, width: i, height: o }, children: r.map((h) => h.visible ? /* @__PURE__ */ b(
    l,
    {
      col: h,
      row: e,
      onRenderCell: a
    },
    h.name
  ) : null) });
}
function ua({
  row: e,
  className: n,
  top: t,
  height: s,
  fixedLeftCols: i,
  fixedRightCols: o,
  scrollCols: r,
  fixedLeftWidth: l,
  scrollWidth: a,
  scrollColsWidth: h,
  fixedRightWidth: c,
  scrollLeft: u,
  CellComponent: d = aa,
  onRenderCell: f,
  style: p,
  ...g
}) {
  let y = null;
  i != null && i.length && (y = /* @__PURE__ */ b(
    oo,
    {
      className: "dtable-fixed-left",
      cols: i,
      width: l,
      row: e,
      CellComponent: d,
      onRenderCell: f
    }
  ));
  let _ = null;
  r != null && r.length && (_ = /* @__PURE__ */ b(
    oo,
    {
      className: "dtable-flexable",
      cols: r,
      left: l - u,
      width: Math.max(a, h),
      row: e,
      CellComponent: d,
      onRenderCell: f
    }
  ));
  let v = null;
  o != null && o.length && (v = /* @__PURE__ */ b(
    oo,
    {
      className: "dtable-fixed-right",
      cols: o,
      left: l + a,
      width: c,
      row: e,
      CellComponent: d,
      onRenderCell: f
    }
  ));
  const S = { top: t, height: s, lineHeight: `${s - 2}px`, ...p };
  return /* @__PURE__ */ b(
    "div",
    {
      className: M("dtable-row", n),
      style: S,
      "data-id": e.id,
      ...g,
      children: [
        y,
        _,
        v
      ]
    }
  );
}
function Pf({ height: e, onRenderRow: n, ...t }) {
  const s = {
    height: e,
    ...t,
    row: { id: "HEADER", index: -1, top: 0 },
    className: "dtable-in-header",
    top: 0
  };
  if (n) {
    const i = n({ props: s }, E);
    i && Object.assign(s, i);
  }
  return /* @__PURE__ */ b("div", { className: "dtable-header", style: { height: e }, children: /* @__PURE__ */ b(ua, { ...s }) });
}
function Df({
  className: e,
  style: n,
  top: t,
  rows: s,
  height: i,
  rowHeight: o,
  scrollTop: r,
  onRenderRow: l,
  ...a
}) {
  return n = { ...n, top: t, height: i }, /* @__PURE__ */ b("div", { className: M("dtable-rows", e), style: n, children: s.map((h) => {
    const c = {
      className: `dtable-row-${h.index % 2 ? "odd" : "even"}`,
      row: h,
      top: h.top - r,
      height: o,
      ...a
    }, u = l == null ? void 0 : l({ props: c, row: h }, E);
    return u && Object.assign(c, u), /* @__PURE__ */ b(ua, { ...c });
  }) });
}
const Us = /* @__PURE__ */ new Map(), Vs = [];
function ha(e, n) {
  const { name: t } = e;
  if (!(n != null && n.override) && Us.has(t))
    throw new Error(`DTable: Plugin with name ${t} already exists`);
  Us.set(t, e), n != null && n.buildIn && !Vs.includes(t) && Vs.push(t);
}
function Nt(e, n) {
  ha(e, n);
  const t = (s) => {
    if (!s)
      return e;
    const { defaultOptions: i, ...o } = e;
    return {
      ...o,
      defaultOptions: { ...i, ...s }
    };
  };
  return t.plugin = e, t;
}
function fa(e) {
  return Us.delete(e);
}
function Hf(e) {
  if (typeof e == "string") {
    const n = Us.get(e);
    return n || console.warn(`DTable: Cannot found plugin "${e}"`), n;
  }
  if (typeof e == "function" && "plugin" in e)
    return e.plugin;
  if (typeof e == "object")
    return e;
  console.warn("DTable: Invalid plugin", e);
}
function da(e, n, t) {
  return n.forEach((s) => {
    var o;
    if (!s)
      return;
    const i = Hf(s);
    i && (t.has(i.name) || ((o = i.plugins) != null && o.length && da(e, i.plugins, t), e.push(i), t.add(i.name)));
  }), e;
}
function If(e = [], n = !0) {
  return n && Vs.length && e.unshift(...Vs), e != null && e.length ? da([], e, /* @__PURE__ */ new Set()) : [];
}
function ol() {
  return {
    cols: [],
    data: [],
    rowKey: "id",
    width: "100%",
    height: "auto",
    rowHeight: 35,
    defaultColWidth: 80,
    minColWidth: 20,
    maxColWidth: 9999,
    header: !0,
    footer: !1,
    headerHeight: 0,
    footerHeight: 0,
    rowHover: !0,
    colHover: !1,
    cellHover: !1,
    bordered: !1,
    striped: !0,
    responsive: !1,
    scrollbarHover: !0,
    horzScrollbarPos: "outside"
  };
}
var ws, Ce, en, ne, St, se, Q, gt, Et, nn, es, ns, Wt, sn, on, $i, pa, Ri, ma, ki, ga, Ti, ya, ss, Ro, Ai, Ni, is, os, Li, Mi, Oi, _a, Pi, ba, Di, wa;
let jf = (ws = class extends U {
  constructor(t) {
    super(t);
    x(this, $i);
    x(this, Ri);
    x(this, ki);
    x(this, Ti);
    x(this, ss);
    x(this, Oi);
    x(this, Pi);
    x(this, Di);
    w(this, "ref", cn());
    x(this, Ce, 0);
    x(this, en, void 0);
    x(this, ne, !1);
    x(this, St, void 0);
    x(this, se, void 0);
    x(this, Q, []);
    x(this, gt, void 0);
    x(this, Et, /* @__PURE__ */ new Map());
    x(this, nn, {});
    x(this, es, void 0);
    x(this, ns, []);
    w(this, "updateLayout", () => {
      m(this, Ce) && cancelAnimationFrame(m(this, Ce)), R(this, Ce, requestAnimationFrame(() => {
        R(this, gt, void 0), this.forceUpdate(), R(this, Ce, 0);
      }));
    });
    x(this, Wt, (t, s) => {
      s = s || t.type;
      const i = m(this, Et).get(s);
      if (i != null && i.length) {
        for (const o of i)
          if (o.call(this, t) === !1) {
            t.stopPropagation(), t.preventDefault();
            break;
          }
      }
    });
    x(this, sn, (t) => {
      m(this, Wt).call(this, t, `window_${t.type}`);
    });
    x(this, on, (t) => {
      m(this, Wt).call(this, t, `document_${t.type}`);
    });
    x(this, Ai, (t, s) => {
      if (this.options.onRenderRow) {
        const i = this.options.onRenderRow.call(this, t, s);
        i && Object.assign(t.props, i);
      }
      return m(this, Q).forEach((i) => {
        if (i.onRenderRow) {
          const o = i.onRenderRow.call(this, t, s);
          o && Object.assign(t.props, o);
        }
      }), t.props;
    });
    x(this, Ni, (t, s) => (this.options.onRenderHeaderRow && (t.props = this.options.onRenderHeaderRow.call(this, t, s)), m(this, Q).forEach((i) => {
      i.onRenderHeaderRow && (t.props = i.onRenderHeaderRow.call(this, t, s));
    }), t.props));
    x(this, is, (t, s, i) => {
      const { row: o, col: r } = s;
      t[0] = this.getCellValue(o, r);
      const l = o.id === "HEADER" ? "onRenderHeaderCell" : "onRenderCell";
      return r.setting[l] && (t = r.setting[l].call(this, t, s, i)), this.options[l] && (t = this.options[l].call(this, t, s, i)), m(this, Q).forEach((a) => {
        a[l] && (t = a[l].call(this, t, s, i));
      }), t;
    });
    x(this, os, (t, s) => {
      s === "horz" ? this.scroll({ scrollLeft: t }) : this.scroll({ scrollTop: t });
    });
    x(this, Li, (t) => {
      var l, a, h, c, u;
      const s = this.getPointerInfo(t);
      if (!s)
        return;
      const { rowID: i, colName: o, cellElement: r } = s;
      if (i === "HEADER")
        r && ((l = this.options.onHeaderCellClick) == null || l.call(this, t, { colName: o, element: r }), m(this, Q).forEach((d) => {
          var f;
          (f = d.onHeaderCellClick) == null || f.call(this, t, { colName: o, element: r });
        }));
      else {
        const { rowElement: d } = s, f = this.layout.visibleRows.find((p) => p.id === i);
        if (r) {
          if (((a = this.options.onCellClick) == null ? void 0 : a.call(this, t, { colName: o, rowID: i, rowInfo: f, element: r, rowElement: d })) === !0)
            return;
          for (const p of m(this, Q))
            if (((h = p.onCellClick) == null ? void 0 : h.call(this, t, { colName: o, rowID: i, rowInfo: f, element: r, rowElement: d })) === !0)
              return;
        }
        if (((c = this.options.onRowClick) == null ? void 0 : c.call(this, t, { rowID: i, rowInfo: f, element: d })) === !0)
          return;
        for (const p of m(this, Q))
          if (((u = p.onRowClick) == null ? void 0 : u.call(this, t, { rowID: i, rowInfo: f, element: d })) === !0)
            return;
      }
    });
    x(this, Mi, (t) => {
      const s = t.key.toLowerCase();
      if (["pageup", "pagedown", "home", "end"].includes(s))
        return !this.scroll({ to: s.replace("page", "") });
    });
    R(this, en, t.id ?? `dtable-${us(10)}`), this.state = { scrollTop: 0, scrollLeft: 0, renderCount: 0 }, R(this, se, Object.freeze(If(t.plugins))), m(this, se).forEach((s) => {
      var l;
      const { methods: i, data: o, state: r } = s;
      i && Object.entries(i).forEach(([a, h]) => {
        typeof h == "function" && Object.assign(this, { [a]: h.bind(this) });
      }), o && Object.assign(m(this, nn), o.call(this)), r && Object.assign(this.state, r.call(this)), (l = s.onCreate) == null || l.call(this, s);
    });
  }
  get options() {
    var t;
    return ((t = m(this, gt)) == null ? void 0 : t.options) || m(this, St) || ol();
  }
  get plugins() {
    return m(this, Q);
  }
  get layout() {
    return m(this, gt);
  }
  get id() {
    return m(this, en);
  }
  get data() {
    return m(this, nn);
  }
  get parent() {
    var t;
    return this.props.parent ?? ((t = this.ref.current) == null ? void 0 : t.parentElement);
  }
  componentWillReceiveProps() {
    R(this, St, void 0);
  }
  componentDidMount() {
    if (m(this, ne) ? this.forceUpdate() : N(this, ss, Ro).call(this), m(this, Q).forEach((t) => {
      let { events: s } = t;
      s && (typeof s == "function" && (s = s.call(this)), Object.entries(s).forEach(([i, o]) => {
        o && this.on(i, o);
      }));
    }), this.on("click", m(this, Li)), this.on("keydown", m(this, Mi)), this.options.responsive) {
      if (typeof ResizeObserver < "u") {
        const { parent: t } = this;
        if (t) {
          const s = new ResizeObserver(this.updateLayout);
          s.observe(t), R(this, es, s);
        }
      }
      this.on("window_resize", this.updateLayout);
    }
    m(this, Q).forEach((t) => {
      var s;
      (s = t.onMounted) == null || s.call(this);
    });
  }
  componentDidUpdate() {
    m(this, ne) ? N(this, ss, Ro).call(this) : m(this, Q).forEach((t) => {
      var s;
      (s = t.onUpdated) == null || s.call(this);
    });
  }
  componentWillUnmount() {
    var s;
    (s = m(this, es)) == null || s.disconnect();
    const { current: t } = this.ref;
    if (t)
      for (const i of m(this, Et).keys())
        i.startsWith("window_") ? window.removeEventListener(i.replace("window_", ""), m(this, sn)) : i.startsWith("document_") ? document.removeEventListener(i.replace("document_", ""), m(this, on)) : t.removeEventListener(i, m(this, Wt));
    m(this, Q).forEach((i) => {
      var o;
      (o = i.onUnmounted) == null || o.call(this);
    }), m(this, se).forEach((i) => {
      var o;
      (o = i.onDestory) == null || o.call(this);
    }), R(this, nn, {}), m(this, Et).clear();
  }
  on(t, s, i) {
    var r;
    i && (t = `${i}_${t}`);
    const o = m(this, Et).get(t);
    o ? o.push(s) : (m(this, Et).set(t, [s]), t.startsWith("window_") ? window.addEventListener(t.replace("window_", ""), m(this, sn)) : t.startsWith("document_") ? document.addEventListener(t.replace("document_", ""), m(this, on)) : (r = this.ref.current) == null || r.addEventListener(t, m(this, Wt)));
  }
  off(t, s, i) {
    var l;
    i && (t = `${i}_${t}`);
    const o = m(this, Et).get(t);
    if (!o)
      return;
    const r = o.indexOf(s);
    r >= 0 && o.splice(r, 1), o.length || (m(this, Et).delete(t), t.startsWith("window_") ? window.removeEventListener(t.replace("window_", ""), m(this, sn)) : t.startsWith("document_") ? document.removeEventListener(t.replace("document_", ""), m(this, on)) : (l = this.ref.current) == null || l.removeEventListener(t, m(this, Wt)));
  }
  emitCustomEvent(t, s) {
    m(this, Wt).call(this, s instanceof Event ? s : new CustomEvent(t, { detail: s }), t);
  }
  scroll(t, s) {
    const { scrollLeft: i, scrollTop: o, rowsHeightTotal: r, rowsHeight: l, rowHeight: a, colsInfo: { scrollWidth: h, scrollColsWidth: c } } = this.layout, { to: u } = t;
    let { scrollLeft: d, scrollTop: f } = t;
    if (u === "up" || u === "down")
      f = o + (u === "down" ? 1 : -1) * Math.floor(l / a) * a;
    else if (u === "left" || u === "right")
      d = i + (u === "right" ? 1 : -1) * h;
    else if (u === "home")
      f = 0;
    else if (u === "end")
      f = r - l;
    else if (u === "left-begin")
      d = 0;
    else if (u === "right-end")
      d = c - h;
    else {
      const { offsetLeft: g, offsetTop: y } = t;
      typeof g == "number" && (d = i + g), typeof y == "number" && (d = o + y);
    }
    const p = {};
    return typeof d == "number" && (d = Math.max(0, Math.min(d, c - h)), d !== i && (p.scrollLeft = d)), typeof f == "number" && (f = Math.max(0, Math.min(f, r - l)), f !== o && (p.scrollTop = f)), Object.keys(p).length ? (this.setState(p, () => {
      var g;
      (g = this.options.onScroll) == null || g.call(this, p), s == null || s.call(this, !0);
    }), !0) : (s == null || s.call(this, !1), !1);
  }
  getColInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    const { colsMap: s, colsList: i } = this.layout;
    return typeof t == "number" ? i[t] : s[t];
  }
  getRowInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    if (t === -1 || t === "HEADER")
      return { id: "HEADER", index: -1, top: 0 };
    const { rows: s, rowsMap: i } = this.layout;
    return typeof t == "number" ? s[t] : i[t];
  }
  getCellValue(t, s) {
    var a;
    const i = typeof t == "object" ? t : this.getRowInfo(t);
    if (!i)
      return;
    const o = typeof s == "object" ? s : this.getColInfo(s);
    if (!o)
      return;
    let r = i.id === "HEADER" ? o.setting.title : (a = i.data) == null ? void 0 : a[o.name];
    const { cellValueGetter: l } = this.options;
    return l && (r = l.call(this, i, o, r)), r;
  }
  getRowInfoByIndex(t) {
    return this.layout.rows[t];
  }
  update(t = {}, s) {
    if (!m(this, St))
      return;
    typeof t == "function" && (s = t, t = {});
    const { dirtyType: i, state: o } = t;
    if (i === "layout")
      R(this, gt, void 0);
    else if (i === "options") {
      if (R(this, St, void 0), !m(this, gt))
        return;
      R(this, gt, void 0);
    }
    this.setState(o ?? ((r) => ({ renderCount: r.renderCount + 1 })), s);
  }
  getPointerInfo(t) {
    const s = t.target;
    if (!s || s.closest(".no-cell-event"))
      return;
    const i = s.closest(".dtable-cell");
    if (!i)
      return;
    const o = i.closest(".dtable-row");
    if (!o)
      return;
    const r = i == null ? void 0 : i.getAttribute("data-col"), l = o == null ? void 0 : o.getAttribute("data-id");
    if (!(typeof r != "string" || typeof l != "string"))
      return {
        cellElement: i,
        rowElement: o,
        colName: r,
        rowID: l,
        target: s
      };
  }
  i18n(t, s, i) {
    return as(m(this, ns), t, s, i, this.options.lang) ?? `{i18n:${t}}`;
  }
  render() {
    const t = N(this, Di, wa).call(this), { className: s, rowHover: i, colHover: o, cellHover: r, bordered: l, striped: a, scrollbarHover: h } = this.options, c = { width: t == null ? void 0 : t.width, height: t == null ? void 0 : t.height }, u = ["dtable", s, {
      "dtable-hover-row": i,
      "dtable-hover-col": o,
      "dtable-hover-cell": r,
      "dtable-bordered": l,
      "dtable-striped": a,
      "dtable-scrolled-down": ((t == null ? void 0 : t.scrollTop) ?? 0) > 0,
      "scrollbar-hover": h
    }], d = [];
    return t && m(this, Q).forEach((f) => {
      var g;
      const p = (g = f.onRender) == null ? void 0 : g.call(this, t);
      p && (p.style && Object.assign(c, p.style), p.className && u.push(p.className), p.children && d.push(p.children));
    }), /* @__PURE__ */ b(
      "div",
      {
        id: m(this, en),
        className: M(u),
        style: c,
        ref: this.ref,
        tabIndex: -1,
        children: [
          t && N(this, $i, pa).call(this, t),
          t && N(this, Ri, ma).call(this, t),
          t && N(this, ki, ga).call(this, t),
          t && N(this, Ti, ya).call(this, t)
        ]
      }
    );
  }
}, Ce = new WeakMap(), en = new WeakMap(), ne = new WeakMap(), St = new WeakMap(), se = new WeakMap(), Q = new WeakMap(), gt = new WeakMap(), Et = new WeakMap(), nn = new WeakMap(), es = new WeakMap(), ns = new WeakMap(), Wt = new WeakMap(), sn = new WeakMap(), on = new WeakMap(), $i = new WeakSet(), pa = function(t) {
  const { header: s, colsInfo: i, headerHeight: o, scrollLeft: r } = t;
  if (!s)
    return null;
  if (s === !0)
    return /* @__PURE__ */ b(
      Pf,
      {
        scrollLeft: r,
        height: o,
        onRenderCell: m(this, is),
        onRenderRow: m(this, Ni),
        ...i
      }
    );
  const l = Array.isArray(s) ? s : [s];
  return /* @__PURE__ */ b(
    yo,
    {
      className: "dtable-header",
      style: { height: o },
      renders: l,
      generateArgs: [t],
      generatorThis: this
    }
  );
}, Ri = new WeakSet(), ma = function(t) {
  const { headerHeight: s, rowsHeight: i, visibleRows: o, rowHeight: r, colsInfo: l, scrollLeft: a, scrollTop: h } = t;
  return /* @__PURE__ */ b(
    Df,
    {
      top: s,
      height: i,
      rows: o,
      rowHeight: r,
      scrollLeft: a,
      scrollTop: h,
      onRenderCell: m(this, is),
      onRenderRow: m(this, Ai),
      ...l
    }
  );
}, ki = new WeakSet(), ga = function(t) {
  const { footer: s } = t;
  if (!s)
    return null;
  const i = typeof s == "function" ? s.call(this, t) : Array.isArray(s) ? s : [s];
  return /* @__PURE__ */ b(
    yo,
    {
      className: "dtable-footer",
      style: { height: t.footerHeight, top: t.rowsHeight + t.headerHeight },
      renders: i,
      generateArgs: [t],
      generatorThis: this,
      generators: t.footerGenerators
    }
  );
}, Ti = new WeakSet(), ya = function(t) {
  const s = [], { scrollLeft: i, colsInfo: o, scrollTop: r, rowsHeight: l, rowsHeightTotal: a, footerHeight: h } = t, { scrollColsWidth: c, scrollWidth: u } = o, { scrollbarSize: d = 12, horzScrollbarPos: f } = this.options;
  return c > u && s.push(
    /* @__PURE__ */ b(
      sl,
      {
        type: "horz",
        scrollPos: i,
        scrollSize: c,
        clientSize: u,
        onScroll: m(this, os),
        left: o.fixedLeftWidth,
        bottom: (f === "inside" ? 0 : -d) + h,
        size: d,
        wheelContainer: this.ref
      },
      "horz"
    )
  ), a > l && s.push(
    /* @__PURE__ */ b(
      sl,
      {
        type: "vert",
        scrollPos: r,
        scrollSize: a,
        clientSize: l,
        onScroll: m(this, os),
        right: 0,
        size: d,
        top: t.headerHeight,
        wheelContainer: this.ref
      },
      "vert"
    )
  ), s.length ? s : null;
}, ss = new WeakSet(), Ro = function() {
  var t;
  R(this, ne, !1), (t = this.options.afterRender) == null || t.call(this), m(this, Q).forEach((s) => {
    var i;
    return (i = s.afterRender) == null ? void 0 : i.call(this);
  });
}, Ai = new WeakMap(), Ni = new WeakMap(), is = new WeakMap(), os = new WeakMap(), Li = new WeakMap(), Mi = new WeakMap(), Oi = new WeakSet(), _a = function() {
  if (m(this, St))
    return !1;
  const s = { ...ol(), ...m(this, se).reduce((i, o) => {
    const { defaultOptions: r } = o;
    return r && Object.assign(i, r), i;
  }, {}), ...this.props };
  return R(this, St, s), R(this, Q, m(this, se).reduce((i, o) => {
    const { when: r, options: l } = o;
    return (!r || r(s)) && (i.push(o), l && Object.assign(s, typeof l == "function" ? l.call(this, s) : l)), i;
  }, [])), R(this, ns, [this.options.i18n, ...this.plugins.map((i) => i.i18n)].filter(Boolean)), !0;
}, Pi = new WeakSet(), ba = function() {
  var Qo, Zo;
  const { plugins: t } = this;
  let s = m(this, St);
  const i = {
    flex: /* @__PURE__ */ b("div", { style: "flex:auto" }),
    divider: /* @__PURE__ */ b("div", { style: "width:1px;margin:var(--space);background:var(--color-border);height:50%" })
  };
  t.forEach((H) => {
    var Mt;
    const Y = (Mt = H.beforeLayout) == null ? void 0 : Mt.call(this, s);
    Y && (s = { ...s, ...Y }), Object.assign(i, H.footer);
  });
  const { defaultColWidth: o, minColWidth: r, maxColWidth: l } = s, a = [], h = [], c = [], u = {}, d = [], f = [];
  let p = 0, g = 0, y = 0;
  s.cols.forEach((H) => {
    if (H.hidden)
      return;
    const {
      name: Y,
      type: Mt = "",
      fixed: Ot = !1,
      flex: ge = !1,
      width: un = o,
      minWidth: hn = r,
      maxWidth: Xi = l,
      ...Ta
    } = H, B = {
      name: Y,
      type: Mt,
      setting: {
        name: Y,
        type: Mt,
        fixed: Ot,
        flex: ge,
        width: un,
        minWidth: hn,
        maxWidth: Xi,
        ...Ta
      },
      flex: Ot ? 0 : ge === !0 ? 1 : typeof ge == "number" ? ge : 0,
      left: 0,
      width: il(un, hn, Xi),
      realWidth: 0,
      visible: !0,
      index: d.length
    };
    t.forEach((tr) => {
      var er, nr;
      const ds = (er = tr.colTypes) == null ? void 0 : er[Mt];
      if (ds) {
        const sr = typeof ds == "function" ? ds(B) : ds;
        sr && Object.assign(B.setting, sr);
      }
      (nr = tr.onAddCol) == null || nr.call(this, B);
    }), B.width = il(B.setting.width ?? B.width, B.setting.minWidth ?? hn, B.setting.maxWidth ?? Xi), B.realWidth = B.realWidth || B.width, Ot === "left" ? (B.left = p, p += B.width, a.push(B)) : Ot === "right" ? (B.left = g, g += B.width, h.push(B)) : (B.left = y, y += B.width, c.push(B)), B.flex && f.push(B), d.push(B), u[B.name] = B;
  });
  let _ = s.width, v = 0;
  const S = p + y + g;
  if (typeof _ == "function" && (_ = _.call(this, S)), _ === "auto")
    v = S;
  else if (_ === "100%") {
    const { parent: H } = this;
    if (H)
      v = H.clientWidth;
    else {
      v = 0, R(this, ne, !0);
      return;
    }
  } else
    v = _ ?? 0;
  const { data: $, rowKey: T = "id", rowHeight: D } = s, L = [], O = (H, Y, Mt) => {
    var ge, un;
    const Ot = { data: Mt ?? { [T]: H }, id: H, index: L.length, top: 0 };
    if (Mt || (Ot.lazy = !0), L.push(Ot), ((ge = s.onAddRow) == null ? void 0 : ge.call(this, Ot, Y)) !== !1) {
      for (const hn of t)
        if (((un = hn.onAddRow) == null ? void 0 : un.call(this, Ot, Y)) === !1)
          return;
    }
  };
  if (typeof $ == "number")
    for (let H = 0; H < $; H++)
      O(`${H}`, H);
  else
    Array.isArray($) && $.forEach((H, Y) => {
      typeof H == "object" ? O(`${H[T] ?? ""}`, Y, H) : O(`${H ?? ""}`, Y);
    });
  let k = L;
  const j = {};
  if (s.onAddRows) {
    const H = s.onAddRows.call(this, k);
    H && (k = H);
  }
  for (const H of t) {
    const Y = (Qo = H.onAddRows) == null ? void 0 : Qo.call(this, k);
    Y && (k = Y);
  }
  k.forEach((H, Y) => {
    j[H.id] = H, H.index = Y, H.top = H.index * D;
  });
  const { header: P, footer: V } = s, F = P ? s.headerHeight || D : 0, G = V ? s.footerHeight || D : 0;
  let I = s.height, K = 0;
  const bt = k.length * D, de = F + G + bt;
  if (typeof I == "function" && (I = I.call(this, de)), I === "auto")
    K = de;
  else if (typeof I == "object")
    K = Math.min(I.max, Math.max(I.min, de));
  else if (I === "100%") {
    const { parent: H } = this;
    if (H)
      K = H.clientHeight;
    else {
      K = 0, R(this, ne, !0);
      return;
    }
  } else
    K = I;
  const pe = K - F - G, me = v - p - g, Lt = {
    options: s,
    allRows: L,
    width: v,
    height: K,
    rows: k,
    rowsMap: j,
    rowHeight: D,
    rowsHeight: pe,
    rowsHeightTotal: bt,
    header: P,
    footer: V,
    footerGenerators: i,
    headerHeight: F,
    footerHeight: G,
    colsMap: u,
    colsList: d,
    flexCols: f,
    colsInfo: {
      fixedLeftCols: a,
      fixedRightCols: h,
      scrollCols: c,
      fixedLeftWidth: p,
      scrollWidth: me,
      scrollColsWidth: y,
      fixedRightWidth: g
    }
  }, Ne = (Zo = s.onLayout) == null ? void 0 : Zo.call(this, Lt);
  Ne && Object.assign(Lt, Ne), t.forEach((H) => {
    if (H.onLayout) {
      const Y = H.onLayout.call(this, Lt);
      Y && Object.assign(Lt, Y);
    }
  }), R(this, gt, Lt);
}, Di = new WeakSet(), wa = function() {
  (N(this, Oi, _a).call(this) || !m(this, gt)) && N(this, Pi, ba).call(this);
  const { layout: t } = this;
  if (!t)
    return;
  let { scrollLeft: s } = this.state;
  const { flexCols: i, colsInfo: { scrollCols: o, scrollWidth: r, scrollColsWidth: l } } = t;
  if (i.length) {
    const S = r - l;
    if (S > 0) {
      const $ = i.reduce((D, L) => D + L.flex, 0);
      let T = 0;
      i.forEach((D) => {
        const L = Math.min(S - T, Math.ceil(S * (D.flex / $)));
        D.realWidth = L + D.width, T += D.realWidth;
      });
    } else
      i.forEach(($) => {
        $.realWidth = $.width;
      });
  }
  s = Math.min(Math.max(0, l - r), s);
  let a = 0;
  o.forEach((S) => {
    S.left = a, a += S.realWidth, S.visible = S.left + S.realWidth >= s && S.left <= s + r;
  });
  const { rowsHeightTotal: h, rowsHeight: c, rows: u, rowHeight: d } = t, f = Math.min(Math.max(0, h - c), this.state.scrollTop), p = Math.floor(f / d), g = f + c, y = Math.min(u.length, Math.ceil(g / d)), _ = [], { rowDataGetter: v } = this.options;
  for (let S = p; S < y; S++) {
    const $ = u[S];
    $.lazy && v && ($.data = v([$.id])[0], $.lazy = !1), _.push($);
  }
  return t.visibleRows = _, t.scrollTop = f, t.scrollLeft = s, t;
}, w(ws, "addPlugin", ha), w(ws, "removePlugin", fa), ws);
function rl(e, n) {
  n !== void 0 ? e.data.hoverCol = n : n = e.data.hoverCol;
  const { current: t } = e.ref;
  if (!t)
    return;
  const s = "dtable-col-hover";
  t.querySelectorAll(`.${s}`).forEach((i) => i.classList.remove(s)), typeof n == "string" && n.length && t.querySelectorAll(`.dtable-cell[data-col="${n}"]`).forEach((i) => i.classList.add(s));
}
const Wf = {
  name: "col-hover",
  defaultOptions: {
    colHover: !1
  },
  when: (e) => !!e.colHover,
  events: {
    mouseover(e) {
      var i;
      const { colHover: n } = this.options;
      if (!n)
        return;
      const t = (i = e.target) == null ? void 0 : i.closest(".dtable-cell");
      if (!t || n === "header" && !t.closest(".dtable-header"))
        return;
      const s = (t == null ? void 0 : t.getAttribute("data-col")) ?? !1;
      rl(this, s);
    },
    mouseleave() {
      rl(this, !1);
    }
  }
}, Ff = Nt(Wf, { buildIn: !0 });
function Bf(e, n) {
  var r, l;
  typeof e == "boolean" && (n = e, e = void 0);
  const t = this.state.checkedRows, s = {}, { canRowCheckable: i } = this.options, o = (a, h) => {
    i && !i.call(this, a) || !!t[a] === h || (h ? t[a] = !0 : delete t[a], s[a] = h);
  };
  if (e === void 0 ? (n === void 0 && (n = !va.call(this)), (r = this.layout) == null || r.allRows.forEach(({ id: a }) => {
    o(a, !!n);
  })) : (Array.isArray(e) || (e = [e]), e.forEach((a) => {
    o(a, n ?? !t[a]);
  })), Object.keys(s).length) {
    const a = (l = this.options.beforeCheckRows) == null ? void 0 : l.call(this, e, s, t);
    a && Object.keys(a).forEach((h) => {
      a[h] ? t[h] = !0 : delete t[h];
    }), this.setState({ checkedRows: { ...t } }, () => {
      var h;
      (h = this.options.onCheckChange) == null || h.call(this, s);
    });
  }
  return s;
}
function zf(e) {
  return this.state.checkedRows[e] ?? !1;
}
function va() {
  var t, s;
  const e = this.getChecks().length, { canRowCheckable: n } = this.options;
  return n ? e === ((t = this.layout) == null ? void 0 : t.allRows.reduce((i, o) => i + (n.call(this, o.id) ? 1 : 0), 0)) : e === ((s = this.layout) == null ? void 0 : s.allRows.length);
}
function Uf() {
  return Object.keys(this.state.checkedRows);
}
const Vf = {
  name: "checkable",
  defaultOptions: { checkable: !0 },
  when: (e) => !!e.checkable,
  state() {
    return { checkedRows: {} };
  },
  methods: {
    toggleCheckRows: Bf,
    isRowChecked: zf,
    isAllRowChecked: va,
    getChecks: Uf
  },
  i18n: {
    zh_cn: {
      checkedCountInfo: "已选择 {selected} 项",
      totalCountInfo: "共 {total} 项"
    },
    en: {
      checkedCountInfo: "Selected {selected} items",
      totalCountInfo: "Total {total} items"
    }
  },
  footer: {
    checkbox() {
      const e = this.isAllRowChecked();
      return [
        /* @__PURE__ */ b("div", { style: { padding: "0 calc(3 * var(--space))", display: "flex", alignItems: "center" }, onClick: () => this.toggleCheckRows(), children: /* @__PURE__ */ b("input", { type: "checkbox", checked: e }) })
      ];
    },
    checkedInfo(e, n) {
      const t = this.getChecks().length, s = [];
      return t && s.push(this.i18n("checkedCountInfo", { selected: t })), s.push(this.i18n("totalCountInfo", { total: n.allRows.length })), [
        /* @__PURE__ */ b("div", { children: s.join(", ") })
      ];
    }
  },
  onRenderCell(e, { row: n, col: t }) {
    var l;
    const { id: s } = n, { canRowCheckable: i } = this.options;
    if (i && !i.call(this, s))
      return e;
    const { checkbox: o } = t.setting;
    if (typeof o == "function" ? o.call(this, s) : o) {
      const a = this.isRowChecked(s), h = ((l = this.options.checkboxRender) == null ? void 0 : l.call(this, a, s)) ?? /* @__PURE__ */ b("input", { type: "checkbox", checked: a });
      e.unshift(h), e.push({ className: "has-checkbox" });
    }
    return e;
  },
  onRenderHeaderCell(e, { row: n, col: t }) {
    var r;
    const { id: s } = n, { checkbox: i } = t.setting;
    if (typeof i == "function" ? i.call(this, s) : i) {
      const l = this.isAllRowChecked(), a = ((r = this.options.checkboxRender) == null ? void 0 : r.call(this, l, s)) ?? /* @__PURE__ */ b("input", { type: "checkbox", checked: l });
      e.unshift(a), e.push({ className: "has-checkbox" });
    }
    return e;
  },
  onRenderRow({ props: e, row: n }) {
    if (this.isRowChecked(n.id))
      return { className: M(e.className, "is-checked") };
  },
  onHeaderCellClick(e) {
    const n = e.target;
    if (!n)
      return;
    const t = n.closest('input[type="checkbox"],.dtable-checkbox');
    t && (this.toggleCheckRows(t.checked), e.stopPropagation());
  },
  onRowClick(e, { rowID: n }) {
    const t = e.target;
    if (!t)
      return;
    (t.closest('input[type="checkbox"],.dtable-checkbox') || this.options.checkOnClickRow) && this.toggleCheckRows(n);
  }
}, qf = Nt(Vf);
var xa = /* @__PURE__ */ ((e) => (e.unknown = "", e.collapsed = "collapsed", e.expanded = "expanded", e.hidden = "hidden", e.normal = "normal", e))(xa || {});
function ko(e) {
  const n = this.data.nestedMap.get(e);
  if (!n || n.state !== "")
    return n ?? { state: "normal", level: -1 };
  if (!n.parent && !n.children)
    return n.state = "normal", n;
  const t = this.state.collapsedRows, s = n.children && t && t[e];
  let i = !1, { parent: o } = n;
  for (; o; ) {
    const r = ko.call(this, o);
    if (r.state !== "expanded") {
      i = !0;
      break;
    }
    o = r.parent;
  }
  return n.state = i ? "hidden" : s ? "collapsed" : n.children ? "expanded" : "normal", n.level = n.parent ? ko.call(this, n.parent).level + 1 : 0, n;
}
function Gf(e, n) {
  let t = this.state.collapsedRows ?? {};
  const { nestedMap: s } = this.data;
  if (e === "HEADER")
    if (n === void 0 && (n = !Sa.call(this)), n) {
      const i = s.entries();
      for (const [o, r] of i)
        r.state === "expanded" && (t[o] = !0);
    } else
      t = {};
  else {
    const i = Array.isArray(e) ? e : [e];
    n === void 0 && (n = !t[i[0]]), i.forEach((o) => {
      const r = s.get(o);
      n && (r != null && r.children) ? t[o] = !0 : delete t[o];
    });
  }
  this.update({
    dirtyType: "layout",
    state: { collapsedRows: { ...t } }
  }, () => {
    var i;
    (i = this.options.onNestedChange) == null || i.call(this);
  });
}
function Sa() {
  const e = this.data.nestedMap.values();
  for (const n of e)
    if (n.state === "expanded")
      return !1;
  return !0;
}
function Ea(e, n = 0, t, s = 0) {
  var i;
  t || (t = [...e.keys()]);
  for (const o of t) {
    const r = e.get(o);
    r && (r.level === s && (r.order = n++), (i = r.children) != null && i.length && (n = Ea(e, n, r.children, s + 1)));
  }
  return n;
}
function Ca(e, n, t, s) {
  const i = e.getNestedRowInfo(n);
  return !i || i.state === "" || !i.children || i.children.forEach((o) => {
    s[o] = t, Ca(e, o, t, s);
  }), i;
}
function $a(e, n, t, s, i) {
  var l;
  const o = e.getNestedRowInfo(n);
  if (!o || o.state === "")
    return;
  ((l = o.children) == null ? void 0 : l.every((a) => {
    const h = !!(s[a] !== void 0 ? s[a] : i[a]);
    return t === h;
  })) && (s[n] = t), o.parent && $a(e, o.parent, t, s, i);
}
const Kf = {
  name: "nested",
  defaultOptions: {
    nested: !0,
    nestedParentKey: "parent",
    asParentKey: "asParent",
    nestedIndent: 20,
    canSortTo(e, n) {
      const { nestedMap: t } = this.data, s = t.get(e.id), i = t.get(n.id);
      return (s == null ? void 0 : s.parent) === (i == null ? void 0 : i.parent);
    },
    beforeCheckRows(e, n, t) {
      if (!this.options.checkable || !(e != null && e.length))
        return;
      const s = {};
      return Object.entries(n).forEach(([i, o]) => {
        const r = Ca(this, i, o, s);
        r != null && r.parent && $a(this, r.parent, o, s, t);
      }), s;
    }
  },
  when: (e) => !!e.nested,
  data() {
    return { nestedMap: /* @__PURE__ */ new Map() };
  },
  methods: {
    toggleRow: Gf,
    isAllCollapsed: Sa,
    getNestedRowInfo: ko
  },
  beforeLayout() {
    this.data.nestedMap.clear();
  },
  onAddRow(e) {
    var i, o;
    const { nestedMap: n } = this.data, t = (i = e.data) == null ? void 0 : i[this.options.nestedParentKey ?? "parent"], s = n.get(e.id) ?? {
      state: "",
      level: 0
    };
    if (s.parent = t, (o = e.data) != null && o[this.options.asParentKey ?? "asParent"] && (s.children = []), n.set(e.id, s), t) {
      let r = n.get(t);
      r || (r = {
        state: "",
        level: 0
      }, n.set(t, r)), r.children || (r.children = []), r.children.push(e.id);
    }
  },
  onAddRows(e) {
    return e = e.filter(
      (n) => this.getNestedRowInfo(n.id).state !== "hidden"
      /* hidden */
    ), Ea(this.data.nestedMap), e.sort((n, t) => {
      const s = this.getNestedRowInfo(n.id), i = this.getNestedRowInfo(t.id), o = (s.order ?? 0) - (i.order ?? 0);
      return o === 0 ? n.index - t.index : o;
    }), e;
  },
  onRenderCell(e, { col: n, row: t }) {
    var l;
    const { id: s, data: i } = t, { nestedToggle: o } = n.setting, r = this.getNestedRowInfo(s);
    if (o && (r.children || r.parent) && e.unshift(((l = this.options.onRenderNestedToggle) == null ? void 0 : l.call(this, r, s, n, i)) ?? /* @__PURE__ */ b("a", { role: "button", className: `dtable-nested-toggle state${r.children ? "" : " is-no-child"}`, children: /* @__PURE__ */ b("span", { className: "toggle-icon" }) })), r.level) {
      let { nestedIndent: a = o } = n.setting;
      a && (a === !0 && (a = this.options.nestedIndent ?? 12), e.unshift(/* @__PURE__ */ b("div", { className: "dtable-nested-indent", style: { width: a * r.level + "px" } })));
    }
    return e;
  },
  onRenderHeaderCell(e, { row: n, col: t }) {
    var i;
    const { id: s } = n;
    return t.setting.nestedToggle && e.unshift(((i = this.options.onRenderNestedToggle) == null ? void 0 : i.call(this, void 0, s, t, void 0)) ?? /* @__PURE__ */ b("a", { type: "button", className: "dtable-nested-toggle state", children: /* @__PURE__ */ b("span", { className: "toggle-icon" }) })), e;
  },
  onRenderRow({ props: e, row: n }) {
    const t = this.getNestedRowInfo(n.id);
    return {
      className: M(e.className, `is-${t.state}`),
      "data-parent": t.parent
    };
  },
  onRenderHeaderRow({ props: e }) {
    return e.className = M(e.className, `is-${this.isAllCollapsed() ? "collapsed" : "expanded"}`), e;
  },
  onHeaderCellClick(e) {
    const n = e.target;
    if (!(!n || !n.closest(".dtable-nested-toggle")))
      return this.toggleRow("HEADER"), !0;
  },
  onCellClick(e, { rowID: n }) {
    const t = e.target;
    if (!(!t || !this.getNestedRowInfo(n).children || !t.closest(".dtable-nested-toggle")))
      return this.toggleRow(n), !0;
  }
}, Yf = Nt(Kf);
const Xf = {
  name: "rich",
  colTypes: {
    html: {
      onRenderCell(e) {
        return e[0] = {
          html: e[0]
        }, e;
      }
    },
    link: {
      onRenderCell(e, { col: n, row: t }) {
        const { linkTemplate: s = "", linkProps: i } = n.setting, o = tt(s, t.data);
        return e[0] = /* @__PURE__ */ b("a", { href: o, ...i, children: e[0] }), e;
      }
    },
    avatar: {
      onRenderCell(e, { col: n, row: t }) {
        const { data: s } = t, { avatarWithName: i, avatarClass: o = "size-xs circle", avatarKey: r = `${n.name}Avatar` } = n.setting, l = /* @__PURE__ */ b("div", { className: `avatar ${o} flex-none`, children: /* @__PURE__ */ b("img", { src: s ? s[r] : "" }) });
        return i ? e.unshift(l) : e[0] = l, e;
      }
    },
    circleProgress: {
      align: "center",
      onRenderCell(e, { col: n }) {
        const { circleSize: t = 24, circleBorderSize: s = 1, circleBgColor: i = "var(--color-border)", circleColor: o = "var(--color-success-500)" } = n.setting, r = (t - s) / 2, l = t / 2, a = e[0];
        return e[0] = /* @__PURE__ */ b("svg", { width: t, height: t, children: [
          /* @__PURE__ */ b("circle", { cx: l, cy: l, r, "stroke-width": s, stroke: i, fill: "transparent" }),
          /* @__PURE__ */ b("circle", { cx: l, cy: l, r, "stroke-width": s, stroke: o, fill: "transparent", "stroke-linecap": "round", "stroke-dasharray": Math.PI * r * 2, "stroke-dashoffset": Math.PI * r * 2 * (100 - a) / 100, style: { transformOrigin: "center", transform: "rotate(-90deg)" } }),
          /* @__PURE__ */ b("text", { x: l, y: l + s, "dominant-baseline": "middle", "text-anchor": "middle", style: { fontSize: `${r}px` }, children: Math.round(a) })
        ] }), e;
      }
    },
    actionButtons: {
      onRenderCell(e, { col: n, row: t }) {
        var l;
        const s = (l = t.data) == null ? void 0 : l[n.name];
        if (!s)
          return e;
        const { actionBtnTemplate: i = '<button type="button" data-action="{action}" title="{title}" class="{className}"><i class="icon icon-{icon}"></i></button>', actionBtnData: o = {}, actionBtnClass: r = "btn text-primary square size-sm ghost" } = n.setting;
        return [{
          html: s.map((a) => {
            typeof a == "string" && (a = { action: a });
            const h = o[a.action];
            return h && (a = { className: r, ...h, ...a }), tt(i, a);
          }).join(" ")
        }];
      }
    },
    format: {
      onRenderCell(e, { col: n }) {
        let { format: t } = n.setting;
        if (!t)
          return e;
        typeof t == "string" && (t = { type: "text", format: t });
        const { format: s, type: i } = t, o = e[0];
        return typeof s == "function" ? e[0] = i === "html" ? { html: s(o) } : s(o) : i === "datetime" ? e[0] = So(o, s) : i === "html" ? e[0] = { html: tt(s, o) } : e[0] = tt(s, o), e;
      }
    }
  }
}, Jf = Nt(Xf, { buildIn: !0 }), Qf = {
  name: "sort-type",
  onRenderHeaderCell(e, { col: n }) {
    const { sortType: t } = n.setting;
    if (t) {
      const { sortLink: s = this.options.sortLink, sortAttrs: i } = n.setting, o = t === !0 ? "none" : t;
      if (e.push(
        /* @__PURE__ */ b("div", { className: `dtable-sort dtable-sort-${o}` }),
        { outer: !0, attrs: { "data-sort": o } }
      ), s) {
        const r = typeof s == "function" ? s.call(this, n, o) : s;
        e.push(
          { tagName: "a", attrs: { href: r, ...i } }
        );
      }
    }
    return e;
  }
}, Zf = Nt(Qf, { buildIn: !0 }), td = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  NestedRowState: xa,
  checkable: qf,
  colHover: Ff,
  nested: Yf,
  rich: Jf,
  sortType: Zf
}, Symbol.toStringTag, { value: "Module" }));
class fn extends J {
}
w(fn, "NAME", "dtable"), w(fn, "Component", jf), w(fn, "definePlugin", Nt), w(fn, "removePlugin", fa), w(fn, "plugins", td);
function ed(e) {
  const [n, t] = e.split(":"), s = n[0] === "-" ? { name: n.substring(1), disabled: !0 } : { name: n };
  return t != null && t.length && (s.type = "dropdown", s.items = t.split(",").reduce((i, o) => (o = o.trim(), o.length && i.push(o[0] === "-" ? { name: o.substring(1), disabled: !0 } : { name: o }), i), [])), s;
}
const nd = (e, n) => {
  var t;
  return e.url && (e.url = tt(e.url, n.row.data)), (t = e.dropdown) != null && t.items && (e.dropdown.items = e.dropdown.items.map((s) => (s.url && (s.url = tt(s.url, n.row.data)), s))), e;
}, sd = {
  name: "actions",
  colTypes: {
    actions: {
      onRenderCell(e, n) {
        var c;
        const { row: t, col: s } = n;
        let i = (c = t.data) == null ? void 0 : c[s.name];
        if (typeof i == "string" && (i = i.split("|")), !(i != null && i.length))
          return e;
        const { actionsSetting: o, actionsMap: r, actionsCreator: l = this.options.actionsCreator, actionItemCreator: a = this.options.actionItemCreator || nd } = s.setting, h = {
          items: (l == null ? void 0 : l(n)) ?? i.map((u) => {
            if (u = typeof u == "string" ? ed(u) : u, !u)
              return;
            const { name: d, items: f, ...p } = u;
            if (r && d && (Object.assign(p, r[d], { ...p }), typeof p.buildProps == "function")) {
              const { buildProps: g } = p;
              delete p.buildProps, Object.assign(p, g(e, n));
            }
            if (f && p.type === "dropdown") {
              const { dropdown: g = {} } = p;
              g.menu = {
                className: "menu-dtable-actions",
                items: f.reduce((y, _) => {
                  const v = typeof _ == "string" ? { name: _ } : { ..._ };
                  return v != null && v.name && (r && "name" in v && Object.assign(v, r[v.name], { ...v }), y.push(v)), y;
                }, [])
              }, p.dropdown = g;
            }
            return a ? a(p, n) : p;
          }).filter(Boolean),
          btnProps: { size: "sm", className: "text-primary" },
          ...o
        };
        return e[0] = /* @__PURE__ */ b(ae, { ...h }), e;
      }
    }
  }
}, id = Nt(sd), od = {
  name: "toolbar",
  footer: {
    toolbar() {
      const { footToolbar: e } = this.options;
      return [e ? /* @__PURE__ */ b(ae, { ...e }) : null];
    }
  }
}, rd = Nt(od), ld = {
  name: "pager",
  footer: {
    pager() {
      const { footPager: e } = this.options;
      return [e ? /* @__PURE__ */ b(Ic, { ...e }) : null];
    }
  }
}, cd = Nt(ld);
const ad = {
  name: "zentao",
  plugins: ["checkable", "nested", id, rd, cd],
  defaultOptions: {
    footer: ["checkbox", "checkedInfo"],
    colHover: !1,
    rowHeight: 36,
    filterable: !0,
    striped: !1,
    responsive: !0,
    checkable: !1,
    nested: !1,
    height: (e) => {
      var n, t;
      return Math.min(e, window.innerHeight - 1 - (((n = document.getElementById("header")) == null ? void 0 : n.clientHeight) ?? 0) - (((t = document.getElementById("mainMenu")) == null ? void 0 : t.clientHeight) ?? 0));
    }
  },
  colTypes: {
    status: {
      width: 80,
      align: "center",
      sortType: !0,
      onRenderCell(e, { col: n, row: t }) {
        var r, l;
        const s = (r = t.data) == null ? void 0 : r[n.name];
        let i, o;
        return typeof s == "string" ? (i = s, o = (l = n.setting.statusMap) == null ? void 0 : l[s]) : typeof s == "object" && s && ({ name: i, label: o } = s), e[0] = /* @__PURE__ */ E("span", { class: `${n.setting.statusClassPrefix ?? "status-"}${i}` }, o ?? i), e;
      }
    },
    avatarBtn: {
      width: 100,
      sortType: !0,
      onRenderCell(e, { col: n, row: t }) {
        const { data: s } = t, i = s ? s[n.name] : void 0;
        if (!(i != null && i.length))
          return e;
        const { avatarClass: o = "circle", avatarKey: r = `${n.name}Avatar`, avatarSetting: l, avatarCodeKey: a, avatarNameKey: h = `${n.name}Name`, avatarBtnProps: c } = n.setting, u = (s ? s[h] : i) || e[0], d = {
          size: "xs",
          className: M(o, l == null ? void 0 : l.className, "flex-none"),
          src: s ? s[r] : void 0,
          text: u,
          code: a ? s ? s[a] : void 0 : i,
          ...l
        }, f = typeof c == "function" ? c(e, n, t) : c || {};
        return e[0] = /* @__PURE__ */ E("button", { type: "button", className: "btn btn-avatar", ...f }, /* @__PURE__ */ E(Nc, { ...d }), /* @__PURE__ */ E("div", null, u)), e;
      }
    }
  },
  onRenderCell(e, { row: n, col: t }) {
    const { iconRender: s } = t.setting;
    if (typeof s != "function")
      return e;
    const i = s(n);
    return i && e.unshift(typeof i == "object" ? /* @__PURE__ */ E("i", { ...i }) : /* @__PURE__ */ E("i", { className: i })), e;
  }
}, Fd = Nt(ad, { buildIn: !0 });
function Ra(e) {
  e = e || location.search, e[0] === "?" && (e = e.substring(1));
  try {
    return JSON.parse('{"' + decodeURI(e).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
  } catch {
    return {};
  }
}
function ud(e) {
  if (!e)
    return { url: e };
  const { config: n } = window;
  if (/^https?:\/\//.test(e)) {
    const a = window.location.origin;
    if (!e.includes(a))
      return { external: !0, url: e };
    e = e.substring((a + n.webRoot).length);
  }
  const t = e.split("#"), s = t[0].split("?"), i = s[1], o = i ? Ra(i) : {};
  let r = s[0];
  const l = {
    url: e,
    isOnlyBody: o.onlybody === "yes",
    vars: [],
    hash: t[1] || "",
    params: o,
    tid: o.tid || ""
  };
  if (n.requestType === "GET") {
    l.moduleName = o[n.moduleVar] || "index", l.methodName = o[n.methodVar] || "index", l.viewType = o[n.viewVar] || n.defaultView;
    for (const a in o)
      a !== n.moduleVar && a !== n.methodVar && a !== n.viewVar && a !== "onlybody" && a !== "tid" && l.vars.push([a, o[a]]);
  } else {
    let a = r.lastIndexOf("/");
    a === r.length - 1 && (r = r.substring(0, a), a = r.lastIndexOf("/")), a >= 0 && (r = r.substring(a + 1));
    const h = r.lastIndexOf(".");
    h >= 0 ? (l.viewType = r.substring(h + 1), r = r.substring(0, h)) : l.viewType = n.defaultView;
    const c = r.split(n.requestFix);
    if (l.moduleName = c[0] || "index", l.methodName = c[1] || "index", c.length > 2)
      for (let u = 2; u < c.length; u++)
        l.vars.push(["", c[u]]), o["$" + (u - 1)] = c[u];
  }
  return l;
}
function ka(e, n, t, s, i, o, r, l) {
  if (typeof e == "object")
    return ka(e.moduleName, e.methodName, e.vars, e.viewType, e.isOnlyBody, e.hash, e.tid, e.params);
  l && l.isOnlyBody !== void 0 && i === void 0 && (i = !!l.isOnlyBody);
  const a = window.config;
  if (s || (s = a.defaultView), i || (i = !1), t) {
    typeof t == "string" && (t = t.split("&"));
    for (let u = 0; u < t.length; u++) {
      const d = t[u];
      if (typeof d == "string") {
        const f = d.split("=");
        t[u] = [f.shift(), f.join("=")];
      }
    }
  }
  const h = [], c = a.requestType === "GET";
  if (c) {
    if (h.push(a.router, "?", a.moduleVar, "=", e, "&", a.methodVar, "=", n), t)
      for (let u = 0; u < t.length; u++)
        h.push("&", t[u][0], "=", t[u][1]);
    h.push("&", a.viewVar, "=", s);
  } else {
    if (a.requestType == "PATH_INFO" && h.push(a.webRoot, e, a.requestFix, n), a.requestType == "PATH_INFO2" && h.push(a.webRoot, "index.php/", e, a.requestFix, n), t)
      for (let u = 0; u < t.length; u++)
        h.push(a.requestFix + t[u][1]);
    h.push(".", s);
  }
  return (a.onlybody === "yes" || i) && h.push(c ? "&" : "?", "onlybody=yes"), l && Object.keys(l).forEach((u) => {
    const d = l[u];
    u === "tid" || u === "isOnlyBody" || u[0] === "$" || h.push(!c && !h.includes("?") ? "?" : "&", u, "=", d);
  }), r && a.tabSession && h.push(!c && !h.includes("?") ? "?" : "&", "tid=", r), typeof o == "string" && h.push(o.startsWith("#") ? "" : "#", o), h.join("");
}
const hd = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  createLink: ka,
  parseLink: ud,
  parseUrlParams: Ra
}, Symbol.toStringTag, { value: "Module" })), ro = /* @__PURE__ */ new Map();
function Bd(e, n, t) {
  const { zui: s } = window;
  ro.size || Object.keys(s).forEach((o) => {
    o[0] === o[0].toUpperCase() && ro.set(o.toLowerCase(), s[o]);
  });
  const i = ro.get(e.toLowerCase());
  return i ? new i(n, t) : null;
}
window.$ && Object.assign(window.$, hd);
export {
  A as $,
  mr as ActionMenu,
  yr as ActionMenuNested,
  Of as AjaxForm,
  jr as Avatar,
  Wr as BtnGroup,
  _r as Button,
  lt as ContextMenu,
  fn as DTable,
  st as Dropdown,
  Hi as EventBus,
  br as Menu,
  tl as MenuTree,
  mn as Messager,
  nt as Modal,
  Cn as ModalTrigger,
  Fr as Nav,
  _n as NavTabs,
  Ur as Pager,
  qr as Picker,
  Mr as ProgressCircle,
  el as QuickMenu,
  nl as SearchForm,
  Or as Switch,
  Pt as TIME_DAY,
  Gr as Toolbar,
  ht as Tooltip,
  Va as addI18nMap,
  Cd as ajax,
  Ed as browser,
  $d as bus,
  zr as calculateTimestamp,
  qu as cash,
  ro as componentsMap,
  pd as convertBytes,
  Bd as create,
  at as createDate,
  dd as formatBytes,
  So as formatDate,
  Od as formatDateSpan,
  tt as formatString,
  za as getLangCode,
  Pd as getTimeBeforeDesc,
  as as i18n,
  Md as isDBY,
  Qi as isObject,
  hs as isSameDay,
  ef as isSameMonth,
  Td as isSameWeek,
  Br as isSameYear,
  Ad as isToday,
  Ld as isTomorrow,
  Nd as isYesterday,
  go as mergeDeep,
  mo as nativeEvents,
  Ua as setLangCode,
  Th as store,
  Fd as zentao,
  ad as zentaoPlugin
};
