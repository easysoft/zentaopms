var gr = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
};
var D = (t, e, n) => (gr(t, e, "read from private field"), n ? n.call(t) : e.get(t)), O = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, F = (t, e, n, s) => (gr(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n);
var ot = (t, e, n) => (gr(t, e, "access private method"), n);
const Xt = document, ui = window, jl = Xt.documentElement, je = Xt.createElement.bind(Xt), Ul = je("div"), mr = je("table"), Fu = je("tbody"), Qa = je("tr"), { isArray: qi, prototype: ql } = Array, { concat: ju, filter: Ro, indexOf: Vl, map: Gl, push: Uu, slice: Yl, some: Ao, splice: qu } = ql, Vu = /^#(?:[\w-]|\\.|[^\x00-\xa0])*$/, Gu = /^\.(?:[\w-]|\\.|[^\x00-\xa0])*$/, Yu = /<.+>/, Ku = /^\w+$/;
function No(t, e) {
  const n = Xu(e);
  return !t || !n && !Ie(e) && !K(e) ? [] : !n && Gu.test(t) ? e.getElementsByClassName(t.slice(1).replace(/\\/g, "")) : !n && Ku.test(t) ? e.getElementsByTagName(t) : e.querySelectorAll(t);
}
class Vi {
  constructor(e, n) {
    if (!e)
      return;
    if (Lr(e))
      return e;
    let s = e;
    if (rt(e)) {
      const i = n || Xt;
      if (s = Vu.test(e) && Ie(i) ? i.getElementById(e.slice(1).replace(/\\/g, "")) : Yu.test(e) ? Jl(e) : Lr(i) ? i.find(e) : rt(i) ? g(i).find(e) : No(e, i), !s)
        return;
    } else if (Ue(e))
      return this.ready(e);
    (s.nodeType || s === ui) && (s = [s]), this.length = s.length;
    for (let i = 0, r = this.length; i < r; i++)
      this[i] = s[i];
  }
  init(e, n) {
    return new Vi(e, n);
  }
}
const S = Vi.prototype, g = S.init;
g.fn = g.prototype = S;
S.length = 0;
S.splice = qu;
typeof Symbol == "function" && (S[Symbol.iterator] = ql[Symbol.iterator]);
function Lr(t) {
  return t instanceof Vi;
}
function Sn(t) {
  return !!t && t === t.window;
}
function Ie(t) {
  return !!t && t.nodeType === 9;
}
function Xu(t) {
  return !!t && t.nodeType === 11;
}
function K(t) {
  return !!t && t.nodeType === 1;
}
function Ju(t) {
  return !!t && t.nodeType === 3;
}
function Zu(t) {
  return typeof t == "boolean";
}
function Ue(t) {
  return typeof t == "function";
}
function rt(t) {
  return typeof t == "string";
}
function ut(t) {
  return t === void 0;
}
function cs(t) {
  return t === null;
}
function Kl(t) {
  return !isNaN(parseFloat(t)) && isFinite(t);
}
function Lo(t) {
  if (typeof t != "object" || t === null)
    return !1;
  const e = Object.getPrototypeOf(t);
  return e === null || e === Object.prototype;
}
g.isWindow = Sn;
g.isFunction = Ue;
g.isArray = qi;
g.isNumeric = Kl;
g.isPlainObject = Lo;
function tt(t, e, n) {
  if (n) {
    let s = t.length;
    for (; s--; )
      if (e.call(t[s], s, t[s]) === !1)
        return t;
  } else if (Lo(t)) {
    const s = Object.keys(t);
    for (let i = 0, r = s.length; i < r; i++) {
      const o = s[i];
      if (e.call(t[o], o, t[o]) === !1)
        return t;
    }
  } else
    for (let s = 0, i = t.length; s < i; s++)
      if (e.call(t[s], s, t[s]) === !1)
        return t;
  return t;
}
g.each = tt;
S.each = function(t) {
  return tt(this, t);
};
S.empty = function() {
  return this.each((t, e) => {
    for (; e.firstChild; )
      e.removeChild(e.firstChild);
  });
};
function di(...t) {
  const e = Zu(t[0]) ? t.shift() : !1, n = t.shift(), s = t.length;
  if (!n)
    return {};
  if (!s)
    return di(e, g, n);
  for (let i = 0; i < s; i++) {
    const r = t[i];
    for (const o in r)
      e && (qi(r[o]) || Lo(r[o])) ? ((!n[o] || n[o].constructor !== r[o].constructor) && (n[o] = new r[o].constructor()), di(e, n[o], r[o])) : n[o] = r[o];
  }
  return n;
}
g.extend = di;
S.extend = function(t) {
  return di(S, t);
};
const Qu = /\S+/g;
function Gi(t) {
  return rt(t) ? t.match(Qu) || [] : [];
}
S.toggleClass = function(t, e) {
  const n = Gi(t), s = !ut(e);
  return this.each((i, r) => {
    K(r) && tt(n, (o, a) => {
      s ? e ? r.classList.add(a) : r.classList.remove(a) : r.classList.toggle(a);
    });
  });
};
S.addClass = function(t) {
  return this.toggleClass(t, !0);
};
S.removeAttr = function(t) {
  const e = Gi(t);
  return this.each((n, s) => {
    K(s) && tt(e, (i, r) => {
      s.removeAttribute(r);
    });
  });
};
function td(t, e) {
  if (t) {
    if (rt(t)) {
      if (arguments.length < 2) {
        if (!this[0] || !K(this[0]))
          return;
        const n = this[0].getAttribute(t);
        return cs(n) ? void 0 : n;
      }
      return ut(e) ? this : cs(e) ? this.removeAttr(t) : this.each((n, s) => {
        K(s) && s.setAttribute(t, e);
      });
    }
    for (const n in t)
      this.attr(n, t[n]);
    return this;
  }
}
S.attr = td;
S.removeClass = function(t) {
  return arguments.length ? this.toggleClass(t, !1) : this.attr("class", "");
};
S.hasClass = function(t) {
  return !!t && Ao.call(this, (e) => K(e) && e.classList.contains(t));
};
S.get = function(t) {
  return ut(t) ? Yl.call(this) : (t = Number(t), this[t < 0 ? t + this.length : t]);
};
S.eq = function(t) {
  return g(this.get(t));
};
S.first = function() {
  return this.eq(0);
};
S.last = function() {
  return this.eq(-1);
};
function ed(t) {
  return ut(t) ? this.get().map((e) => K(e) || Ju(e) ? e.textContent : "").join("") : this.each((e, n) => {
    K(n) && (n.textContent = t);
  });
}
S.text = ed;
function Jt(t, e, n) {
  if (!K(t))
    return;
  const s = ui.getComputedStyle(t, null);
  return n ? s.getPropertyValue(e) || void 0 : s[e] || t.style[e];
}
function Tt(t, e) {
  return parseInt(Jt(t, e), 10) || 0;
}
function tl(t, e) {
  return Tt(t, `border${e ? "Left" : "Top"}Width`) + Tt(t, `padding${e ? "Left" : "Top"}`) + Tt(t, `padding${e ? "Right" : "Bottom"}`) + Tt(t, `border${e ? "Right" : "Bottom"}Width`);
}
const yr = {};
function nd(t) {
  if (yr[t])
    return yr[t];
  const e = je(t);
  Xt.body.insertBefore(e, null);
  const n = Jt(e, "display");
  return Xt.body.removeChild(e), yr[t] = n !== "none" ? n : "block";
}
function el(t) {
  return Jt(t, "display") === "none";
}
function Xl(t, e) {
  const n = t && (t.matches || t.webkitMatchesSelector || t.msMatchesSelector);
  return !!n && !!e && n.call(t, e);
}
function Yi(t) {
  return rt(t) ? (e, n) => Xl(n, t) : Ue(t) ? t : Lr(t) ? (e, n) => t.is(n) : t ? (e, n) => n === t : () => !1;
}
S.filter = function(t) {
  const e = Yi(t);
  return g(Ro.call(this, (n, s) => e.call(n, s, n)));
};
function xe(t, e) {
  return e ? t.filter(e) : t;
}
S.detach = function(t) {
  return xe(this, t).each((e, n) => {
    n.parentNode && n.parentNode.removeChild(n);
  }), this;
};
const sd = /^\s*<(\w+)[^>]*>/, id = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, nl = {
  "*": Ul,
  tr: Fu,
  td: Qa,
  th: Qa,
  thead: mr,
  tbody: mr,
  tfoot: mr
};
function Jl(t) {
  if (!rt(t))
    return [];
  if (id.test(t))
    return [je(RegExp.$1)];
  const e = sd.test(t) && RegExp.$1, n = nl[e] || nl["*"];
  return n.innerHTML = t, g(n.childNodes).detach().get();
}
g.parseHTML = Jl;
S.has = function(t) {
  const e = rt(t) ? (n, s) => No(t, s).length : (n, s) => s.contains(t);
  return this.filter(e);
};
S.not = function(t) {
  const e = Yi(t);
  return this.filter((n, s) => (!rt(t) || K(s)) && !e.call(s, n, s));
};
function ee(t, e, n, s) {
  const i = [], r = Ue(e), o = s && Yi(s);
  for (let a = 0, l = t.length; a < l; a++)
    if (r) {
      const h = e(t[a]);
      h.length && Uu.apply(i, h);
    } else {
      let h = t[a][e];
      for (; h != null && !(s && o(-1, h)); )
        i.push(h), h = n ? h[e] : null;
    }
  return i;
}
function Zl(t) {
  return t.multiple && t.options ? ee(Ro.call(t.options, (e) => e.selected && !e.disabled && !e.parentNode.disabled), "value") : t.value || "";
}
function rd(t) {
  return arguments.length ? this.each((e, n) => {
    const s = n.multiple && n.options;
    if (s || oc.test(n.type)) {
      const i = qi(t) ? Gl.call(t, String) : cs(t) ? [] : [String(t)];
      s ? tt(n.options, (r, o) => {
        o.selected = i.indexOf(o.value) >= 0;
      }, !0) : n.checked = i.indexOf(n.value) >= 0;
    } else
      n.value = ut(t) || cs(t) ? "" : t;
  }) : this[0] && Zl(this[0]);
}
S.val = rd;
S.is = function(t) {
  const e = Yi(t);
  return Ao.call(this, (n, s) => e.call(n, s, n));
};
g.guid = 1;
function Dt(t) {
  return t.length > 1 ? Ro.call(t, (e, n, s) => Vl.call(s, e) === n) : t;
}
g.unique = Dt;
S.add = function(t, e) {
  return g(Dt(this.get().concat(g(t, e).get())));
};
S.children = function(t) {
  return xe(g(Dt(ee(this, (e) => e.children))), t);
};
S.parent = function(t) {
  return xe(g(Dt(ee(this, "parentNode"))), t);
};
S.index = function(t) {
  const e = t ? g(t)[0] : this[0], n = t ? this : g(e).parent().children();
  return Vl.call(n, e);
};
S.closest = function(t) {
  const e = this.filter(t);
  if (e.length)
    return e;
  const n = this.parent();
  return n.length ? n.closest(t) : e;
};
S.siblings = function(t) {
  return xe(g(Dt(ee(this, (e) => g(e).parent().children().not(e)))), t);
};
S.find = function(t) {
  return g(Dt(ee(this, (e) => No(t, e))));
};
const od = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, ad = /^$|^module$|\/(java|ecma)script/i, ld = ["type", "src", "nonce", "noModule"];
function cd(t, e) {
  const n = g(t);
  n.filter("script").add(n.find("script")).each((s, i) => {
    if (ad.test(i.type) && jl.contains(i)) {
      const r = je("script");
      r.text = i.textContent.replace(od, ""), tt(ld, (o, a) => {
        i[a] && (r[a] = i[a]);
      }), e.head.insertBefore(r, null), e.head.removeChild(r);
    }
  });
}
function hd(t, e, n, s, i) {
  s ? t.insertBefore(e, n ? t.firstChild : null) : t.nodeName === "HTML" ? t.parentNode.replaceChild(e, t) : t.parentNode.insertBefore(e, n ? t : t.nextSibling), i && cd(e, t.ownerDocument);
}
function $e(t, e, n, s, i, r, o, a) {
  return tt(t, (l, h) => {
    tt(g(h), (c, u) => {
      tt(g(e), (d, f) => {
        const p = n ? u : f, m = n ? f : u, v = n ? c : d;
        hd(p, v ? m.cloneNode(!0) : m, s, i, !v);
      }, a);
    }, o);
  }, r), e;
}
S.after = function() {
  return $e(arguments, this, !1, !1, !1, !0, !0);
};
S.append = function() {
  return $e(arguments, this, !1, !1, !0);
};
function ud(t) {
  if (!arguments.length)
    return this[0] && this[0].innerHTML;
  if (ut(t))
    return this;
  const e = /<script[\s>]/.test(t);
  return this.each((n, s) => {
    K(s) && (e ? g(s).empty().append(t) : s.innerHTML = t);
  });
}
S.html = ud;
S.appendTo = function(t) {
  return $e(arguments, this, !0, !1, !0);
};
S.wrapInner = function(t) {
  return this.each((e, n) => {
    const s = g(n), i = s.contents();
    i.length ? i.wrapAll(t) : s.append(t);
  });
};
S.before = function() {
  return $e(arguments, this, !1, !0);
};
S.wrapAll = function(t) {
  let e = g(t), n = e[0];
  for (; n.children.length; )
    n = n.firstElementChild;
  return this.first().before(e), this.appendTo(n);
};
S.wrap = function(t) {
  return this.each((e, n) => {
    const s = g(t)[0];
    g(n).wrapAll(e ? s.cloneNode(!0) : s);
  });
};
S.insertAfter = function(t) {
  return $e(arguments, this, !0, !1, !1, !1, !1, !0);
};
S.insertBefore = function(t) {
  return $e(arguments, this, !0, !0);
};
S.prepend = function() {
  return $e(arguments, this, !1, !0, !0, !0, !0);
};
S.prependTo = function(t) {
  return $e(arguments, this, !0, !0, !0, !1, !1, !0);
};
S.contents = function() {
  return g(Dt(ee(this, (t) => t.tagName === "IFRAME" ? [t.contentDocument] : t.tagName === "TEMPLATE" ? t.content.childNodes : t.childNodes)));
};
S.next = function(t, e, n) {
  return xe(g(Dt(ee(this, "nextElementSibling", e, n))), t);
};
S.nextAll = function(t) {
  return this.next(t, !0);
};
S.nextUntil = function(t, e) {
  return this.next(e, !0, t);
};
S.parents = function(t, e) {
  return xe(g(Dt(ee(this, "parentElement", !0, e))), t);
};
S.parentsUntil = function(t, e) {
  return this.parents(e, t);
};
S.prev = function(t, e, n) {
  return xe(g(Dt(ee(this, "previousElementSibling", e, n))), t);
};
S.prevAll = function(t) {
  return this.prev(t, !0);
};
S.prevUntil = function(t, e) {
  return this.prev(e, !0, t);
};
S.map = function(t) {
  return g(ju.apply([], Gl.call(this, (e, n) => t.call(e, n, e))));
};
S.clone = function() {
  return this.map((t, e) => e.cloneNode(!0));
};
S.offsetParent = function() {
  return this.map((t, e) => {
    let n = e.offsetParent;
    for (; n && Jt(n, "position") === "static"; )
      n = n.offsetParent;
    return n || jl;
  });
};
S.slice = function(t, e) {
  return g(Yl.call(this, t, e));
};
const dd = /-([a-z])/g;
function Do(t) {
  return t.replace(dd, (e, n) => n.toUpperCase());
}
S.ready = function(t) {
  const e = () => setTimeout(t, 0, g);
  return Xt.readyState !== "loading" ? e() : Xt.addEventListener("DOMContentLoaded", e), this;
};
S.unwrap = function() {
  return this.parent().each((t, e) => {
    if (e.tagName === "BODY")
      return;
    const n = g(e);
    n.replaceWith(n.children());
  }), this;
};
S.offset = function() {
  const t = this[0];
  if (!t)
    return;
  const e = t.getBoundingClientRect();
  return {
    top: e.top + ui.pageYOffset,
    left: e.left + ui.pageXOffset
  };
};
S.position = function() {
  const t = this[0];
  if (!t)
    return;
  const e = Jt(t, "position") === "fixed", n = e ? t.getBoundingClientRect() : this.offset();
  if (!e) {
    const s = t.ownerDocument;
    let i = t.offsetParent || s.documentElement;
    for (; (i === s.body || i === s.documentElement) && Jt(i, "position") === "static"; )
      i = i.parentNode;
    if (i !== t && K(i)) {
      const r = g(i).offset();
      n.top -= r.top + Tt(i, "borderTopWidth"), n.left -= r.left + Tt(i, "borderLeftWidth");
    }
  }
  return {
    top: n.top - Tt(t, "marginTop"),
    left: n.left - Tt(t, "marginLeft")
  };
};
const Ql = {
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
S.prop = function(t, e) {
  if (t) {
    if (rt(t))
      return t = Ql[t] || t, arguments.length < 2 ? this[0] && this[0][t] : this.each((n, s) => {
        s[t] = e;
      });
    for (const n in t)
      this.prop(n, t[n]);
    return this;
  }
};
S.removeProp = function(t) {
  return this.each((e, n) => {
    delete n[Ql[t] || t];
  });
};
const fd = /^--/;
function Po(t) {
  return fd.test(t);
}
const wr = {}, { style: pd } = Ul, gd = ["webkit", "moz", "ms"];
function md(t, e = Po(t)) {
  if (e)
    return t;
  if (!wr[t]) {
    const n = Do(t), s = `${n[0].toUpperCase()}${n.slice(1)}`, i = `${n} ${gd.join(`${s} `)}${s}`.split(" ");
    tt(i, (r, o) => {
      if (o in pd)
        return wr[t] = o, !1;
    });
  }
  return wr[t];
}
const yd = {
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
function tc(t, e, n = Po(t)) {
  return !n && !yd[t] && Kl(e) ? `${e}px` : e;
}
function wd(t, e) {
  if (rt(t)) {
    const n = Po(t);
    return t = md(t, n), arguments.length < 2 ? this[0] && Jt(this[0], t, n) : t ? (e = tc(t, e, n), this.each((s, i) => {
      K(i) && (n ? i.style.setProperty(t, e) : i.style[t] = e);
    })) : this;
  }
  for (const n in t)
    this.css(n, t[n]);
  return this;
}
S.css = wd;
function ec(t, e) {
  try {
    return t(e);
  } catch {
    return e;
  }
}
const vd = /^\s+|\s+$/;
function sl(t, e) {
  const n = t.dataset[e] || t.dataset[Do(e)];
  return vd.test(n) ? n : ec(JSON.parse, n);
}
function bd(t, e, n) {
  n = ec(JSON.stringify, n), t.dataset[Do(e)] = n;
}
function _d(t, e) {
  if (!t) {
    if (!this[0])
      return;
    const n = {};
    for (const s in this[0].dataset)
      n[s] = sl(this[0], s);
    return n;
  }
  if (rt(t))
    return arguments.length < 2 ? this[0] && sl(this[0], t) : ut(e) ? this : this.each((n, s) => {
      bd(s, t, e);
    });
  for (const n in t)
    this.data(n, t[n]);
  return this;
}
S.data = _d;
function nc(t, e) {
  const n = t.documentElement;
  return Math.max(t.body[`scroll${e}`], n[`scroll${e}`], t.body[`offset${e}`], n[`offset${e}`], n[`client${e}`]);
}
tt([!0, !1], (t, e) => {
  tt(["Width", "Height"], (n, s) => {
    const i = `${e ? "outer" : "inner"}${s}`;
    S[i] = function(r) {
      if (this[0])
        return Sn(this[0]) ? e ? this[0][`inner${s}`] : this[0].document.documentElement[`client${s}`] : Ie(this[0]) ? nc(this[0], s) : this[0][`${e ? "offset" : "client"}${s}`] + (r && e ? Tt(this[0], `margin${n ? "Top" : "Left"}`) + Tt(this[0], `margin${n ? "Bottom" : "Right"}`) : 0);
    };
  });
});
tt(["Width", "Height"], (t, e) => {
  const n = e.toLowerCase();
  S[n] = function(s) {
    if (!this[0])
      return ut(s) ? void 0 : this;
    if (!arguments.length)
      return Sn(this[0]) ? this[0].document.documentElement[`client${e}`] : Ie(this[0]) ? nc(this[0], e) : this[0].getBoundingClientRect()[n] - tl(this[0], !t);
    const i = parseInt(s, 10);
    return this.each((r, o) => {
      if (!K(o))
        return;
      const a = Jt(o, "boxSizing");
      o.style[n] = tc(n, i + (a === "border-box" ? tl(o, !t) : 0));
    });
  };
});
const il = "___cd";
S.toggle = function(t) {
  return this.each((e, n) => {
    if (!K(n))
      return;
    const s = el(n);
    (ut(t) ? s : t) ? (n.style.display = n[il] || "", el(n) && (n.style.display = nd(n.tagName))) : s || (n[il] = Jt(n, "display"), n.style.display = "none");
  });
};
S.hide = function() {
  return this.toggle(!1);
};
S.show = function() {
  return this.toggle(!0);
};
const rl = "___ce", Wo = ".", Oo = { focus: "focusin", blur: "focusout" }, sc = { mouseenter: "mouseover", mouseleave: "mouseout" }, xd = /^(mouse|pointer|contextmenu|drag|drop|click|dblclick)/i;
function Io(t) {
  return sc[t] || Oo[t] || t;
}
function Ho(t) {
  const e = t.split(Wo);
  return [e[0], e.slice(1).sort()];
}
S.trigger = function(t, e) {
  if (rt(t)) {
    const [s, i] = Ho(t), r = Io(s);
    if (!r)
      return this;
    const o = xd.test(r) ? "MouseEvents" : "HTMLEvents";
    t = Xt.createEvent(o), t.initEvent(r, !0, !0), t.namespace = i.join(Wo), t.___ot = s;
  }
  t.___td = e;
  const n = t.___ot in Oo;
  return this.each((s, i) => {
    n && Ue(i[t.___ot]) && (i[`___i${t.type}`] = !0, i[t.___ot](), i[`___i${t.type}`] = !1), i.dispatchEvent(t);
  });
};
function ic(t) {
  return t[rl] = t[rl] || {};
}
function $d(t, e, n, s, i) {
  const r = ic(t);
  r[e] = r[e] || [], r[e].push([n, s, i]), t.addEventListener(e, i);
}
function rc(t, e) {
  return !e || !Ao.call(e, (n) => t.indexOf(n) < 0);
}
function fi(t, e, n, s, i) {
  const r = ic(t);
  if (e)
    r[e] && (r[e] = r[e].filter(([o, a, l]) => {
      if (i && l.guid !== i.guid || !rc(o, n) || s && s !== a)
        return !0;
      t.removeEventListener(e, l);
    }));
  else
    for (e in r)
      fi(t, e, n, s, i);
}
S.off = function(t, e, n) {
  if (ut(t))
    this.each((s, i) => {
      !K(i) && !Ie(i) && !Sn(i) || fi(i);
    });
  else if (rt(t))
    Ue(e) && (n = e, e = ""), tt(Gi(t), (s, i) => {
      const [r, o] = Ho(i), a = Io(r);
      this.each((l, h) => {
        !K(h) && !Ie(h) && !Sn(h) || fi(h, a, o, e, n);
      });
    });
  else
    for (const s in t)
      this.off(s, t[s]);
  return this;
};
S.remove = function(t) {
  return xe(this, t).detach().off(), this;
};
S.replaceWith = function(t) {
  return this.before(t).remove();
};
S.replaceAll = function(t) {
  return g(t).replaceWith(this), this;
};
function kd(t, e, n, s, i) {
  if (!rt(t)) {
    for (const r in t)
      this.on(r, e, n, t[r], i);
    return this;
  }
  return rt(e) || (ut(e) || cs(e) ? e = "" : ut(n) ? (n = e, e = "") : (s = n, n = e, e = "")), Ue(s) || (s = n, n = void 0), s ? (tt(Gi(t), (r, o) => {
    const [a, l] = Ho(o), h = Io(a), c = a in sc, u = a in Oo;
    h && this.each((d, f) => {
      if (!K(f) && !Ie(f) && !Sn(f))
        return;
      const p = function(m) {
        if (m.target[`___i${m.type}`])
          return m.stopImmediatePropagation();
        if (m.namespace && !rc(l, m.namespace.split(Wo)) || !e && (u && (m.target !== f || m.___ot === h) || c && m.relatedTarget && f.contains(m.relatedTarget)))
          return;
        let v = f;
        if (e) {
          let b = m.target;
          for (; !Xl(b, e); )
            if (b === f || (b = b.parentNode, !b))
              return;
          v = b;
        }
        Object.defineProperty(m, "currentTarget", {
          configurable: !0,
          get() {
            return v;
          }
        }), Object.defineProperty(m, "delegateTarget", {
          configurable: !0,
          get() {
            return f;
          }
        }), Object.defineProperty(m, "data", {
          configurable: !0,
          get() {
            return n;
          }
        });
        const w = s.call(v, m, m.___td);
        i && fi(f, h, l, e, p), w === !1 && (m.preventDefault(), m.stopPropagation());
      };
      p.guid = s.guid = s.guid || g.guid++, $d(f, h, l, e, p);
    });
  }), this) : this;
}
S.on = kd;
function Sd(t, e, n, s) {
  return this.on(t, e, n, s, !0);
}
S.one = Sd;
const Cd = /\r?\n/g;
function Ed(t, e) {
  return `&${encodeURIComponent(t)}=${encodeURIComponent(e.replace(Cd, `\r
`))}`;
}
const Md = /file|reset|submit|button|image/i, oc = /radio|checkbox/i;
S.serialize = function() {
  let t = "";
  return this.each((e, n) => {
    tt(n.elements || [n], (s, i) => {
      if (i.disabled || !i.name || i.tagName === "FIELDSET" || Md.test(i.type) || oc.test(i.type) && !i.checked)
        return;
      const r = Zl(i);
      if (!ut(r)) {
        const o = qi(r) ? r : [r];
        tt(o, (a, l) => {
          t += Ed(i.name, l);
        });
      }
    });
  }), t.slice(1);
};
window.$ = g;
function Td(t, e) {
  if (t == null)
    return [t, void 0];
  typeof e == "string" && (e = e.split("."));
  const n = e.join(".");
  let s = t;
  const i = [s];
  for (; typeof s == "object" && s !== null && e.length; ) {
    let r = e.shift(), o;
    const a = r.indexOf("[");
    if (a > 0 && a < r.length - 1 && r.endsWith("]") && (o = r.substring(a + 1, r.length - 1), r = r.substring(0, a)), s = s[r], i.push(s), o !== void 0)
      if (typeof s == "object" && s !== null)
        s instanceof Map ? s = s.get(o) : s = s[o], i.push(s);
      else
        throw new Error(`Cannot access property "${r}[${o}]", the full path is "${n}".`);
  }
  if (e.length)
    throw new Error(`Cannot access property with rest path "${e.join(".")}", the full path is "${n}".`);
  return i;
}
function Rd(t, e, n) {
  try {
    const s = Td(t, e), i = s[s.length - 1];
    return i === void 0 ? n : i;
  } catch {
    return n;
  }
}
function vr(t) {
  return !!t && typeof t == "object" && !Array.isArray(t);
}
function Dr(t, ...e) {
  if (!e.length)
    return t;
  const n = e.shift();
  if (vr(t) && vr(n))
    for (const s in n)
      vr(n[s]) ? (t[s] || Object.assign(t, { [s]: {} }), Dr(t[s], n[s])) : Object.assign(t, { [s]: n[s] });
  return Dr(t, ...e);
}
function X(t, ...e) {
  if (e.length === 0)
    return t;
  if (e.length === 1 && typeof e[0] == "object" && e[0]) {
    const n = e[0];
    return Object.keys(n).forEach((s) => {
      const i = n[s] ?? 0;
      t = t.replace(new RegExp(`\\{${s}\\}`, "g"), `${i}`);
    }), t;
  }
  for (let n = 0; n < e.length; n++) {
    const s = e[n] ?? "";
    t = t.replace(new RegExp(`\\{${n}\\}`, "g"), `${s}`);
  }
  return t;
}
var Bo = /* @__PURE__ */ ((t) => (t[t.B = 1] = "B", t[t.KB = 1024] = "KB", t[t.MB = 1048576] = "MB", t[t.GB = 1073741824] = "GB", t[t.TB = 1099511627776] = "TB", t))(Bo || {});
function br(t, e = 2, n) {
  return Number.isNaN(t) ? "?KB" : (n || (t < 1024 ? n = "B" : t < 1048576 ? n = "KB" : t < 1073741824 ? n = "MB" : t < 1099511627776 ? n = "GB" : n = "TB"), (t / Bo[n]).toFixed(e) + n);
}
const Lg = (t) => {
  const e = /^[0-9]*(B|KB|MB|GB|TB)$/;
  t = t.toUpperCase();
  const n = t.match(e);
  if (!n)
    return 0;
  const s = n[1];
  return t = t.replace(s, ""), Number.parseInt(t, 10) * Bo[s];
};
let zo = (document.documentElement.getAttribute("lang") || "zh_cn").toLowerCase().replace("-", "_"), ce;
function Ad() {
  return zo;
}
function Nd(t) {
  zo = t.toLowerCase();
}
function ac(t, e) {
  ce || (ce = {}), typeof t == "string" && (t = { [t]: e ?? {} }), Dr(ce, t);
}
function Zt(t, e, n, s, i, r) {
  Array.isArray(t) ? ce && t.unshift(ce) : t = ce ? [ce, t] : [t], typeof n == "string" && (r = i, i = s, s = n, n = void 0);
  const o = i || zo;
  let a;
  for (const l of t) {
    if (!l)
      continue;
    const h = l[o];
    if (!h)
      continue;
    const c = r && l === ce ? `${r}.${e}` : e;
    if (a = Rd(h, c), a !== void 0)
      break;
  }
  return a === void 0 ? s : n ? X(a, ...Array.isArray(n) ? n : [n]) : a;
}
function Ld(t, e, n, s) {
  return Zt(void 0, t, e, n, s);
}
Zt.addLang = ac;
Zt.getLang = Ld;
Zt.getCode = Ad;
Zt.setCode = Nd;
ac({
  zh_cn: {
    confirm: "确定",
    cancel: "取消",
    delete: "删除",
    add: "添加"
  },
  zh_tw: {
    confirm: "確定",
    cancel: "取消",
    delete: "刪除",
    add: "添加"
  },
  en: {
    confirm: "Confirm",
    cancel: "Cancel",
    delete: "Delete",
    add: "Add"
  }
});
function lc(...t) {
  const e = [], n = /* @__PURE__ */ new Map(), s = (i, r) => {
    if (Array.isArray(i) && (r = i[1], i = i[0]), !i.length)
      return;
    const o = n.get(i);
    typeof o == "number" ? e[o][1] = !!r : (n.set(i, e.length), e.push([i, !!r]));
  };
  return t.forEach((i) => {
    typeof i == "function" && (i = i()), Array.isArray(i) ? lc(...i).forEach(s) : i && typeof i == "object" ? Object.entries(i).forEach(s) : typeof i == "string" && i.split(" ").forEach((r) => s(r, !0));
  }), e.sort((i, r) => (n.get(i[0]) || 0) - (n.get(r[0]) || 0));
}
const N = (...t) => lc(...t).reduce((e, [n, s]) => (s && e.push(n), e), []).join(" ");
g.classes = N;
g.fn.setClass = function(t, ...e) {
  return this.each((n, s) => {
    const i = g(s);
    t === !0 ? i.attr("class", N(i.attr("class"), ...e)) : i.addClass(N(t, ...e));
  });
};
const Wn = /* @__PURE__ */ new WeakMap();
function cc(t, e, n) {
  const s = Wn.has(t), i = s ? Wn.get(t) : {};
  typeof e == "string" ? i[e] = n : e === null ? Object.keys(i).forEach((r) => {
    delete i[r];
  }) : Object.assign(i, e), Object.keys(i).forEach((r) => {
    i[r] === void 0 && delete i[r];
  }), Object.keys(i).length ? (!s && t instanceof Element && Object.assign(i, g(t).dataset(), i), Wn.set(t, i)) : Wn.delete(t);
}
function Dd(t, e) {
  let n = Wn.get(t) || {};
  return t instanceof Element && (n = Object.assign({}, g(t).dataset(), n)), e === void 0 ? n : n[e];
}
g.fn.dataset = g.fn.data;
g.fn.data = function(...t) {
  if (!this.length)
    return;
  const [e, n] = t;
  return !t.length || t.length === 1 && typeof e == "string" ? Dd(this[0], e) : this.each((s, i) => cc(i, e, n));
};
g.fn.removeData = function(t = null) {
  return this.each((e, n) => cc(n, t));
};
g.fn._attr = g.fn.attr;
g.fn.extend({
  attr(...t) {
    const [e, n] = t;
    return !t.length || t.length === 1 && typeof e == "string" ? this._attr.apply(this, t) : typeof e == "object" ? (e && Object.keys(e).forEach((s) => {
      const i = e[s];
      i === null ? this.removeAttr(s) : this._attr(s, i);
    }), this) : n === null ? this.removeAttr(e) : this._attr(e, n);
  }
});
g.Event = (t, e) => {
  const [n, ...s] = t.split("."), i = new Event(n, {
    bubbles: !0,
    cancelable: !0
  });
  return i.namespace = s.join("."), i.___ot = n, i.___td = e, i;
};
function hc(t, e) {
  const n = g(t)[0];
  if (!n)
    return !1;
  const { left: s, top: i, width: r, height: o } = n.getBoundingClientRect(), { innerHeight: a, innerWidth: l } = window, { clientHeight: h, clientWidth: c } = document.documentElement, u = a || h, d = l || c;
  if (e != null && e.fullyCheck)
    return s >= 0 && i >= 0 && s + r <= d && i + o <= u;
  const f = i <= u && i + o >= 0, p = s <= d && s + r >= 0;
  return f && p;
}
g.fn.isVisible = function(t) {
  return this.each((e, n) => {
    hc(n, t);
  });
};
function Fo(t, e, n = !1) {
  const s = g(t);
  if (e !== void 0) {
    const i = `zui-runjs-${g.guid++}`;
    s.append(`<script id="${i}">${e}<\/script>`), n && s.find(`#${i}`).remove();
    return;
  }
  s.find("script").each((i, r) => {
    Fo(s, r.innerHTML), r.remove();
  });
}
g.runJS = (t, ...e) => (t = t.trim(), t.startsWith("return ") || (t = `return ${t}`), new Function(...e.map(([s]) => s), t)(...e.map(([, s]) => s)));
g.fn.runJS = function(t) {
  return this.each((e, n) => {
    Fo(n, t);
  });
};
const Dg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  isVisible: hc,
  runJS: Fo
}, Symbol.toStringTag, { value: "Module" }));
var Ki, q, uc, it, Te, ol, dc, Pr, pi = {}, fc = [], Pd = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i, jo = Array.isArray;
function ge(t, e) {
  for (var n in e)
    t[n] = e[n];
  return t;
}
function pc(t) {
  var e = t.parentNode;
  e && e.removeChild(t);
}
function _(t, e, n) {
  var s, i, r, o = {};
  for (r in e)
    r == "key" ? s = e[r] : r == "ref" ? i = e[r] : o[r] = e[r];
  if (arguments.length > 2 && (o.children = arguments.length > 3 ? Ki.call(arguments, 2) : n), typeof t == "function" && t.defaultProps != null)
    for (r in t.defaultProps)
      o[r] === void 0 && (o[r] = t.defaultProps[r]);
  return Us(t, o, s, i, null);
}
function Us(t, e, n, s, i) {
  var r = { type: t, props: e, key: n, ref: s, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++uc };
  return i == null && q.vnode != null && q.vnode(r), r;
}
function $t() {
  return { current: null };
}
function Xi(t) {
  return t.children;
}
function U(t, e) {
  this.props = t, this.context = e;
}
function hs(t, e) {
  if (e == null)
    return t.__ ? hs(t.__, t.__.__k.indexOf(t) + 1) : null;
  for (var n; e < t.__k.length; e++)
    if ((n = t.__k[e]) != null && n.__e != null)
      return n.__e;
  return typeof t.type == "function" ? hs(t) : null;
}
function gc(t) {
  var e, n;
  if ((t = t.__) != null && t.__c != null) {
    for (t.__e = t.__c.base = null, e = 0; e < t.__k.length; e++)
      if ((n = t.__k[e]) != null && n.__e != null) {
        t.__e = t.__c.base = n.__e;
        break;
      }
    return gc(t);
  }
}
function al(t) {
  (!t.__d && (t.__d = !0) && Te.push(t) && !gi.__r++ || ol !== q.debounceRendering) && ((ol = q.debounceRendering) || dc)(gi);
}
function gi() {
  var t, e, n, s, i, r, o, a;
  for (Te.sort(Pr); t = Te.shift(); )
    t.__d && (e = Te.length, s = void 0, i = void 0, o = (r = (n = t).__v).__e, (a = n.__P) && (s = [], (i = ge({}, r)).__v = r.__v + 1, Uo(a, r, i, n.__n, a.ownerSVGElement !== void 0, r.__h != null ? [o] : null, s, o ?? hs(r), r.__h), bc(s, r), r.__e != o && gc(r)), Te.length > e && Te.sort(Pr));
  gi.__r = 0;
}
function mc(t, e, n, s, i, r, o, a, l, h) {
  var c, u, d, f, p, m, v, w = s && s.__k || fc, b = w.length;
  for (n.__k = [], c = 0; c < e.length; c++)
    if ((f = n.__k[c] = (f = e[c]) == null || typeof f == "boolean" || typeof f == "function" ? null : typeof f == "string" || typeof f == "number" || typeof f == "bigint" ? Us(null, f, null, null, f) : jo(f) ? Us(Xi, { children: f }, null, null, null) : f.__b > 0 ? Us(f.type, f.props, f.key, f.ref ? f.ref : null, f.__v) : f) != null) {
      if (f.__ = n, f.__b = n.__b + 1, (d = w[c]) === null || d && f.key == d.key && f.type === d.type)
        w[c] = void 0;
      else
        for (u = 0; u < b; u++) {
          if ((d = w[u]) && f.key == d.key && f.type === d.type) {
            w[u] = void 0;
            break;
          }
          d = null;
        }
      Uo(t, f, d = d || pi, i, r, o, a, l, h), p = f.__e, (u = f.ref) && d.ref != u && (v || (v = []), d.ref && v.push(d.ref, null, f), v.push(u, f.__c || p, f)), p != null ? (m == null && (m = p), typeof f.type == "function" && f.__k === d.__k ? f.__d = l = yc(f, l, t) : l = wc(t, f, d, w, p, l), typeof n.type == "function" && (n.__d = l)) : l && d.__e == l && l.parentNode != t && (l = hs(d));
    }
  for (n.__e = m, c = b; c--; )
    w[c] != null && (typeof n.type == "function" && w[c].__e != null && w[c].__e == n.__d && (n.__d = vc(s).nextSibling), xc(w[c], w[c]));
  if (v)
    for (c = 0; c < v.length; c++)
      _c(v[c], v[++c], v[++c]);
}
function yc(t, e, n) {
  for (var s, i = t.__k, r = 0; i && r < i.length; r++)
    (s = i[r]) && (s.__ = t, e = typeof s.type == "function" ? yc(s, e, n) : wc(n, s, s, i, s.__e, e));
  return e;
}
function wc(t, e, n, s, i, r) {
  var o, a, l;
  if (e.__d !== void 0)
    o = e.__d, e.__d = void 0;
  else if (n == null || i != r || i.parentNode == null)
    t:
      if (r == null || r.parentNode !== t)
        t.appendChild(i), o = null;
      else {
        for (a = r, l = 0; (a = a.nextSibling) && l < s.length; l += 1)
          if (a == i)
            break t;
        t.insertBefore(i, r), o = r;
      }
  return o !== void 0 ? o : i.nextSibling;
}
function vc(t) {
  var e, n, s;
  if (t.type == null || typeof t.type == "string")
    return t.__e;
  if (t.__k) {
    for (e = t.__k.length - 1; e >= 0; e--)
      if ((n = t.__k[e]) && (s = vc(n)))
        return s;
  }
  return null;
}
function Wd(t, e, n, s, i) {
  var r;
  for (r in n)
    r === "children" || r === "key" || r in e || mi(t, r, null, n[r], s);
  for (r in e)
    i && typeof e[r] != "function" || r === "children" || r === "key" || r === "value" || r === "checked" || n[r] === e[r] || mi(t, r, e[r], n[r], s);
}
function ll(t, e, n) {
  e[0] === "-" ? t.setProperty(e, n ?? "") : t[e] = n == null ? "" : typeof n != "number" || Pd.test(e) ? n : n + "px";
}
function mi(t, e, n, s, i) {
  var r;
  t:
    if (e === "style")
      if (typeof n == "string")
        t.style.cssText = n;
      else {
        if (typeof s == "string" && (t.style.cssText = s = ""), s)
          for (e in s)
            n && e in n || ll(t.style, e, "");
        if (n)
          for (e in n)
            s && n[e] === s[e] || ll(t.style, e, n[e]);
      }
    else if (e[0] === "o" && e[1] === "n")
      r = e !== (e = e.replace(/Capture$/, "")), e = e.toLowerCase() in t ? e.toLowerCase().slice(2) : e.slice(2), t.l || (t.l = {}), t.l[e + r] = n, n ? s || t.addEventListener(e, r ? hl : cl, r) : t.removeEventListener(e, r ? hl : cl, r);
    else if (e !== "dangerouslySetInnerHTML") {
      if (i)
        e = e.replace(/xlink(H|:h)/, "h").replace(/sName$/, "s");
      else if (e !== "width" && e !== "height" && e !== "href" && e !== "list" && e !== "form" && e !== "tabIndex" && e !== "download" && e !== "rowSpan" && e !== "colSpan" && e in t)
        try {
          t[e] = n ?? "";
          break t;
        } catch {
        }
      typeof n == "function" || (n == null || n === !1 && e[4] !== "-" ? t.removeAttribute(e) : t.setAttribute(e, n));
    }
}
function cl(t) {
  return this.l[t.type + !1](q.event ? q.event(t) : t);
}
function hl(t) {
  return this.l[t.type + !0](q.event ? q.event(t) : t);
}
function Uo(t, e, n, s, i, r, o, a, l) {
  var h, c, u, d, f, p, m, v, w, b, k, C, E, P, M, T = e.type;
  if (e.constructor !== void 0)
    return null;
  n.__h != null && (l = n.__h, a = e.__e = n.__e, e.__h = null, r = [a]), (h = q.__b) && h(e);
  try {
    t:
      if (typeof T == "function") {
        if (v = e.props, w = (h = T.contextType) && s[h.__c], b = h ? w ? w.props.value : h.__ : s, n.__c ? m = (c = e.__c = n.__c).__ = c.__E : ("prototype" in T && T.prototype.render ? e.__c = c = new T(v, b) : (e.__c = c = new U(v, b), c.constructor = T, c.render = Id), w && w.sub(c), c.props = v, c.state || (c.state = {}), c.context = b, c.__n = s, u = c.__d = !0, c.__h = [], c._sb = []), c.__s == null && (c.__s = c.state), T.getDerivedStateFromProps != null && (c.__s == c.state && (c.__s = ge({}, c.__s)), ge(c.__s, T.getDerivedStateFromProps(v, c.__s))), d = c.props, f = c.state, c.__v = e, u)
          T.getDerivedStateFromProps == null && c.componentWillMount != null && c.componentWillMount(), c.componentDidMount != null && c.__h.push(c.componentDidMount);
        else {
          if (T.getDerivedStateFromProps == null && v !== d && c.componentWillReceiveProps != null && c.componentWillReceiveProps(v, b), !c.__e && c.shouldComponentUpdate != null && c.shouldComponentUpdate(v, c.__s, b) === !1 || e.__v === n.__v) {
            for (e.__v !== n.__v && (c.props = v, c.state = c.__s, c.__d = !1), c.__e = !1, e.__e = n.__e, e.__k = n.__k, e.__k.forEach(function(A) {
              A && (A.__ = e);
            }), k = 0; k < c._sb.length; k++)
              c.__h.push(c._sb[k]);
            c._sb = [], c.__h.length && o.push(c);
            break t;
          }
          c.componentWillUpdate != null && c.componentWillUpdate(v, c.__s, b), c.componentDidUpdate != null && c.__h.push(function() {
            c.componentDidUpdate(d, f, p);
          });
        }
        if (c.context = b, c.props = v, c.__P = t, C = q.__r, E = 0, "prototype" in T && T.prototype.render) {
          for (c.state = c.__s, c.__d = !1, C && C(e), h = c.render(c.props, c.state, c.context), P = 0; P < c._sb.length; P++)
            c.__h.push(c._sb[P]);
          c._sb = [];
        } else
          do
            c.__d = !1, C && C(e), h = c.render(c.props, c.state, c.context), c.state = c.__s;
          while (c.__d && ++E < 25);
        c.state = c.__s, c.getChildContext != null && (s = ge(ge({}, s), c.getChildContext())), u || c.getSnapshotBeforeUpdate == null || (p = c.getSnapshotBeforeUpdate(d, f)), mc(t, jo(M = h != null && h.type === Xi && h.key == null ? h.props.children : h) ? M : [M], e, n, s, i, r, o, a, l), c.base = e.__e, e.__h = null, c.__h.length && o.push(c), m && (c.__E = c.__ = null), c.__e = !1;
      } else
        r == null && e.__v === n.__v ? (e.__k = n.__k, e.__e = n.__e) : e.__e = Od(n.__e, e, n, s, i, r, o, l);
    (h = q.diffed) && h(e);
  } catch (A) {
    e.__v = null, (l || r != null) && (e.__e = a, e.__h = !!l, r[r.indexOf(a)] = null), q.__e(A, e, n);
  }
}
function bc(t, e) {
  q.__c && q.__c(e, t), t.some(function(n) {
    try {
      t = n.__h, n.__h = [], t.some(function(s) {
        s.call(n);
      });
    } catch (s) {
      q.__e(s, n.__v);
    }
  });
}
function Od(t, e, n, s, i, r, o, a) {
  var l, h, c, u = n.props, d = e.props, f = e.type, p = 0;
  if (f === "svg" && (i = !0), r != null) {
    for (; p < r.length; p++)
      if ((l = r[p]) && "setAttribute" in l == !!f && (f ? l.localName === f : l.nodeType === 3)) {
        t = l, r[p] = null;
        break;
      }
  }
  if (t == null) {
    if (f === null)
      return document.createTextNode(d);
    t = i ? document.createElementNS("http://www.w3.org/2000/svg", f) : document.createElement(f, d.is && d), r = null, a = !1;
  }
  if (f === null)
    u === d || a && t.data === d || (t.data = d);
  else {
    if (r = r && Ki.call(t.childNodes), h = (u = n.props || pi).dangerouslySetInnerHTML, c = d.dangerouslySetInnerHTML, !a) {
      if (r != null)
        for (u = {}, p = 0; p < t.attributes.length; p++)
          u[t.attributes[p].name] = t.attributes[p].value;
      (c || h) && (c && (h && c.__html == h.__html || c.__html === t.innerHTML) || (t.innerHTML = c && c.__html || ""));
    }
    if (Wd(t, d, u, i, a), c)
      e.__k = [];
    else if (mc(t, jo(p = e.props.children) ? p : [p], e, n, s, i && f !== "foreignObject", r, o, r ? r[0] : n.__k && hs(n, 0), a), r != null)
      for (p = r.length; p--; )
        r[p] != null && pc(r[p]);
    a || ("value" in d && (p = d.value) !== void 0 && (p !== t.value || f === "progress" && !p || f === "option" && p !== u.value) && mi(t, "value", p, u.value, !1), "checked" in d && (p = d.checked) !== void 0 && p !== t.checked && mi(t, "checked", p, u.checked, !1));
  }
  return t;
}
function _c(t, e, n) {
  try {
    typeof t == "function" ? t(e) : t.current = e;
  } catch (s) {
    q.__e(s, n);
  }
}
function xc(t, e, n) {
  var s, i;
  if (q.unmount && q.unmount(t), (s = t.ref) && (s.current && s.current !== t.__e || _c(s, null, e)), (s = t.__c) != null) {
    if (s.componentWillUnmount)
      try {
        s.componentWillUnmount();
      } catch (r) {
        q.__e(r, e);
      }
    s.base = s.__P = null, t.__c = void 0;
  }
  if (s = t.__k)
    for (i = 0; i < s.length; i++)
      s[i] && xc(s[i], e, n || typeof t.type != "function");
  n || t.__e == null || pc(t.__e), t.__ = t.__e = t.__d = void 0;
}
function Id(t, e, n) {
  return this.constructor(t, n);
}
function us(t, e, n) {
  var s, i, r;
  q.__ && q.__(t, e), i = (s = typeof n == "function") ? null : n && n.__k || e.__k, r = [], Uo(e, t = (!s && n || e).__k = _(Xi, null, [t]), i || pi, pi, e.ownerSVGElement !== void 0, !s && n ? [n] : i ? null : e.firstChild ? Ki.call(e.childNodes) : null, r, !s && n ? n : i ? i.__e : e.firstChild, s), bc(r, t);
}
Ki = fc.slice, q = { __e: function(t, e, n, s) {
  for (var i, r, o; e = e.__; )
    if ((i = e.__c) && !i.__)
      try {
        if ((r = i.constructor) && r.getDerivedStateFromError != null && (i.setState(r.getDerivedStateFromError(t)), o = i.__d), i.componentDidCatch != null && (i.componentDidCatch(t, s || {}), o = i.__d), o)
          return i.__E = i;
      } catch (a) {
        t = a;
      }
  throw t;
} }, uc = 0, it = function(t) {
  return t != null && t.constructor === void 0;
}, U.prototype.setState = function(t, e) {
  var n;
  n = this.__s != null && this.__s !== this.state ? this.__s : this.__s = ge({}, this.state), typeof t == "function" && (t = t(ge({}, n), this.props)), t && ge(n, t), t != null && this.__v && (e && this._sb.push(e), al(this));
}, U.prototype.forceUpdate = function(t) {
  this.__v && (this.__e = !0, t && this.__h.push(t), al(this));
}, U.prototype.render = Xi, Te = [], dc = typeof Promise == "function" ? Promise.prototype.then.bind(Promise.resolve()) : setTimeout, Pr = function(t, e) {
  return t.__v.__b - e.__v.__b;
}, gi.__r = 0;
var $c = function(t, e, n, s) {
  var i;
  e[0] = 0;
  for (var r = 1; r < e.length; r++) {
    var o = e[r++], a = e[r] ? (e[0] |= o ? 1 : 2, n[e[r++]]) : e[++r];
    o === 3 ? s[0] = a : o === 4 ? s[1] = Object.assign(s[1] || {}, a) : o === 5 ? (s[1] = s[1] || {})[e[++r]] = a : o === 6 ? s[1][e[++r]] += a + "" : o ? (i = t.apply(a, $c(t, a, n, ["", null])), s.push(i), a[0] ? e[0] |= 2 : (e[r - 2] = 0, e[r] = i)) : s.push(a);
  }
  return s;
}, ul = /* @__PURE__ */ new Map();
function Hd(t) {
  var e = ul.get(this);
  return e || (e = /* @__PURE__ */ new Map(), ul.set(this, e)), (e = $c(this, e.get(t) || (e.set(t, e = function(n) {
    for (var s, i, r = 1, o = "", a = "", l = [0], h = function(d) {
      r === 1 && (d || (o = o.replace(/^\s*\n\s*|\s*\n\s*$/g, ""))) ? l.push(0, d, o) : r === 3 && (d || o) ? (l.push(3, d, o), r = 2) : r === 2 && o === "..." && d ? l.push(4, d, 0) : r === 2 && o && !d ? l.push(5, 0, !0, o) : r >= 5 && ((o || !d && r === 5) && (l.push(r, 0, o, i), r = 6), d && (l.push(r, d, 0, i), r = 6)), o = "";
    }, c = 0; c < n.length; c++) {
      c && (r === 1 && h(), h(c));
      for (var u = 0; u < n[c].length; u++)
        s = n[c][u], r === 1 ? s === "<" ? (h(), l = [l], r = 3) : o += s : r === 4 ? o === "--" && s === ">" ? (r = 1, o = "") : o = s + o[0] : a ? s === a ? a = "" : o += s : s === '"' || s === "'" ? a = s : s === ">" ? (h(), r = 1) : r && (s === "=" ? (r = 5, i = o, o = "") : s === "/" && (r < 5 || n[c][u + 1] === ">") ? (h(), r === 3 && (l = l[0]), r = l, (l = l[0]).push(2, 0, r), r = 0) : s === " " || s === "	" || s === `
` || s === "\r" ? (h(), r = 2) : o += s), r === 3 && o === "!--" && (r = 4, l = l[0]);
    }
    return h(), l;
  }(t)), e), arguments, [])).length > 1 ? e : e[0];
}
const Pg = Hd.bind(_);
function Bd(t) {
  const { tagName: e = "div", className: n, style: s, children: i, attrs: r, ...o } = t;
  return _(e, { class: N(n), style: s, ...o, ...r }, i);
}
var zd = 0;
function y(t, e, n, s, i, r) {
  var o, a, l = {};
  for (a in e)
    a == "ref" ? o = e[a] : l[a] = e[a];
  var h = { type: t, props: l, key: n, ref: o, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --zd, __source: i, __self: r };
  if (typeof t == "function" && (o = t.defaultProps))
    for (a in o)
      l[a] === void 0 && (l[a] = o[a]);
  return q.vnode && q.vnode(h), h;
}
var ws;
class kc extends U {
  constructor() {
    super(...arguments);
    O(this, ws, $t());
  }
  componentDidMount() {
    this.props.executeScript && g(D(this, ws).current).runJS();
  }
  render(n) {
    const { executeScript: s, html: i, ...r } = n;
    return /* @__PURE__ */ y(Bd, { ref: D(this, ws), dangerouslySetInnerHTML: { __html: i }, ...r });
  }
}
ws = new WeakMap();
function Fd(t) {
  const {
    tag: e,
    className: n,
    style: s,
    renders: i,
    generateArgs: r = [],
    generatorThis: o,
    generators: a,
    onGenerate: l,
    onRenderItem: h,
    ...c
  } = t, u = [n], d = { ...s }, f = [], p = [];
  return i.forEach((m) => {
    const v = [];
    if (typeof m == "string" && a && a[m] && (m = a[m]), typeof m == "function")
      if (l)
        v.push(...l.call(o, m, f, ...r));
      else {
        const w = m.call(o, f, ...r);
        w && (Array.isArray(w) ? v.push(...w) : v.push(w));
      }
    else
      v.push(m);
    v.forEach((w) => {
      w != null && (typeof w == "object" && !it(w) && ("html" in w || "__html" in w || "className" in w || "style" in w || "attrs" in w || "children" in w) ? w.html ? f.push(
        /* @__PURE__ */ y("div", { className: N(w.className), style: w.style, dangerouslySetInnerHTML: { __html: w.html }, ...w.attrs ?? {} })
      ) : w.__html ? p.push(w.__html) : (w.style && Object.assign(d, w.style), w.className && u.push(w.className), w.children && f.push(w.children), w.attrs && Object.assign(c, w.attrs)) : f.push(w));
    });
  }), p.length && Object.assign(c, { dangerouslySetInnerHTML: { __html: p } }), [{
    className: N(u),
    style: d,
    ...c
  }, f];
}
function qo({
  tag: t = "div",
  ...e
}) {
  const [n, s] = Fd(e);
  return _(t, n, ...s);
}
function ds(t) {
  const { icon: e, className: n, ...s } = t;
  if (!e)
    return null;
  if (it(e))
    return e;
  const i = ["icon", n];
  return typeof e == "string" ? i.push(e.startsWith("icon-") ? e : `icon-${e}`) : typeof e == "object" && (i.push(e.className), Object.assign(s, e)), /* @__PURE__ */ y("i", { className: N(i), ...s });
}
function jd(t) {
  return this.getChildContext = () => t.context, t.children;
}
function Ud(t) {
  const e = this, n = t._container;
  e.componentWillUnmount = function() {
    us(null, e._temp), e._temp = null, e._container = null;
  }, e._container && e._container !== n && e.componentWillUnmount(), t._vnode ? (e._temp || (e._container = n, e._temp = {
    nodeType: 1,
    parentNode: n,
    childNodes: [],
    appendChild(s) {
      this.childNodes.push(s), e._container.appendChild(s);
    },
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    insertBefore(s, i) {
      this.childNodes.push(s), e._container.appendChild(s);
    },
    removeChild(s) {
      this.childNodes.splice(this.childNodes.indexOf(s) >>> 1, 1), e._container.removeChild(s);
    }
  }), us(
    _(jd, { context: e.context }, t._vnode),
    e._temp
  )) : e._temp && e.componentWillUnmount();
}
function qd(t, e) {
  const n = _(Ud, { _vnode: t, _container: e });
  return n.containerInfo = e, n;
}
var Vo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Wt = (t, e, n) => (Vo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Rn = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ve = (t, e, n, s) => (Vo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Ls = (t, e, n) => (Vo(t, e, "access private method"), n), ke, On, qs, Se, Xe, In;
const Sc = class {
  /**
   * The component constructor.
   *
   * @param options The component initial options.
   */
  constructor(t, e) {
    Rn(this, Xe), Rn(this, ke, void 0), Rn(this, On, void 0), Rn(this, qs, void 0), Rn(this, Se, void 0);
    const { KEY: n, DATA_KEY: s, DEFAULT: i, MULTI_INSTANCE: r } = this.constructor, o = g(t);
    if (o.data(n) && !r)
      throw new Error("[ZUI] The component has been initialized on element.");
    const a = g.guid++;
    if (Ve(this, qs, a), Ve(this, On, o[0]), o.on("DOMNodeRemovedFromDocument", () => {
      this.destroy();
    }), Ve(this, ke, { ...i, ...o.dataset() }), this.setOptions(e), Ve(this, Se, this.options.key ?? `__${a}`), o.data(n, this).attr(s, `${a}`), r) {
      const l = `${n}:ALL`;
      let h = o.data(l);
      h || (h = /* @__PURE__ */ new Map(), o.data(l, h)), h.set(Wt(this, Se), this);
    }
    this.init(), requestAnimationFrame(() => {
      this.emit("inited", this.options), this.afterInit();
    });
  }
  /**
   * Component data key, like "zui.menu"
   */
  static get KEY() {
    return `zui.${this.NAME}`;
  }
  /**
   * Component namespace, like ".zui.menu"
   */
  static get NAMESPACE() {
    return `.${this.NAME}.zui`;
  }
  static get DATA_KEY() {
    return `data-zui-${this.NAME}`;
  }
  /**
   * Get the component element.
   */
  get element() {
    return Wt(this, On);
  }
  get key() {
    return Wt(this, Se);
  }
  /**
   * Get the component options.
   */
  get options() {
    return Wt(this, ke);
  }
  /**
   * Get the component global id.
   */
  get gid() {
    return Wt(this, qs);
  }
  /**
   * Get the component element as a jQuery like object.
   */
  get $element() {
    return g(this.element);
  }
  /**
   * Initialize the component.
   */
  init() {
  }
  /**
   * Do something after the component initialized.
   */
  afterInit() {
  }
  /**
   * Render the component.
   *
   * @param options The component options to override before render.
   */
  render(t) {
    this.setOptions(t);
  }
  /**
   * Destroy the component.
   */
  destroy() {
    const { KEY: t, DATA_KEY: e, MULTI_INSTANCE: n } = this.constructor, { $element: s } = this;
    if (this.emit("destroyed"), s.off(this.namespace).removeData(t).attr(e, null), n) {
      const i = this.$element.data(`${t}:ALL`);
      if (i)
        if (i.delete(Wt(this, Se)), i.size === 0)
          this.$element.removeData(`${t}:ALL`);
        else {
          const r = i.values().next().value;
          s.data(t, r).attr(e, r.gid);
        }
    }
    Ve(this, ke, void 0), Ve(this, On, void 0);
  }
  /**
   * Set the component options.
   *
   * @param options  The component options to set.
   * @returns The component options.
   */
  setOptions(t) {
    return t && g.extend(Wt(this, ke), t), Wt(this, ke);
  }
  /**
   * Emit a component event.
   * @param event  The event name.
   * @param args   The event arguments.
   */
  emit(t, ...e) {
    const n = g.Event(Ls(this, Xe, In).call(this, t));
    return this.$element.trigger(n, [this, ...e]), n;
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  on(t, e) {
    this.$element.on(Ls(this, Xe, In).call(this, t), e);
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  one(t, e) {
    this.$element.one(Ls(this, Xe, In).call(this, t), e);
  }
  /**
   * Stop listening to a component event.
   * @param event     The event name.
   * @param callback  The event callback.
   */
  off(t, e) {
    this.$element.off(Ls(this, Xe, In).call(this, t), e);
  }
  /**
   * Get the i18n text.
   *
   * @param key          The i18n key.
   * @param args         The i18n arguments or the default value.
   * @param defaultValue The default value if the key is not found.
   * @returns            The i18n text.
   */
  i18n(t, e, n) {
    return Zt(this.options.i18n, t, e, n, this.options.lang, this.constructor.NAME) ?? Zt(this.options.i18n, t, e, n, this.options.lang) ?? `{i18n:${t}}`;
  }
  /**
   * Get event namespace.
   * @returns Event namespace.
   */
  get namespace() {
    return `.${Wt(this, Se)}${this.constructor.NAMESPACE}`;
  }
  /**
   * Get the component instance of the given element.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        The component instance.
   */
  static get(t, e) {
    const n = g(t);
    if (this.MULTI_INSTANCE && e !== void 0) {
      const s = n.data(`${this.KEY}:ALL`);
      return s ? s.get(e) : void 0;
    }
    return n.data(this.KEY);
  }
  /**
   * Ensure the component instance of the given element.
   *
   * @param this      Current component constructor.
   * @param selector  The component element selector.
   * @param options   The component options.
   * @returns         The component instance.
   */
  static ensure(t, e) {
    const n = this.get(t, e == null ? void 0 : e.key);
    return n ? (e && n.setOptions(e), n) : new this(t, e);
  }
  /**
   * Get all component instances.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        All component instances.
   */
  static getAll(t) {
    const { MULTI_INSTANCE: e, DATA_KEY: n } = this, s = [];
    return g(t || document).find(`[${n}]`).each((i, r) => {
      if (e) {
        const a = g(r).data(`${this.KEY}:ALL`);
        if (a) {
          s.push(...a.values());
          return;
        }
      }
      const o = g(r).data(this.KEY);
      o && s.push(o);
    }), s;
  }
  /**
   * Query the component instance.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        The component instance.
   */
  static query(t, e) {
    return t === void 0 ? this.getAll().sort((n, s) => n.gid - s.gid)[0] : this.get(g(t).closest(`[${this.DATA_KEY}]`), e);
  }
  /**
   * Create cash fn.method for current component.
   *
   * @param name The method name.
   */
  static defineFn(t) {
    g.fn.extend({
      [t || this.NAME.replace(/(^[A-Z]+)/, (e) => e.toLowerCase())](e, ...n) {
        return this.each((s, i) => {
          var o;
          const r = this.ensure(i, typeof e == "object" ? e : void 0);
          typeof e == "string" && ((o = r[e]) == null || o.call(r, ...n));
        });
      }
    });
  }
};
let lt = Sc;
ke = /* @__PURE__ */ new WeakMap();
On = /* @__PURE__ */ new WeakMap();
qs = /* @__PURE__ */ new WeakMap();
Se = /* @__PURE__ */ new WeakMap();
Xe = /* @__PURE__ */ new WeakSet();
In = function(t) {
  return t.split(" ").map((e) => e.includes(".") ? e : `${e}${this.namespace}`).join(" ");
};
lt.DEFAULT = {};
lt.NAME = Sc.name;
lt.MULTI_INSTANCE = !1;
class J extends lt {
  constructor() {
    super(...arguments), this.ref = $t();
  }
  /**
   * The React component instance.
   */
  get $() {
    return this.ref.current;
  }
  /**
   * Render after component init.
   */
  afterInit() {
    this.render();
  }
  /**
   * Destroy component.
   */
  destroy() {
    var e, n;
    (n = (e = this.$) == null ? void 0 : e.componentWillUnmount) == null || n.call(e), this.element && (this.element.innerHTML = ""), super.destroy();
  }
  /**
   * Render component.
   *
   * @param options new options.
   */
  render(e) {
    us(
      _(this.constructor.Component, {
        ref: this.ref,
        ...this.setOptions(e)
      }),
      this.element
    );
  }
}
function Vd({
  component: t = "div",
  className: e,
  children: n,
  style: s,
  attrs: i
}) {
  return _(t, {
    className: N(e),
    style: s,
    ...i
  }, n);
}
function Cc({
  type: t,
  component: e = "a",
  className: n,
  children: s,
  attrs: i,
  url: r,
  disabled: o,
  active: a,
  icon: l,
  text: h,
  target: c,
  trailingIcon: u,
  hint: d,
  checked: f,
  onClick: p,
  ...m
}) {
  const v = [
    typeof f == "boolean" ? /* @__PURE__ */ y("div", { class: `checkbox-primary${f ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) }) : null,
    /* @__PURE__ */ y(ds, { icon: l }),
    /* @__PURE__ */ y("span", { className: "text", children: h }),
    typeof s == "function" ? s() : s,
    /* @__PURE__ */ y(ds, { icon: u })
  ];
  return _(e, {
    className: N(n, { disabled: o, active: a }),
    title: d,
    [e === "a" ? "href" : "data-url"]: r,
    [e === "a" ? "target" : "data-target"]: c,
    onClick: p,
    ...m,
    ...i
  }, ...v);
}
function Gd({
  component: t = "div",
  className: e,
  text: n,
  attrs: s,
  children: i,
  style: r,
  onClick: o
}) {
  return _(t, {
    className: N(e),
    style: r,
    onClick: o,
    ...s
  }, n, typeof i == "function" ? i() : i);
}
function Yd({
  component: t = "div",
  className: e,
  style: n,
  space: s,
  flex: i,
  attrs: r,
  onClick: o,
  children: a
}) {
  return _(t, {
    className: N(e),
    style: { width: s, height: s, flex: i, ...n },
    onClick: o,
    ...r
  }, a);
}
function Kd({ type: t, ...e }) {
  return /* @__PURE__ */ y(qo, { ...e });
}
function Ec({
  component: t = "div",
  className: e,
  children: n,
  style: s,
  attrs: i
}) {
  return _(t, {
    className: N(e),
    style: s,
    ...i
  }, n);
}
const Wr = class extends U {
  constructor() {
    super(...arguments), this.ref = $t();
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
    var t, e;
    (e = (t = this.props).beforeDestroy) == null || e.call(t, { menu: this });
  }
  afterRender(t) {
    var e, n;
    (n = (e = this.props).afterRender) == null || n.call(e, { menu: this, firstRender: t });
  }
  handleItemClick(t, e, n, s) {
    n && n.call(s.target, s, t, e);
    const { onClickItem: i } = this.props;
    i && i({ menu: this, item: t, index: e, event: s });
  }
  beforeRender() {
    var n;
    const t = { ...this.props }, e = (n = t.beforeRender) == null ? void 0 : n.call(t, { menu: this, options: t });
    return e && Object.assign(t, e), t;
  }
  getItemRenderProps(t, e, n) {
    const { commonItemProps: s, onClickItem: i } = t, r = { ...e };
    return s && Object.assign(r, s[e.type || "item"]), (i || e.onClick) && (r.onClick = this.handleItemClick.bind(this, r, n, e.onClick)), r.className = N(r.className), r;
  }
  renderItem(t, e, n) {
    if (!e)
      return null;
    const s = this.getItemRenderProps(t, e, n), { itemRender: i } = t;
    if (i) {
      if (typeof i == "object") {
        const p = i[e.type || "item"];
        if (p)
          return /* @__PURE__ */ y(p, { ...s });
      } else if (typeof i == "function") {
        const p = i.call(this, s, _);
        if (it(p))
          return p;
        typeof p == "object" && Object.assign(s, p);
      }
    }
    const { type: r = "item", component: o, key: a = n, rootAttrs: l, rootClass: h, rootStyle: c, rootChildren: u, ...d } = s;
    if (r === "html")
      return /* @__PURE__ */ y(
        "li",
        {
          className: N("action-menu-item", `${this.name}-html`, h, d.className),
          ...l,
          style: c || d.style,
          dangerouslySetInnerHTML: { __html: d.html }
        },
        a
      );
    const f = !o || typeof o == "string" ? this.constructor.ItemComponents && this.constructor.ItemComponents[r] || Wr.ItemComponents[r] : o;
    return Object.assign(d, {
      type: r,
      component: typeof o == "string" ? o : void 0
    }), t.checkbox && r === "item" && d.checked === void 0 && (d.checked = !!d.active), this.renderTypedItem(f, {
      className: N(h),
      children: u,
      style: c,
      key: a,
      ...l
    }, {
      ...d,
      type: r,
      component: typeof o == "string" ? o : void 0
    });
  }
  renderTypedItem(t, e, n) {
    const { children: s, className: i, key: r, ...o } = e;
    return /* @__PURE__ */ y(
      "li",
      {
        className: N(`${this.constructor.NAME}-item`, `${this.name}-${n.type}`, i),
        ...o,
        children: [
          /* @__PURE__ */ y(t, { ...n }),
          typeof s == "function" ? s() : s
        ]
      },
      r
    );
  }
  render() {
    const t = this.beforeRender(), {
      name: e,
      style: n,
      commonItemProps: s,
      className: i,
      items: r,
      children: o,
      itemRender: a,
      onClickItem: l,
      beforeRender: h,
      afterRender: c,
      beforeDestroy: u,
      ...d
    } = t, f = this.constructor.ROOT_TAG;
    return /* @__PURE__ */ y(f, { class: N(this.name, i), style: n, ...d, ref: this.ref, children: [
      r && r.map(this.renderItem.bind(this, t)),
      o
    ] });
  }
};
let qe = Wr;
qe.ItemComponents = {
  divider: Vd,
  item: Cc,
  heading: Gd,
  space: Yd,
  custom: Kd,
  basic: Ec
};
qe.ROOT_TAG = "menu";
qe.NAME = "action-menu";
class Mc extends J {
}
Mc.NAME = "ActionMenu";
Mc.Component = qe;
function Xd({
  items: t,
  show: e,
  level: n,
  ...s
}) {
  return /* @__PURE__ */ y(Cc, { ...s });
}
var Tc = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, wt = (t, e, n) => (Tc(t, e, "read from private field"), n ? n.call(t) : e.get(t)), _r = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Jd = (t, e, n, s) => (Tc(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Vs, Ht, Hn;
let Ji = class extends qe {
  constructor(e) {
    super(e), _r(this, Vs, /* @__PURE__ */ new Set()), _r(this, Ht, void 0), _r(this, Hn, (n, s, i) => {
      g(i.target).closest(".not-nested-toggle").length || (this.toggleNestedMenu(n, s), i.preventDefault());
    }), Jd(this, Ht, e.nestedShow === void 0), wt(this, Ht) && (this.state = { nestedShow: e.defaultNestedShow ?? {} });
  }
  get nestedTrigger() {
    return this.props.nestedTrigger;
  }
  beforeRender() {
    const e = super.beforeRender(), { nestedShow: n, nestedTrigger: s, defaultNestedShow: i, controlledMenu: r, indent: o, ...a } = e;
    return typeof a.items == "function" && (a.items = a.items(this)), !r && o && (a.style = Object.assign({
      [`--${this.name}-indent`]: `${o}px`
    }, a.style)), a;
  }
  getNestedMenuProps(e) {
    const { name: n, controlledMenu: s, nestedShow: i, beforeDestroy: r, beforeRender: o, itemRender: a, onClickItem: l, afterRender: h, commonItemProps: c, level: u } = this.props;
    return {
      items: e,
      name: n,
      nestedShow: wt(this, Ht) ? this.state.nestedShow : i,
      nestedTrigger: this.nestedTrigger,
      controlledMenu: s || this,
      commonItemProps: c,
      onClickItem: l,
      afterRender: h,
      beforeRender: o,
      beforeDestroy: r,
      itemRender: a,
      level: (u || 0) + 1
    };
  }
  renderNestedMenu(e) {
    let { items: n } = e;
    if (!n || (typeof n == "function" && (n = n(e, this)), !n.length))
      return;
    const s = this.constructor, i = this.getNestedMenuProps(n);
    return /* @__PURE__ */ y(s, { ...i, "data-level": i.level });
  }
  isNestedItem(e) {
    return (!e.type || e.type === "item") && !!e.items;
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  renderToggleIcon(e, n) {
  }
  getItemRenderProps(e, n, s) {
    const i = super.getItemRenderProps(e, n, s);
    if (i.level = e.level || 0, !this.isNestedItem(i))
      return i;
    const r = i.key ?? i.id ?? `${e.level || 0}:${s}`;
    wt(this, Vs).add(r);
    const o = this.isNestedMenuShow(r);
    if (o && (i.rootChildren = [
      i.rootChildren,
      this.renderNestedMenu(n)
    ]), this.nestedTrigger === "hover")
      i.rootAttrs = {
        ...i.rootAttrs,
        onMouseEnter: wt(this, Hn).bind(this, r, !0),
        onMouseLeave: wt(this, Hn).bind(this, r, !1)
      };
    else if (this.nestedTrigger === "click") {
      const { onClick: l } = i;
      i.onClick = (h) => {
        wt(this, Hn).call(this, r, void 0, h), l == null || l(h);
      };
    }
    const a = this.renderToggleIcon(o, i);
    return a && (i.children = [i.children, a]), i.show = o, i.rootClass = [i.rootClass, "has-nested-menu", o ? "show" : ""], i;
  }
  isNestedMenuShow(e) {
    const n = wt(this, Ht) ? this.state.nestedShow : this.props.nestedShow;
    return n && typeof n == "object" ? n[e] : !!n;
  }
  toggleNestedMenu(e, n) {
    const { controlledMenu: s } = this.props;
    if (s)
      return s.toggleNestedMenu(e, n);
    if (!wt(this, Ht))
      return !1;
    let { nestedShow: i = {} } = this.state;
    if (typeof i == "boolean" && (i === !0 ? i = [...wt(this, Vs).values()].reduce((r, o) => (r[o] = !0, r), {}) : i = {}), n === void 0)
      n = !i[e];
    else if (!!i[e] == !!n)
      return !1;
    return n ? i[e] = n : delete i[e], this.setState({ nestedShow: { ...i } }), !0;
  }
  showNestedMenu(e) {
    return this.toggleNestedMenu(e, !0);
  }
  hideNestedMenu(e) {
    return this.toggleNestedMenu(e, !1);
  }
  showAllNestedMenu() {
    wt(this, Ht) && this.setState({ nestedShow: !0 });
  }
  hideAllNestedMenu() {
    wt(this, Ht) && this.setState({ nestedShow: !1 });
  }
};
Vs = /* @__PURE__ */ new WeakMap();
Ht = /* @__PURE__ */ new WeakMap();
Hn = /* @__PURE__ */ new WeakMap();
Ji.ItemComponents = {
  item: Xd
};
class Rc extends J {
}
Rc.NAME = "ActionMenuNested";
Rc.Component = Ji;
let Zi = class extends Ji {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "menu-nested";
  }
  beforeRender() {
    const e = super.beforeRender();
    let { hasIcons: n } = e;
    return n === void 0 && (n = e.items.some((s) => s.icon)), e.className = N(e.className, this.menuName, {
      "has-icons": n,
      "has-nested-items": e.items.some((s) => this.isNestedItem(s)),
      "menu-popup": e.popup
    }), e;
  }
  renderToggleIcon(e) {
    return /* @__PURE__ */ y("span", { class: `${this.name}-toggle-icon caret-${e ? "down" : "right"}` });
  }
};
Zi.NAME = "menu";
class Ac extends J {
}
Ac.NAME = "Menu";
Ac.Component = Zi;
class Lt extends U {
  render() {
    const {
      component: e,
      type: n,
      btnType: s,
      size: i,
      className: r,
      children: o,
      url: a,
      target: l,
      disabled: h,
      active: c,
      loading: u,
      loadingIcon: d,
      loadingText: f,
      icon: p,
      text: m,
      trailingIcon: v,
      caret: w,
      square: b,
      hint: k,
      ...C
    } = this.props, E = e || (a ? "a" : "button"), P = m == null || typeof m == "string" && !m.length || u && !f, M = w && P && !p && !v && !o && !u;
    return _(
      E,
      {
        className: N("btn", n, r, {
          "btn-caret": M,
          disabled: h || u,
          active: c,
          loading: u,
          square: b === void 0 ? !M && !o && P : b
        }, i ? `size-${i}` : ""),
        title: k,
        [E === "a" ? "href" : "data-url"]: a,
        [E === "a" ? "target" : "data-target"]: l,
        type: E === "button" ? s : void 0,
        ...C
      },
      /* @__PURE__ */ y(ds, { icon: u ? `icon ${d || "icon-spinner-snake"} spin` : p }),
      P ? null : /* @__PURE__ */ y("span", { className: "text", children: u ? f : m }),
      u ? null : o,
      u ? null : typeof v == "string" ? /* @__PURE__ */ y("i", { class: `icon ${v}` }) : v,
      u ? null : w ? /* @__PURE__ */ y("span", { className: typeof w == "string" ? `caret-${w}` : "caret" }) : null
    );
  }
}
function Zd({
  key: t,
  type: e,
  btnType: n,
  ...s
}) {
  return /* @__PURE__ */ y(Lt, { type: n, ...s });
}
function Ss(t) {
  return t.split("-")[1];
}
function Go(t) {
  return t === "y" ? "height" : "width";
}
function De(t) {
  return t.split("-")[0];
}
function Cs(t) {
  return ["top", "bottom"].includes(De(t)) ? "x" : "y";
}
function dl(t, e, n) {
  let { reference: s, floating: i } = t;
  const r = s.x + s.width / 2 - i.width / 2, o = s.y + s.height / 2 - i.height / 2, a = Cs(e), l = Go(a), h = s[l] / 2 - i[l] / 2, c = a === "x";
  let u;
  switch (De(e)) {
    case "top":
      u = { x: r, y: s.y - i.height };
      break;
    case "bottom":
      u = { x: r, y: s.y + s.height };
      break;
    case "right":
      u = { x: s.x + s.width, y: o };
      break;
    case "left":
      u = { x: s.x - i.width, y: o };
      break;
    default:
      u = { x: s.x, y: s.y };
  }
  switch (Ss(e)) {
    case "start":
      u[a] -= h * (n && c ? -1 : 1);
      break;
    case "end":
      u[a] += h * (n && c ? -1 : 1);
  }
  return u;
}
const Qd = async (t, e, n) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: r = [], platform: o } = n, a = r.filter(Boolean), l = await (o.isRTL == null ? void 0 : o.isRTL(e));
  let h = await o.getElementRects({ reference: t, floating: e, strategy: i }), { x: c, y: u } = dl(h, s, l), d = s, f = {}, p = 0;
  for (let m = 0; m < a.length; m++) {
    const { name: v, fn: w } = a[m], { x: b, y: k, data: C, reset: E } = await w({ x: c, y: u, initialPlacement: s, placement: d, strategy: i, middlewareData: f, rects: h, platform: o, elements: { reference: t, floating: e } });
    c = b ?? c, u = k ?? u, f = { ...f, [v]: { ...f[v], ...C } }, E && p <= 50 && (p++, typeof E == "object" && (E.placement && (d = E.placement), E.rects && (h = E.rects === !0 ? await o.getElementRects({ reference: t, floating: e, strategy: i }) : E.rects), { x: c, y: u } = dl(h, d, l)), m = -1);
  }
  return { x: c, y: u, placement: d, strategy: i, middlewareData: f };
};
function Nc(t) {
  return typeof t != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(t) : { top: t, right: t, bottom: t, left: t };
}
function yi(t) {
  return { ...t, top: t.y, left: t.x, right: t.x + t.width, bottom: t.y + t.height };
}
async function Lc(t, e) {
  var n;
  e === void 0 && (e = {});
  const { x: s, y: i, platform: r, rects: o, elements: a, strategy: l } = t, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: u = "floating", altBoundary: d = !1, padding: f = 0 } = e, p = Nc(f), m = a[d ? u === "floating" ? "reference" : "floating" : u], v = yi(await r.getClippingRect({ element: (n = await (r.isElement == null ? void 0 : r.isElement(m))) == null || n ? m : m.contextElement || await (r.getDocumentElement == null ? void 0 : r.getDocumentElement(a.floating)), boundary: h, rootBoundary: c, strategy: l })), w = u === "floating" ? { ...o.floating, x: s, y: i } : o.reference, b = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(a.floating)), k = await (r.isElement == null ? void 0 : r.isElement(b)) && await (r.getScale == null ? void 0 : r.getScale(b)) || { x: 1, y: 1 }, C = yi(r.convertOffsetParentRelativeRectToViewportRelativeRect ? await r.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: w, offsetParent: b, strategy: l }) : w);
  return { top: (v.top - C.top + p.top) / k.y, bottom: (C.bottom - v.bottom + p.bottom) / k.y, left: (v.left - C.left + p.left) / k.x, right: (C.right - v.right + p.right) / k.x };
}
const tf = Math.min, ef = Math.max;
function Or(t, e, n) {
  return ef(t, tf(e, n));
}
const Ir = (t) => ({ name: "arrow", options: t, async fn(e) {
  const { element: n, padding: s = 0 } = t || {}, { x: i, y: r, placement: o, rects: a, platform: l, elements: h } = e;
  if (n == null)
    return {};
  const c = Nc(s), u = { x: i, y: r }, d = Cs(o), f = Go(d), p = await l.getDimensions(n), m = d === "y", v = m ? "top" : "left", w = m ? "bottom" : "right", b = m ? "clientHeight" : "clientWidth", k = a.reference[f] + a.reference[d] - u[d] - a.floating[f], C = u[d] - a.reference[d], E = await (l.getOffsetParent == null ? void 0 : l.getOffsetParent(n));
  let P = E ? E[b] : 0;
  P && await (l.isElement == null ? void 0 : l.isElement(E)) || (P = h.floating[b] || a.floating[f]);
  const M = k / 2 - C / 2, T = c[v], A = P - p[f] - c[w], x = P / 2 - p[f] / 2 + M, $ = Or(T, x, A), L = Ss(o) != null && x != $ && a.reference[f] / 2 - (x < T ? c[v] : c[w]) - p[f] / 2 < 0;
  return { [d]: u[d] - (L ? x < T ? T - x : A - x : 0), data: { [d]: $, centerOffset: x - $ } };
} }), nf = ["top", "right", "bottom", "left"];
nf.reduce((t, e) => t.concat(e, e + "-start", e + "-end"), []);
const sf = { left: "right", right: "left", bottom: "top", top: "bottom" };
function wi(t) {
  return t.replace(/left|right|bottom|top/g, (e) => sf[e]);
}
function rf(t, e, n) {
  n === void 0 && (n = !1);
  const s = Ss(t), i = Cs(t), r = Go(i);
  let o = i === "x" ? s === (n ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return e.reference[r] > e.floating[r] && (o = wi(o)), { main: o, cross: wi(o) };
}
const of = { start: "end", end: "start" };
function xr(t) {
  return t.replace(/start|end/g, (e) => of[e]);
}
const Qi = function(t) {
  return t === void 0 && (t = {}), { name: "flip", options: t, async fn(e) {
    var n;
    const { placement: s, middlewareData: i, rects: r, initialPlacement: o, platform: a, elements: l } = e, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: u, fallbackStrategy: d = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...m } = t, v = De(s), w = De(o) === o, b = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), k = u || (w || !p ? [wi(o)] : function($) {
      const L = wi($);
      return [xr($), L, xr(L)];
    }(o));
    u || f === "none" || k.push(...function($, L, W, H) {
      const B = Ss($);
      let G = function(Pt, Mn, As) {
        const Tn = ["left", "right"], Ns = ["right", "left"], pr = ["top", "bottom"], zu = ["bottom", "top"];
        switch (Pt) {
          case "top":
          case "bottom":
            return As ? Mn ? Ns : Tn : Mn ? Tn : Ns;
          case "left":
          case "right":
            return Mn ? pr : zu;
          default:
            return [];
        }
      }(De($), W === "start", H);
      return B && (G = G.map((Pt) => Pt + "-" + B), L && (G = G.concat(G.map(xr)))), G;
    }(o, p, f, b));
    const C = [o, ...k], E = await Lc(e, m), P = [];
    let M = ((n = i.flip) == null ? void 0 : n.overflows) || [];
    if (h && P.push(E[v]), c) {
      const { main: $, cross: L } = rf(s, r, b);
      P.push(E[$], E[L]);
    }
    if (M = [...M, { placement: s, overflows: P }], !P.every(($) => $ <= 0)) {
      var T, A;
      const $ = (((T = i.flip) == null ? void 0 : T.index) || 0) + 1, L = C[$];
      if (L)
        return { data: { index: $, overflows: M }, reset: { placement: L } };
      let W = (A = M.filter((H) => H.overflows[0] <= 0).sort((H, B) => H.overflows[1] - B.overflows[1])[0]) == null ? void 0 : A.placement;
      if (!W)
        switch (d) {
          case "bestFit": {
            var x;
            const H = (x = M.map((B) => [B.placement, B.overflows.filter((G) => G > 0).reduce((G, Pt) => G + Pt, 0)]).sort((B, G) => B[1] - G[1])[0]) == null ? void 0 : x[0];
            H && (W = H);
            break;
          }
          case "initialPlacement":
            W = o;
        }
      if (s !== W)
        return { reset: { placement: W } };
    }
    return {};
  } };
}, Yo = function(t) {
  return t === void 0 && (t = 0), { name: "offset", options: t, async fn(e) {
    const { x: n, y: s } = e, i = await async function(r, o) {
      const { placement: a, platform: l, elements: h } = r, c = await (l.isRTL == null ? void 0 : l.isRTL(h.floating)), u = De(a), d = Ss(a), f = Cs(a) === "x", p = ["left", "top"].includes(u) ? -1 : 1, m = c && f ? -1 : 1, v = typeof o == "function" ? o(r) : o;
      let { mainAxis: w, crossAxis: b, alignmentAxis: k } = typeof v == "number" ? { mainAxis: v, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...v };
      return d && typeof k == "number" && (b = d === "end" ? -1 * k : k), f ? { x: b * m, y: w * p } : { x: w * p, y: b * m };
    }(e, t);
    return { x: n + i.x, y: s + i.y, data: i };
  } };
};
function af(t) {
  return t === "x" ? "y" : "x";
}
const Hr = function(t) {
  return t === void 0 && (t = {}), { name: "shift", options: t, async fn(e) {
    const { x: n, y: s, placement: i } = e, { mainAxis: r = !0, crossAxis: o = !1, limiter: a = { fn: (v) => {
      let { x: w, y: b } = v;
      return { x: w, y: b };
    } }, ...l } = t, h = { x: n, y: s }, c = await Lc(e, l), u = Cs(De(i)), d = af(u);
    let f = h[u], p = h[d];
    if (r) {
      const v = u === "y" ? "bottom" : "right";
      f = Or(f + c[u === "y" ? "top" : "left"], f, f - c[v]);
    }
    if (o) {
      const v = d === "y" ? "bottom" : "right";
      p = Or(p + c[d === "y" ? "top" : "left"], p, p - c[v]);
    }
    const m = a.fn({ ...e, [u]: f, [d]: p });
    return { ...m, data: { x: m.x - n, y: m.y - s } };
  } };
};
function mt(t) {
  var e;
  return ((e = t.ownerDocument) == null ? void 0 : e.defaultView) || window;
}
function _t(t) {
  return mt(t).getComputedStyle(t);
}
function Dc(t) {
  return t instanceof mt(t).Node;
}
function be(t) {
  return Dc(t) ? (t.nodeName || "").toLowerCase() : "";
}
function kt(t) {
  return t instanceof mt(t).HTMLElement;
}
function ht(t) {
  return t instanceof mt(t).Element;
}
function fl(t) {
  return typeof ShadowRoot > "u" ? !1 : t instanceof mt(t).ShadowRoot || t instanceof ShadowRoot;
}
function fs(t) {
  const { overflow: e, overflowX: n, overflowY: s, display: i } = _t(t);
  return /auto|scroll|overlay|hidden|clip/.test(e + s + n) && !["inline", "contents"].includes(i);
}
function lf(t) {
  return ["table", "td", "th"].includes(be(t));
}
function Br(t) {
  const e = Ko(), n = _t(t);
  return n.transform !== "none" || n.perspective !== "none" || !e && !!n.backdropFilter && n.backdropFilter !== "none" || !e && !!n.filter && n.filter !== "none" || ["transform", "perspective", "filter"].some((s) => (n.willChange || "").includes(s)) || ["paint", "layout", "strict", "content"].some((s) => (n.contain || "").includes(s));
}
function Ko() {
  return !(typeof CSS > "u" || !CSS.supports) && CSS.supports("-webkit-backdrop-filter", "none");
}
function tr(t) {
  return ["html", "body", "#document"].includes(be(t));
}
const pl = Math.min, Qn = Math.max, vi = Math.round;
function Pc(t) {
  const e = _t(t);
  let n = parseFloat(e.width) || 0, s = parseFloat(e.height) || 0;
  const i = kt(t), r = i ? t.offsetWidth : n, o = i ? t.offsetHeight : s, a = vi(n) !== r || vi(s) !== o;
  return a && (n = r, s = o), { width: n, height: s, fallback: a };
}
function Wc(t) {
  return ht(t) ? t : t.contextElement;
}
const Oc = { x: 1, y: 1 };
function cn(t) {
  const e = Wc(t);
  if (!kt(e))
    return Oc;
  const n = e.getBoundingClientRect(), { width: s, height: i, fallback: r } = Pc(e);
  let o = (r ? vi(n.width) : n.width) / s, a = (r ? vi(n.height) : n.height) / i;
  return o && Number.isFinite(o) || (o = 1), a && Number.isFinite(a) || (a = 1), { x: o, y: a };
}
const gl = { x: 0, y: 0 };
function Ic(t, e, n) {
  var s, i;
  if (e === void 0 && (e = !0), !Ko())
    return gl;
  const r = t ? mt(t) : window;
  return !n || e && n !== r ? gl : { x: ((s = r.visualViewport) == null ? void 0 : s.offsetLeft) || 0, y: ((i = r.visualViewport) == null ? void 0 : i.offsetTop) || 0 };
}
function He(t, e, n, s) {
  e === void 0 && (e = !1), n === void 0 && (n = !1);
  const i = t.getBoundingClientRect(), r = Wc(t);
  let o = Oc;
  e && (s ? ht(s) && (o = cn(s)) : o = cn(t));
  const a = Ic(r, n, s);
  let l = (i.left + a.x) / o.x, h = (i.top + a.y) / o.y, c = i.width / o.x, u = i.height / o.y;
  if (r) {
    const d = mt(r), f = s && ht(s) ? mt(s) : s;
    let p = d.frameElement;
    for (; p && s && f !== d; ) {
      const m = cn(p), v = p.getBoundingClientRect(), w = getComputedStyle(p);
      v.x += (p.clientLeft + parseFloat(w.paddingLeft)) * m.x, v.y += (p.clientTop + parseFloat(w.paddingTop)) * m.y, l *= m.x, h *= m.y, c *= m.x, u *= m.y, l += v.x, h += v.y, p = mt(p).frameElement;
    }
  }
  return yi({ width: c, height: u, x: l, y: h });
}
function ye(t) {
  return ((Dc(t) ? t.ownerDocument : t.document) || window.document).documentElement;
}
function er(t) {
  return ht(t) ? { scrollLeft: t.scrollLeft, scrollTop: t.scrollTop } : { scrollLeft: t.pageXOffset, scrollTop: t.pageYOffset };
}
function Hc(t) {
  return He(ye(t)).left + er(t).scrollLeft;
}
function Cn(t) {
  if (be(t) === "html")
    return t;
  const e = t.assignedSlot || t.parentNode || fl(t) && t.host || ye(t);
  return fl(e) ? e.host : e;
}
function Bc(t) {
  const e = Cn(t);
  return tr(e) ? e.ownerDocument.body : kt(e) && fs(e) ? e : Bc(e);
}
function ts(t, e) {
  var n;
  e === void 0 && (e = []);
  const s = Bc(t), i = s === ((n = t.ownerDocument) == null ? void 0 : n.body), r = mt(s);
  return i ? e.concat(r, r.visualViewport || [], fs(s) ? s : []) : e.concat(s, ts(s));
}
function ml(t, e, n) {
  let s;
  if (e === "viewport")
    s = function(i, r) {
      const o = mt(i), a = ye(i), l = o.visualViewport;
      let h = a.clientWidth, c = a.clientHeight, u = 0, d = 0;
      if (l) {
        h = l.width, c = l.height;
        const f = Ko();
        (!f || f && r === "fixed") && (u = l.offsetLeft, d = l.offsetTop);
      }
      return { width: h, height: c, x: u, y: d };
    }(t, n);
  else if (e === "document")
    s = function(i) {
      const r = ye(i), o = er(i), a = i.ownerDocument.body, l = Qn(r.scrollWidth, r.clientWidth, a.scrollWidth, a.clientWidth), h = Qn(r.scrollHeight, r.clientHeight, a.scrollHeight, a.clientHeight);
      let c = -o.scrollLeft + Hc(i);
      const u = -o.scrollTop;
      return _t(a).direction === "rtl" && (c += Qn(r.clientWidth, a.clientWidth) - l), { width: l, height: h, x: c, y: u };
    }(ye(t));
  else if (ht(e))
    s = function(i, r) {
      const o = He(i, !0, r === "fixed"), a = o.top + i.clientTop, l = o.left + i.clientLeft, h = kt(i) ? cn(i) : { x: 1, y: 1 };
      return { width: i.clientWidth * h.x, height: i.clientHeight * h.y, x: l * h.x, y: a * h.y };
    }(e, n);
  else {
    const i = Ic(t);
    s = { ...e, x: e.x - i.x, y: e.y - i.y };
  }
  return yi(s);
}
function zc(t, e) {
  const n = Cn(t);
  return !(n === e || !ht(n) || tr(n)) && (_t(n).position === "fixed" || zc(n, e));
}
function yl(t, e) {
  return kt(t) && _t(t).position !== "fixed" ? e ? e(t) : t.offsetParent : null;
}
function wl(t, e) {
  const n = mt(t);
  if (!kt(t))
    return n;
  let s = yl(t, e);
  for (; s && lf(s) && _t(s).position === "static"; )
    s = yl(s, e);
  return s && (be(s) === "html" || be(s) === "body" && _t(s).position === "static" && !Br(s)) ? n : s || function(i) {
    let r = Cn(i);
    for (; kt(r) && !tr(r); ) {
      if (Br(r))
        return r;
      r = Cn(r);
    }
    return null;
  }(t) || n;
}
function cf(t, e, n) {
  const s = kt(e), i = ye(e), r = n === "fixed", o = He(t, !0, r, e);
  let a = { scrollLeft: 0, scrollTop: 0 };
  const l = { x: 0, y: 0 };
  if (s || !s && !r)
    if ((be(e) !== "body" || fs(i)) && (a = er(e)), kt(e)) {
      const h = He(e, !0, r, e);
      l.x = h.x + e.clientLeft, l.y = h.y + e.clientTop;
    } else
      i && (l.x = Hc(i));
  return { x: o.left + a.scrollLeft - l.x, y: o.top + a.scrollTop - l.y, width: o.width, height: o.height };
}
const hf = { getClippingRect: function(t) {
  let { element: e, boundary: n, rootBoundary: s, strategy: i } = t;
  const r = n === "clippingAncestors" ? function(h, c) {
    const u = c.get(h);
    if (u)
      return u;
    let d = ts(h).filter((v) => ht(v) && be(v) !== "body"), f = null;
    const p = _t(h).position === "fixed";
    let m = p ? Cn(h) : h;
    for (; ht(m) && !tr(m); ) {
      const v = _t(m), w = Br(m);
      w || v.position !== "fixed" || (f = null), (p ? !w && !f : !w && v.position === "static" && f && ["absolute", "fixed"].includes(f.position) || fs(m) && !w && zc(h, m)) ? d = d.filter((b) => b !== m) : f = v, m = Cn(m);
    }
    return c.set(h, d), d;
  }(e, this._c) : [].concat(n), o = [...r, s], a = o[0], l = o.reduce((h, c) => {
    const u = ml(e, c, i);
    return h.top = Qn(u.top, h.top), h.right = pl(u.right, h.right), h.bottom = pl(u.bottom, h.bottom), h.left = Qn(u.left, h.left), h;
  }, ml(e, a, i));
  return { width: l.right - l.left, height: l.bottom - l.top, x: l.left, y: l.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(t) {
  let { rect: e, offsetParent: n, strategy: s } = t;
  const i = kt(n), r = ye(n);
  if (n === r)
    return e;
  let o = { scrollLeft: 0, scrollTop: 0 }, a = { x: 1, y: 1 };
  const l = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((be(n) !== "body" || fs(r)) && (o = er(n)), kt(n))) {
    const h = He(n);
    a = cn(n), l.x = h.x + n.clientLeft, l.y = h.y + n.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - o.scrollLeft * a.x + l.x, y: e.y * a.y - o.scrollTop * a.y + l.y };
}, isElement: ht, getDimensions: function(t) {
  return Pc(t);
}, getOffsetParent: wl, getDocumentElement: ye, getScale: cn, async getElementRects(t) {
  let { reference: e, floating: n, strategy: s } = t;
  const i = this.getOffsetParent || wl, r = this.getDimensions;
  return { reference: cf(e, await i(n), s), floating: { x: 0, y: 0, ...await r(n) } };
}, getClientRects: (t) => Array.from(t.getClientRects()), isRTL: (t) => _t(t).direction === "rtl" };
function Xo(t, e, n, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: r = !0, elementResize: o = !0, animationFrame: a = !1 } = s, l = i || r ? [...ht(t) ? ts(t) : t.contextElement ? ts(t.contextElement) : [], ...ts(e)] : [];
  l.forEach((d) => {
    const f = !ht(d) && d.toString().includes("V");
    !i || a && !f || d.addEventListener("scroll", n, { passive: !0 }), r && d.addEventListener("resize", n);
  });
  let h, c = null;
  o && (c = new ResizeObserver(() => {
    n();
  }), ht(t) && !a && c.observe(t), ht(t) || !t.contextElement || a || c.observe(t.contextElement), c.observe(e));
  let u = a ? He(t) : null;
  return a && function d() {
    const f = He(t);
    !u || f.x === u.x && f.y === u.y && f.width === u.width && f.height === u.height || n(), u = f, h = requestAnimationFrame(d);
  }(), n(), () => {
    var d;
    l.forEach((f) => {
      i && f.removeEventListener("scroll", n), r && f.removeEventListener("resize", n);
    }), (d = c) == null || d.disconnect(), c = null, a && cancelAnimationFrame(h);
  };
}
const nr = (t, e, n) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: hf, ...n }, r = { ...i.platform, _c: s };
  return Qd(t, e, { ...i, platform: r });
};
let uf = class extends Zi {
  get nestedTrigger() {
    return this.props.nestedTrigger || "hover";
  }
  get name() {
    return "menu";
  }
  get menuName() {
    return "menu-context";
  }
  getPopperOptions() {
    return {
      middleware: [Qi()],
      placement: "right-start"
    };
  }
  getPopperElement() {
    var e;
    return (e = this.ref.current) == null ? void 0 : e.parentElement;
  }
  createPopper() {
    const e = this.getPopperOptions();
    this.ref.current && nr(this.getPopperElement(), this.ref.current, e).then(({ x: n, y: s }) => {
      Object.assign(this.ref.current.style, {
        left: `${n}px`,
        top: `${s}px`,
        position: "absolute"
      });
    });
  }
  afterRender(e) {
    super.afterRender(e), this.props.controlledMenu && this.createPopper();
  }
  beforeRender() {
    const e = super.beforeRender();
    return e.className = N(e.className, "menu-popup"), e;
  }
  renderToggleIcon() {
    return /* @__PURE__ */ y("span", { class: "contextmenu-toggle-icon caret-right" });
  }
};
var Jo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Ot = (t, e, n) => (Jo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ge = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ds = (t, e, n, s) => (Jo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), vl = (t, e, n) => (Jo(t, e, "access private method"), n), oe, Bn, Gs, Ys, zr, Fc, Fr, jc;
const $r = "show", df = '[data-toggle="contextmenu"]';
class bt extends lt {
  constructor() {
    super(...arguments), Ge(this, zr), Ge(this, Fr), Ge(this, oe, void 0), Ge(this, Bn, void 0), Ge(this, Gs, void 0), Ge(this, Ys, void 0);
  }
  get isShown() {
    return Ot(this, oe) && g(Ot(this, oe)).hasClass($r);
  }
  get menu() {
    return Ot(this, oe) || this.ensureMenu();
  }
  get trigger() {
    return Ot(this, Gs) || this.element;
  }
  get isDynamic() {
    return this.options.items || this.options.menu;
  }
  init() {
    const { $element: e } = this;
    !e.is("body") && !e.attr("data-toggle") && e.attr("data-toggle", this.constructor.NAME.toLowerCase());
  }
  show(e) {
    return this.isShown || (Ds(this, Gs, e), this.emit("show", this.trigger).defaultPrevented) || this.isDynamic && !this.renderMenu() ? !1 : (g(this.menu).addClass($r), this.createPopper(), this.emit("shown"), !0);
  }
  hide() {
    var e;
    return !this.isShown || ((e = Ot(this, Ys)) == null || e.call(this), this.emit("hide").defaultPrevented) ? !1 : (g(Ot(this, oe)).removeClass($r), this.emit("hidden"), !0);
  }
  toggle(e) {
    return this.isShown ? this.hide() : this.show(e);
  }
  destroy() {
    var e;
    super.destroy(), this.hide(), (e = Ot(this, oe)) == null || e.remove();
  }
  ensureMenu() {
    const { $element: e } = this, n = this.constructor.MENU_CLASS;
    let s;
    if (this.isDynamic)
      s = g(`<div class="${n}" />`).appendTo("body");
    else if (e.length) {
      const i = e.attr("href") || e.dataset("target") || "";
      if (i[0] === "#" && (s = g(document).find(i)), !(s != null && s.length)) {
        const r = e.next();
        r.hasClass(n) ? s = r : s = e.parent().find(`.${n}`);
      }
      s && s.addClass("menu-popup");
    }
    if (!(s != null && s.length))
      throw new Error("[ZUI] ContextMenu: Cannot find menu element.");
    return s.css({
      width: "max-content",
      position: this.options.strategy,
      top: 0,
      left: 0
    }), Ds(this, oe, s[0]), s[0];
  }
  getPopperOptions() {
    var i;
    const { placement: e, strategy: n } = this.options, s = {
      middleware: [],
      placement: e,
      strategy: n
    };
    return this.options.flip && ((i = s.middleware) == null || i.push(Qi())), s;
  }
  createPopper() {
    const e = this.getPopperOptions(), n = this.getPopperElement(), s = this.menu;
    Ds(this, Ys, Xo(n, s, () => {
      nr(n, s, e).then(({ x: i, y: r, middlewareData: o, placement: a }) => {
        g(s).css({ left: `${i}px`, top: `${r}px` });
        const l = a.split("-")[0], h = vl(this, zr, Fc).call(this, l);
        if (o.arrow && this.arrowEl) {
          const { x: c, y: u } = o.arrow;
          g(this.arrowEl).css({
            left: c != null ? `${c}px` : "",
            top: u != null ? `${u}px` : "",
            [h]: `${-this.arrowEl.offsetWidth / 2}px`,
            background: "inherit",
            border: "inherit",
            ...vl(this, Fr, jc).call(this, l)
          });
        }
      });
    }));
  }
  getMenuOptions() {
    const { menu: e, items: n } = this.options;
    let s = n || (e == null ? void 0 : e.items);
    if (s)
      return typeof s == "function" && (s = s(this)), {
        nestedTrigger: "hover",
        ...e,
        items: s
      };
  }
  renderMenu() {
    const e = this.getMenuOptions();
    return !e || this.emit("updateMenu", e, this.trigger).defaultPrevented ? !1 : (us(_(uf, e), this.menu), !0);
  }
  getPopperElement() {
    return Ot(this, Bn) || Ds(this, Bn, {
      getBoundingClientRect: () => {
        const { trigger: e } = this;
        if (e instanceof MouseEvent) {
          const { clientX: n, clientY: s } = e;
          return {
            width: 0,
            height: 0,
            top: s,
            right: n,
            bottom: s,
            left: n
          };
        }
        return e instanceof HTMLElement ? e.getBoundingClientRect() : e;
      },
      contextElement: this.element
    }), Ot(this, Bn);
  }
  static clear(e) {
    var a, l;
    e instanceof Event && (e = { event: e });
    const { event: n, exclude: s, ignoreSelector: i = ".not-hide-menu" } = e || {};
    if (n && i && ((l = (a = n.target).closest) != null && l.call(a, i)) || n && n.button === 2)
      return;
    const r = this.getAll(), o = new Set(s || []);
    for (const h of r)
      o.has(h.element) || h.hide();
  }
  static show(e) {
    const { event: n, ...s } = e, i = this.ensure(document.body);
    return i.setOptions(s), i.show(n), n instanceof Event && n.stopPropagation(), i;
  }
  static hide(e) {
    const n = this.query(e);
    return n == null || n.hide(), n;
  }
}
oe = /* @__PURE__ */ new WeakMap();
Bn = /* @__PURE__ */ new WeakMap();
Gs = /* @__PURE__ */ new WeakMap();
Ys = /* @__PURE__ */ new WeakMap();
zr = /* @__PURE__ */ new WeakSet();
Fc = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
};
Fr = /* @__PURE__ */ new WeakSet();
jc = function(t) {
  const e = {
    bottom: "Right",
    top: "Left",
    left: "Bottom",
    right: "Top"
  };
  return {
    [`border${t[0].toUpperCase()}${t.substring(1)}Style`]: "none",
    [`border${e[t]}Style`]: "none"
  };
};
bt.MENU_CLASS = "contextmenu";
bt.NAME = "ContextMenu";
bt.MULTI_INSTANCE = !0;
bt.DEFAULT = {
  placement: "bottom-start",
  strategy: "fixed",
  flip: !0,
  preventOverflow: !0
};
g(document).on("contextmenu.contextmenu.zui", (t) => {
  const e = g(t.target);
  if (e.closest(`.${bt.MENU_CLASS}`).length)
    return;
  const n = e.closest(df).not(":disabled,.disabled");
  n.length && (bt.ensure(n).show(t), t.preventDefault());
}).on("click.contextmenu.zui", bt.clear.bind(bt));
var Zo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, zn = (t, e, n) => (Zo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ps = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, jr = (t, e, n, s) => (Zo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), ff = (t, e, n) => (Zo(t, e, "access private method"), n), es, Fn, bi, Ur, Uc;
const bl = '[data-toggle="dropdown"]', qc = class extends bt {
  constructor() {
    super(...arguments), Ps(this, Ur), Ps(this, es, !1), Ps(this, Fn, 0), this.hideLater = () => {
      zn(this, bi).call(this), jr(this, Fn, window.setTimeout(this.hide.bind(this), 100));
    }, Ps(this, bi, () => {
      clearTimeout(zn(this, Fn)), jr(this, Fn, 0);
    });
  }
  get isHover() {
    return this.options.trigger === "hover";
  }
  get elementShowClass() {
    return `with-${this.constructor.NAME}-show`;
  }
  show(t, e) {
    (e == null ? void 0 : e.clearOthers) !== !1 && qc.clear({ event: e == null ? void 0 : e.event, exclude: [this.element] });
    const n = super.show(t);
    return n && (!zn(this, es) && this.isHover && ff(this, Ur, Uc).call(this), this.$element.addClass(this.elementShowClass)), n;
  }
  hide() {
    const t = super.hide();
    return t && this.$element.removeClass(this.elementShowClass), t;
  }
  toggle(t, e) {
    return this.isShown ? this.hide() : this.show(t, { event: t, ...e });
  }
  destroy() {
    zn(this, es) && g(this.menu).off(this.constructor.NAMESPACE), super.destroy();
  }
  getArrowSize() {
    const { arrow: t } = this.options;
    return t ? typeof t == "number" ? t : 8 : 0;
  }
  getPopperOptions() {
    var n, s;
    const t = super.getPopperOptions(), e = this.getArrowSize();
    return e && this.arrowEl && ((n = t.middleware) == null || n.push(Yo(e)), (s = t.middleware) == null || s.push(Ir({ element: this.arrowEl }))), t;
  }
  ensureMenu() {
    const t = super.ensureMenu();
    if (this.options.arrow) {
      const e = this.getArrowSize(), n = g('<div class="arrow-el" />').css({
        position: "absolute",
        width: `${e}px`,
        height: `${e}px`,
        transform: "rotate(45deg)"
      });
      this.arrowEl = n[0];
    }
    return t;
  }
  getMenuOptions() {
    const t = super.getMenuOptions();
    if (t && this.options.arrow) {
      const { afterRender: e } = t;
      t.afterRender = (...n) => {
        this.arrowEl && g(this.menu).find(".menu").each((s, i) => {
          g(i).find(".arrow-el").length === 0 && g(i).parent().hasClass("dropdown-menu") && g(i).append(this.arrowEl);
        }), e == null || e(...n);
      };
    }
    return t;
  }
};
let we = qc;
es = /* @__PURE__ */ new WeakMap();
Fn = /* @__PURE__ */ new WeakMap();
bi = /* @__PURE__ */ new WeakMap();
Ur = /* @__PURE__ */ new WeakSet();
Uc = function() {
  g(this.menu).on(`mouseenter${this.constructor.NAMESPACE}`, zn(this, bi)).on(`mouseleave${this.constructor.NAMESPACE}`, this.hideLater), this.on("mouseleave", this.hideLater), jr(this, es, !0);
};
we.MENU_CLASS = "dropdown-menu";
we.NAME = "Dropdown";
we.DEFAULT = {
  ...bt.DEFAULT,
  strategy: "fixed",
  trigger: "click"
};
g(document).on("click", function(t) {
  const e = g(t.target).closest(bl).not(":disabled,.disabled");
  if (e.length) {
    const n = we.ensure(e);
    n.options.trigger === "click" && n.toggle();
  } else
    we.clear({ event: t });
}).on("mouseover", function(t) {
  var s, i;
  const e = (i = (s = t.target).closest) == null ? void 0 : i.call(s, bl);
  if (!e)
    return;
  const n = we.ensure(e);
  n.isHover && n.show();
});
let Ws = 0;
window.addEventListener("scroll", (t) => {
  Ws && clearTimeout(Ws), Ws = window.setTimeout(() => {
    we.clear({ event: t }), Ws = 0;
  }, 50);
}, !0);
var vs, pn;
class pf extends U {
  constructor(n) {
    var s;
    super(n);
    O(this, vs, void 0);
    O(this, pn, $t());
    this.state = { placement: ((s = n.dropdown) == null ? void 0 : s.placement) || "", show: !1 };
  }
  get ref() {
    return D(this, pn);
  }
  get triggerElement() {
    return D(this, pn).current;
  }
  componentDidMount() {
    const { modifiers: n = [], ...s } = this.props.dropdown || {};
    n.push({
      name: "dropdown-trigger",
      enabled: !0,
      phase: "beforeMain",
      fn: ({ state: i }) => {
        var o;
        const r = ((o = i.placement) == null ? void 0 : o.split("-").shift()) || "";
        this.setState({ placement: r });
      }
    }), F(this, vs, we.ensure(this.triggerElement, {
      ...s,
      modifiers: n,
      onShow: () => {
        this.setState({ show: !0 });
      },
      onHide: () => {
        this.setState({ show: !0 });
      }
    }));
  }
  componentWillUnmount() {
    var n;
    (n = D(this, vs)) == null || n.destroy();
  }
  beforeRender() {
    const { className: n, children: s, dropdown: i, ...r } = this.props;
    return {
      className: N("dropdown", n),
      children: typeof s == "function" ? s(this.state) : s,
      ...r,
      "data-toggle": "dropdown",
      "data-dropdown-placement": this.state.placement,
      ref: D(this, pn)
    };
  }
  render() {
    const { children: n, ...s } = this.beforeRender();
    return /* @__PURE__ */ y("div", { ...s, children: n });
  }
}
vs = new WeakMap(), pn = new WeakMap();
class gf extends pf {
  get triggerElement() {
    return this.ref.current.base;
  }
  render() {
    var r;
    const { placement: e, show: n } = this.state, s = this.beforeRender();
    let { caret: i = !0 } = s;
    if (i !== !1 && (n || i === !0)) {
      const o = (n ? e : (r = this.props.dropdown) == null ? void 0 : r.placement) || "";
      i = (o.includes("top") ? "up" : o.includes("bottom") ? "down" : o) || (typeof i == "string" ? i : "") || "down";
    }
    return s.caret = i, /* @__PURE__ */ y(Lt, { ...s });
  }
}
function Vc({
  key: t,
  type: e,
  btnType: n,
  ...s
}) {
  return /* @__PURE__ */ y(gf, { type: n, ...s });
}
let Gc = class extends U {
  componentDidMount() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var e;
    (e = this.props.beforeDestroy) == null || e.call(this);
  }
  handleItemClick(e, n, s, i) {
    s && s.call(i.target, i);
    const { onClickItem: r } = this.props;
    r && r.call(this, { item: e, index: n, event: i });
  }
  beforeRender() {
    var s;
    const e = { ...this.props }, n = (s = e.beforeRender) == null ? void 0 : s.call(this, e);
    return n && Object.assign(e, n), typeof e.items == "function" && (e.items = e.items.call(this)), e;
  }
  onRenderItem(e, n) {
    const { key: s = n, ...i } = e;
    return /* @__PURE__ */ y(Lt, { ...i }, s);
  }
  renderItem(e, n, s) {
    const { itemRender: i, btnProps: r, onClickItem: o } = e, a = { key: s, ...n };
    if (r && Object.assign(a, r), o && (a.onClick = this.handleItemClick.bind(this, a, s, n.onClick)), i) {
      const l = i.call(this, a, _);
      if (it(l))
        return l;
      typeof l == "object" && Object.assign(a, l);
    }
    return this.onRenderItem(a, s);
  }
  render() {
    const e = this.beforeRender(), {
      className: n,
      items: s,
      size: i,
      type: r,
      btnProps: o,
      children: a,
      itemRender: l,
      onClickItem: h,
      beforeRender: c,
      afterRender: u,
      beforeDestroy: d,
      ...f
    } = e;
    return /* @__PURE__ */ y(
      "div",
      {
        className: N("btn-group", i ? `size-${i}` : "", n),
        ...f,
        children: [
          s && s.map(this.renderItem.bind(this, e)),
          a
        ]
      }
    );
  }
};
function mf({
  key: t,
  type: e,
  btnType: n,
  ...s
}) {
  return /* @__PURE__ */ y(Gc, { type: n, ...s });
}
let dt = class extends qe {
  beforeRender() {
    const { gap: e, btnProps: n, wrap: s, ...i } = super.beforeRender();
    return i.className = N(i.className, s ? "flex-wrap" : "", typeof e == "number" ? `gap-${e}` : ""), typeof e == "string" && (i.style ? i.style.gap = e : i.style = { gap: e }), i;
  }
  isBtnItem(e) {
    return e === "item" || e === "dropdown";
  }
  renderTypedItem(e, n, s) {
    const { type: i } = s, r = this.props.btnProps, o = this.isBtnItem(i) ? { btnType: "ghost", ...r } : {}, a = {
      ...n,
      ...o,
      ...s,
      className: N(`${this.name}-${i}`, n.className, o.className, s.className),
      style: Object.assign({}, n.style, o.style, s.style)
    };
    return i === "btn-group" && (a.btnProps = r), /* @__PURE__ */ y(e, { ...a });
  }
};
dt.ItemComponents = {
  item: Zd,
  dropdown: Vc,
  "btn-group": mf
};
dt.ROOT_TAG = "nav";
dt.NAME = "toolbar";
dt.defaultProps = {
  btnProps: {
    btnType: "ghost"
  }
};
function yf({
  className: t,
  style: e,
  actions: n,
  heading: s,
  content: i,
  contentClass: r,
  children: o,
  close: a,
  onClose: l,
  icon: h,
  ...c
}) {
  let u;
  a === !0 ? u = /* @__PURE__ */ y(Lt, { className: "alert-close btn ghost", square: !0, onClick: l, children: /* @__PURE__ */ y("span", { class: "close" }) }) : it(a) ? u = a : typeof a == "object" && (u = /* @__PURE__ */ y(Lt, { ...a, onClick: l }));
  const d = it(n) ? n : n ? /* @__PURE__ */ y(dt, { ...n }) : null;
  return /* @__PURE__ */ y("div", { className: N("alert", t), style: e, ...c, children: [
    it(h) ? h : typeof h == "string" ? /* @__PURE__ */ y("i", { className: `icon ${h}` }) : null,
    it(i) ? i : /* @__PURE__ */ y("div", { className: N("alert-content", r), children: [
      it(s) ? s : s && /* @__PURE__ */ y("div", { className: "alert-heading", children: s }),
      /* @__PURE__ */ y("div", { className: "alert-text", children: i }),
      s ? d : null
    ] }),
    s ? null : d,
    u,
    o
  ] });
}
function wf(t) {
  if (t === "center")
    return "fade-from-center";
  if (t) {
    if (t.includes("top"))
      return "fade-from-top";
    if (t.includes("bottom"))
      return "fade-from-bottom";
  }
  return "fade";
}
let vf = class extends U {
  componentDidMount() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var e;
    (e = this.props.beforeDestroy) == null || e.call(this);
  }
  render() {
    const {
      afterRender: e,
      beforeDestroy: n,
      margin: s,
      type: i,
      placement: r,
      animation: o,
      show: a,
      className: l,
      time: h,
      ...c
    } = this.props;
    return /* @__PURE__ */ y(
      yf,
      {
        className: N("messager", l, i, o === !0 ? wf(r) : o, a ? "in" : ""),
        ...c
      }
    );
  }
};
var bf = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, _f = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, An = (t, e, n) => (bf(t, e, "access private method"), n), Ce, Je;
class Qo extends J {
  constructor() {
    super(...arguments), _f(this, Ce), this._show = !1, this._showTimer = 0, this._afterRender = ({ firstRender: e }) => {
      e && this.show();
      const { margin: n } = this.options;
      n && this.$element.css("margin", `${n}px`);
    };
  }
  get isShown() {
    return this._show;
  }
  afterInit() {
    this.on("click", (e) => {
      g(e.target).closest('.alert-close,[data-dismiss="messager"]').length && (e.preventDefault(), e.stopPropagation(), this.hide());
    });
  }
  setOptions(e) {
    return e = super.setOptions(e), {
      ...e,
      show: this._show,
      afterRender: this._afterRender
    };
  }
  show() {
    this.render(), this.emit("show"), An(this, Ce, Je).call(this, () => {
      this._show = !0, this.render(), An(this, Ce, Je).call(this, () => {
        this.emit("shown");
        const { time: e } = this.options;
        e && An(this, Ce, Je).call(this, () => this.hide(), e);
      });
    }, 100);
  }
  hide() {
    this._show && An(this, Ce, Je).call(this, () => {
      this.emit("hide"), this._show = !1, this.render(), An(this, Ce, Je).call(this, () => {
        this.emit("hidden");
      });
    }, 50);
  }
}
Ce = /* @__PURE__ */ new WeakSet();
Je = function(t, e = 200) {
  this._showTimer && clearTimeout(this._showTimer), this._showTimer = window.setTimeout(() => {
    t(), this._showTimer = 0;
  }, e);
};
Qo.NAME = "MessagerItem";
Qo.Component = vf;
var ta = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Pe = (t, e, n) => (ta(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Os = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ks = (t, e, n, s) => (ta(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Yc = (t, e, n) => (ta(t, e, "access private method"), n), hn, Vt, qr, Kc, ea, Xc;
const Jc = class extends lt {
  constructor() {
    super(...arguments), Os(this, qr), Os(this, ea), Os(this, hn, void 0), Os(this, Vt, void 0);
  }
  get isShown() {
    var t;
    return !!((t = Pe(this, Vt)) != null && t.isShown);
  }
  show(t) {
    this.setOptions(t), Yc(this, qr, Kc).call(this).show();
  }
  hide() {
    var t;
    (t = Pe(this, Vt)) == null || t.hide();
  }
  static show(t) {
    typeof t == "string" && (t = { content: t });
    const { container: e, ...n } = t, s = Jc.ensure(e || "body");
    return s.setOptions(n), s.hide(), s.show(), s;
  }
};
let na = Jc;
hn = /* @__PURE__ */ new WeakMap();
Vt = /* @__PURE__ */ new WeakMap();
qr = /* @__PURE__ */ new WeakSet();
Kc = function() {
  if (Pe(this, Vt))
    Pe(this, Vt).setOptions(this.options);
  else {
    const t = Yc(this, ea, Xc).call(this), e = new Qo(t, this.options);
    e.on("hidden", () => {
      e.destroy(), t == null || t.remove(), Ks(this, hn, void 0), Ks(this, Vt, void 0);
    }), Ks(this, Vt, e);
  }
  return Pe(this, Vt);
};
ea = /* @__PURE__ */ new WeakSet();
Xc = function() {
  if (Pe(this, hn))
    return Pe(this, hn);
  const { placement: t = "top" } = this.options;
  let e = this.$element.find(`.messagers-${t}`);
  e.length || (e = g(`<div class="messagers messagers-${t}"></div>`).appendTo(this.$element));
  let n = e.find(`#messager-${this.gid}`);
  return n.length || (n = g(`<div class="messager-holder" id="messager-${this.gid}"></div>`).appendTo(e), Ks(this, hn, n[0])), n[0];
};
na.NAME = "messager";
na.DEFAULT = {
  placement: "top",
  animation: !0,
  close: !0,
  margin: 6,
  time: 5e3
};
g(document).on("zui.messager.show", (t, e) => {
  e && na.show(e);
});
let sa = class extends U {
  render() {
    const { percent: e, circleSize: n, circleBorderSize: s, circleBgColor: i, circleColor: r } = this.props, o = (n - s) / 2, a = n / 2;
    return /* @__PURE__ */ y("svg", { width: n, height: n, class: "progress-circle", children: [
      /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, stroke: i, "stroke-width": s }),
      /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, stroke: r, "stroke-dasharray": Math.PI * o * 2, "stroke-dashoffset": Math.PI * o * 2 * (100 - e) / 100, "stroke-width": s }),
      /* @__PURE__ */ y("text", { x: a, y: a + s / 4, "dominant-baseline": "middle", style: { fontSize: `${o}px` }, children: Math.round(e) })
    ] });
  }
};
sa.NAME = "zui.progress-circle";
sa.defaultProps = {
  circleSize: 24,
  circleBorderSize: 2,
  circleBgColor: "var(--progress-circle-bg)",
  circleColor: "var(--progress-circle-bar-color)"
};
class Zc extends J {
}
Zc.NAME = "ProgressCircle";
Zc.Component = sa;
let xf = class extends U {
  constructor() {
    super(...arguments), this.state = { checked: !1 }, this.handleOnClick = () => {
      this.setState({ checked: !this.state.checked });
    };
  }
  componentDidMount() {
    this.setState({ checked: this.props.defaultChecked ?? !1 });
  }
  render() {
    const {
      component: e,
      className: n,
      children: s,
      text: i,
      icon: r,
      surffixIcon: o,
      disabled: a,
      defaultChecked: l,
      onChange: h,
      ...c
    } = this.props, u = this.state.checked ? 1 : 0, d = e || "div", f = typeof r == "string" ? /* @__PURE__ */ y("i", { class: `icon ${r}` }) : r, p = typeof o == "string" ? /* @__PURE__ */ y("i", { class: `icon ${o}` }) : o, m = [
      /* @__PURE__ */ y("input", { onChange: h, type: "checkbox", value: u, checked: !!this.state.checked }),
      /* @__PURE__ */ y("label", { children: [
        f,
        i,
        p
      ] })
    ];
    return _(
      d,
      {
        className: N("switch", n, { disabled: a }),
        onClick: this.handleOnClick,
        ...c
      },
      ...m,
      s
    );
  }
};
class Qc extends J {
}
Qc.NAME = "Switch";
Qc.Component = xf;
var qt;
class $f {
  constructor(e = "") {
    O(this, qt, void 0);
    typeof e == "object" ? F(this, qt, e) : F(this, qt, document.appendChild(document.createComment(e)));
  }
  on(e, n, s) {
    D(this, qt).addEventListener(e, n, s);
  }
  once(e, n, s) {
    D(this, qt).addEventListener(e, n, { once: !0, ...s });
  }
  off(e, n, s) {
    D(this, qt).removeEventListener(e, n, s);
  }
  emit(e) {
    return D(this, qt).dispatchEvent(e), e;
  }
}
qt = new WeakMap();
const _l = /* @__PURE__ */ new Set([
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
class ia extends $f {
  on(e, n, s) {
    super.on(e, n, s);
  }
  off(e, n, s) {
    super.off(e, n, s);
  }
  once(e, n, s) {
    super.once(e, n, s);
  }
  emit(e, n) {
    return typeof e == "string" && (_l.has(e) ? (e = new Event(e), Object.assign(e, { detail: n })) : e = new CustomEvent(e, { detail: n })), super.emit(ia.createEvent(e, n));
  }
  static createEvent(e, n) {
    return typeof e == "string" && (_l.has(e) ? (e = new Event(e), Object.assign(e, { detail: n })) : e = new CustomEvent(e, { detail: n })), e;
  }
}
let sr = (t = 21) => crypto.getRandomValues(new Uint8Array(t)).reduce((e, n) => (n &= 63, n < 36 ? e += n.toString(36) : n < 62 ? e += (n - 26).toString(36).toUpperCase() : n > 62 ? e += "-" : e += "_", e), "");
var bs, ue, Et, gn, mn, Xs;
const Za = class {
  /**
   * Create new store instance
   * @param name Name of store
   * @param type Store type
   */
  constructor(e, n = "local") {
    O(this, mn);
    O(this, bs, void 0);
    O(this, ue, void 0);
    O(this, Et, void 0);
    O(this, gn, void 0);
    F(this, bs, n), F(this, ue, `ZUI_STORE:${e ?? sr()}`), F(this, Et, n === "local" ? localStorage : sessionStorage);
  }
  /**
   * Get store type
   */
  get type() {
    return D(this, bs);
  }
  /**
   * Get session type store instance
   */
  get session() {
    return this.type === "session" ? this : (D(this, gn) || F(this, gn, new Za(D(this, ue), "session")), D(this, gn));
  }
  /**
   * Get value from store
   * @param key Key to get
   * @param defaultValue default value to return if key is not found
   * @returns Value of key or defaultValue if key is not found
   */
  get(e, n) {
    const s = D(this, Et).getItem(ot(this, mn, Xs).call(this, e));
    return typeof s == "string" ? JSON.parse(s) : s ?? n;
  }
  /**
   * Set key-value pair in store
   * @param key Key to set
   * @param value Value to set
   */
  set(e, n) {
    if (n == null)
      return this.remove(e);
    D(this, Et).setItem(ot(this, mn, Xs).call(this, e), JSON.stringify(n));
  }
  /**
   * Remove key-value pair from store
   * @param key Key to remove
   */
  remove(e) {
    D(this, Et).removeItem(ot(this, mn, Xs).call(this, e));
  }
  /**
   * Iterate all key-value pairs in store
   * @param callback Callback function to call for each key-value pair in the store
   */
  each(e) {
    for (let n = 0; n < D(this, Et).length; n++) {
      const s = D(this, Et).key(n);
      if (s != null && s.startsWith(D(this, ue))) {
        const i = D(this, Et).getItem(s);
        typeof i == "string" && e(s.substring(D(this, ue).length + 1), JSON.parse(i));
      }
    }
  }
  /**
   * Get all key values in store
   * @returns All key-value pairs in the store
   */
  getAll() {
    const e = {};
    return this.each((n, s) => {
      e[n] = s;
    }), e;
  }
};
let _i = Za;
bs = new WeakMap(), ue = new WeakMap(), Et = new WeakMap(), gn = new WeakMap(), mn = new WeakSet(), Xs = function(e) {
  return `${D(this, ue)}:${e}`;
};
const kf = new _i("DEFAULT");
function Sf(t, e = "local") {
  return new _i(t, e);
}
Object.assign(kf, { create: Sf });
const I = g, ra = window.document;
let Is, se;
const Cf = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, Ef = /^(?:text|application)\/javascript/i, Mf = /^(?:text|application)\/xml/i, th = "application/json", eh = "text/html", Tf = /^\s*$/, Vr = ra.createElement("a");
Vr.href = window.location.href;
function Rf(t, e, n) {
  const s = new CustomEvent(e, { detail: n });
  return I(t).trigger(s, n), !s.defaultPrevented;
}
function Be(t, e, n, s) {
  if (t.global)
    return Rf(e || ra, n, s);
}
I.active = 0;
function Af(t) {
  t.global && I.active++ === 0 && Be(t, null, "ajaxStart");
}
function Nf(t) {
  t.global && !--I.active && Be(t, null, "ajaxStop");
}
function Lf(t, e) {
  const n = e.context;
  if (e.beforeSend.call(n, t, e) === !1 || Be(e, n, "ajaxBeforeSend", [t, e]) === !1)
    return !1;
  Be(e, n, "ajaxSend", [t, e]);
}
function Df(t, e, n) {
  const s = n.context, i = "success";
  n.success.call(s, t, i, e), Be(n, s, "ajaxSuccess", [e, n, t]), nh(i, e, n);
}
function Hs(t, e, n, s) {
  const i = s.context;
  s.error.call(i, n, e, t), Be(s, i, "ajaxError", [n, s, t || e]), nh(e, n, s);
}
function nh(t, e, n) {
  const s = n.context;
  n.complete.call(s, e, t), Be(n, s, "ajaxComplete", [e, n]), Nf(n);
}
function Pf(t, e, n) {
  if (n.dataFilter == he)
    return t;
  const s = n.context;
  return n.dataFilter.call(s, t, e);
}
function he() {
}
I.ajaxSettings = {
  // Default type of request
  type: "GET",
  // Callback that is executed before request
  beforeSend: he,
  // Callback that is executed if the request succeeds
  success: he,
  // Callback that is executed the the server drops error
  error: he,
  // Callback that is executed on request complete (both: error and success)
  complete: he,
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
    json: th,
    xml: "application/xml, text/xml",
    html: eh,
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
  dataFilter: he
};
function Wf(t) {
  return t && (t = t.split(";", 2)[0]), t && (t == eh ? "html" : t == th ? "json" : Ef.test(t) ? "script" : Mf.test(t) && "xml") || "text";
}
function sh(t, e) {
  return e == "" ? t : (t + "&" + e).replace(/[&?]{1,2}/, "?");
}
function Of(t) {
  t.processData && t.data && typeof t.data != "string" && (t.data = I.param(t.data, t.traditional)), t.data && (!t.type || t.type.toUpperCase() == "GET" || t.dataType == "jsonp") && (t.url = sh(t.url, t.data), t.data = void 0);
}
I.ajax = function(t) {
  var m;
  const e = I.extend({}, t || {});
  let n, s;
  for (Is in I.ajaxSettings)
    e[Is] === void 0 && (e[Is] = I.ajaxSettings[Is]);
  Af(e), e.crossDomain || (n = ra.createElement("a"), n.href = e.url, n.href = n.href, e.crossDomain = Vr.protocol + "//" + Vr.host != n.protocol + "//" + n.host);
  const i = e.type.toUpperCase() === "GET";
  e.url || (e.url = window.location.toString()), (s = e.url.indexOf("#")) > -1 && (e.url = e.url.slice(0, s)), i ? Of(e) : e.data instanceof FormData && e.contentType === void 0 && (e.contentType = !1);
  let r = e.dataType;
  /\?.+=\?/.test(e.url) && (r = "jsonp"), (e.cache === !1 || (!t || t.cache !== !0) && (r == "script" || r == "jsonp")) && (e.url = sh(e.url, "_=" + Date.now()));
  let a = e.accepts[r];
  const l = {}, h = function(v, w) {
    l[v.toLowerCase()] = [v, w];
  }, c = /^([\w-]+:)\/\//.test(e.url) ? RegExp.$1 : window.location.protocol, u = e.xhr(), d = u.setRequestHeader;
  let f;
  if (e.crossDomain || h("X-Requested-With", "XMLHttpRequest"), h("Accept", a || "*/*"), a = e.mimeType, a && (a.indexOf(",") > -1 && (a = a.split(",", 2)[0]), (m = u.overrideMimeType) == null || m.call(u, a)), (e.contentType || e.contentType !== !1 && e.data && !i) && h("Content-Type", e.contentType || "application/x-www-form-urlencoded"), e.headers)
    for (se in e.headers)
      h(se, e.headers[se]);
  if (u.setRequestHeader = h, u.onreadystatechange = function() {
    if (u.readyState == 4) {
      u.onreadystatechange = he, clearTimeout(f);
      let v, w = !1;
      if (u.status >= 200 && u.status < 300 || u.status == 304 || u.status == 0 && c == "file:") {
        if (r = r || Wf(e.mimeType || u.getResponseHeader("content-type")), u.responseType == "arraybuffer" || u.responseType == "blob")
          v = u.response;
        else {
          v = u.responseText;
          try {
            v = Pf(v, r, e), r == "xml" ? v = u.responseXML : r == "json" && (v = Tf.test(v) ? null : JSON.parse(v));
          } catch (b) {
            w = b;
          }
          if (w)
            return Hs(w, "parsererror", u, e);
        }
        Df(v, u, e);
      } else
        Hs(u.statusText || null, u.status ? "error" : "abort", u, e);
    }
  }, Lf(u, e) === !1)
    return u.abort(), Hs(null, "abort", u, e), u;
  const p = "async" in e ? e.async : !0;
  if (u.open(e.type, e.url, p, e.username, e.password), e.xhrFields)
    for (se in e.xhrFields)
      u[se] = e.xhrFields[se];
  for (se in l)
    d.apply(u, l[se]);
  return e.timeout > 0 && (f = setTimeout(function() {
    u.onreadystatechange = he, u.abort(), Hs(null, "timeout", u, e);
  }, e.timeout)), u.send(e.data ? e.data : null), u;
};
function ir(t, e, n, s) {
  return I.isFunction(e) && (s = n, n = e, e = void 0), I.isFunction(n) || (s = n, n = void 0), {
    url: t,
    data: e,
    success: n,
    dataType: s
  };
}
I.get = function(t, e, n, s) {
  return I.ajax(ir(t, e, n, s));
};
I.post = function(t, e, n, s) {
  const i = ir(t, e, n, s);
  return I.ajax(Object.assign(i, { type: "POST" }));
};
I.getJSON = function(t, e, n, s) {
  const i = ir(t, e, n, s);
  return i.dataType = "json", I.ajax(i);
};
I.fn.load = function(t, e, n) {
  if (!this.length)
    return this;
  const s = t.split(/\s/);
  let i;
  const r = ir(t, e, n), o = r.success;
  return s.length > 1 && (r.url = s[0], i = s[1]), r.success = (a, ...l) => {
    this.html(i ? I("<div>").html(a.replace(Cf, "")).find(i) : a), o == null || o.call(this, a, ...l);
  }, I.ajax(r), this;
};
const xl = encodeURIComponent;
function ih(t, e, n, s) {
  const i = I.isArray(e), r = I.isPlainObject(e);
  I.each(e, function(o, a) {
    const l = Array.isArray(a) ? "array" : typeof a;
    s && (o = n ? s : s + "[" + (r || l == "object" || l == "array" ? o : "") + "]"), !s && i ? t.add(a.name, a.value) : l == "array" || !n && l == "object" ? ih(t, a, n, o) : t.add(o, a);
  });
}
I.param = function(t, e) {
  const n = [];
  return n.add = function(s, i) {
    I.isFunction(i) && (i = i()), i == null && (i = ""), this.push(xl(s) + "=" + xl(i));
  }, ih(n, t, e), n.join("&").replace(/%20/g, "+");
};
const Ug = Object.assign(I.ajax, {
  get: I.get,
  post: I.post,
  getJSON: I.getJSON,
  param: I.param,
  ajaxSettings: I.ajaxSettings
}), qg = new ia();
/*! js-cookie v3.0.1 | MIT */
function Bs(t) {
  for (var e = 1; e < arguments.length; e++) {
    var n = arguments[e];
    for (var s in n)
      t[s] = n[s];
  }
  return t;
}
var If = {
  read: function(t) {
    return t[0] === '"' && (t = t.slice(1, -1)), t.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
  },
  write: function(t) {
    return encodeURIComponent(t).replace(
      /%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,
      decodeURIComponent
    );
  }
};
function Gr(t, e) {
  function n(i, r, o) {
    if (!(typeof document > "u")) {
      o = Bs({}, e, o), typeof o.expires == "number" && (o.expires = new Date(Date.now() + o.expires * 864e5)), o.expires && (o.expires = o.expires.toUTCString()), i = encodeURIComponent(i).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
      var a = "";
      for (var l in o)
        o[l] && (a += "; " + l, o[l] !== !0 && (a += "=" + o[l].split(";")[0]));
      return document.cookie = i + "=" + t.write(r, i) + a;
    }
  }
  function s(i) {
    if (!(typeof document > "u" || arguments.length && !i)) {
      for (var r = document.cookie ? document.cookie.split("; ") : [], o = {}, a = 0; a < r.length; a++) {
        var l = r[a].split("="), h = l.slice(1).join("=");
        try {
          var c = decodeURIComponent(l[0]);
          if (o[c] = t.read(h, c), i === c)
            break;
        } catch {
        }
      }
      return i ? o[i] : o;
    }
  }
  return Object.create(
    {
      set: n,
      get: s,
      remove: function(i, r) {
        n(
          i,
          "",
          Bs({}, r, {
            expires: -1
          })
        );
      },
      withAttributes: function(i) {
        return Gr(this.converter, Bs({}, this.attributes, i));
      },
      withConverter: function(i) {
        return Gr(Bs({}, this.converter, i), this.attributes);
      }
    },
    {
      attributes: { value: Object.freeze(e) },
      converter: { value: Object.freeze(t) }
    }
  );
}
var Hf = Gr(If, { path: "/" });
window.$ && Object.assign(window.$, { cookie: Hf });
function Bf(t) {
  if (t.indexOf("#") === 0 && (t = t.slice(1)), t.length === 3 && (t = t[0] + t[0] + t[1] + t[1] + t[2] + t[2]), t.length !== 6)
    throw new Error(`Invalid HEX color "${t}".`);
  return [
    parseInt(t.slice(0, 2), 16),
    // r
    parseInt(t.slice(2, 4), 16),
    // g
    parseInt(t.slice(4, 6), 16)
    // b
  ];
}
function zf(t) {
  const [e, n, s] = typeof t == "string" ? Bf(t) : t;
  return e * 0.299 + n * 0.587 + s * 0.114 > 186;
}
function $l(t, e) {
  return zf(t) ? (e == null ? void 0 : e.dark) ?? "#333333" : (e == null ? void 0 : e.light) ?? "#ffffff";
}
function kl(t, e = 255) {
  return Math.min(Math.max(t, 0), e);
}
function Ff(t, e, n) {
  t = t % 360 / 360, e = kl(e), n = kl(n);
  const s = n <= 0.5 ? n * (e + 1) : n + e - n * e, i = n * 2 - s, r = (o) => (o = o < 0 ? o + 1 : o > 1 ? o - 1 : o, o * 6 < 1 ? i + (s - i) * o * 6 : o * 2 < 1 ? s : o * 3 < 2 ? i + (s - i) * (2 / 3 - o) * 6 : i);
  return [
    r(t + 1 / 3) * 255,
    r(t) * 255,
    r(t - 1 / 3) * 255
  ];
}
function jf(t) {
  let e = 0;
  if (typeof t != "string" && (t = String(t)), t && t.length)
    for (let n = 0; n < t.length; ++n)
      e += (n + 1) * t.charCodeAt(n);
  return e;
}
function Uf(t, e) {
  return /^[\u4e00-\u9fa5\s]+$/.test(t) ? t.length <= e ? t : t.substring(t.length - e) : /^[A-Za-z\d\s]+$/.test(t) ? t[0].toUpperCase() : t.length <= e ? t : t.substring(0, e);
}
let rh = class extends U {
  render() {
    const {
      className: e,
      style: n,
      size: s = "",
      circle: i,
      rounded: r,
      background: o,
      foreColor: a,
      text: l,
      code: h,
      maxTextLength: c = 2,
      src: u,
      hueDistance: d = 43,
      saturation: f = 0.4,
      lightness: p = 0.6,
      children: m,
      ...v
    } = this.props, w = ["avatar", e], b = { ...n, background: o, color: a };
    let k = 32;
    s && (typeof s == "number" ? (b.width = `${s}px`, b.height = `${s}px`, b.fontSize = `${Math.max(12, Math.round(s / 2))}px`, k = s) : (w.push(`size-${s}`), k = { xs: 20, sm: 24, lg: 48, xl: 80 }[s])), i ? w.push("circle") : r && (typeof r == "number" ? b.borderRadius = `${r}px` : w.push(`rounded-${r}`));
    let C;
    if (u)
      w.push("has-img"), C = /* @__PURE__ */ y("img", { className: "avatar-img", src: u, alt: l });
    else if (l != null && l.length) {
      const E = Uf(l, c);
      if (w.push("has-text", `has-text-${E.length}`), o)
        !a && o && (b.color = $l(o));
      else {
        const M = h ?? l, T = (typeof M == "number" ? M : jf(M)) * d % 360;
        if (b.background = `hsl(${T},${f * 100}%,${p * 100}%)`, !a) {
          const A = Ff(T, f, p);
          b.color = $l(A);
        }
      }
      let P;
      k && k < 14 * E.length && (P = { transform: `scale(${k / (14 * E.length)})`, whiteSpace: "nowrap" }), C = /* @__PURE__ */ y("div", { "data-actualSize": k, className: "avatar-text", style: P, children: E });
    }
    return /* @__PURE__ */ y(
      "div",
      {
        className: N(w),
        style: b,
        ...v,
        children: [
          C,
          m
        ]
      }
    );
  }
};
class oh extends J {
}
oh.NAME = "Avatar";
oh.Component = rh;
class ah extends J {
}
ah.NAME = "BtnGroup";
ah.Component = Gc;
var oa = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Re = (t, e, n) => (oa(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Nn = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, ns = (t, e, n, s) => (oa(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), kr = (t, e, n) => (oa(t, e, "access private method"), n), nn, Js, Ee, Yr, jn, Zs;
const Sr = "show", Sl = "in", qf = '[data-dismiss="modal"]', Qs = class extends lt {
  constructor() {
    super(...arguments), Nn(this, jn), Nn(this, nn, 0), Nn(this, Js, void 0), Nn(this, Ee, void 0), Nn(this, Yr, (t) => {
      const e = t.target;
      (e.closest(qf) || this.options.backdrop === !0 && !e.closest(".modal-dialog") && e.closest(".modal")) && this.hide();
    });
  }
  get modalElement() {
    return this.element;
  }
  get shown() {
    return this.modalElement.classList.contains(Sr);
  }
  get dialog() {
    return this.modalElement.querySelector(".modal-dialog");
  }
  afterInit() {
    if (this.on("click", Re(this, Yr)), this.options.responsive && typeof ResizeObserver < "u") {
      const { dialog: t } = this;
      if (t) {
        const e = new ResizeObserver(() => {
          if (!this.shown)
            return;
          const n = t.clientWidth, s = t.clientHeight;
          (!Re(this, Ee) || Re(this, Ee)[0] !== n || Re(this, Ee)[1] !== s) && (ns(this, Ee, [n, s]), this.layout());
        });
        e.observe(t), ns(this, Js, e);
      }
    }
    this.options.show && this.show();
  }
  destroy() {
    var t;
    super.destroy(), (t = Re(this, Js)) == null || t.disconnect();
  }
  show(t) {
    if (this.shown)
      return !1;
    this.setOptions(t);
    const { modalElement: e } = this, { animation: n, backdrop: s, className: i, style: r } = this.options;
    return g(e).setClass({
      "modal-trans": n,
      "modal-no-backdrop": !s
    }, Sr, i).css({
      zIndex: `${Qs.zIndex++}`,
      ...r
    }), this.layout(), this.emit("show"), kr(this, jn, Zs).call(this, () => {
      g(e).addClass(Sl), kr(this, jn, Zs).call(this, () => {
        this.emit("shown");
      });
    }, 50), !0;
  }
  hide() {
    return this.shown ? (g(this.modalElement).removeClass(Sl), this.emit("hide"), kr(this, jn, Zs).call(this, () => {
      g(this.modalElement).removeClass(Sr), this.emit("hidden");
    }), !0) : !1;
  }
  layout(t, e) {
    if (!this.shown)
      return;
    const { dialog: n } = this;
    if (!n)
      return;
    e = e ?? this.options.size, g(n).removeAttr("data-size");
    const s = { width: "", height: "" };
    typeof e == "object" ? (s.width = e.width, s.height = e.height) : typeof e == "string" && ["md", "sm", "lg", "full"].includes(e) ? g(n).attr("data-size", e) : e && (s.width = e), g(n).css(s), t = t ?? this.options.position ?? "fit";
    const i = n.clientWidth, r = n.clientHeight;
    ns(this, Ee, [i, r]), typeof t == "function" && (t = t({ width: i, height: r }));
    const o = {
      top: null,
      left: null,
      bottom: null,
      right: null,
      alignSelf: "center"
    };
    typeof t == "number" ? (o.alignSelf = "flex-start", o.top = t) : typeof t == "object" && t ? (o.alignSelf = "flex-start", Object.assign(o, t)) : t === "fit" ? (o.alignSelf = "flex-start", o.top = `${Math.max(0, Math.floor((window.innerHeight - r) / 3))}px`) : t === "bottom" ? o.alignSelf = "flex-end" : t === "top" ? o.alignSelf = "flex-start" : t !== "center" && typeof t == "string" && (o.alignSelf = "flex-start", o.top = t), g(n).css(o), g(this.modalElement).css("justifyContent", o.left ? "flex-start" : "center");
  }
  static hide(t) {
    var e;
    (e = Qs.query(t)) == null || e.hide();
  }
  static show(t) {
    var e;
    (e = Qs.query(t)) == null || e.show();
  }
};
let ne = Qs;
nn = /* @__PURE__ */ new WeakMap();
Js = /* @__PURE__ */ new WeakMap();
Ee = /* @__PURE__ */ new WeakMap();
Yr = /* @__PURE__ */ new WeakMap();
jn = /* @__PURE__ */ new WeakSet();
Zs = function(t, e) {
  Re(this, nn) && (clearTimeout(Re(this, nn)), ns(this, nn, 0)), t && (this.options.animation ? ns(this, nn, window.setTimeout(t, e ?? this.options.transTime)) : t());
};
ne.NAME = "Modal";
ne.MULTI_INSTANCE = !0;
ne.DEFAULT = {
  position: "fit",
  show: !0,
  keyboard: !0,
  animation: !0,
  backdrop: !0,
  responsive: !0,
  transTime: 300
};
ne.zIndex = 2e3;
g(window).on("resize.modal.zui", () => {
  ne.getAll().forEach((t) => {
    const e = t;
    e.shown && e.options.responsive && e.layout();
  });
});
g(document).on("to-hide.modal.zui", (t, e) => {
  ne.hide(e == null ? void 0 : e.target);
});
class lh extends U {
  componentDidMount() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !0 });
  }
  componentDidUpdate() {
    var e;
    (e = this.props.afterRender) == null || e.call(this, { firstRender: !1 });
  }
  componentWillUnmount() {
    var e;
    (e = this.props.beforeDestroy) == null || e.call(this);
  }
  renderHeader() {
    const {
      header: e,
      title: n
    } = this.props;
    return it(e) ? e : e === !1 || !n ? null : /* @__PURE__ */ y("div", { className: "modal-header", children: /* @__PURE__ */ y("div", { className: "modal-title", children: n }) });
  }
  renderActions() {
    const {
      actions: e,
      closeBtn: n
    } = this.props;
    return !n && !e ? null : it(e) ? e : /* @__PURE__ */ y("div", { className: "modal-actions", children: [
      e ? /* @__PURE__ */ y(dt, { ...e }) : null,
      n ? /* @__PURE__ */ y("button", { type: "button", class: "btn square ghost", "data-dismiss": "modal", children: /* @__PURE__ */ y("span", { class: "close" }) }) : null
    ] });
  }
  renderBody() {
    const {
      body: e
    } = this.props;
    return e ? it(e) ? e : /* @__PURE__ */ y("div", { className: "modal-body", children: e }) : null;
  }
  renderFooter() {
    const {
      footer: e,
      footerActions: n
    } = this.props;
    return it(e) ? e : e === !1 || !n ? null : /* @__PURE__ */ y("div", { className: "modal-footer", children: n ? /* @__PURE__ */ y(dt, { ...n }) : null });
  }
  render() {
    const {
      className: e,
      style: n,
      children: s
    } = this.props;
    return /* @__PURE__ */ y("div", { className: N("modal-dialog", e), style: n, children: /* @__PURE__ */ y("div", { className: "modal-content", children: [
      this.renderHeader(),
      this.renderActions(),
      this.renderBody(),
      s,
      this.renderFooter()
    ] }) });
  }
}
lh.defaultProps = { closeBtn: !0 };
var yn, wn, vn;
class Vf extends U {
  constructor() {
    super(...arguments);
    O(this, yn, void 0);
    O(this, wn, void 0);
    O(this, vn, void 0);
    F(this, yn, $t()), this.state = {}, F(this, vn, () => {
      var i, r;
      const n = (r = (i = D(this, yn).current) == null ? void 0 : i.contentWindow) == null ? void 0 : r.document;
      if (!n)
        return;
      let s = D(this, wn);
      s == null || s.disconnect(), s = new ResizeObserver(() => {
        const o = n.body, a = n.documentElement, l = Math.ceil(Math.max(o.scrollHeight, o.offsetHeight, a.offsetHeight));
        this.setState({ height: l });
      }), s.observe(n.body), s.observe(n.documentElement), F(this, wn, s);
    });
  }
  componentDidMount() {
    D(this, vn).call(this);
  }
  componentWillUnmount() {
    var n;
    (n = D(this, wn)) == null || n.disconnect();
  }
  render() {
    const { url: n } = this.props;
    return /* @__PURE__ */ y(
      "iframe",
      {
        className: "modal-iframe",
        style: this.state,
        src: n,
        ref: D(this, yn),
        onLoad: D(this, vn)
      }
    );
  }
}
yn = new WeakMap(), wn = new WeakMap(), vn = new WeakMap();
var aa = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, ie = (t, e, n) => (aa(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ye = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ke = (t, e, n, s) => (aa(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), sn = (t, e, n) => (aa(t, e, "access private method"), n), Me, ti, Bt, ps, rr, Kr, ch, ei, Xr;
function Gf(t, e) {
  const { custom: n, title: s, content: i } = e;
  return {
    body: i,
    title: s,
    ...typeof n == "function" ? n() : n
  };
}
async function Yf(t, e) {
  const { dataType: n = "html", url: s, request: i, custom: r, title: o, replace: a = !0, executeScript: l = !0 } = e, c = await (await fetch(s, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "X-ZUI-Modal": "true"
    },
    ...i
  })).text();
  if (n !== "html")
    try {
      const u = JSON.parse(c);
      return {
        title: o,
        ...r,
        ...u
      };
    } catch {
    }
  return a !== !1 && n === "html" ? [c] : {
    title: o,
    ...r,
    body: n === "html" ? /* @__PURE__ */ y(kc, { className: "modal-body", html: c, executeScript: l }) : c
  };
}
async function Kf(t, e) {
  const { url: n, custom: s, title: i } = e;
  return {
    title: i,
    ...s,
    body: /* @__PURE__ */ y(Vf, { url: n })
  };
}
const Xf = {
  custom: Gf,
  ajax: Yf,
  iframe: Kf
}, ae = class extends ne {
  constructor() {
    super(...arguments), Ye(this, ps), Ye(this, Kr), Ye(this, ei), Ye(this, Me, void 0), Ye(this, ti, void 0), Ye(this, Bt, void 0);
  }
  get id() {
    return ie(this, ti);
  }
  get loading() {
    var t;
    return (t = this.modalElement) == null ? void 0 : t.classList.contains(ae.LOADING_CLASS);
  }
  get shown() {
    var t;
    return !!((t = ie(this, Me)) != null && t.classList.contains("show"));
  }
  get modalElement() {
    let t = ie(this, Me);
    if (!t) {
      const { id: e } = this;
      t = g(this.element).find(`#${e}`)[0], t || (t = g("<div>").attr("id", e).css(this.options.style || {}).setClass("modal modal-async", this.options.className).appendTo(this.element)[0]), Ke(this, Me, t);
    }
    return t;
  }
  afterInit() {
    super.afterInit(), Ke(this, ti, this.options.id || `modal-${sr()}`), this.on("hidden", () => {
      this.options.destoryOnHide && this.destroy();
    });
  }
  show(t) {
    return super.show(t) ? (this.buildDialog(), !0) : !1;
  }
  destroy() {
    var t;
    super.destroy(), (t = ie(this, Me)) == null || t.remove(), Ke(this, Me, void 0);
  }
  render(t) {
    super.render(t), this.buildDialog();
  }
  async buildDialog() {
    if (this.loading)
      return !1;
    ie(this, Bt) && clearTimeout(ie(this, Bt));
    const { modalElement: t, options: e } = this, { type: n, loadTimeout: s } = e, i = Xf[n];
    if (!i)
      return console.warn(`Modal: Cannot build modal with type "${n}"`), !1;
    t.classList.add(ae.LOADING_CLASS), await sn(this, Kr, ch).call(this), s && Ke(this, Bt, window.setTimeout(() => {
      Ke(this, Bt, 0), sn(this, ei, Xr).call(this, this.options.timeoutTip);
    }, s));
    const r = await i.call(this, t, e);
    return r === !1 ? await sn(this, ei, Xr).call(this, this.options.failedTip) : r && typeof r == "object" && await sn(this, ps, rr).call(this, r), ie(this, Bt) && (clearTimeout(ie(this, Bt)), Ke(this, Bt, 0)), t.classList.remove(ae.LOADING_CLASS), !0;
  }
  static open(t) {
    return new Promise((e) => {
      const { container: n = document.body, ...s } = t, i = ae.ensure(n, { show: !0, ...s });
      i.one("hidden", () => e(i)), i.show();
    });
  }
  static async alert(t) {
    typeof t == "string" && (t = { message: t });
    const { type: e, message: n, icon: s, iconClass: i = "icon-lg muted", actions: r = "confirm", onClickAction: o, custom: a, key: l = "__alert", ...h } = t;
    let c = /* @__PURE__ */ y("div", { children: n });
    s ? c = /* @__PURE__ */ y("div", { className: "modal-body row gap-4 items-center", children: [
      /* @__PURE__ */ y("div", { className: `icon ${s} ${i}` }),
      c
    ] }) : c = /* @__PURE__ */ y("div", { className: "modal-body", children: c });
    const u = [];
    (Array.isArray(r) ? r : [r]).forEach((p) => {
      p = {
        ...typeof p == "string" ? { key: p } : p
      }, typeof p.key == "string" && (p.text || (p.text = Zt.getLang(p.key, p.key)), p.btnType || (p.btnType = `btn-wide ${p.key === "confirm" ? "primary" : "btn-default"}`)), p && u.push(p);
    }, []);
    let d;
    const f = u.length ? {
      gap: 4,
      items: u,
      onClickItem: ({ item: p, event: m }) => {
        const v = ae.query(m.target, l);
        d = p.key, (o == null ? void 0 : o(p, v)) !== !1 && v && v.hide();
      }
    } : void 0;
    return await ae.open({
      key: l,
      type: "custom",
      size: 400,
      className: "modal-alert",
      content: c,
      backdrop: "static",
      custom: { footerActions: f, ...typeof a == "function" ? a() : a },
      ...h
    }), d;
  }
  static async confirm(t) {
    typeof t == "string" && (t = { message: t });
    const { onClickAction: e, onResult: n, ...s } = t;
    return await ae.alert({
      actions: ["confirm", "cancel"],
      onClickAction: (r, o) => {
        n == null || n(r.key === "confirm", o), e == null || e(r, o);
      },
      ...s
    }) === "confirm";
  }
};
let or = ae;
Me = /* @__PURE__ */ new WeakMap();
ti = /* @__PURE__ */ new WeakMap();
Bt = /* @__PURE__ */ new WeakMap();
ps = /* @__PURE__ */ new WeakSet();
rr = function(t) {
  return new Promise((e) => {
    if (Array.isArray(t))
      return this.modalElement.innerHTML = t[0], g(this.modalElement).runJS(), e();
    const { afterRender: n, ...s } = t;
    t = {
      afterRender: (i) => {
        this.layout(), n == null || n(i), e();
      },
      ...s
    }, us(
      /* @__PURE__ */ y(lh, { ...t }),
      this.modalElement
    );
  });
};
Kr = /* @__PURE__ */ new WeakSet();
ch = function() {
  const { loadingText: t } = this.options;
  return sn(this, ps, rr).call(this, {
    body: /* @__PURE__ */ y("div", { className: "modal-loading-indicator", children: [
      /* @__PURE__ */ y("span", { className: "spinner" }),
      t ? /* @__PURE__ */ y("span", { className: "modal-loading-text", children: t }) : null
    ] })
  });
};
ei = /* @__PURE__ */ new WeakSet();
Xr = function(t) {
  if (t)
    return sn(this, ps, rr).call(this, {
      body: /* @__PURE__ */ y("div", { className: "modal-load-failed", children: t })
    });
};
or.LOADING_CLASS = "loading";
or.DEFAULT = {
  ...ne.DEFAULT,
  loadTimeout: 1e4,
  destoryOnHide: !0
};
var la = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Jr = (t, e, n) => (la(t, e, "read from private field"), n ? n.call(t) : e.get(t)), zs = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Cl = (t, e, n, s) => (la(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Zr = (t, e, n) => (la(t, e, "access private method"), n), We, ca, hh, Qr, uh, ha, dh;
const Jf = '[data-toggle="modal"]';
class fh extends lt {
  constructor() {
    super(...arguments), zs(this, ca), zs(this, Qr), zs(this, ha), zs(this, We, void 0);
  }
  get modal() {
    return Jr(this, We);
  }
  get container() {
    const { container: e } = this.options;
    return typeof e == "string" ? document.querySelector(e) : e instanceof HTMLElement ? e : document.body;
  }
  show() {
    var e;
    return (e = Zr(this, Qr, uh).call(this)) == null ? void 0 : e.show();
  }
  hide() {
    var e;
    return (e = Jr(this, We)) == null ? void 0 : e.hide();
  }
}
We = /* @__PURE__ */ new WeakMap();
ca = /* @__PURE__ */ new WeakSet();
hh = function() {
  const {
    container: t,
    ...e
  } = this.options, n = e, s = this.$element.attr("href") || "";
  return n.type || (n.target || s[0] === "#" ? n.type = "static" : n.type = n.type || (n.url || s ? "ajax" : "custom")), !n.url && (n.type === "iframe" || n.type === "ajax") && s[0] !== "#" && (n.url = s), n;
};
Qr = /* @__PURE__ */ new WeakSet();
uh = function() {
  const t = Zr(this, ca, hh).call(this);
  let e = Jr(this, We);
  if (e)
    return e.setOptions(t), e;
  if (t.type === "static") {
    const n = Zr(this, ha, dh).call(this);
    if (!n)
      return;
    e = ne.ensure(n, t);
  } else
    e = or.ensure(this.container, t);
  return Cl(this, We, e), e.on("destroyed", () => {
    Cl(this, We, void 0);
  }), e;
};
ha = /* @__PURE__ */ new WeakSet();
dh = function() {
  let t = this.options.target;
  if (!t) {
    const { $element: e } = this;
    if (e.is("a")) {
      const n = e.attr("href");
      n != null && n.startsWith("#") && (t = n);
    }
  }
  return this.container.querySelector(t || ".modal");
};
fh.NAME = "ModalTrigger";
g(document).on("click.modal.zui", (t) => {
  var s;
  const e = t.target, n = (s = e.closest) == null ? void 0 : s.call(e, Jf);
  if (n) {
    const i = fh.ensure(n);
    i && i.show();
  }
});
let ph = class extends qe {
  beforeRender() {
    const e = super.beforeRender();
    return e.className = N(e.className, e.type ? `nav-${e.type}` : "", {
      "nav-stacked": e.stacked
    }), e;
  }
};
ph.NAME = "nav";
class gh extends J {
}
gh.NAME = "Nav";
gh.Component = ph;
function gs(t, e) {
  const n = t.pageTotal || Math.ceil(t.recTotal / t.recPerPage);
  return typeof e == "string" && (e === "first" ? e = 1 : e === "last" ? e = n : e === "prev" ? e = t.page - 1 : e === "next" ? e = t.page + 1 : e === "current" ? e = t.page : e = Number.parseInt(e, 10)), e = e !== void 0 ? Math.max(1, Math.min(e < 0 ? n + e : e, n)) : t.page, {
    ...t,
    pageTotal: n,
    page: e
  };
}
function Zf({
  key: t,
  type: e,
  btnType: n,
  page: s,
  format: i,
  pagerInfo: r,
  linkCreator: o,
  ...a
}) {
  const l = gs(r, s);
  return a.text === void 0 && !a.icon && i && (a.text = typeof i == "function" ? i(l) : X(i, l)), a.url === void 0 && o && (a.url = typeof o == "function" ? o(l) : X(o, l)), a.disabled === void 0 && (a.disabled = s !== void 0 && l.page === r.page), /* @__PURE__ */ y(Lt, { type: n, ...a });
}
const jt = 24 * 60 * 60 * 1e3, ft = (t) => t ? (t instanceof Date || (typeof t == "string" && (t = t.trim(), /^\d+$/.test(t) && (t = Number.parseInt(t, 10))), typeof t == "number" && t < 1e10 && (t *= 1e3), t = new Date(t)), t) : /* @__PURE__ */ new Date(), Es = (t, e = /* @__PURE__ */ new Date()) => (t = ft(t), e = ft(e), t.getFullYear() === e.getFullYear() && t.getMonth() === e.getMonth() && t.getDate() === e.getDate()), to = (t, e = /* @__PURE__ */ new Date()) => ft(t).getFullYear() === ft(e).getFullYear(), Qf = (t, e = /* @__PURE__ */ new Date()) => (t = ft(t), e = ft(e), t.getFullYear() === e.getFullYear() && t.getMonth() === e.getMonth()), Yg = (t, e = /* @__PURE__ */ new Date()) => {
  t = ft(t), e = ft(e);
  const n = 1e3 * 60 * 60 * 24, s = Math.floor(t.getTime() / n), i = Math.floor(e.getTime() / n);
  return Math.floor((s + 4) / 7) === Math.floor((i + 4) / 7);
}, Kg = (t, e) => Es(ft(e), t), Xg = (t, e) => Es(ft(e).getTime() - jt, t), Jg = (t, e) => Es(ft(e).getTime() + jt, t), Zg = (t, e) => Es(ft(e).getTime() - 2 * jt, t), eo = (t, e = "yyyy-MM-dd hh:mm", n = "") => {
  if (t = ft(t), Number.isNaN(t.getDay()))
    return n;
  const s = {
    "M+": t.getMonth() + 1,
    "d+": t.getDate(),
    "h+": t.getHours(),
    "H+": t.getHours() % 12,
    "m+": t.getMinutes(),
    "s+": t.getSeconds(),
    "S+": t.getMilliseconds()
  };
  return /(y+)/i.test(e) && (e.includes("[yyyy-]") && (e = e.replace("[yyyy-]", to(t) ? "" : "yyyy-")), e = e.replace(RegExp.$1, `${t.getFullYear()}`.substring(4 - RegExp.$1.length))), Object.keys(s).forEach((i) => {
    if (new RegExp(`(${i})`).test(e)) {
      const r = `${s[i]}`;
      e = e.replace(RegExp.$1, RegExp.$1.length === 1 ? r : `00${r}`.substring(r.length));
    }
  }), e;
}, Qg = (t, e, n) => {
  const s = {
    full: "yyyy-M-d",
    month: "M-d",
    day: "d",
    str: "{0} ~ {1}",
    ...n
  }, i = eo(t, to(t) ? s.month : s.full);
  if (Es(t, e))
    return i;
  const r = eo(e, to(t, e) ? Qf(t, e) ? s.day : s.month : s.full);
  return s.str.replace("{0}", i).replace("{1}", r);
}, tm = (t) => {
  const e = (/* @__PURE__ */ new Date()).getTime();
  switch (t) {
    case "oneWeek":
      return e - jt * 7;
    case "oneMonth":
      return e - jt * 31;
    case "threeMonth":
      return e - jt * 31 * 3;
    case "halfYear":
      return e - jt * 183;
    case "oneYear":
      return e - jt * 365;
    case "twoYear":
      return e - 2 * (jt * 365);
    default:
      return 0;
  }
}, El = (t, e, n = !0, s = Date.now()) => {
  switch (e) {
    case "year":
      return t *= 365, El(t, "day", n, s);
    case "quarter":
      t *= 3;
      break;
    case "month":
      return t *= 30, El(t, "day", n, s);
    case "week":
      t *= 7;
      break;
    case "day":
      t *= 24;
      break;
    case "hour":
      t *= 60;
      break;
    case "minute":
      t *= 6e4;
      break;
    default:
      t = 0;
  }
  return n ? s + t : s - t;
};
function tp({
  key: t,
  type: e,
  page: n,
  text: s = "",
  pagerInfo: i,
  children: r,
  ...o
}) {
  const a = gs(i, n);
  return s = typeof s == "function" ? s(a) : X(s, a), /* @__PURE__ */ y(Ec, { ...o, children: [
    r,
    s
  ] });
}
function ep({
  key: t,
  type: e,
  btnType: n,
  count: s = 12,
  pagerInfo: i,
  onClick: r,
  linkCreator: o,
  ...a
}) {
  if (!i.pageTotal)
    return;
  const l = { ...a, square: !0 }, h = () => (l.text = "", l.icon = "icon-ellipsis-h", l.disabled = !0, /* @__PURE__ */ y(Lt, { type: n, ...l })), c = (d, f) => {
    const p = [];
    for (let m = d; m <= f; m++) {
      l.text = m, delete l.icon, l.disabled = !1;
      const v = gs(i, m);
      o && (l.url = typeof o == "function" ? o(v) : X(o, v)), p.push(/* @__PURE__ */ y(Lt, { type: n, ...l, onClick: r }));
    }
    return p;
  };
  let u = [];
  return u = [...c(1, 1)], i.pageTotal <= 1 || (i.pageTotal <= s ? u = [...u, ...c(2, i.pageTotal)] : i.page < s - 2 ? u = [...u, ...c(2, s - 2), h(), ...c(i.pageTotal, i.pageTotal)] : i.page > i.pageTotal - s + 3 ? u = [...u, h(), ...c(i.pageTotal - s + 3, i.pageTotal)] : u = [...u, h(), ...c(i.page - Math.ceil((s - 4) / 2), i.page + Math.floor((s - 4) / 2)), h(), ...c(i.pageTotal, i.pageTotal)]), u;
}
function np({
  type: t,
  pagerInfo: e,
  linkCreator: n,
  items: s = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1e3, 2e3],
  dropdown: i = {},
  itemProps: r,
  ...o
}) {
  var l;
  i.items = i.items ?? s.map((h) => {
    const c = { ...e, recPerPage: h };
    return {
      ...r,
      text: `${h}`,
      active: h === e.recPerPage,
      url: typeof n == "function" ? n(c) : X(n, c)
    };
  });
  const { text: a = "" } = o;
  return o.text = typeof a == "function" ? a(e) : X(a, e), i.menu = { ...i.menu, className: N((l = i.menu) == null ? void 0 : l.className, "pager-size-menu") }, /* @__PURE__ */ y(Vc, { type: "dropdown", dropdown: i, ...o });
}
function sp({
  key: t,
  page: e,
  type: n,
  btnType: s,
  pagerInfo: i,
  size: r,
  onClick: o,
  onChange: a,
  linkCreator: l,
  ...h
}) {
  const c = { ...h };
  let u;
  const d = (m) => {
    var v;
    u = Number((v = m.target) == null ? void 0 : v.value) || 1, u = u > i.pageTotal ? i.pageTotal : u;
  }, f = (m) => {
    if (!(m != null && m.target))
      return;
    u = u <= i.pageTotal ? u : i.pageTotal;
    const v = gs(i, u);
    a && !a({ info: v, event: m }) || (m.target.href = c.url = typeof l == "function" ? l(v) : X(l, v));
  }, p = gs(i, e || 0);
  return c.url = typeof l == "function" ? l(p) : X(l, p), /* @__PURE__ */ y("div", { className: N("input-group", "pager-goto-group", r ? `size-${r}` : ""), children: [
    /* @__PURE__ */ y("input", { type: "number", class: "form-control", max: i.pageTotal, min: "1", onInput: d }),
    /* @__PURE__ */ y(Lt, { type: s, ...c, onClick: f })
  ] });
}
let Ms = class extends dt {
  get pagerInfo() {
    const { page: e = 1, recTotal: n = 0, recPerPage: s = 10 } = this.props;
    return { page: e, recTotal: n, recPerPage: s, pageTotal: s ? Math.ceil(n / s) : 0 };
  }
  isBtnItem(e) {
    return e === "link" || e === "nav" || e === "size-menu" || e === "goto" || super.isBtnItem(e);
  }
  getItemRenderProps(e, n, s) {
    const i = super.getItemRenderProps(e, n, s), r = n.type || "item";
    return r === "info" ? Object.assign(i, { pagerInfo: this.pagerInfo }) : (r === "link" || r === "size-menu" || r === "nav" || r === "goto") && Object.assign(i, { pagerInfo: this.pagerInfo, linkCreator: e.linkCreator }), i;
  }
};
Ms.NAME = "pager";
Ms.defaultProps = {
  btnProps: {
    btnType: "ghost",
    size: "sm"
  }
};
Ms.ItemComponents = {
  ...dt.ItemComponents,
  link: Zf,
  info: tp,
  nav: ep,
  "size-menu": np,
  goto: sp
};
class mh extends J {
}
mh.NAME = "Pager";
mh.Component = Ms;
var Oi, Ii, yh;
class ip extends U {
  constructor() {
    super(...arguments);
    O(this, Ii);
    O(this, Oi, (n) => {
      var o;
      const { onDeselect: s, selections: i } = this.props, r = (o = n.target.closest(".picker-deselect-btn")) == null ? void 0 : o.dataset.idx;
      r && s && (i != null && i.length) && (n.stopPropagation(), s([i[+r]], n));
    });
  }
  render() {
    const {
      className: n,
      style: s,
      disabled: i,
      focused: r,
      onClick: o,
      children: a
    } = this.props;
    return /* @__PURE__ */ y(
      "div",
      {
        className: N("picker-select picker-select-multi form-control", n, { disabled: i, focused: r }),
        style: s,
        onClick: o,
        children: [
          ot(this, Ii, yh).call(this),
          a,
          /* @__PURE__ */ y("span", { class: "caret" })
        ]
      }
    );
  }
}
Oi = new WeakMap(), Ii = new WeakSet(), yh = function() {
  const { selections: n = [], placeholder: s } = this.props;
  return n.length ? /* @__PURE__ */ y("div", { className: "picker-multi-selections", children: n.map((i, r) => /* @__PURE__ */ y("div", { className: "picker-multi-selection", children: [
    i.text ?? i.value,
    /* @__PURE__ */ y("div", { className: "picker-deselect-btn btn size-xs ghost", onClick: D(this, Oi), "data-idx": r, children: /* @__PURE__ */ y("span", { className: "close" }) })
  ] })) }) : /* @__PURE__ */ y("span", { className: "picker-select-placeholder", children: s });
};
var Hi;
class rp extends U {
  constructor() {
    super(...arguments);
    O(this, Hi, (n) => {
      const { onDeselect: s, selections: i } = this.props;
      s && (i != null && i.length) && (n.stopPropagation(), s(i, n));
    });
  }
  render() {
    const {
      className: n,
      style: s,
      disabled: i,
      placeholder: r,
      focused: o,
      selections: a = [],
      onDeselect: l,
      onClick: h,
      children: c
    } = this.props, [u] = a, d = u ? /* @__PURE__ */ y("span", { className: "picker-single-selection", children: u.text ?? u.value }) : /* @__PURE__ */ y("span", { className: "picker-select-placeholder", children: r }), f = u && l ? /* @__PURE__ */ y("button", { type: "button", className: "btn picker-deselect-btn size-sm square ghost", onClick: D(this, Hi), children: /* @__PURE__ */ y("span", { className: "close" }) }) : null;
    return /* @__PURE__ */ y(
      "div",
      {
        className: N("picker-select picker-select-single form-control", n, { disabled: i, focused: o }),
        style: s,
        onClick: h,
        children: [
          d,
          c,
          f,
          /* @__PURE__ */ y("span", { class: "caret" })
        ]
      }
    );
  }
}
Hi = new WeakMap();
var op = ["Shift", "Meta", "Alt", "Control"], ap = typeof navigator == "object" && /Mac|iPod|iPhone|iPad/.test(navigator.platform) ? "Meta" : "Control";
function Cr(t, e) {
  return typeof t.getModifierState == "function" && t.getModifierState(e);
}
function lp(t) {
  return t.trim().split(" ").map(function(e) {
    var n = e.split(/\b\+/), s = n.pop();
    return [n = n.map(function(i) {
      return i === "$mod" ? ap : i;
    }), s];
  });
}
function cp(t, e) {
  var n;
  e === void 0 && (e = {});
  var s = (n = e.timeout) != null ? n : 1e3, i = Object.keys(t).map(function(a) {
    return [lp(a), t[a]];
  }), r = /* @__PURE__ */ new Map(), o = null;
  return function(a) {
    a instanceof KeyboardEvent && (i.forEach(function(l) {
      var h = l[0], c = l[1], u = r.get(h) || h;
      (function(d, f) {
        return !(f[1].toUpperCase() !== d.key.toUpperCase() && f[1] !== d.code || f[0].find(function(p) {
          return !Cr(d, p);
        }) || op.find(function(p) {
          return !f[0].includes(p) && f[1] !== p && Cr(d, p);
        }));
      })(a, u[0]) ? u.length > 1 ? r.set(h, u.slice(1)) : (r.delete(h), c(a)) : Cr(a, a.key) || r.delete(h);
    }), o && clearTimeout(o), o = setTimeout(r.clear.bind(r), s));
  };
}
function hp(t, e, n) {
  var s;
  n === void 0 && (n = {});
  var i = (s = n.event) != null ? s : "keydown", r = cp(e, n);
  return t.addEventListener(i, r), function() {
    t.removeEventListener(i, r);
  };
}
const up = (t, e) => t.reduce((n, s) => [...n].reduce((i, r) => {
  if (typeof r != "string")
    return i.push(r), i;
  const o = r.toLowerCase().split(s);
  if (o.length === 1)
    return i.push(r), i;
  let a = 0;
  return o.forEach((l, h) => {
    h && (i.push(/* @__PURE__ */ y("span", { class: "picker-menu-item-match", children: r.substring(a, a + s.length) })), a += s.length), i.push(r.substring(a, a + l.length)), a += l.length;
  }), i;
}, []), e);
var de, bn, _n, _s, xn, ni, Le, Un, Bi, wh, $n, xs, kn, $s, zi, vh;
class dp extends U {
  constructor() {
    super(...arguments);
    O(this, xn);
    O(this, Le);
    O(this, Bi);
    O(this, zi);
    O(this, de, void 0);
    O(this, bn, void 0);
    O(this, _n, void 0);
    O(this, _s, void 0);
    O(this, $n, void 0);
    O(this, xs, void 0);
    O(this, kn, void 0);
    O(this, $s, void 0);
    this.state = { keys: "", show: !1 }, F(this, de, 0), F(this, bn, $t()), F(this, _n, $t()), F(this, $n, (n) => {
      g(n.target).closest(`#picker-menu-${this.props.id}`).length || this.hide();
    }), F(this, xs, ({ item: n }) => {
      this.select(n.key);
    }), F(this, kn, (n) => {
      this.setState({ keys: n.target.value });
    }), F(this, $s, (n) => {
      n.stopPropagation(), this.setState({ keys: "" }, this.focus.bind(this));
    });
  }
  componentDidMount() {
    g(document).on("click", D(this, $n)), this.show(this.focus.bind(this)), F(this, _s, hp(window, {
      Escape: () => {
        this.state.show && (this.state.keys ? this.setState({ keys: "" }) : this.hide());
      },
      Enter: () => {
        if (!this.state.show)
          return;
        const s = ot(this, Le, Un).call(this);
        s != null && s.length && this.select(s.dataset("value"));
      },
      ArrowUp: () => {
        var r;
        if (!this.state.show)
          return;
        const s = (r = ot(this, Le, Un).call(this)) == null ? void 0 : r.parent();
        if (!(s != null && s.length))
          return;
        let i = s.prev();
        i.length || (i = s.parent().children().last()), this.setHoverItem(i.children("a").dataset("value"));
      },
      ArrowDown: () => {
        var r;
        if (!this.state.show)
          return;
        const s = (r = ot(this, Le, Un).call(this)) == null ? void 0 : r.parent();
        if (!(s != null && s.length))
          return;
        let i = s.next();
        i.length || (i = s.parent().children().first()), this.setHoverItem(i.children("a").dataset("value"));
      }
    }));
    const n = ot(this, xn, ni).call(this);
    n && g(n).on("mouseenter.pickerMenu.zui", ".menu-item", (s) => {
      const i = g(s.currentTarget);
      this.setHoverItem(i.children("a").dataset("value"));
    });
  }
  componentWillUnmount() {
    var s;
    g(document).off("click", D(this, $n)), (s = D(this, _s)) == null || s.call(this);
    const n = ot(this, xn, ni).call(this);
    n && g(n).off(".pickerMenu.zui");
  }
  show(n) {
    if (this.state.show) {
      n == null || n();
      return;
    }
    this.setState({ show: !0 }, n);
  }
  focus() {
    var n;
    (n = D(this, bn).current) == null || n.focus();
  }
  hide() {
    this.state.show && (D(this, de) && window.clearTimeout(D(this, de)), this.setState({ show: !1 }, () => {
      F(this, de, window.setTimeout(() => {
        var n, s;
        F(this, de, 0), (s = (n = this.props).onRequestHide) == null || s.call(n);
      }, 200));
    }));
  }
  select(n) {
    const s = this.props.items.find((i) => i.value === n);
    s && this.props.onSelectItem(s);
  }
  setHoverItem(n) {
    this.setState({ hover: n }, () => {
      const s = ot(this, Le, Un).call(this);
      s != null && s.length && s[0].scrollIntoView({ block: "nearest", behavior: "smooth" });
    });
  }
  render() {
    const {
      id: n,
      className: s,
      style: i = {},
      maxHeight: r,
      maxWidth: o,
      width: a,
      menu: l,
      checkbox: h
    } = this.props, { show: c, keys: u } = this.state, d = u.trim().length;
    return /* @__PURE__ */ y(
      "div",
      {
        className: N("picker-menu menu-popup", s, { shown: c, "has-search": d }),
        id: `picker-menu-${n}`,
        style: { maxHeight: r, maxWidth: o, width: a, ...i },
        children: [
          ot(this, zi, vh).call(this),
          /* @__PURE__ */ y(
            Zi,
            {
              ref: D(this, _n),
              className: "picker-menu-list",
              items: ot(this, Bi, wh).call(this),
              onClickItem: D(this, xs),
              checkbox: h,
              ...l
            }
          )
        ]
      }
    );
  }
}
de = new WeakMap(), bn = new WeakMap(), _n = new WeakMap(), _s = new WeakMap(), xn = new WeakSet(), ni = function() {
  var n;
  return (n = D(this, _n).current) == null ? void 0 : n.ref.current;
}, Le = new WeakSet(), Un = function() {
  const n = ot(this, xn, ni).call(this);
  if (n)
    return g(n).find(".menu-item>a.hover");
}, Bi = new WeakSet(), wh = function() {
  const { selections: n, items: s } = this.props, i = new Set(n), { keys: r, hover: o } = this.state, a = r.toLowerCase().split(" ").filter((c) => c.length);
  let l = !1;
  const h = s.reduce((c, u) => {
    const {
      value: d,
      keys: f,
      text: p,
      className: m,
      ...v
    } = u;
    if (!a.length || a.every((w) => d.toLowerCase().includes(w) || (f == null ? void 0 : f.toLowerCase().includes(w)) || typeof p == "string" && p.toLowerCase().includes(w))) {
      let w = p ?? d;
      typeof w == "string" && a.length && (w = up(a, [w])), d === o && (l = !0), c.push({
        key: d,
        active: i.has(d),
        text: w,
        className: N(m, { hover: d === o }),
        "data-value": d,
        ...v
      });
    }
    return c;
  }, []);
  return !l && h.length && (h[0].className = N(h[0].className, "hover")), h;
}, $n = new WeakMap(), xs = new WeakMap(), kn = new WeakMap(), $s = new WeakMap(), zi = new WeakSet(), vh = function() {
  const {
    search: n,
    searchHint: s
  } = this.props, { keys: i } = this.state, r = i.trim().length;
  return n ? /* @__PURE__ */ y("div", { className: "picker-menu-search", children: [
    /* @__PURE__ */ y(
      "input",
      {
        className: "form-control picker-menu-search-input",
        type: "text",
        placeholder: s,
        value: i,
        onChange: D(this, kn),
        onInput: D(this, kn),
        ref: D(this, bn)
      }
    ),
    r ? /* @__PURE__ */ y("button", { type: "button", className: "btn picker-menu-search-clear square size-sm ghost", onClick: D(this, $s), children: /* @__PURE__ */ y("span", { className: "close" }) }) : /* @__PURE__ */ y("span", { className: "magnifier" })
  ] }) : null;
};
var ua = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, st = (t, e, n) => (ua(t, e, "read from private field"), n ? n.call(t) : e.get(t)), et = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, xi = (t, e, n, s) => (ua(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), fp = (t, e, n, s) => ({
  set _(i) {
    xi(t, e, i, n);
  },
  get _() {
    return st(t, e, s);
  }
}), at = (t, e, n) => (ua(t, e, "access private method"), n), si, En, $i, Gt, Ze, qn, ki, da, ii, no, so, bh, fa, pa, ga, ma, ya, _h, io, xh, wa, $h, ri, ro;
let kh = class extends U {
  constructor(e) {
    super(e), et(this, Ze), et(this, ki), et(this, ii), et(this, so), et(this, ya), et(this, io), et(this, wa), et(this, ri), et(this, si, 0), et(this, En, sr()), et(this, $i, $t()), et(this, Gt, void 0), et(this, fa, (n) => {
      const { valueList: s } = this, i = new Set(n.map((o) => o.value)), r = s.filter((o) => !i.has(o));
      this.setValue(r);
    }), et(this, pa, () => {
      requestAnimationFrame(() => this.toggle());
    }), et(this, ga, () => {
      this.close();
    }), et(this, ma, (n) => {
      this.props.multiple ? this.toggleValue(n.value) : this.setValue(n.value).then(() => {
        var s;
        (s = st(this, $i).current) == null || s.hide();
      });
    }), this.state = {
      value: at(this, ii, no).call(this, e.defaultValue) ?? "",
      open: !1,
      loading: !1,
      search: "",
      items: Array.isArray(e.items) ? e.items : []
    };
  }
  get value() {
    return this.state.value;
  }
  get valueList() {
    return at(this, ki, da).call(this, this.state.value);
  }
  componentDidMount() {
    at(this, ri, ro).call(this, !0);
  }
  componentDidUpdate() {
    at(this, ri, ro).call(this);
  }
  componentWillUnmount() {
    var n;
    var e;
    (n = this.props.beforeDestroy) == null || n.call(this), (e = st(this, Gt)) == null || e.call(this), xi(this, Gt, void 0);
  }
  async loadItemList() {
    let { items: e } = this.props;
    if (typeof e == "function") {
      const s = ++fp(this, si)._;
      if (await at(this, Ze, qn).call(this, { loading: !0, items: [] }), e = await e(), st(this, si) !== s)
        return [];
    }
    const n = {};
    return Array.isArray(e) && this.state.items !== e && (n.items = e), this.state.loading && (n.loading = !1), Object.keys(n).length && await at(this, Ze, qn).call(this, n), e;
  }
  getItemList() {
    return this.state.items;
  }
  getItemMap() {
    return this.getItemList().reduce((e, n) => (e[n.value] = n, e), {});
  }
  getItemByValue(e) {
    return this.getItemList().find((n) => n.value === e);
  }
  getSelections() {
    const e = this.getItemMap();
    return this.valueList.map((n) => e[n] || { value: n });
  }
  async toggle(e) {
    if (e === void 0)
      e = !this.state.open;
    else if (e === this.state.open)
      return;
    await at(this, Ze, qn).call(this, { open: e }), e && this.loadItemList();
  }
  open() {
    return this.toggle(!0);
  }
  close() {
    return this.toggle(!1);
  }
  getValue() {
    return this.props.multiple ? this.valueList : this.value;
  }
  async setValue(e, n) {
    var s;
    await at(this, Ze, qn).call(this, { value: at(this, ii, no).call(this, e), ...n }), (s = this.props.onChange) == null || s.call(this, this.getValue());
  }
  toggleValue(e, n) {
    const { valueList: s } = this, i = s.indexOf(e);
    if (n !== !!i)
      return i > -1 ? s.splice(i, 1) : s.push(e), this.setValue(s);
  }
  render() {
    const {
      className: e,
      style: n,
      children: s,
      multiple: i,
      Select: r,
      name: o
    } = this.props, a = r || (i ? ip : rp), l = at(this, so, bh).call(this);
    return /* @__PURE__ */ y(
      "div",
      {
        id: `picker-${st(this, En)}`,
        className: N("picker", e),
        style: n,
        children: [
          /* @__PURE__ */ y(a, { ...l }),
          s,
          at(this, io, xh).call(this),
          o ? /* @__PURE__ */ y("input", { type: "hidden", className: "picker-value", name: o, value: this.state.value }) : null
        ]
      }
    );
  }
};
si = /* @__PURE__ */ new WeakMap();
En = /* @__PURE__ */ new WeakMap();
$i = /* @__PURE__ */ new WeakMap();
Gt = /* @__PURE__ */ new WeakMap();
Ze = /* @__PURE__ */ new WeakSet();
qn = function(t) {
  return new Promise((e) => {
    this.setState(t, e);
  });
};
ki = /* @__PURE__ */ new WeakSet();
da = function(t) {
  return typeof t == "string" ? t.length ? g.unique(t.split(this.props.valueSplitter ?? ",")) : [] : Array.isArray(t) ? g.unique(t) : [];
};
ii = /* @__PURE__ */ new WeakSet();
no = function(t) {
  const e = at(this, ki, da).call(this, t);
  return e.length ? e.join(this.props.valueSplitter ?? ",") : void 0;
};
so = /* @__PURE__ */ new WeakSet();
bh = function() {
  const { placeholder: t, disabled: e, multiple: n } = this.props, { open: s } = this.state;
  return {
    focused: s,
    placeholder: t,
    disabled: e,
    multiple: n,
    selections: this.getSelections(),
    onClick: st(this, pa),
    onDeselect: st(this, fa)
  };
};
fa = /* @__PURE__ */ new WeakMap();
pa = /* @__PURE__ */ new WeakMap();
ga = /* @__PURE__ */ new WeakMap();
ma = /* @__PURE__ */ new WeakMap();
ya = /* @__PURE__ */ new WeakSet();
_h = function() {
  const { search: t, menuClass: e, menuWidth: n, menuStyle: s, menuMaxHeight: i, menuMaxWidth: r, menuMinWidth: o, multiple: a, searchHint: l, menuCheckbox: h } = this.props, { items: c } = this.state;
  return {
    id: st(this, En),
    items: c,
    selections: this.valueList,
    search: t === !0 || typeof t == "number" && t <= c.length,
    searchHint: l,
    style: s,
    multiple: a,
    className: e,
    width: n === "100%" ? "auto" : n,
    maxHeight: i,
    maxWidth: r,
    minWidth: o,
    checkbox: h,
    onRequestHide: st(this, ga),
    onSelectItem: st(this, ma)
  };
};
io = /* @__PURE__ */ new WeakSet();
xh = function() {
  const { open: t } = this.state;
  if (!t)
    return null;
  const e = g(this.props.container || "body");
  let n = e.find(".pickers-container");
  n.length || (n = g("<div>").addClass("pickers-container").appendTo(e));
  const { Menu: s = dp } = this.props;
  return qd(/* @__PURE__ */ y(s, { ...at(this, ya, _h).call(this), ref: st(this, $i) }), n[0]);
};
wa = /* @__PURE__ */ new WeakSet();
$h = function() {
  const t = g(`#picker-${st(this, En)}`)[0], e = g(`#picker-menu-${st(this, En)}`)[0];
  if (!e || !t || !this.state.open) {
    st(this, Gt) && (st(this, Gt).call(this), xi(this, Gt, void 0));
    return;
  }
  st(this, Gt) || xi(this, Gt, Xo(t, e, () => {
    const { menuDirection: n, menuWidth: s } = this.props;
    nr(t, e, {
      placement: `${n === "top" ? "top" : "bottom"}-start`,
      middleware: [n === "auto" ? Qi() : null, Hr(), Yo(1)].filter(Boolean)
    }).then(({ x: i, y: r }) => {
      g(e).css({ left: i, top: r, width: s === "100%" ? g(t).width() : void 0 });
    }), s === "100%" && g(e).css({ width: g(t).width() });
  }));
};
ri = /* @__PURE__ */ new WeakSet();
ro = function(t = !1) {
  var e;
  (e = this.props.afterRender) == null || e.call(this, { firstRender: t }), at(this, wa, $h).call(this);
};
kh.defaultProps = {
  container: "body",
  valueSplitter: ",",
  search: !0,
  menuWidth: "100%",
  menuDirection: "auto",
  menuMaxHeight: 300
};
class Sh extends J {
}
Sh.NAME = "Picker";
Sh.Component = kh;
class Ch extends lt {
  constructor() {
    super(...arguments), this.cleanup = () => {
    }, this.toggle = () => {
    };
  }
  init() {
    this.initTarget(), this.initMask(), this.initArrow(), this.createPopper(), this.toggle = () => {
      if (this.$target.hasClass("hidden")) {
        this.show();
        return;
      }
      this.hide();
    }, this.$element.addClass("z-50").on("click", this.toggle);
  }
  destroy() {
    this.cleanup(), this.$element.off("click", this.toggle), this.$target.remove();
  }
  computePositionConfig() {
    const { placement: e, strategy: n } = this.options, s = {
      placement: e,
      strategy: n,
      middleware: []
    }, { flip: i, shift: r, arrow: o, offset: a } = this.options;
    return i && s.middleware.push(Qi()), r && s.middleware.push(r === !0 ? Hr() : Hr(r)), o && s.middleware.push(Ir({ element: this.$arrow[0] })), a && s.middleware.push(Yo(a)), s;
  }
  createPopper() {
    const e = this.element, n = this.$target[0];
    this.cleanup = Xo(e, n, () => {
      nr(e, n, this.computePositionConfig()).then(({ x: s, y: i, placement: r, middlewareData: o }) => {
        if (Object.assign(n.style, {
          left: `${s}px`,
          top: `${i}px`
        }), !Ir || !o.arrow)
          return;
        const { x: a, y: l } = o.arrow, h = {
          top: "bottom",
          right: "left",
          bottom: "top",
          left: "right"
        }[r.split("-")[0]];
        Object.assign(this.$arrow[0].style, {
          left: a != null ? `${a}px` : "",
          top: l != null ? `${l}px` : "",
          right: "",
          bottom: "",
          [h]: "-4px"
        });
      });
    });
  }
  initTarget() {
    const e = this.$element.data("target");
    if (!e)
      throw new Error("popsvers trigger must have target.");
    const n = g(e);
    if (!n.length)
      throw new Error("popovers target must exist.");
    const { strategy: s } = this.options;
    n.addClass(s), n.addClass("hidden"), n.addClass("z-50"), n.on("click", (i) => {
      g(i.target).data("dismiss") === "popovers" && this.hide();
    }), this.$target = n;
  }
  show() {
    this.$target.removeClass("hidden"), this.$mask.removeClass("hidden");
  }
  hide() {
    this.$target.addClass("hidden"), this.$mask.addClass("hidden");
  }
  initMask() {
    const e = g('<div class="fixed top-0 right-0 bottom-0 left-0 z-40 hidden"></div>');
    e.on("click", () => {
      this.hide();
    }), this.$target.parent().append(e), this.$mask = e;
  }
  initArrow() {
    const { arrow: e } = this.options;
    e && (this.$arrow = g('<div class="arrow bg-inherit rotate-45 absolute w-2 h-2"></div>'), this.$target.append(this.$arrow));
  }
}
Ch.NAME = "Popovers";
Ch.DEFAULT = {
  placement: "bottom",
  strategy: "fixed",
  flip: !0,
  shift: { padding: 5 },
  arrow: !1,
  offset: 1
};
class Eh extends J {
}
Eh.NAME = "Toolbar";
Eh.Component = dt;
function Ts(t) {
  return t.split("-")[1];
}
function va(t) {
  return t === "y" ? "height" : "width";
}
function un(t) {
  return t.split("-")[0];
}
function ar(t) {
  return ["top", "bottom"].includes(un(t)) ? "x" : "y";
}
function Ml(t, e, n) {
  let { reference: s, floating: i } = t;
  const r = s.x + s.width / 2 - i.width / 2, o = s.y + s.height / 2 - i.height / 2, a = ar(e), l = va(a), h = s[l] / 2 - i[l] / 2, c = a === "x";
  let u;
  switch (un(e)) {
    case "top":
      u = { x: r, y: s.y - i.height };
      break;
    case "bottom":
      u = { x: r, y: s.y + s.height };
      break;
    case "right":
      u = { x: s.x + s.width, y: o };
      break;
    case "left":
      u = { x: s.x - i.width, y: o };
      break;
    default:
      u = { x: s.x, y: s.y };
  }
  switch (Ts(e)) {
    case "start":
      u[a] -= h * (n && c ? -1 : 1);
      break;
    case "end":
      u[a] += h * (n && c ? -1 : 1);
  }
  return u;
}
const pp = async (t, e, n) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: r = [], platform: o } = n, a = r.filter(Boolean), l = await (o.isRTL == null ? void 0 : o.isRTL(e));
  let h = await o.getElementRects({ reference: t, floating: e, strategy: i }), { x: c, y: u } = Ml(h, s, l), d = s, f = {}, p = 0;
  for (let m = 0; m < a.length; m++) {
    const { name: v, fn: w } = a[m], { x: b, y: k, data: C, reset: E } = await w({ x: c, y: u, initialPlacement: s, placement: d, strategy: i, middlewareData: f, rects: h, platform: o, elements: { reference: t, floating: e } });
    c = b ?? c, u = k ?? u, f = { ...f, [v]: { ...f[v], ...C } }, E && p <= 50 && (p++, typeof E == "object" && (E.placement && (d = E.placement), E.rects && (h = E.rects === !0 ? await o.getElementRects({ reference: t, floating: e, strategy: i }) : E.rects), { x: c, y: u } = Ml(h, d, l)), m = -1);
  }
  return { x: c, y: u, placement: d, strategy: i, middlewareData: f };
};
function Mh(t) {
  return typeof t != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(t) : { top: t, right: t, bottom: t, left: t };
}
function Si(t) {
  return { ...t, top: t.y, left: t.x, right: t.x + t.width, bottom: t.y + t.height };
}
async function gp(t, e) {
  var n;
  e === void 0 && (e = {});
  const { x: s, y: i, platform: r, rects: o, elements: a, strategy: l } = t, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: u = "floating", altBoundary: d = !1, padding: f = 0 } = e, p = Mh(f), m = a[d ? u === "floating" ? "reference" : "floating" : u], v = Si(await r.getClippingRect({ element: (n = await (r.isElement == null ? void 0 : r.isElement(m))) == null || n ? m : m.contextElement || await (r.getDocumentElement == null ? void 0 : r.getDocumentElement(a.floating)), boundary: h, rootBoundary: c, strategy: l })), w = u === "floating" ? { ...o.floating, x: s, y: i } : o.reference, b = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(a.floating)), k = await (r.isElement == null ? void 0 : r.isElement(b)) && await (r.getScale == null ? void 0 : r.getScale(b)) || { x: 1, y: 1 }, C = Si(r.convertOffsetParentRelativeRectToViewportRelativeRect ? await r.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: w, offsetParent: b, strategy: l }) : w);
  return { top: (v.top - C.top + p.top) / k.y, bottom: (C.bottom - v.bottom + p.bottom) / k.y, left: (v.left - C.left + p.left) / k.x, right: (C.right - v.right + p.right) / k.x };
}
const mp = Math.min, yp = Math.max;
function wp(t, e, n) {
  return yp(t, mp(e, n));
}
const vp = (t) => ({ name: "arrow", options: t, async fn(e) {
  const { element: n, padding: s = 0 } = t || {}, { x: i, y: r, placement: o, rects: a, platform: l } = e;
  if (n == null)
    return {};
  const h = Mh(s), c = { x: i, y: r }, u = ar(o), d = va(u), f = await l.getDimensions(n), p = u === "y" ? "top" : "left", m = u === "y" ? "bottom" : "right", v = a.reference[d] + a.reference[u] - c[u] - a.floating[d], w = c[u] - a.reference[u], b = await (l.getOffsetParent == null ? void 0 : l.getOffsetParent(n));
  let k = b ? u === "y" ? b.clientHeight || 0 : b.clientWidth || 0 : 0;
  k === 0 && (k = a.floating[d]);
  const C = v / 2 - w / 2, E = h[p], P = k - f[d] - h[m], M = k / 2 - f[d] / 2 + C, T = wp(E, M, P), A = Ts(o) != null && M != T && a.reference[d] / 2 - (M < E ? h[p] : h[m]) - f[d] / 2 < 0;
  return { [u]: c[u] - (A ? M < E ? E - M : P - M : 0), data: { [u]: T, centerOffset: M - T } };
} }), bp = ["top", "right", "bottom", "left"];
bp.reduce((t, e) => t.concat(e, e + "-start", e + "-end"), []);
const _p = { left: "right", right: "left", bottom: "top", top: "bottom" };
function Ci(t) {
  return t.replace(/left|right|bottom|top/g, (e) => _p[e]);
}
function xp(t, e, n) {
  n === void 0 && (n = !1);
  const s = Ts(t), i = ar(t), r = va(i);
  let o = i === "x" ? s === (n ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return e.reference[r] > e.floating[r] && (o = Ci(o)), { main: o, cross: Ci(o) };
}
const $p = { start: "end", end: "start" };
function Er(t) {
  return t.replace(/start|end/g, (e) => $p[e]);
}
const kp = function(t) {
  return t === void 0 && (t = {}), { name: "flip", options: t, async fn(e) {
    var n;
    const { placement: s, middlewareData: i, rects: r, initialPlacement: o, platform: a, elements: l } = e, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: u, fallbackStrategy: d = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...m } = t, v = un(s), w = un(o) === o, b = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), k = u || (w || !p ? [Ci(o)] : function(x) {
      const $ = Ci(x);
      return [Er(x), $, Er($)];
    }(o));
    u || f === "none" || k.push(...function(x, $, L, W) {
      const H = Ts(x);
      let B = function(G, Pt, Mn) {
        const As = ["left", "right"], Tn = ["right", "left"], Ns = ["top", "bottom"], pr = ["bottom", "top"];
        switch (G) {
          case "top":
          case "bottom":
            return Mn ? Pt ? Tn : As : Pt ? As : Tn;
          case "left":
          case "right":
            return Pt ? Ns : pr;
          default:
            return [];
        }
      }(un(x), L === "start", W);
      return H && (B = B.map((G) => G + "-" + H), $ && (B = B.concat(B.map(Er)))), B;
    }(o, p, f, b));
    const C = [o, ...k], E = await gp(e, m), P = [];
    let M = ((n = i.flip) == null ? void 0 : n.overflows) || [];
    if (h && P.push(E[v]), c) {
      const { main: x, cross: $ } = xp(s, r, b);
      P.push(E[x], E[$]);
    }
    if (M = [...M, { placement: s, overflows: P }], !P.every((x) => x <= 0)) {
      var T;
      const x = (((T = i.flip) == null ? void 0 : T.index) || 0) + 1, $ = C[x];
      if ($)
        return { data: { index: x, overflows: M }, reset: { placement: $ } };
      let L = "bottom";
      switch (d) {
        case "bestFit": {
          var A;
          const W = (A = M.map((H) => [H, H.overflows.filter((B) => B > 0).reduce((B, G) => B + G, 0)]).sort((H, B) => H[1] - B[1])[0]) == null ? void 0 : A[0].placement;
          W && (L = W);
          break;
        }
        case "initialPlacement":
          L = o;
      }
      if (s !== L)
        return { reset: { placement: L } };
    }
    return {};
  } };
}, Sp = function(t) {
  return t === void 0 && (t = 0), { name: "offset", options: t, async fn(e) {
    const { x: n, y: s } = e, i = await async function(r, o) {
      const { placement: a, platform: l, elements: h } = r, c = await (l.isRTL == null ? void 0 : l.isRTL(h.floating)), u = un(a), d = Ts(a), f = ar(a) === "x", p = ["left", "top"].includes(u) ? -1 : 1, m = c && f ? -1 : 1, v = typeof o == "function" ? o(r) : o;
      let { mainAxis: w, crossAxis: b, alignmentAxis: k } = typeof v == "number" ? { mainAxis: v, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...v };
      return d && typeof k == "number" && (b = d === "end" ? -1 * k : k), f ? { x: b * m, y: w * p } : { x: w * p, y: b * m };
    }(e, t);
    return { x: n + i.x, y: s + i.y, data: i };
  } };
};
function gt(t) {
  var e;
  return ((e = t.ownerDocument) == null ? void 0 : e.defaultView) || window;
}
function At(t) {
  return gt(t).getComputedStyle(t);
}
function _e(t) {
  return Rh(t) ? (t.nodeName || "").toLowerCase() : "";
}
let Fs;
function Th() {
  if (Fs)
    return Fs;
  const t = navigator.userAgentData;
  return t && Array.isArray(t.brands) ? (Fs = t.brands.map((e) => e.brand + "/" + e.version).join(" "), Fs) : navigator.userAgent;
}
function Qt(t) {
  return t instanceof gt(t).HTMLElement;
}
function xt(t) {
  return t instanceof gt(t).Element;
}
function Rh(t) {
  return t instanceof gt(t).Node;
}
function Tl(t) {
  return typeof ShadowRoot > "u" ? !1 : t instanceof gt(t).ShadowRoot || t instanceof ShadowRoot;
}
function lr(t) {
  const { overflow: e, overflowX: n, overflowY: s, display: i } = At(t);
  return /auto|scroll|overlay|hidden|clip/.test(e + s + n) && !["inline", "contents"].includes(i);
}
function Cp(t) {
  return ["table", "td", "th"].includes(_e(t));
}
function oo(t) {
  const e = /firefox/i.test(Th()), n = At(t), s = n.backdropFilter || n.WebkitBackdropFilter;
  return n.transform !== "none" || n.perspective !== "none" || !!s && s !== "none" || e && n.willChange === "filter" || e && !!n.filter && n.filter !== "none" || ["transform", "perspective"].some((i) => n.willChange.includes(i)) || ["paint", "layout", "strict", "content"].some((i) => {
    const r = n.contain;
    return r != null && r.includes(i);
  });
}
function Ah() {
  return !/^((?!chrome|android).)*safari/i.test(Th());
}
function ba(t) {
  return ["html", "body", "#document"].includes(_e(t));
}
const Rl = Math.min, ss = Math.max, Ei = Math.round;
function Nh(t) {
  const e = At(t);
  let n = parseFloat(e.width), s = parseFloat(e.height);
  const i = t.offsetWidth, r = t.offsetHeight, o = Ei(n) !== i || Ei(s) !== r;
  return o && (n = i, s = r), { width: n, height: s, fallback: o };
}
function Lh(t) {
  return xt(t) ? t : t.contextElement;
}
const Dh = { x: 1, y: 1 };
function dn(t) {
  const e = Lh(t);
  if (!Qt(e))
    return Dh;
  const n = e.getBoundingClientRect(), { width: s, height: i, fallback: r } = Nh(e);
  let o = (r ? Ei(n.width) : n.width) / s, a = (r ? Ei(n.height) : n.height) / i;
  return o && Number.isFinite(o) || (o = 1), a && Number.isFinite(a) || (a = 1), { x: o, y: a };
}
function ze(t, e, n, s) {
  var i, r;
  e === void 0 && (e = !1), n === void 0 && (n = !1);
  const o = t.getBoundingClientRect(), a = Lh(t);
  let l = Dh;
  e && (s ? xt(s) && (l = dn(s)) : l = dn(t));
  const h = a ? gt(a) : window, c = !Ah() && n;
  let u = (o.left + (c && ((i = h.visualViewport) == null ? void 0 : i.offsetLeft) || 0)) / l.x, d = (o.top + (c && ((r = h.visualViewport) == null ? void 0 : r.offsetTop) || 0)) / l.y, f = o.width / l.x, p = o.height / l.y;
  if (a) {
    const m = gt(a), v = s && xt(s) ? gt(s) : s;
    let w = m.frameElement;
    for (; w && s && v !== m; ) {
      const b = dn(w), k = w.getBoundingClientRect(), C = getComputedStyle(w);
      k.x += (w.clientLeft + parseFloat(C.paddingLeft)) * b.x, k.y += (w.clientTop + parseFloat(C.paddingTop)) * b.y, u *= b.x, d *= b.y, f *= b.x, p *= b.y, u += k.x, d += k.y, w = gt(w).frameElement;
    }
  }
  return { width: f, height: p, top: d, right: u + f, bottom: d + p, left: u, x: u, y: d };
}
function ve(t) {
  return ((Rh(t) ? t.ownerDocument : t.document) || window.document).documentElement;
}
function cr(t) {
  return xt(t) ? { scrollLeft: t.scrollLeft, scrollTop: t.scrollTop } : { scrollLeft: t.pageXOffset, scrollTop: t.pageYOffset };
}
function Ph(t) {
  return ze(ve(t)).left + cr(t).scrollLeft;
}
function Ep(t, e, n) {
  const s = Qt(e), i = ve(e), r = ze(t, !0, n === "fixed", e);
  let o = { scrollLeft: 0, scrollTop: 0 };
  const a = { x: 0, y: 0 };
  if (s || !s && n !== "fixed")
    if ((_e(e) !== "body" || lr(i)) && (o = cr(e)), Qt(e)) {
      const l = ze(e, !0);
      a.x = l.x + e.clientLeft, a.y = l.y + e.clientTop;
    } else
      i && (a.x = Ph(i));
  return { x: r.left + o.scrollLeft - a.x, y: r.top + o.scrollTop - a.y, width: r.width, height: r.height };
}
function ms(t) {
  if (_e(t) === "html")
    return t;
  const e = t.assignedSlot || t.parentNode || (Tl(t) ? t.host : null) || ve(t);
  return Tl(e) ? e.host : e;
}
function Al(t) {
  return Qt(t) && At(t).position !== "fixed" ? t.offsetParent : null;
}
function Nl(t) {
  const e = gt(t);
  let n = Al(t);
  for (; n && Cp(n) && At(n).position === "static"; )
    n = Al(n);
  return n && (_e(n) === "html" || _e(n) === "body" && At(n).position === "static" && !oo(n)) ? e : n || function(s) {
    let i = ms(s);
    for (; Qt(i) && !ba(i); ) {
      if (oo(i))
        return i;
      i = ms(i);
    }
    return null;
  }(t) || e;
}
function Wh(t) {
  const e = ms(t);
  return ba(e) ? t.ownerDocument.body : Qt(e) && lr(e) ? e : Wh(e);
}
function is(t, e) {
  var n;
  e === void 0 && (e = []);
  const s = Wh(t), i = s === ((n = t.ownerDocument) == null ? void 0 : n.body), r = gt(s);
  return i ? e.concat(r, r.visualViewport || [], lr(s) ? s : []) : e.concat(s, is(s));
}
function Ll(t, e, n) {
  return e === "viewport" ? Si(function(s, i) {
    const r = gt(s), o = ve(s), a = r.visualViewport;
    let l = o.clientWidth, h = o.clientHeight, c = 0, u = 0;
    if (a) {
      l = a.width, h = a.height;
      const d = Ah();
      (d || !d && i === "fixed") && (c = a.offsetLeft, u = a.offsetTop);
    }
    return { width: l, height: h, x: c, y: u };
  }(t, n)) : xt(e) ? function(s, i) {
    const r = ze(s, !0, i === "fixed"), o = r.top + s.clientTop, a = r.left + s.clientLeft, l = Qt(s) ? dn(s) : { x: 1, y: 1 }, h = s.clientWidth * l.x, c = s.clientHeight * l.y, u = a * l.x, d = o * l.y;
    return { top: d, left: u, right: u + h, bottom: d + c, x: u, y: d, width: h, height: c };
  }(e, n) : Si(function(s) {
    var i;
    const r = ve(s), o = cr(s), a = (i = s.ownerDocument) == null ? void 0 : i.body, l = ss(r.scrollWidth, r.clientWidth, a ? a.scrollWidth : 0, a ? a.clientWidth : 0), h = ss(r.scrollHeight, r.clientHeight, a ? a.scrollHeight : 0, a ? a.clientHeight : 0);
    let c = -o.scrollLeft + Ph(s);
    const u = -o.scrollTop;
    return At(a || r).direction === "rtl" && (c += ss(r.clientWidth, a ? a.clientWidth : 0) - l), { width: l, height: h, x: c, y: u };
  }(ve(t)));
}
const Mp = { getClippingRect: function(t) {
  let { element: e, boundary: n, rootBoundary: s, strategy: i } = t;
  const r = n === "clippingAncestors" ? function(h, c) {
    const u = c.get(h);
    if (u)
      return u;
    let d = is(h).filter((v) => xt(v) && _e(v) !== "body"), f = null;
    const p = At(h).position === "fixed";
    let m = p ? ms(h) : h;
    for (; xt(m) && !ba(m); ) {
      const v = At(m), w = oo(m);
      (p ? w || f : w || v.position !== "static" || !f || !["absolute", "fixed"].includes(f.position)) ? f = v : d = d.filter((b) => b !== m), m = ms(m);
    }
    return c.set(h, d), d;
  }(e, this._c) : [].concat(n), o = [...r, s], a = o[0], l = o.reduce((h, c) => {
    const u = Ll(e, c, i);
    return h.top = ss(u.top, h.top), h.right = Rl(u.right, h.right), h.bottom = Rl(u.bottom, h.bottom), h.left = ss(u.left, h.left), h;
  }, Ll(e, a, i));
  return { width: l.right - l.left, height: l.bottom - l.top, x: l.left, y: l.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(t) {
  let { rect: e, offsetParent: n, strategy: s } = t;
  const i = Qt(n), r = ve(n);
  if (n === r)
    return e;
  let o = { scrollLeft: 0, scrollTop: 0 }, a = { x: 1, y: 1 };
  const l = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((_e(n) !== "body" || lr(r)) && (o = cr(n)), Qt(n))) {
    const h = ze(n);
    a = dn(n), l.x = h.x + n.clientLeft, l.y = h.y + n.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - o.scrollLeft * a.x + l.x, y: e.y * a.y - o.scrollTop * a.y + l.y };
}, isElement: xt, getDimensions: function(t) {
  return Nh(t);
}, getOffsetParent: Nl, getDocumentElement: ve, getScale: dn, async getElementRects(t) {
  let { reference: e, floating: n, strategy: s } = t;
  const i = this.getOffsetParent || Nl, r = this.getDimensions;
  return { reference: Ep(e, await i(n), s), floating: { x: 0, y: 0, ...await r(n) } };
}, getClientRects: (t) => Array.from(t.getClientRects()), isRTL: (t) => At(t).direction === "rtl" };
function Tp(t, e, n, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: r = !0, elementResize: o = !0, animationFrame: a = !1 } = s, l = i && !a, h = l || r ? [...xt(t) ? is(t) : t.contextElement ? is(t.contextElement) : [], ...is(e)] : [];
  h.forEach((f) => {
    l && f.addEventListener("scroll", n, { passive: !0 }), r && f.addEventListener("resize", n);
  });
  let c, u = null;
  if (o) {
    let f = !0;
    u = new ResizeObserver(() => {
      f || n(), f = !1;
    }), xt(t) && !a && u.observe(t), xt(t) || !t.contextElement || a || u.observe(t.contextElement), u.observe(e);
  }
  let d = a ? ze(t) : null;
  return a && function f() {
    const p = ze(t);
    !d || p.x === d.x && p.y === d.y && p.width === d.width && p.height === d.height || n(), d = p, c = requestAnimationFrame(f);
  }(), n(), () => {
    var f;
    h.forEach((p) => {
      l && p.removeEventListener("scroll", n), r && p.removeEventListener("resize", n);
    }), (f = u) == null || f.disconnect(), u = null, a && cancelAnimationFrame(c);
  };
}
const Rp = (t, e, n) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: Mp, ...n }, r = { ...i.platform, _c: s };
  return pp(t, e, { ...i, platform: r });
};
var _a = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, V = (t, e, n) => (_a(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Z = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Fe = (t, e, n, s) => (_a(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Rt = (t, e, n) => (_a(t, e, "access private method"), n), rs, os, Vn, rn, ct, ao, Mi, hr, xa, $a, Oh, lo, Ih, ka, Hh, Sa, Bh, Ca, zh, co, Fh, Ea, jh, as, ho, Uh;
const on = class extends lt {
  constructor() {
    super(...arguments), Z(this, hr), Z(this, $a), Z(this, lo), Z(this, ka), Z(this, Sa), Z(this, Ca), Z(this, co), Z(this, Ea), Z(this, ho), Z(this, rs, !1), Z(this, os, void 0), Z(this, Vn, 0), Z(this, rn, void 0), Z(this, ct, void 0), Z(this, ao, void 0), Z(this, Mi, void 0), this.hideLater = () => {
      V(this, as).call(this), Fe(this, Vn, window.setTimeout(this.hide.bind(this), 100));
    }, Z(this, as, () => {
      clearTimeout(V(this, Vn)), Fe(this, Vn, 0);
    });
  }
  get isShown() {
    var t;
    return (t = V(this, rn)) == null ? void 0 : t.classList.contains(on.CLASS_SHOW);
  }
  get tooltip() {
    return V(this, rn) || Rt(this, lo, Ih).call(this);
  }
  get trigger() {
    return V(this, ao) || this.element;
  }
  get isHover() {
    return this.options.trigger === "hover";
  }
  get elementShowClass() {
    return `with-${on.NAME}-show`;
  }
  get isDynamic() {
    return this.options.title;
  }
  init() {
    const { element: t } = this;
    t !== document.body && !t.hasAttribute("data-toggle") && t.setAttribute("data-toggle", "tooltip");
  }
  show(t) {
    return this.setOptions(t), !V(this, rs) && this.isHover && Rt(this, ho, Uh).call(this), this.options.animation && this.tooltip.classList.add("fade"), this.element.classList.add(this.elementShowClass), this.tooltip.classList.add(on.CLASS_SHOW), Rt(this, co, Fh).call(this), !0;
  }
  hide() {
    var e;
    var t;
    return (t = V(this, Mi)) == null || t.call(this), this.element.classList.remove(this.elementShowClass), (e = V(this, rn)) == null || e.classList.remove(on.CLASS_SHOW), !0;
  }
  toggle(t) {
    return this.isShown ? this.hide() : this.show(t);
  }
  destroy() {
    V(this, rs) && (this.element.removeEventListener("mouseleave", this.hideLater), this.tooltip.removeEventListener("mouseenter", V(this, as)), this.tooltip.removeEventListener("mouseleave", this.hideLater)), super.destroy();
  }
  static clear(t) {
    t instanceof Event && (t = { event: t });
    const { exclude: e } = t || {}, n = this.getAll().entries(), s = new Set(e || []);
    for (const [i, r] of n)
      s.has(i) || r.hide();
  }
};
let Nt = on;
rs = /* @__PURE__ */ new WeakMap();
os = /* @__PURE__ */ new WeakMap();
Vn = /* @__PURE__ */ new WeakMap();
rn = /* @__PURE__ */ new WeakMap();
ct = /* @__PURE__ */ new WeakMap();
ao = /* @__PURE__ */ new WeakMap();
Mi = /* @__PURE__ */ new WeakMap();
hr = /* @__PURE__ */ new WeakSet();
xa = function() {
  const { arrow: t } = this.options;
  return typeof t == "number" ? t : 8;
};
$a = /* @__PURE__ */ new WeakSet();
Oh = function() {
  const t = Rt(this, hr, xa).call(this);
  return Fe(this, ct, document.createElement("div")), V(this, ct).style.position = this.options.strategy, V(this, ct).style.width = `${t}px`, V(this, ct).style.height = `${t}px`, V(this, ct).style.transform = "rotate(45deg)", V(this, ct);
};
lo = /* @__PURE__ */ new WeakSet();
Ih = function() {
  var n;
  const t = on.TOOLTIP_CLASS;
  let e;
  if (this.isDynamic) {
    e = document.createElement("div");
    const s = this.options.className ? this.options.className.split(" ") : [];
    let i = [t, this.options.type || ""];
    i = i.concat(s), e.classList.add(...i), e[this.options.html ? "innerHTML" : "innerText"] = this.options.title || "";
  } else if (this.element) {
    const s = this.element.getAttribute("href") ?? this.element.dataset.target;
    if (s != null && s.startsWith("#") && (e = document.querySelector(s)), !e) {
      const i = this.element.nextElementSibling;
      i != null && i.classList.contains(t) ? e = i : e = (n = this.element.parentNode) == null ? void 0 : n.querySelector(`.${t}`);
    }
  }
  if (this.options.arrow && (e == null || e.append(Rt(this, $a, Oh).call(this))), !e)
    throw new Error("Tooltip: Cannot find tooltip element");
  return e.style.width = "max-content", e.style.position = "absolute", e.style.top = "0", e.style.left = "0", document.body.appendChild(e), Fe(this, rn, e), e;
};
ka = /* @__PURE__ */ new WeakSet();
Hh = function() {
  var i;
  const t = Rt(this, hr, xa).call(this), { strategy: e, placement: n } = this.options, s = {
    middleware: [Sp(t), kp()],
    strategy: e,
    placement: n
  };
  return this.options.arrow && V(this, ct) && ((i = s.middleware) == null || i.push(vp({ element: V(this, ct) }))), s;
};
Sa = /* @__PURE__ */ new WeakSet();
Bh = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
};
Ca = /* @__PURE__ */ new WeakSet();
zh = function(t) {
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
};
co = /* @__PURE__ */ new WeakSet();
Fh = function() {
  const t = Rt(this, ka, Hh).call(this), e = Rt(this, Ea, jh).call(this);
  Fe(this, Mi, Tp(e, this.tooltip, () => {
    Rp(e, this.tooltip, t).then(({ x: n, y: s, middlewareData: i, placement: r }) => {
      Object.assign(this.tooltip.style, {
        left: `${n}px`,
        top: `${s}px`
      });
      const o = r.split("-")[0], a = Rt(this, Sa, Bh).call(this, o);
      if (i.arrow && V(this, ct)) {
        const { x: l, y: h } = i.arrow;
        Object.assign(V(this, ct).style, {
          left: l != null ? `${l}px` : "",
          top: h != null ? `${h}px` : "",
          [a]: `${-V(this, ct).offsetWidth / 2}px`,
          background: "inherit",
          border: "inherit",
          ...Rt(this, Ca, zh).call(this, o)
        });
      }
    });
  }));
};
Ea = /* @__PURE__ */ new WeakSet();
jh = function() {
  return V(this, os) || Fe(this, os, {
    getBoundingClientRect: () => {
      const { element: t } = this;
      if (t instanceof MouseEvent) {
        const { clientX: e, clientY: n } = t;
        return {
          width: 0,
          height: 0,
          top: n,
          right: e,
          bottom: n,
          left: e
        };
      }
      return t instanceof HTMLElement ? t.getBoundingClientRect() : t;
    },
    contextElement: this.element
  }), V(this, os);
};
as = /* @__PURE__ */ new WeakMap();
ho = /* @__PURE__ */ new WeakSet();
Uh = function() {
  const { tooltip: t } = this;
  t.addEventListener("mouseenter", V(this, as)), t.addEventListener("mouseleave", this.hideLater), this.element.addEventListener("mouseleave", this.hideLater), Fe(this, rs, !0);
};
Nt.NAME = "tooltip";
Nt.TOOLTIP_CLASS = "tooltip";
Nt.CLASS_SHOW = "show";
Nt.MENU_SELECTOR = '[data-toggle="tooltip"]:not(.disabled):not(:disabled)';
Nt.DEFAULT = {
  animation: !0,
  placement: "top",
  strategy: "absolute",
  trigger: "hover",
  type: "darker",
  arrow: !0
};
document.addEventListener("click", function(t) {
  var s;
  const e = t.target, n = (s = e.closest) == null ? void 0 : s.call(e, Nt.MENU_SELECTOR);
  if (n) {
    const i = Nt.ensure(n);
    i.options.trigger === "click" && i.toggle();
  } else
    Nt.clear({ event: t });
});
document.addEventListener("mouseover", function(t) {
  var i;
  const e = t.target, n = (i = e.closest) == null ? void 0 : i.call(e, Nt.MENU_SELECTOR);
  if (!n)
    return;
  const s = Nt.ensure(n);
  s.isHover && s.show();
});
function Ap({
  type: t,
  component: e,
  className: n,
  children: s,
  style: i,
  attrs: r,
  url: o,
  disabled: a,
  active: l,
  icon: h,
  text: c,
  target: u,
  trailingIcon: d,
  hint: f,
  checked: p,
  actions: m,
  show: v,
  level: w = 0,
  items: b,
  ...k
}) {
  const C = Array.isArray(m) ? { items: m } : m;
  return C && (C.btnProps || (C.btnProps = { size: "sm" }), C.className = N("tree-actions not-nested-toggle", C.className)), /* @__PURE__ */ y(
    "div",
    {
      className: N("tree-item-content", n, { disabled: a, active: l }),
      title: f,
      "data-target": u,
      style: Object.assign({ paddingLeft: `${w * 20}px` }, i),
      "data-level": w,
      ...r,
      ...k,
      children: [
        /* @__PURE__ */ y("span", { class: `tree-toggle-icon${b ? " state" : ""}`, children: b ? /* @__PURE__ */ y("span", { class: `caret-${v ? "down" : "right"}` }) : null }),
        typeof p == "boolean" ? /* @__PURE__ */ y("div", { class: `tree-checkbox checkbox-primary${p ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) }) : null,
        /* @__PURE__ */ y(ds, { icon: h, className: "tree-icon" }),
        o ? /* @__PURE__ */ y("a", { className: "text tree-link not-nested-toggle", href: o, children: c }) : /* @__PURE__ */ y("span", { class: "text", children: c }),
        typeof s == "function" ? s() : s,
        C ? /* @__PURE__ */ y(dt, { ...C }) : null,
        /* @__PURE__ */ y(ds, { icon: d, className: "tree-trailing-icon" })
      ]
    }
  );
}
let Ma = class extends Ji {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "tree";
  }
  getNestedMenuProps(e) {
    const n = super.getNestedMenuProps(e), { collapsedIcon: s, expandedIcon: i, normalIcon: r, itemActions: o } = this.props;
    return {
      collapsedIcon: s,
      expandedIcon: i,
      normalIcon: r,
      itemActions: o,
      ...n
    };
  }
  getItemRenderProps(e, n, s) {
    const i = super.getItemRenderProps(e, n, s), { collapsedIcon: r, expandedIcon: o, normalIcon: a, itemActions: l } = e;
    return i.icon === void 0 && (i.icon = i.items ? i.show ? o : r : a), i.actions === void 0 && l && (i.actions = typeof l == "function" ? l(n) : l), i;
  }
  renderToggleIcon() {
    return null;
  }
  beforeRender() {
    const e = super.beforeRender(), { hover: n } = this.props;
    return n && (e.className = N(e.className, "tree-hover")), e;
  }
};
Ma.ItemComponents = {
  item: Ap
};
Ma.NAME = "tree";
class qh extends J {
}
qh.NAME = "Tree";
qh.Component = Ma;
var Ta = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, j = (t, e, n) => (Ta(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ct = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Mt = (t, e, n, s) => (Ta(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Np = (t, e, n, s) => ({
  set _(i) {
    Mt(t, e, i, n);
  },
  get _() {
    return j(t, e, s);
  }
}), Ae = (t, e, n) => (Ta(t, e, "access private method"), n), me, fn, ys, an, Ti, Y, uo, Vh, ln, ls, Ra, Gh, Aa, Yh;
class Kh extends lt {
  constructor() {
    super(...arguments), Ct(this, uo), Ct(this, ln), Ct(this, Ra), Ct(this, Aa), Ct(this, me, void 0), Ct(this, fn, void 0), Ct(this, ys, null), Ct(this, an, []), Ct(this, Ti, 0), Ct(this, Y, []);
  }
  afterInit() {
    const e = g(this.element), n = e.find(".form-batch-table").addClass("borderless");
    let s = n.find("tbody");
    s.length || (s = g("<tbody></tbody>").appendTo(n)), Mt(this, me, s), Mt(this, fn, e.find(".form-batch-template").get(0)), Mt(this, an, []), n.find("thead>tr>.form-batch-head").each((i, r) => {
      const a = g(r).data();
      a && j(this, an).push(a);
    }), e.on("click", (i) => {
      const r = g(i.target).closest(".form-batch-btn");
      if (!r.length)
        return;
      const o = r.data("type"), l = r.closest("tr").data("index");
      o === "add" ? this.addRow(l) : o === "delete" ? this.deleteRow(l) : o === "ditto" && this.toggleDitto(r);
    }).on("change", ".form-batch-input", (i) => {
      this.syncDitto(g(i.target));
    }), this.render();
  }
  destroy() {
    g(this.element).off("click change"), Mt(this, me, void 0), Mt(this, fn, void 0), j(this, an).length = 0, j(this, Y).length = 0;
  }
  render(e) {
    super.render(e), j(this, Y).length ? Ae(this, ln, ls).call(this) : (Mt(this, ys, null), Ae(this, uo, Vh).call(this));
  }
  addRow(e) {
    const n = Np(this, Ti)._++;
    typeof e == "number" && e >= 0 && e <= j(this, Y).length ? j(this, Y).splice(e + 1, 0, n) : (e = j(this, Y).length, j(this, Y).push(n)), Ae(this, ln, ls).call(this, void 0, e);
  }
  deleteRow(e) {
    var s;
    if (j(this, Y).length <= 1 || typeof e != "number" || e < 0 || e >= j(this, Y).length)
      return !1;
    const n = j(this, Y)[e];
    j(this, Y).splice(e, 1), (s = j(this, me)) == null || s.children(`[data-gid="${n}"]`).remove(), Ae(this, ln, ls).call(this, void 0, e);
  }
  deleteRowByGid(e) {
    return this.deleteRow(j(this, Y).indexOf(e));
  }
  toggleDitto(e, n) {
    const s = e.closest("td");
    n = n ?? s.attr("data-ditto") !== "on", s.attr("data-ditto", n ? "on" : "off"), n && s.closest("tr").prev("tr").find(`td[data-name="${s.data("name")}"]`).find(".form-batch-input").each((o, a) => {
      const l = g(a), h = l.data("name"), c = l.val();
      this.syncDitto(s.find(`.form-batch-input[data-name="${h}"]`).val(c), !1);
    });
  }
  syncDitto(e, n = !0) {
    const s = e.closest("td");
    n && s.attr("data-ditto", "off");
    const i = s.data("name"), r = e.data("name"), o = `td[data-name="${i}"][data-ditto="on"]`, a = e.val();
    let l = e.closest("tr").next("tr"), h = l.find(o);
    for (; h.length; )
      h.find(`.form-batch-input[data-name="${r}"]`).val(a), l = l.next("tr"), h = l.find(o);
  }
}
me = /* @__PURE__ */ new WeakMap();
fn = /* @__PURE__ */ new WeakMap();
ys = /* @__PURE__ */ new WeakMap();
an = /* @__PURE__ */ new WeakMap();
Ti = /* @__PURE__ */ new WeakMap();
Y = /* @__PURE__ */ new WeakMap();
uo = /* @__PURE__ */ new WeakSet();
Vh = function() {
  const t = j(this, fn), e = j(this, me);
  if (!t || !(e != null && e.length))
    return;
  const { data: n = [], minRows: s, maxRows: i, mode: r } = this.options, a = r === "add" ? Math.min(Math.max(1, i ?? 100), Math.max(1, 10, s ?? 10, n.length)) : n.length;
  Mt(this, Y, Array(a).fill(0).map((l, h) => h)), Mt(this, Ti, j(this, Y).length), Ae(this, ln, ls).call(this, n);
};
ln = /* @__PURE__ */ new WeakSet();
ls = function(t = [], e = 0) {
  var s;
  const n = j(this, Y).length;
  for (let i = e; i < n; i++)
    Ae(this, Aa, Yh).call(this, i, t[i]);
  (s = j(this, me)) == null || s.attr("data-count", `${n}`);
};
Ra = /* @__PURE__ */ new WeakSet();
Gh = function(t) {
  let e = j(this, ys);
  if (!e) {
    const { addRowIcon: n = "icon-plus", deleteRowIcon: s = "icon-trash" } = this.options;
    e = new DocumentFragment();
    const i = '<button type="button" data-type="{type}" class="form-batch-btn btn square ghost size-sm" title="{text}"><i class="icon {icon}"></i></button>';
    n !== !1 && e.append(g(X(i, { type: "add", icon: n, text: this.i18n("add") }))[0]), s !== !1 && e.append(g(X(i, { type: "delete", icon: s, text: this.i18n("delete") }))[0]), Mt(this, ys, e);
  }
  t.empty().append(e.cloneNode(!0));
};
Aa = /* @__PURE__ */ new WeakSet();
Yh = function(t, e) {
  var h;
  const n = j(this, me), s = String(j(this, Y)[t]);
  let i = n.children(`[data-gid="${s}"]`);
  if (i.length) {
    if (!e && i.data("index") === t)
      return;
  } else {
    const c = j(this, fn), d = document.importNode(c.content, !0).querySelector("tr");
    i = g(d).attr("data-gid", s);
  }
  if (i.attr("data-index", `${t}`), t) {
    const c = j(this, Y)[t - 1], u = n.children(`[data-gid="${c}"]`);
    u.length ? u.after(i) : i.appendTo(n);
  } else
    i.prependTo(n);
  const { idKey: r = "id", mode: o } = this.options, a = o === "add", l = String(a || !e ? t : e[r]);
  j(this, an).forEach((c) => {
    var d, f;
    let u = i.find(`td[data-name="${c.name}"]`);
    if (u.length || (u = g(`<td data-name="${c.name}"></td>`).appendTo(i)), c.index) {
      u.find(".form-control-static").text(l).attr("id", `${c.name}_${s}`), (d = this.options.onRenderRowCol) == null || d.call(this, u, c, e);
      return;
    }
    if (!u.data("init") || e) {
      if (c.name === "ACTIONS") {
        if (u.addClass("form-batch-row-actions"), !a)
          return;
        Ae(this, Ra, Gh).call(this, u);
        return;
      }
      u.data("init", 1).find("[name],.form-control-static").each((p, m) => {
        const v = g(m);
        if (v.hasClass("form-control-static")) {
          const w = v.attr("data-name");
          v.attr("id", `${c.name}_${s}`), e && v.text(String(e[w] ?? ""));
        } else {
          const w = v.attr("name"), b = v.attr("id");
          v.attr({
            id: `${b}_${s}`,
            name: `${w}[${l}]`,
            "data-name": w
          }).addClass("form-batch-input"), u.find(`label[for="${b}"]`).each((k, C) => {
            g(C).attr("for", `${b}_${s}`);
          }), e && v.val(String(e[w] ?? ""));
        }
      });
    }
    if (c.ditto)
      if (u.addClass("form-batch-ditto"), t) {
        const p = g(`<div class="input-control-suffix form-batch-ditto-btn"><button type="button" class="btn ghost form-batch-btn" data-type="ditto">${this.i18n("ditto")}</button></div>`), m = g('<div class="input-control input-control-ditto has-suffix"></div>').append(u.children()).append(p).appendTo(u);
        requestAnimationFrame(() => m.css("--input-control-suffix", `${p.find(".btn").outerWidth()}px`)), u.attr("data-ditto", c.defaultDitto ?? "on");
      } else {
        u.attr("data-ditto", "");
        const p = u.find(".input-control-ditto");
        p.length && (p.children().not(".form-batch-ditto-btn").appendTo(u), p.remove());
      }
    (f = this.options.onRenderRowCol) == null || f.call(this, u, c, e);
  }), (h = this.options.onRenderRow) == null || h.call(this, i, t, e);
};
Kh.NAME = "BatchForm";
Kh.DEFAULT = {
  mode: "add",
  idKey: "id",
  i18n: {
    zh_cn: {
      ditto: "同上",
      delete: "删除",
      add: "添加"
    },
    zh_tw: {
      ditto: "同上",
      delete: "删除",
      add: "添加"
    },
    en: {
      ditto: "Ditto",
      delete: "Delete",
      add: "Add"
    }
  }
};
var Xh = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, It = (t, e, n) => (Xh(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ln = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Dn = (t, e, n, s) => (Xh(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Gn, Qe, Yn, oi, ai;
function Lp(t) {
  return typeof t == "string" ? t.split(",").map((e) => {
    const n = parseFloat(e);
    return Number.isNaN(n) ? null : n;
  }) : t;
}
let Na = class extends U {
  constructor() {
    super(...arguments), Ln(this, Gn, $t()), Ln(this, Qe, 0), Ln(this, Yn, void 0), Ln(this, oi, void 0), Ln(this, ai, !1);
  }
  componentDidMount() {
    var n;
    this.tryDraw = this.tryDraw.bind(this), this.tryDraw();
    const e = (n = It(this, Gn).current) == null ? void 0 : n.parentElement;
    if (this.props.responsive !== !1) {
      if (e && typeof ResizeObserver < "u") {
        const s = new ResizeObserver(this.tryDraw);
        s.observe(e), Dn(this, Yn, s);
      }
      It(this, Yn) || window.addEventListener("resize", this.tryDraw);
    }
    if (e && typeof IntersectionObserver < "u") {
      const s = new IntersectionObserver((i) => {
        It(this, ai) && i.some((r) => r.isIntersecting) && this.tryDraw();
      });
      s.observe(e), Dn(this, oi, s);
    }
  }
  componentWillUnmount() {
    var e;
    (e = It(this, Yn)) == null || e.disconnect(), window.removeEventListener("resize", this.tryDraw);
  }
  tryDraw() {
    It(this, Qe) && cancelAnimationFrame(It(this, Qe)), Dn(this, Qe, requestAnimationFrame(() => {
      this.draw(), Dn(this, Qe, 0);
    }));
  }
  draw() {
    const e = It(this, Gn).current;
    if (!e)
      return;
    const n = e.parentElement, { width: s, height: i, responsive: r = !0 } = this.props;
    let o = s || n.clientWidth, a = i || n.clientHeight;
    if (s && i && r && (o = n.clientWidth, a = Math.floor(i * o / s)), e.style.width = `${o}px`, e.style.height = `${a}px`, o = o * (window.devicePixelRatio || 1), a = a * (window.devicePixelRatio || 1), e.width = o, e.height = a, !g(n).isVisible() && It(this, oi)) {
      Dn(this, ai, !0);
      return;
    }
    const {
      lineSize: l = 1,
      scaleLine: h = !1,
      scaleLineSize: c,
      scaleLineGap: u = 1,
      scaleLineDash: d,
      referenceLine: f,
      referenceLineSize: p,
      referenceLineDash: m,
      color: v = "#2c78f1",
      fillColor: w = ["rgba(46, 127, 255, 0.3)", "rgba(46, 127, 255, 0.05)"],
      lineDash: b = [],
      bezier: k
    } = this.props, C = Lp(this.props.data), E = Math.floor(o / (C.length - 1)), P = Math.max(...C.filter((x) => x !== null)), M = C.map((x, $) => {
      const L = typeof x != "number";
      return {
        x: $ * E,
        y: L ? a : Math.round((1 - x / P) * (a - l)),
        empty: L
      };
    });
    let T = M[0];
    const A = e.getContext("2d");
    if (h) {
      const x = typeof h == "string" ? h : "rgba(100,100,100,.1)";
      A.strokeStyle = x, A.lineWidth = c || l, d && A.setLineDash(d);
      for (let $ = 0; $ < M.length; ++$) {
        if ($ % u !== 0)
          continue;
        const L = M[$];
        A.moveTo(L.x, 0), A.lineTo(L.x, a);
      }
      A.stroke();
    }
    if (f && M.length > 1) {
      const x = typeof f == "string" ? f : "rgba(100,100,100,.2)", $ = M[M.length - 1];
      A.moveTo($.x, $.y), A.strokeStyle = x, A.lineWidth = p || l, A.lineTo(T.x, T.y), m && A.setLineDash(m), A.stroke();
    }
    for (A.setLineDash(b); M.length && M[M.length - 1].empty; )
      M.pop();
    if (w) {
      const x = M[M.length - 1];
      if (A.beginPath(), A.moveTo(0, a), A.lineTo(T.x, T.y), k) {
        const $ = Math.round(E / 2);
        for (let L = 1; L < M.length; ++L) {
          const W = M[L], H = Math.round((W.y - T.y) / 5);
          A.bezierCurveTo(T.x + $, T.y + H, W.x - $, W.y - H, W.x, W.y), T = W;
        }
      } else
        for (let $ = 1; $ < M.length; ++$)
          T = M[$], A.lineTo(T.x, T.y);
      if (A.lineTo(x.x, a), Array.isArray(w)) {
        const $ = A.createLinearGradient(0, 0, 0, a);
        for (let L = 0; L < w.length; ++L)
          $.addColorStop(L / (w.length - 1), w[L]);
        A.fillStyle = $;
      } else
        A.fillStyle = w;
      A.fill();
    }
    if (T = M[0], A.beginPath(), A.moveTo(T.x, T.y), k) {
      const x = Math.round(E / 2);
      for (let $ = 1; $ < M.length; ++$) {
        const L = M[$], W = Math.round((L.y - T.y) / 5);
        A.bezierCurveTo(T.x + x, T.y + W, L.x - x, L.y - W, L.x, L.y), T = L;
      }
    } else
      for (let x = 1; x < M.length; ++x)
        T = M[x], A.lineTo(T.x, T.y);
    A.strokeStyle = v, A.lineWidth = l, A.stroke();
  }
  render() {
    const { style: e, className: n, canvasClass: s } = this.props;
    return /* @__PURE__ */ _("div", { class: "center burn-chart", className: n, style: e }, /* @__PURE__ */ _("canvas", { className: s, ref: It(this, Gn) }));
  }
};
Gn = /* @__PURE__ */ new WeakMap();
Qe = /* @__PURE__ */ new WeakMap();
Yn = /* @__PURE__ */ new WeakMap();
oi = /* @__PURE__ */ new WeakMap();
ai = /* @__PURE__ */ new WeakMap();
Na.defaultProps = {
  responsive: !0,
  lineSize: 1,
  scaleLine: !1,
  scaleLineSize: 1,
  bezier: !0
};
class Jh extends J {
}
Jh.NAME = "Burn";
Jh.Component = Na;
class Dp extends lt {
  init() {
    var r;
    const { echarts: e } = window;
    if (!e)
      return;
    const { responsive: n = !0, theme: s, ...i } = this.options;
    this.myChart = e.init(this.element, s), this.myChart.setOption(i), n && window.addEventListener("resize", (r = this.myChart) == null ? void 0 : r.resize);
  }
  destroy() {
    var s, i;
    const { echarts: e } = window;
    if (!e) {
      super.destroy();
      return;
    }
    const { responsive: n = !0 } = this.options;
    n && window.removeEventListener("resize", (s = this.myChart) == null ? void 0 : s.resize), (i = this.myChart) == null || i.dispose(), super.destroy();
  }
}
Dp.NAME = "Echarts";
const Pp = ({
  formConfig: t,
  className: e,
  fields: n,
  operators: s,
  savedQuery: i,
  andOr: r,
  formSession: o,
  searchBtnText: a,
  resetBtnText: l,
  saveSearch: h,
  savedQueryTitle: c,
  onApplyQuery: u,
  onDeleteQuery: d,
  groupName: f,
  handleSelect: p,
  toggleMore: m,
  toggleHistory: v,
  resetForm: w,
  submitForm: b,
  actionURL: k,
  module: C,
  groupItems: E
}) => {
  const M = [e, ...["search-form"]], T = [1, 2, 3], A = o ? o.groupAndOr : "", x = ($) => {
    const L = o ? o[`andOr${$}`] : "";
    return /* @__PURE__ */ _("div", { class: [1, 4].includes($) ? "search-group" : "search-group hidden", "data-id": $ }, /* @__PURE__ */ _("div", { class: "group-name" }, [1, 4].includes($) ? $ === 1 ? f[0] : f[1] : /* @__PURE__ */ _("select", { class: "form-control", id: `andOr${$}`, name: `andOr${$}` }, r.map((W) => /* @__PURE__ */ _("option", { value: W.value, selected: L === W.value, title: W.value }, W.title)))), /* @__PURE__ */ _("div", { class: "group-select" }, /* @__PURE__ */ _("select", { class: "form-control field-select", id: `field${$}`, name: `field${$}`, onChange: p.bind(void 0) }, " ", n == null ? void 0 : n.map((W) => /* @__PURE__ */ _("option", { value: W.name, selected: !1, title: W.name, control: W.control }, W.label)))), /* @__PURE__ */ _("div", { class: "group-select" }, /* @__PURE__ */ _("select", { class: "form-control search-method", id: `operator${$}`, name: `operator${$}` }, s.map((W) => /* @__PURE__ */ _("option", { key: W.value, value: W.value, title: W.value }, W.title)))), /* @__PURE__ */ _("div", { class: "group-value" }, /* @__PURE__ */ _("input", { type: "text", class: "form-control value-input", value: n[$ - 1].defaultValue, placeholder: n[$ - 1].placeholder }), /* @__PURE__ */ _("select", { class: "form-control value-select hidden" }), /* @__PURE__ */ _("input", { type: "datetime-local", class: "form-control value-date hidden" })));
  };
  return /* @__PURE__ */ _(
    "form",
    {
      id: "searchForm",
      className: N(M),
      ...t
    },
    /* @__PURE__ */ _("div", { class: "search-form-content" }, /* @__PURE__ */ _("div", { class: "search-form-items" }, /* @__PURE__ */ _("div", { class: "search-col" }, T.map(($) => x($))), /* @__PURE__ */ _("div", { class: "search-col" }, /* @__PURE__ */ _("select", { class: "form-control", id: "groupAndOr", name: "groupAndOr" }, r.map(($) => /* @__PURE__ */ _("option", { value: $.value, selected: A === $.value, title: $.value }, $.title)))), /* @__PURE__ */ _("div", { class: "search-col" }, T.map(($) => x($ + 3)))), /* @__PURE__ */ _("div", { class: "search-form-footer" }, /* @__PURE__ */ _("div", { class: "inline-block flex items-center justify-center" }, /* @__PURE__ */ _("button", { class: "btn primary btn-submit-form", type: "button", onClick: b }, a || "搜索"), /* @__PURE__ */ _("button", { class: "btn btn-reset-form", type: "button", onClick: w }, l || "重置")), /* @__PURE__ */ _("div", { class: "save-bar" }, (h == null ? void 0 : h.hasPriv) && /* @__PURE__ */ _("a", { class: "btn save-query", ...h.config }, /* @__PURE__ */ _("i", { class: "icon icon-save" }), h.text || "保存搜索条件"), /* @__PURE__ */ _("a", { class: "btn toggle-more", onClick: m }, /* @__PURE__ */ _("i", { class: "icon icon-chevron-double-down" }))))),
    /* @__PURE__ */ _("div", null, /* @__PURE__ */ _("button", { class: "btn search-toggle-btn", type: "button", onClick: v }, /* @__PURE__ */ _("i", { class: "icon icon-angle-left" }))),
    /* @__PURE__ */ _("div", { class: "history-record hidden" }, /* @__PURE__ */ _("p", null, c), /* @__PURE__ */ _("div", { class: "labels" }, (i == null ? void 0 : i.length) && i.map(($) => {
      if ($)
        return /* @__PURE__ */ _("div", { class: "label-btn", "data-id": $.id }, /* @__PURE__ */ _("span", { class: "label lighter-pale bd-lighter", onClick: (L) => u(L, Number($.id)) }, $.title, " ", $.hasPriv ? /* @__PURE__ */ _("i", { onClick: (L) => d(L, Number($.id)), class: "icon icon-close" }) : ""));
    }))),
    k ? /* @__PURE__ */ _("input", { type: "hidden", name: "actionURL", value: k }) : "",
    C ? /* @__PURE__ */ _("input", { type: "hidden", name: "module", value: C }) : "",
    E ? /* @__PURE__ */ _("input", { type: "hidden", name: "groupItems", value: E }) : ""
  );
}, li = class extends U {
  componentDidMount() {
    this.initForm();
  }
  initForm() {
    const { formSession: t } = this.props;
    this.base.querySelectorAll(".search-form-content .search-group").forEach((n, s) => {
      let i = {};
      const r = n.querySelector(".field-select");
      r && (r.value = (t ? t[r.id] : null) || this.props.fields[s].name, this.props.fields.forEach((a) => {
        a.name == r.value && (i = JSON.parse(JSON.stringify(a)));
      })), i.defaultValue = t ? t["value" + (s + 1)] : "";
      const o = n.querySelector(".search-method");
      o && (o.value = (t ? t[o.id] : null) || this.props.fields[s].operator || ""), this.toggleElement(n, i);
    });
  }
  toggleAttr(t, e) {
    if (!t.classList.contains("hidden")) {
      t.setAttribute("name", e), t.setAttribute("id", e);
      return;
    }
    t.removeAttribute("name"), t.removeAttribute("id");
  }
  toggleElement(t, e) {
    const n = t.querySelector(".value-select"), s = t.querySelector(".value-input"), i = t.querySelector(".value-date"), r = t.querySelector(".search-method");
    if (e.operator, e.control === "select" && (n.innerHTML = "", e.values)) {
      for (const h in e.values) {
        const c = document.createElement("option");
        c.value = h, c.setAttribute("value", h), c.innerHTML = e.values[h], n.appendChild(c);
      }
      n.value = e.defaultValue || "";
    }
    n.classList.toggle("hidden", e.control !== "select"), s.classList.toggle("hidden", e.control !== "input"), i == null || i.classList.toggle("hidden", e.control !== "date"), s.classList.contains("hidden") || (s.value = e.defaultValue || "", s.placeholder = e.placeholder || ""), i && !i.classList.contains("hidden") && (i.value = e.defaultValue || "");
    const o = t.dataset.id, a = t.querySelector(".group-value");
    if (!a)
      return;
    a.childNodes.forEach((h) => {
      this.toggleAttr(h, `value${o}`);
    });
  }
  handleSelect(t) {
    if (!t || !t.target)
      return;
    const e = t.target, s = this.props.fields.filter((r) => r.name === e.value)[0], i = e.closest(".search-group");
    this.toggleElement(i, s);
  }
  toggleElementDisplay(t, e, n, s) {
    const i = e.classList.contains("hidden"), r = t.querySelector(".icon");
    r == null || r.classList.toggle(n, i), r == null || r.classList.toggle(s, !i);
  }
  toggleMore(t) {
    if (!(t != null && t.target))
      return;
    const e = t.target, s = e.closest(".search-form-content").querySelectorAll(".search-col .search-group + .search-group");
    s.forEach((i) => {
      i.classList.toggle("hidden", !i.classList.contains("hidden"));
    }), this.toggleElementDisplay(e, s[0], "icon-chevron-double-down", "icon-chevron-double-up");
  }
  toggleHistory(t) {
    var s;
    if (!(t != null && t.target))
      return;
    const e = t.target, n = (s = e.closest(li.FORM_ID)) == null ? void 0 : s.querySelector(".history-record");
    n && (this.toggleElementDisplay(e, n, "icon-angle-right", "icon-angle-left"), n.classList.toggle("hidden", !n.classList.contains("hidden")));
  }
  resetForm(t) {
    if (!(t != null && t.target))
      return;
    const n = t.target.closest(li.FORM_ID);
    if (!n)
      return;
    n.querySelectorAll('.group-value [id^="value"]:not(.hidden), #searchForm .group-value [id*=" value"]:not(.hidden)').forEach((i) => {
      i.value = "";
    });
  }
  submitForm(t) {
    if (!(t != null && t.target))
      return;
    const n = t.target.closest(li.FORM_ID);
    n && n.submit();
  }
  onDeleteQuery(t, e) {
    !t || !t.target || e && t.stopPropagation();
  }
  onApplyQuery(t, e) {
    if (!t || !t.target || !e)
      return;
    const { applyQueryURL: n } = this.props;
    n && (location.href = n.replace("myQueryID", e.toString()));
  }
  render() {
    const { submitForm: t, onApplyQuery: e, onDeleteQuery: n } = this.props;
    return /* @__PURE__ */ _(
      Pp,
      {
        ...this.props,
        handleSelect: this.handleSelect.bind(this),
        toggleMore: this.toggleMore.bind(this),
        toggleHistory: this.toggleHistory.bind(this),
        resetForm: this.resetForm.bind(this),
        submitForm: t ? t.bind(this) : this.submitForm.bind(this),
        onDeleteQuery: n ? n.bind(this) : this.onDeleteQuery.bind(this),
        onApplyQuery: e ? e.bind(this) : this.onApplyQuery.bind(this)
      }
    );
  }
};
let La = li;
La.NAME = "SearchForm";
La.FORM_ID = "#searchForm";
class Zh extends J {
}
Zh.NAME = "SearchForm";
Zh.Component = La;
const Wp = { 1: "error", 2: "warning", 4: "parse", 8: "notice", 16: "core-error", 32: "core-warning", 64: "compile-error", 128: "compile-warning", 256: "user-error", 512: "user-warning", 1024: "user-notice", 2048: "strict", 4096: "recoverable-error", 8192: "deprecated", 16384: "user-deprecated", 32767: "all" };
function Qh(t) {
  return typeof t == "number" && (t = Wp[t]), t;
}
function tu(t) {
  return t = Qh(t), t.includes("error") ? "error" : t.includes("warning") ? "warning" : "info";
}
function Op({ errors: t, ...e }) {
  const n = t.reduce((s, i) => (s[tu(i.level)]++, s), { error: 0, warning: 0, info: 0 });
  return /* @__PURE__ */ _("div", { class: "row items-stretch text-sm", "data-hint": "PHP errors", ...e }, n.error ? /* @__PURE__ */ _("button", { type: "button", class: "state font-bold px-0.5 danger" }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "ERR"), n.error) : null, n.warning ? /* @__PURE__ */ _("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-90" }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "WAR"), n.warning) : null, n.info ? /* @__PURE__ */ _("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-80" }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "INF"), n.info) : null);
}
function pt(t, e, n, s) {
  console.groupCollapsed(`%c${t} %c${e}`, "color: #fff; background-color: #9333ea; padding: 0 0.1em 0 0.25em; border-radius: 0.25em 0 0 0.25em;", "color: #9333ea; background-color: #e9d5ff; padding: 0 0.5em; border-radius: 0 0.25em 0.25em 0;", s), console.table(n), console.groupEnd();
}
function re(t, e = 400, n = 100) {
  return t < n ? "success" : t < e ? "warning" : "danger";
}
function Pn(t) {
  return t < 1e3 ? `${t.toFixed(0)}ms` : `${(t / 1e3).toFixed(2)}s`;
}
function Dl({ perf: t }) {
  var o;
  const e = t.id === "page" ? "PAGE" : t.id === "#dtable" ? "TABLE" : "PART", n = [], { trace: s, xhprof: i } = t, r = s == null ? void 0 : s.request;
  if (t.requestEnd) {
    const a = t.requestEnd - t.requestBegin;
    if (n.push(/* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(a, 1e3, 400)}`, "data-hint": "Total load time (G<400<=N<1000<=B)", onClick: pt.bind(null, "Trace", "Perf", t, t.id) }, /* @__PURE__ */ _("i", { class: "icon-history" }), " ", Pn(a))), r) {
      const h = r.timeUsed;
      n.push(
        /* @__PURE__ */ _("div", { class: "muted" }, "/"),
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h)}`, "data-hint": "Server time (G<100<=N<400<=B)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "S"), Pn(h))
      );
    }
    if (t.dataSize) {
      if (r) {
        const h = a - r.timeUsed;
        n.push(
          /* @__PURE__ */ _("div", { class: "muted" }, "/"),
          /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h, 600, 200)}`, "data-hint": "Network time (G<200<=N<600<=B)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "N"), Pn(h))
        );
      }
      if (n.push(
        /* @__PURE__ */ _("div", { class: "px-0.5 state", "data-hint": "Loaded data size", onClick: pt.bind(null, "Trace", "Perf", t, t.id) }, /* @__PURE__ */ _("span", { class: "muted" }, /* @__PURE__ */ _("i", { class: "icon icon-cube muted" }), " ", br(t.dataSize, 1)))
      ), r) {
        const h = a - r.timeUsed, c = 1e3 * t.dataSize / h;
        n.push(
          /* @__PURE__ */ _("div", { class: "muted" }, "/"),
          /* @__PURE__ */ _("div", { class: `px-0.5 state text-${c < 102400 ? "danger" : c < 1024e3 ? "warning" : "success"}`, "data-hint": "Download speed(B<100KB<=N<1MB<=G)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("i", { class: "icon icon-arrow-down" }), br(c, 1), "/s")
        );
      }
    }
    if (t.renderEnd && t.renderBegin) {
      const h = t.renderEnd - t.renderBegin, c = re(h, 200, 50);
      n.push(
        /* @__PURE__ */ _("div", { class: "muted" }, "/"),
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${c}`, "data-hint": "Client render time (G<50<=N<200<=B)", onClick: pt.bind(null, "Trace", "Perf", t, t.id) }, /* @__PURE__ */ _("i", { class: `icon-${c === "danger" ? "frown" : c === "warning" ? "meh" : "smile"}` }), Pn(h))
      );
    }
    if (r) {
      const { memory: h, querys: c } = r;
      typeof h == "number" && n.push(
        /* @__PURE__ */ _("div", { class: "muted" }, "/"),
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h, 1024e3, 102400)}`, "data-hint": "Server memory usage(G<10KB<=N<100KB<=B)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "M"), br(h))
      ), typeof c == "number" && n.push(
        /* @__PURE__ */ _("div", { class: "muted" }, "/"),
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(c, 30, 10)}`, "data-hint": "Server sql queries count (G<30<=N<10<=B)", onClick: pt.bind(null, "SQL Query", `${((o = s.sqlQuery) == null ? void 0 : o.length) ?? 0} queries`, s.sqlQuery, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "Q"), c)
      );
    }
    s != null && s.files && n.push(
      /* @__PURE__ */ _("div", { class: "muted" }, "/"),
      /* @__PURE__ */ _("div", { class: "px-0.5 state", "data-hint": "Server loaded php files count", onClick: pt.bind(null, "Trace", `${s.files.length} php files`, s.files, t.id) }, /* @__PURE__ */ _("span", { class: "muted" }, /* @__PURE__ */ _("i", { class: "icon-file icon-sm muted scale-75" }), s.files.length))
    );
    const l = s == null ? void 0 : s.profiles;
    if (l != null && l.length) {
      let h = 0, c = { Duration: 0 };
      if (l.forEach((u) => {
        u.Duration > 0.3 && h++, u.Duration > c.Duration && (c = u);
      }), n.push(
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h, 3, 1)}`, "data-hint": "Server slow SQL queries count (G<3<=N<1<=B)", onClick: pt.bind(null, "SQL Query", `${l.length} SQL profiles`, l, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "LQ"), h)
      ), c.Duration) {
        const u = c.Duration * 1e3;
        n.push(
          /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(u, 600, 300)}`, "data-hint": "Server lowest SQL query duration (G<600<=N<300<=B)", onClick: pt.bind(null, "SQL Query", `Slowest SQL query: ${u}ms`, c, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "MLQ"), Pn(u))
        );
      }
    }
  } else
    n.push(/* @__PURE__ */ _("div", { class: "muted px-0.5" }, "loading..."));
  return /* @__PURE__ */ _("div", { class: "zin-perf-btn-list row items-center bg-black text-sm" }, /* @__PURE__ */ _("div", { class: "px-1 bg-canvas bg-opacity-20 self-stretch flex items-center", "data-hint": `REQUEST: ${t.id} URL: ${t.url}` }, /* @__PURE__ */ _("span", { class: "muted" }, e)), n, i ? /* @__PURE__ */ _("a", { class: "state text-secondary px-0.5", href: i, target: "_blank", "data-hint": "Visit xhprof page" }, "XHP") : null);
}
function Ip(t) {
  pt("Trace", "Error", t, t.message), navigator.clipboard.writeText(`vim +${t.line} ${t.file}`);
}
function Hp({ errors: t = [], show: e, basePath: n }) {
  t.length || (e = !1);
  const s = t.map((i) => {
    const r = Qh(i.level), o = tu(r), a = o === "error" ? "danger" : o === "info" ? "important" : "warning";
    return /* @__PURE__ */ _("div", { class: `zin-error-item state ${a}-pale text-fore px-2 py-1 ring ring-darker`, onClick: Ip.bind(null, i) }, /* @__PURE__ */ _("div", { class: "zin-error-msg font-bold text-base" }, /* @__PURE__ */ _("strong", { class: `text-${a}`, style: "text-transform: uppercase;" }, r), " ", i.message), /* @__PURE__ */ _("div", { class: "zin-error-info text-sm opacity-60 break-all" }, /* @__PURE__ */ _("strong", null, "vim +", i.line), " ", /* @__PURE__ */ _("span", { className: "underline" }, n ? i.file.substring(n.length) : i.file)));
  });
  return /* @__PURE__ */ _("div", { class: `zin-errors-panel absolute bottom-full left-0 mono shadow-xl ring rounded fade-from-bottom ${e ? "in" : "events-none"}` }, s);
}
let Bp = class extends U {
  constructor(e) {
    var n, s;
    super(e), this.state = {
      showPanel: e.defaultShow ?? !0,
      showZinbar: !!((s = (n = e.defaultData) == null ? void 0 : n.errors) != null && s.length) || localStorage.getItem("showZinbar") === "true",
      ...e.defaultData
    }, this.togglePanel = this.togglePanel.bind(this), this.handleClickOutside = this.handleClickOutside.bind(this);
  }
  componentDidMount() {
    document.addEventListener("click", this.handleClickOutside);
  }
  componentWillUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  }
  handleClickOutside(e) {
    const n = e.target;
    this.state.showPanel && !n.closest(".zinbar") && this.setState({ showPanel: !1 });
  }
  update(e, n, s) {
    this.setState((i) => {
      const r = !(e != null && e.id) || e.id === "page", o = {};
      return r ? (e && (o.pagePerf = { ...i.pagePerf, ...e }), n && (o.errors = n)) : (e && (o.partPerf = { ...i.partPerf, ...e }), n && (o.errors = [...i.errors ?? [], ...n ?? []])), s && (o.basePath = s), o;
    });
  }
  togglePanel() {
    this.setState({ showPanel: !this.state.showPanel });
  }
  toggleZinbar() {
    localStorage.setItem("showZinbar", `${!this.state.showZinbar}`), this.setState({ showZinbar: !this.state.showZinbar });
  }
  render() {
    const { errors: e, pagePerf: n, partPerf: s, showZinbar: i, basePath: r } = this.state, { fixed: o } = this.props;
    return /* @__PURE__ */ _(
      "div",
      {
        class: N(
          "zinbar row h-5 items-stretch gap-px inverse bg-opacity-50",
          o ? "relative" : "fixed right-0 bottom-0",
          { collapse: !i }
        ),
        style: { zIndex: 9999 }
      },
      /* @__PURE__ */ _(
        "button",
        {
          type: "button",
          "data-hint": i ? "collapse" : "expand",
          class: "font-bold px-1 primary absolute flex items-center",
          style: { marginLeft: -15 },
          onClick: this.toggleZinbar.bind(this)
        },
        i ? ">" : "<"
      ),
      e != null && e.length ? /* @__PURE__ */ _(Op, { errors: e, onClick: this.togglePanel }) : null,
      n ? /* @__PURE__ */ _(Dl, { perf: n }) : null,
      s ? /* @__PURE__ */ _(Dl, { perf: s }) : null,
      /* @__PURE__ */ _(Hp, { show: this.state.showPanel, basePath: r, errors: e })
    );
  }
};
class eu extends J {
}
eu.NAME = "Zinbar";
eu.Component = Bp;
var ks, Fi, ji, Ui;
class zp extends U {
  constructor(n) {
    super(n);
    O(this, ks, $t());
    O(this, Fi, (n) => {
      n.stopPropagation(), bt.show({
        event: n.target,
        placement: "bottom-end",
        menu: {
          onClickItem: ({ item: s }) => {
            var i;
            ((i = s.attrs) == null ? void 0 : i["data-type"]) === "refresh" && this.load();
          }
        },
        ...this.props.block.menu
      });
    });
    O(this, ji, (n) => {
      var r, o, a;
      const { element: s } = this, i = s.getBoundingClientRect();
      if (n.clientY - i.top > 48) {
        n.preventDefault();
        return;
      }
      this.setState({ dragging: !0 }), (r = n.dataTransfer) == null || r.setData("application/id", this.props.block.id), (a = (o = this.props).onDragStart) == null || a.call(o, n);
    });
    O(this, Ui, (n) => {
      var s, i;
      this.setState({ dragging: !1 }), (i = (s = this.props).onDragEnd) == null || i.call(s, n);
    });
    this.state = { content: /* @__PURE__ */ y("div", { class: "dashboard-block-body", children: n.block.content }) };
  }
  get element() {
    return D(this, ks).current;
  }
  componentDidMount() {
    this.load(), g(this.element).on("load.zui.dashboard", this.load.bind(this));
  }
  componentWillUnmount() {
    g(this.element).off("load.zui.dashboard");
  }
  load() {
    const { block: n } = this.props;
    let s = n.fetch;
    if (!s || this.state.loading)
      return;
    typeof s == "string" ? s = { url: s } : typeof s == "function" && (s = s(n.id, n));
    const { url: i, ...r } = s;
    this.setState({ loading: !0 }, () => {
      fetch(X(i, n), {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        ...r
      }).then((o) => {
        o.ok ? o.text().then((a) => {
          this.setState({ loading: !1, content: /* @__PURE__ */ y(kc, { class: "dashboard-block-body", html: a, executeScript: !0 }) });
        }) : this.setState({ loading: !1, content: /* @__PURE__ */ y("div", { class: "text-danger p-5 text-center", children: [
          "Error: ",
          o.statusText
        ] }) });
      });
    });
  }
  render() {
    const { left: n, top: s, width: i, height: r, style: o, block: a } = this.props, { title: l, menu: h, id: c } = a, { loading: u, content: d, dragging: f } = this.state;
    return /* @__PURE__ */ y("div", { class: "dashboard-block-cell", style: { left: n, top: s, width: i, height: r, ...o }, children: /* @__PURE__ */ y(
      "div",
      {
        class: `dashboard-block load-indicator${u ? " loading" : ""}${h ? " has-more-menu" : ""}${f ? " is-dragging" : ""}`,
        draggable: !0,
        onDragStart: D(this, ji),
        onDragEnd: D(this, Ui),
        "data-id": c,
        ref: D(this, ks),
        children: [
          /* @__PURE__ */ y("div", { class: "dashboard-block-header", children: [
            /* @__PURE__ */ y("div", { class: "dashboard-block-title", children: l }),
            h ? /* @__PURE__ */ y("div", { class: "dashboard-block-actions toolbar", children: /* @__PURE__ */ y("button", { class: "toolbar-item dashboard-block-action btn square ghost rounded size-sm", "data-type": "more", onClick: D(this, Fi), children: /* @__PURE__ */ y("div", { class: "more-vert" }) }) }) : null
          ] }),
          d
        ]
      }
    ) });
  }
}
ks = new WeakMap(), Fi = new WeakMap(), ji = new WeakMap(), Ui = new WeakMap();
var nu = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Kt = (t, e, n) => (nu(t, e, "read from private field"), n ? n.call(t) : e.get(t)), vt = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, St = (t, e, n) => (nu(t, e, "access private method"), n), te, Da, su, Pa, iu, fo, ru, Wa, ou, Ri, po, ur, go, Oa, au, mo, yo, dr, Ia;
const Ha = class extends U {
  constructor() {
    super(...arguments), vt(this, Da), vt(this, Pa), vt(this, fo), vt(this, Wa), vt(this, Ri), vt(this, ur), vt(this, Oa), vt(this, te, /* @__PURE__ */ new Map()), this.state = {}, vt(this, mo, (t) => {
      var n;
      const e = (n = t.dataTransfer) == null ? void 0 : n.getData("application/id");
      e !== void 0 && (this.setState({ dragging: e }), console.log("handleBlockDragStart", t));
    }), vt(this, yo, (t) => {
      this.setState({ dragging: void 0 }), console.log("handleBlockDragEnd", t);
    });
  }
  render() {
    const { blocks: t, height: e } = St(this, fo, ru).call(this), { cellHeight: n, grid: s } = this.props, i = Kt(this, te);
    return console.log("Dashboard.render", { blocks: t, map: i }, this), /* @__PURE__ */ y("div", { class: "dashboard", children: /* @__PURE__ */ y("div", { class: "dashboard-blocks", style: { height: e * n }, children: t.map((r, o) => {
      const { id: a } = r, [l, h, c, u] = i.get(a) || [0, 0, r.width, r.height];
      return /* @__PURE__ */ y(
        zp,
        {
          id: a,
          index: o,
          left: `${100 * l / s}%`,
          top: n * h,
          height: n * u,
          width: `${100 * c / s}%`,
          block: r,
          moreMenu: !0,
          onDragStart: Kt(this, mo),
          onDragEnd: Kt(this, yo)
        },
        r.id
      );
    }) }) });
  }
};
let Ba = Ha;
te = /* @__PURE__ */ new WeakMap();
Da = /* @__PURE__ */ new WeakSet();
su = function(t) {
  const { blockDefaultSize: e, blockSizeMap: n } = this.props;
  return t = t ?? e, typeof t == "string" && (t = n[t]), t = t || e, Array.isArray(t) || (t = [t.width, t.height]), t;
};
Pa = /* @__PURE__ */ new WeakSet();
iu = function() {
  const { blocks: t, blockFetch: e, blockMenu: n } = this.props;
  return t.map((i) => {
    const {
      id: r,
      size: o,
      left: a = -1,
      top: l = -1,
      fetch: h = e,
      menu: c = n,
      ...u
    } = i, [d, f] = St(this, Da, su).call(this, o);
    return {
      id: `${r}`,
      width: d,
      height: f,
      left: a,
      top: l,
      fetch: h,
      menu: c,
      ...u
    };
  });
};
fo = /* @__PURE__ */ new WeakSet();
ru = function() {
  Kt(this, te).clear();
  let t = 0;
  const e = St(this, Pa, iu).call(this);
  return e.forEach((n) => {
    St(this, Wa, ou).call(this, n);
    const [, s, , i] = Kt(this, te).get(n.id);
    t = Math.max(t, s + i);
  }), { blocks: e, height: t };
};
Wa = /* @__PURE__ */ new WeakSet();
ou = function(t) {
  const e = Kt(this, te), { id: n, left: s, top: i, width: r, height: o } = t;
  if (s < 0 || i < 0) {
    const [a, l] = St(this, Oa, au).call(this, r, o, s, i);
    e.set(n, [a, l, r, o]);
  } else
    St(this, ur, go).call(this, n, [s, i, r, o]);
};
Ri = /* @__PURE__ */ new WeakSet();
po = function(t) {
  var e;
  const { dragging: n } = this.state;
  for (const [s, i] of Kt(this, te).entries())
    if (s !== n && St(e = Ha, dr, Ia).call(e, i, t))
      return !1;
  return !0;
};
ur = /* @__PURE__ */ new WeakSet();
go = function(t, e) {
  var n;
  Kt(this, te).set(t, e);
  for (const [s, i] of Kt(this, te).entries())
    s !== t && St(n = Ha, dr, Ia).call(n, i, e) && (i[1] = e[1] + e[3], St(this, ur, go).call(this, s, i));
};
Oa = /* @__PURE__ */ new WeakSet();
au = function(t, e, n, s) {
  if (n >= 0 && s >= 0) {
    if (St(this, Ri, po).call(this, [n, s, t, e]))
      return [n, s];
    s = -1;
  }
  let i = n < 0 ? 0 : n, r = s < 0 ? 0 : s, o = !1;
  const a = this.props.grid;
  for (; !o; ) {
    if (St(this, Ri, po).call(this, [i, r, t, e])) {
      o = !0;
      break;
    }
    n < 0 ? (i += 1, i + t > a && (i = 0, r += 1)) : r += 1;
  }
  return [i, r];
};
mo = /* @__PURE__ */ new WeakMap();
yo = /* @__PURE__ */ new WeakMap();
dr = /* @__PURE__ */ new WeakSet();
Ia = function([t, e, n, s], [i, r, o, a]) {
  return !(t + n <= i || i + o <= t || e + s <= r || r + a <= e);
};
vt(Ba, dr);
Ba.defaultProps = {
  responsive: !1,
  blocks: [],
  grid: 3,
  gap: 16,
  cellHeight: 64,
  blockDefaultSize: [1, 3],
  blockMenu: { items: [{ text: "Refresh", attrs: { "data-type": "refresh" } }] },
  blockSizeMap: {
    xs: [1, 3],
    sm: [1, 4],
    md: [1, 5],
    lg: [1, 6],
    xl: [1, 8],
    xsWide: [2, 3],
    smWide: [2, 4],
    mdWide: [2, 5],
    lgWide: [2, 6],
    xlWide: [2, 8],
    xsLong: [3, 3],
    smLong: [3, 4],
    mdLong: [3, 5],
    lgLong: [3, 6],
    xlLong: [3, 8]
  }
};
class lu extends J {
}
lu.NAME = "Dashboard";
lu.Component = Ba;
var fe, pe;
class Pl extends U {
  constructor(n) {
    super(n);
    O(this, fe, void 0);
    O(this, pe, void 0);
    F(this, fe, 0), F(this, pe, null), this._handleWheel = (s) => {
      const { wheelContainer: i } = this.props, r = s.target;
      if (!(!r || !i) && (typeof i == "string" && r.closest(i) || typeof i == "object")) {
        const o = (this.props.type === "horz" ? s.deltaX : s.deltaY) * (this.props.wheelSpeed ?? 1);
        this.scrollOffset(o) && s.preventDefault();
      }
    }, this._handleMouseMove = (s) => {
      const { dragStart: i } = this.state;
      i && (D(this, fe) && cancelAnimationFrame(D(this, fe)), F(this, fe, requestAnimationFrame(() => {
        const r = this.props.type === "horz" ? s.clientX - i.x : s.clientY - i.y;
        this.scroll(i.offset + r * this.props.scrollSize / this.props.clientSize), F(this, fe, 0);
      })), s.preventDefault());
    }, this._handleMouseUp = () => {
      this.state.dragStart && this.setState({
        dragStart: !1
      });
    }, this._handleMouseDown = (s) => {
      this.state.dragStart || this.setState({ dragStart: { x: s.clientX, y: s.clientY, offset: this.scrollPos } }), s.stopPropagation();
    }, this._handleClick = (s) => {
      const i = s.currentTarget;
      if (!i)
        return;
      const r = i.getBoundingClientRect(), { type: o, clientSize: a, scrollSize: l } = this.props, h = (o === "horz" ? s.clientX - r.left : s.clientY - r.top) - this.barSize / 2;
      this.scroll(h * l / a), s.preventDefault();
    }, this.state = {
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
    const { scrollSize: n, clientSize: s } = this.props;
    return Math.max(0, n - s);
  }
  get barSize() {
    const { clientSize: n, scrollSize: s, size: i = 12, minBarSize: r = 3 * i } = this.props;
    return Math.max(Math.round(n * n / s), r);
  }
  componentDidMount() {
    document.addEventListener("mousemove", this._handleMouseMove), document.addEventListener("mouseup", this._handleMouseUp);
    const { wheelContainer: n } = this.props;
    n && (F(this, pe, typeof n == "string" ? document : n.current), D(this, pe).addEventListener("wheel", this._handleWheel, { passive: !1 }));
  }
  componentWillUnmount() {
    document.removeEventListener("mousemove", this._handleMouseMove), document.removeEventListener("mouseup", this._handleMouseUp), D(this, pe) && D(this, pe).removeEventListener("wheel", this._handleWheel);
  }
  scroll(n) {
    return n = Math.max(0, Math.min(Math.round(n), this.maxScrollPos)), n === this.scrollPos ? !1 : (this.controlled ? this._afterScroll(n) : this.setState({
      scrollPos: n
    }, this._afterScroll.bind(this, n)), !0);
  }
  scrollOffset(n) {
    return this.scroll(this.scrollPos + n);
  }
  _afterScroll(n) {
    const { onScroll: s } = this.props;
    s && s(n, this.props.type ?? "vert");
  }
  render() {
    const {
      clientSize: n,
      type: s,
      size: i = 12,
      className: r,
      style: o,
      left: a,
      top: l,
      bottom: h,
      right: c
    } = this.props, { maxScrollPos: u, scrollPos: d } = this, { dragStart: f } = this.state, p = {
      left: a,
      top: l,
      bottom: h,
      right: c,
      ...o
    }, m = {};
    return s === "horz" ? (p.height = i, p.width = n, m.width = this.barSize, m.left = Math.round(Math.min(u, d) * (n - m.width) / u)) : (p.width = i, p.height = n, m.height = this.barSize, m.top = Math.round(Math.min(u, d) * (n - m.height) / u)), /* @__PURE__ */ y(
      "div",
      {
        className: N("scrollbar", r, {
          "is-vert": s === "vert",
          "is-horz": s === "horz",
          "is-dragging": f
        }),
        style: p,
        onMouseDown: this._handleClick,
        children: /* @__PURE__ */ y(
          "div",
          {
            className: "scrollbar-bar",
            style: m,
            onMouseDown: this._handleMouseDown
          }
        )
      }
    );
  }
}
fe = new WeakMap(), pe = new WeakMap();
function cu({ col: t, className: e, height: n, row: s, onRenderCell: i, style: r, outerStyle: o, children: a, outerClass: l, ...h }) {
  var A;
  const c = {
    left: t.left,
    width: t.realWidth,
    height: n,
    ...o
  }, { align: u, border: d } = t.setting, f = {
    justifyContent: u ? u === "left" ? "start" : u === "right" ? "end" : u : void 0,
    ...t.setting.cellStyle,
    ...r
  }, p = ["dtable-cell", l, e, t.setting.className, {
    "has-border-left": d === !0 || d === "left",
    "has-border-right": d === !0 || d === "right"
  }], m = ["dtable-cell-content", t.setting.cellClass], v = (A = s.data) == null ? void 0 : A[t.name], w = [a ?? v ?? ""], b = i ? i(w, { row: s, col: t, value: v }, _) : w, k = [], C = [], E = {}, P = {};
  let M = "div";
  b == null || b.forEach((x) => {
    if (typeof x == "object" && x && !it(x) && ("html" in x || "className" in x || "style" in x || "attrs" in x || "children" in x || "tagName" in x)) {
      const $ = x.outer ? k : C;
      x.html ? $.push(/* @__PURE__ */ y("div", { className: N("dtable-cell-html", x.className), style: x.style, dangerouslySetInnerHTML: { __html: x.html }, ...x.attrs ?? {} })) : (x.style && Object.assign(x.outer ? c : f, x.style), x.className && (x.outer ? p : m).push(x.className), x.children && $.push(x.children), x.attrs && Object.assign(x.outer ? E : P, x.attrs)), x.tagName && !x.outer && (M = x.tagName);
    } else
      C.push(x);
  });
  const T = M;
  return /* @__PURE__ */ y(
    "div",
    {
      className: N(p),
      style: c,
      "data-col": t.name,
      "data-type": t.type,
      ...h,
      ...E,
      children: [
        C.length > 0 && /* @__PURE__ */ y(T, { className: N(m), style: f, ...P, children: C }),
        k
      ]
    }
  );
}
function Mr({ row: t, className: e, top: n = 0, left: s = 0, width: i, height: r, cols: o, CellComponent: a = cu, onRenderCell: l }) {
  return /* @__PURE__ */ y("div", { className: N("dtable-cells", e), style: { top: n, left: s, width: i, height: r }, children: o.map((h) => h.visible ? /* @__PURE__ */ y(
    a,
    {
      col: h,
      row: t,
      onRenderCell: l
    },
    h.name
  ) : null) });
}
function hu({
  row: t,
  className: e,
  top: n,
  height: s,
  cols: { left: i, center: r, right: o },
  scrollLeft: a,
  CellComponent: l = cu,
  onRenderCell: h,
  style: c,
  ...u
}) {
  let d = null;
  i.list.length && (d = /* @__PURE__ */ y(
    Mr,
    {
      className: "dtable-fixed-left",
      cols: i.list,
      width: i.width,
      row: t,
      CellComponent: l,
      onRenderCell: h
    }
  ));
  let f = null;
  r.list.length && (f = /* @__PURE__ */ y(
    Mr,
    {
      className: "dtable-flexable",
      cols: r.list,
      left: i.width - a,
      width: Math.max(r.width, r.totalWidth),
      row: t,
      CellComponent: l,
      onRenderCell: h
    }
  ));
  let p = null;
  o.list.length && (p = /* @__PURE__ */ y(
    Mr,
    {
      className: "dtable-fixed-right",
      cols: o.list,
      left: i.width + r.width,
      width: o.width,
      row: t,
      CellComponent: l,
      onRenderCell: h
    }
  ));
  const m = { top: n, height: s, lineHeight: `${s - 2}px`, ...c };
  return /* @__PURE__ */ y(
    "div",
    {
      className: N("dtable-row", e),
      style: m,
      "data-id": t.id,
      ...u,
      children: [
        d,
        f,
        p
      ]
    }
  );
}
function Fp({ height: t, onRenderRow: e, ...n }) {
  const s = {
    height: t,
    ...n,
    row: { id: "HEADER", index: -1, top: 0 },
    className: "dtable-in-header",
    top: 0
  };
  if (e) {
    const i = e({ props: s }, _);
    i && Object.assign(s, i);
  }
  return /* @__PURE__ */ y("div", { className: "dtable-header", style: { height: t }, children: /* @__PURE__ */ y(hu, { ...s }) });
}
function jp({
  className: t,
  style: e,
  top: n,
  rows: s,
  height: i,
  rowHeight: r,
  scrollTop: o,
  onRenderRow: a,
  ...l
}) {
  return e = { ...e, top: n, height: i }, /* @__PURE__ */ y("div", { className: N("dtable-rows", t), style: e, children: s.map((h) => {
    const c = {
      className: `dtable-row-${h.index % 2 ? "odd" : "even"}`,
      row: h,
      top: h.top - o,
      height: r,
      ...l
    }, u = a == null ? void 0 : a({ props: c, row: h }, _);
    return u && Object.assign(c, u), /* @__PURE__ */ y(hu, { ...c }, h.id);
  }) });
}
const Ai = /* @__PURE__ */ new Map(), Ni = [];
function uu(t, e) {
  const { name: n } = t;
  if (!(e != null && e.override) && Ai.has(n))
    throw new Error(`DTable: Plugin with name ${n} already exists`);
  Ai.set(n, t), e != null && e.buildIn && !Ni.includes(n) && Ni.push(n);
}
function yt(t, e) {
  uu(t, e);
  const n = (s) => {
    if (!s)
      return t;
    const { defaultOptions: i, ...r } = t;
    return {
      ...r,
      defaultOptions: { ...i, ...s }
    };
  };
  return n.plugin = t, n;
}
function du(t) {
  return Ai.delete(t);
}
function Up(t) {
  if (typeof t == "string") {
    const e = Ai.get(t);
    return e || console.warn(`DTable: Cannot found plugin "${t}"`), e;
  }
  if (typeof t == "function" && "plugin" in t)
    return t.plugin;
  if (typeof t == "object")
    return t;
  console.warn("DTable: Invalid plugin", t);
}
function fu(t, e, n) {
  return e.forEach((s) => {
    var r;
    if (!s)
      return;
    const i = Up(s);
    i && (n.has(i.name) || ((r = i.plugins) != null && r.length && fu(t, i.plugins, n), t.push(i), n.add(i.name)));
  }), t;
}
function qp(t = [], e = !0) {
  return e && Ni.length && t.unshift(...Ni), t != null && t.length ? fu([], t, /* @__PURE__ */ new Set()) : [];
}
function pu() {
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
    footer: void 0,
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
function Vp(t, e, n) {
  return t && (e && (t = Math.max(e, t)), n && (t = Math.min(n, t))), t;
}
function Wl(t, e) {
  return typeof t == "string" && (t = t.endsWith("%") ? parseFloat(t) / 100 : parseFloat(t)), typeof e == "number" && (typeof t != "number" || isNaN(t)) && (t = e), t;
}
function Tr(t, e = !1) {
  if (!t.list.length)
    return;
  if (t.widthSetting && t.width !== t.widthSetting) {
    t.width = t.widthSetting;
    const s = t.width - t.totalWidth;
    if (e || s > 0) {
      const i = t.flexList.length ? t.flexList : t.list, r = i.reduce((o, a) => o + (a.flex || 1), 0);
      i.forEach((o) => {
        const a = Math.min(s, Math.ceil(s * ((o.flex || 1) / r)));
        o.realWidth = o.width + a;
      });
    }
  }
  let n = 0;
  t.list.forEach((s) => {
    s.realWidth || (s.realWidth = s.width), s.left = n, n += s.realWidth;
  });
}
function Gp(t, e, n, s) {
  const { defaultColWidth: i, minColWidth: r, maxColWidth: o, fixedLeftWidth: a = 0, fixedRightWidth: l = 0 } = e, h = (b) => (typeof b == "function" && (b = b.call(t)), b = Wl(b, 0), b < 1 && (b = Math.round(b * s)), b), c = {
    width: 0,
    list: [],
    flexList: [],
    widthSetting: 0,
    totalWidth: 0
  }, u = {
    ...c,
    list: [],
    flexList: [],
    widthSetting: h(a)
  }, d = {
    ...c,
    list: [],
    flexList: [],
    widthSetting: h(l)
  }, f = [], p = {};
  let m = !1;
  const v = [], w = {};
  if (n.forEach((b) => {
    const { colTypes: k, onAddCol: C } = b;
    k && Object.entries(k).forEach(([E, P]) => {
      w[E] || (w[E] = []), w[E].push(P);
    }), C && v.push(C);
  }), e.cols.forEach((b) => {
    if (b.hidden)
      return;
    const { type: k = "", name: C } = b, E = {
      fixed: !1,
      flex: !1,
      width: i,
      minWidth: r,
      maxWidth: o,
      ...b,
      type: k
    }, P = {
      name: C,
      type: k,
      setting: E,
      flex: 0,
      left: 0,
      width: 0,
      realWidth: 0,
      visible: !0,
      index: f.length
    }, M = w[k];
    M && M.forEach((H) => {
      const B = typeof H == "function" ? H.call(t, E) : H;
      B && Object.assign(E, B, b);
    });
    const { fixed: T, flex: A, minWidth: x = r, maxWidth: $ = o } = E, L = Wl(E.width || i, i);
    P.flex = A === !0 ? 1 : typeof A == "number" ? A : 0, P.width = Vp(L < 1 ? Math.round(L * s) : L, x, $), v.forEach((H) => H.call(t, P)), f.push(P), p[P.name] = P;
    const W = T ? T === "left" ? u : d : c;
    W.list.push(P), W.totalWidth += P.width, W.width = W.totalWidth, P.flex && W.flexList.push(P), typeof E.order == "number" && (m = !0);
  }), m) {
    const b = (k, C) => (k.setting.order ?? 0) - (C.setting.order ?? 0);
    f.sort(b), u.list.sort(b), c.list.sort(b), d.list.sort(b);
  }
  return Tr(u, !0), Tr(d, !0), c.widthSetting = s - u.width - d.width, Tr(c), {
    list: f,
    map: p,
    left: u,
    center: c,
    right: d
  };
}
var za = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, R = (t, e, n) => (za(t, e, "read from private field"), n ? n.call(t) : e.get(t)), z = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Q = (t, e, n, s) => (za(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Ft = (t, e, n) => (za(t, e, "access private method"), n), tn, Kn, Oe, Yt, Ne, nt, Ut, zt, Xn, ci, Li, le, Jn, Zn, wo, gu, vo, mu, bo, yu, _o, wu, hi, xo, Fa, ja, fr, Di, $o, ko, Ua, vu, qa, bu, So, _u;
let Va = class extends U {
  constructor(e) {
    super(e), z(this, wo), z(this, vo), z(this, bo), z(this, _o), z(this, hi), z(this, Ua), z(this, qa), z(this, So), this.ref = $t(), z(this, tn, 0), z(this, Kn, void 0), z(this, Oe, !1), z(this, Yt, void 0), z(this, Ne, void 0), z(this, nt, []), z(this, Ut, void 0), z(this, zt, /* @__PURE__ */ new Map()), z(this, Xn, {}), z(this, ci, void 0), z(this, Li, []), this.updateLayout = () => {
      R(this, tn) && cancelAnimationFrame(R(this, tn)), Q(this, tn, requestAnimationFrame(() => {
        this.update({ dirtyType: "layout" }), Q(this, tn, 0);
      }));
    }, z(this, le, (n, s) => {
      s = s || n.type;
      const i = R(this, zt).get(s);
      if (i != null && i.length) {
        for (const r of i)
          if (r.call(this, n) === !1) {
            n.stopPropagation(), n.preventDefault();
            break;
          }
      }
    }), z(this, Jn, (n) => {
      R(this, le).call(this, n, `window_${n.type}`);
    }), z(this, Zn, (n) => {
      R(this, le).call(this, n, `document_${n.type}`);
    }), z(this, Fa, (n, s) => {
      if (this.options.onRenderRow) {
        const i = this.options.onRenderRow.call(this, n, s);
        i && Object.assign(n.props, i);
      }
      return R(this, nt).forEach((i) => {
        if (i.onRenderRow) {
          const r = i.onRenderRow.call(this, n, s);
          r && Object.assign(n.props, r);
        }
      }), n.props;
    }), z(this, ja, (n, s) => (this.options.onRenderHeaderRow && (n.props = this.options.onRenderHeaderRow.call(this, n, s)), R(this, nt).forEach((i) => {
      i.onRenderHeaderRow && (n.props = i.onRenderHeaderRow.call(this, n, s));
    }), n.props)), z(this, fr, (n, s, i) => {
      const { row: r, col: o } = s;
      s.value = this.getCellValue(r, o), n[0] = s.value;
      const a = r.id === "HEADER" ? "onRenderHeaderCell" : "onRenderCell";
      return R(this, nt).forEach((l) => {
        l[a] && (n = l[a].call(this, n, s, i));
      }), this.options[a] && (n = this.options[a].call(this, n, s, i)), o.setting[a] && (n = o.setting[a].call(this, n, s, i)), n;
    }), z(this, Di, (n, s) => {
      s === "horz" ? this.scroll({ scrollLeft: n }) : this.scroll({ scrollTop: n });
    }), z(this, $o, (n) => {
      var a, l, h, c, u;
      const s = this.getPointerInfo(n);
      if (!s)
        return;
      const { rowID: i, colName: r, cellElement: o } = s;
      if (i === "HEADER")
        o && ((a = this.options.onHeaderCellClick) == null || a.call(this, n, { colName: r, element: o }), R(this, nt).forEach((d) => {
          var f;
          (f = d.onHeaderCellClick) == null || f.call(this, n, { colName: r, element: o });
        }));
      else {
        const { rowElement: d } = s, f = this.layout.visibleRows.find((p) => p.id === i);
        if (o) {
          if (((l = this.options.onCellClick) == null ? void 0 : l.call(this, n, { colName: r, rowID: i, rowInfo: f, element: o, rowElement: d })) === !0)
            return;
          for (const p of R(this, nt))
            if (((h = p.onCellClick) == null ? void 0 : h.call(this, n, { colName: r, rowID: i, rowInfo: f, element: o, rowElement: d })) === !0)
              return;
        }
        if (((c = this.options.onRowClick) == null ? void 0 : c.call(this, n, { rowID: i, rowInfo: f, element: d })) === !0)
          return;
        for (const p of R(this, nt))
          if (((u = p.onRowClick) == null ? void 0 : u.call(this, n, { rowID: i, rowInfo: f, element: d })) === !0)
            return;
      }
    }), z(this, ko, (n) => {
      const s = n.key.toLowerCase();
      if (["pageup", "pagedown", "home", "end"].includes(s))
        return !this.scroll({ to: s.replace("page", "") });
    }), Q(this, Kn, e.id ?? `dtable-${sr(10)}`), this.state = { scrollTop: 0, scrollLeft: 0, renderCount: 0 }, Q(this, Ne, Object.freeze(qp(e.plugins))), R(this, Ne).forEach((n) => {
      var o;
      const { methods: s, data: i, state: r } = n;
      s && Object.entries(s).forEach(([a, l]) => {
        typeof l == "function" && Object.assign(this, { [a]: l.bind(this) });
      }), i && Object.assign(R(this, Xn), i.call(this)), r && Object.assign(this.state, r.call(this)), (o = n.onCreate) == null || o.call(this, n);
    });
  }
  get options() {
    var e;
    return ((e = R(this, Ut)) == null ? void 0 : e.options) || R(this, Yt) || pu();
  }
  get plugins() {
    return R(this, nt);
  }
  get layout() {
    return R(this, Ut);
  }
  get id() {
    return R(this, Kn);
  }
  get data() {
    return R(this, Xn);
  }
  get parent() {
    var e;
    return this.props.parent ?? ((e = this.ref.current) == null ? void 0 : e.parentElement);
  }
  componentWillReceiveProps() {
    Q(this, Yt, void 0);
  }
  componentDidMount() {
    if (R(this, Oe) ? this.forceUpdate() : Ft(this, hi, xo).call(this), R(this, nt).forEach((e) => {
      let { events: n } = e;
      n && (typeof n == "function" && (n = n.call(this)), Object.entries(n).forEach(([s, i]) => {
        i && this.on(s, i);
      }));
    }), this.on("click", R(this, $o)), this.on("keydown", R(this, ko)), this.options.responsive)
      if (typeof ResizeObserver < "u") {
        const { parent: e } = this;
        if (e) {
          const n = new ResizeObserver(this.updateLayout);
          n.observe(e), Q(this, ci, n);
        }
      } else
        this.on("window_resize", this.updateLayout);
    R(this, nt).forEach((e) => {
      var n;
      (n = e.onMounted) == null || n.call(this);
    });
  }
  componentDidUpdate() {
    R(this, Oe) ? Ft(this, hi, xo).call(this) : R(this, nt).forEach((e) => {
      var n;
      (n = e.onUpdated) == null || n.call(this);
    });
  }
  componentWillUnmount() {
    var n;
    (n = R(this, ci)) == null || n.disconnect();
    const { current: e } = this.ref;
    if (e)
      for (const s of R(this, zt).keys())
        s.startsWith("window_") ? window.removeEventListener(s.replace("window_", ""), R(this, Jn)) : s.startsWith("document_") ? document.removeEventListener(s.replace("document_", ""), R(this, Zn)) : e.removeEventListener(s, R(this, le));
    R(this, nt).forEach((s) => {
      var i;
      (i = s.onUnmounted) == null || i.call(this);
    }), R(this, Ne).forEach((s) => {
      var i;
      (i = s.onDestory) == null || i.call(this);
    }), Q(this, Xn, {}), R(this, zt).clear();
  }
  on(e, n, s) {
    var r;
    s && (e = `${s}_${e}`);
    const i = R(this, zt).get(e);
    i ? i.push(n) : (R(this, zt).set(e, [n]), e.startsWith("window_") ? window.addEventListener(e.replace("window_", ""), R(this, Jn)) : e.startsWith("document_") ? document.addEventListener(e.replace("document_", ""), R(this, Zn)) : (r = this.ref.current) == null || r.addEventListener(e, R(this, le)));
  }
  off(e, n, s) {
    var o;
    s && (e = `${s}_${e}`);
    const i = R(this, zt).get(e);
    if (!i)
      return;
    const r = i.indexOf(n);
    r >= 0 && i.splice(r, 1), i.length || (R(this, zt).delete(e), e.startsWith("window_") ? window.removeEventListener(e.replace("window_", ""), R(this, Jn)) : e.startsWith("document_") ? document.removeEventListener(e.replace("document_", ""), R(this, Zn)) : (o = this.ref.current) == null || o.removeEventListener(e, R(this, le)));
  }
  emitCustomEvent(e, n) {
    R(this, le).call(this, n instanceof Event ? n : new CustomEvent(e, { detail: n }), e);
  }
  scroll(e, n) {
    const { scrollLeft: s, scrollTop: i, rowsHeightTotal: r, rowsHeight: o, rowHeight: a, cols: { center: { totalWidth: l, width: h } } } = this.layout, { to: c } = e;
    let { scrollLeft: u, scrollTop: d } = e;
    if (c === "up" || c === "down")
      d = i + (c === "down" ? 1 : -1) * Math.floor(o / a) * a;
    else if (c === "left" || c === "right")
      u = s + (c === "right" ? 1 : -1) * h;
    else if (c === "home")
      d = 0;
    else if (c === "end")
      d = r - o;
    else if (c === "left-begin")
      u = 0;
    else if (c === "right-end")
      u = l - h;
    else {
      const { offsetLeft: p, offsetTop: m } = e;
      typeof p == "number" && (u = s + p), typeof m == "number" && (u = i + m);
    }
    const f = {};
    return typeof u == "number" && (u = Math.max(0, Math.min(u, l - h)), u !== s && (f.scrollLeft = u)), typeof d == "number" && (d = Math.max(0, Math.min(d, r - o)), d !== i && (f.scrollTop = d)), Object.keys(f).length ? (this.setState(f, () => {
      var p;
      (p = this.options.onScroll) == null || p.call(this, f), n == null || n.call(this, !0);
    }), !0) : (n == null || n.call(this, !1), !1);
  }
  getColInfo(e) {
    if (e === void 0)
      return;
    if (typeof e == "object")
      return e;
    const { cols: n } = this.layout;
    return typeof e == "number" ? n.list[e] : n.map[e];
  }
  getRowInfo(e) {
    if (e === void 0)
      return;
    if (typeof e == "object")
      return e;
    if (e === -1 || e === "HEADER")
      return { id: "HEADER", index: -1, top: 0 };
    const { rows: n, rowsMap: s, allRows: i } = this.layout;
    return typeof e == "number" ? n[e] : s[e] || i.find((r) => r.id === e);
  }
  getCellValue(e, n) {
    var a;
    const s = typeof e == "object" ? e : this.getRowInfo(e);
    if (!s)
      return;
    const i = typeof n == "object" ? n : this.getColInfo(n);
    if (!i)
      return;
    let r = s.id === "HEADER" ? i.setting.title : (a = s.data) == null ? void 0 : a[i.name];
    const { cellValueGetter: o } = this.options;
    return o && (r = o.call(this, s, i, r)), r;
  }
  getRowInfoByIndex(e) {
    return this.layout.rows[e];
  }
  update(e = {}, n) {
    if (!R(this, Yt))
      return;
    typeof e == "function" && (n = e, e = {});
    const { dirtyType: s, state: i } = e;
    if (s === "layout")
      Q(this, Ut, void 0);
    else if (s === "options") {
      if (Q(this, Yt, void 0), !R(this, Ut))
        return;
      Q(this, Ut, void 0);
    }
    this.setState(i ?? ((r) => ({ renderCount: r.renderCount + 1 })), n);
  }
  getPointerInfo(e) {
    const n = e.target;
    if (!n || n.closest(".no-cell-event"))
      return;
    const s = n.closest(".dtable-cell");
    if (!s)
      return;
    const i = s.closest(".dtable-row");
    if (!i)
      return;
    const r = s == null ? void 0 : s.getAttribute("data-col"), o = i == null ? void 0 : i.getAttribute("data-id");
    if (!(typeof r != "string" || typeof o != "string"))
      return {
        cellElement: s,
        rowElement: i,
        colName: r,
        rowID: o,
        target: n
      };
  }
  i18n(e, n, s) {
    return Zt(R(this, Li), e, n, s, this.options.lang) ?? `{i18n:${e}}`;
  }
  getPlugin(e) {
    return this.plugins.find((n) => n.name === e);
  }
  render() {
    const e = Ft(this, So, _u).call(this), { className: n, rowHover: s, colHover: i, cellHover: r, bordered: o, striped: a, scrollbarHover: l } = this.options, h = { width: e == null ? void 0 : e.width, height: e == null ? void 0 : e.height }, c = ["dtable", n, {
      "dtable-hover-row": s,
      "dtable-hover-col": i,
      "dtable-hover-cell": r,
      "dtable-bordered": o,
      "dtable-striped": a,
      "dtable-scrolled-down": ((e == null ? void 0 : e.scrollTop) ?? 0) > 0,
      "scrollbar-hover": l
    }], u = [];
    return e && (u.push(
      Ft(this, wo, gu).call(this, e),
      Ft(this, vo, mu).call(this, e),
      Ft(this, bo, yu).call(this, e),
      Ft(this, _o, wu).call(this, e)
    ), R(this, nt).forEach((d) => {
      var p;
      const f = (p = d.onRender) == null ? void 0 : p.call(this, e);
      f && (f.style && Object.assign(h, f.style), f.className && c.push(f.className), f.children && u.push(f.children));
    })), /* @__PURE__ */ y(
      "div",
      {
        id: R(this, Kn),
        className: N(c),
        style: h,
        ref: this.ref,
        tabIndex: -1,
        children: u
      }
    );
  }
};
tn = /* @__PURE__ */ new WeakMap();
Kn = /* @__PURE__ */ new WeakMap();
Oe = /* @__PURE__ */ new WeakMap();
Yt = /* @__PURE__ */ new WeakMap();
Ne = /* @__PURE__ */ new WeakMap();
nt = /* @__PURE__ */ new WeakMap();
Ut = /* @__PURE__ */ new WeakMap();
zt = /* @__PURE__ */ new WeakMap();
Xn = /* @__PURE__ */ new WeakMap();
ci = /* @__PURE__ */ new WeakMap();
Li = /* @__PURE__ */ new WeakMap();
le = /* @__PURE__ */ new WeakMap();
Jn = /* @__PURE__ */ new WeakMap();
Zn = /* @__PURE__ */ new WeakMap();
wo = /* @__PURE__ */ new WeakSet();
gu = function(t) {
  const { header: e, cols: n, headerHeight: s, scrollLeft: i } = t;
  if (!e)
    return null;
  if (e === !0)
    return /* @__PURE__ */ y(
      Fp,
      {
        scrollLeft: i,
        height: s,
        cols: n,
        onRenderCell: R(this, fr),
        onRenderRow: R(this, ja)
      },
      "header"
    );
  const r = Array.isArray(e) ? e : [e];
  return /* @__PURE__ */ y(
    qo,
    {
      className: "dtable-header",
      style: { height: s },
      renders: r,
      generateArgs: [t],
      generatorThis: this
    },
    "header"
  );
};
vo = /* @__PURE__ */ new WeakSet();
mu = function(t) {
  const { headerHeight: e, rowsHeight: n, visibleRows: s, rowHeight: i, cols: r, scrollLeft: o, scrollTop: a } = t;
  return /* @__PURE__ */ y(
    jp,
    {
      top: e,
      height: n,
      rows: s,
      rowHeight: i,
      scrollLeft: o,
      scrollTop: a,
      cols: r,
      onRenderCell: R(this, fr),
      onRenderRow: R(this, Fa)
    },
    "rows"
  );
};
bo = /* @__PURE__ */ new WeakSet();
yu = function(t) {
  let { footer: e } = t;
  if (typeof e == "function" && (e = e.call(this, t)), !e)
    return null;
  const n = Array.isArray(e) ? e : [e];
  return /* @__PURE__ */ y(
    qo,
    {
      className: "dtable-footer",
      style: { height: t.footerHeight, top: t.rowsHeight + t.headerHeight },
      renders: n,
      generateArgs: [t],
      generatorThis: this,
      generators: t.footerGenerators
    },
    "footer"
  );
};
_o = /* @__PURE__ */ new WeakSet();
wu = function(t) {
  const e = [], { scrollLeft: n, cols: { left: { width: s }, center: { width: i, totalWidth: r } }, scrollTop: o, rowsHeight: a, rowsHeightTotal: l, footerHeight: h } = t, { scrollbarSize: c = 12, horzScrollbarPos: u } = this.options;
  return r > i && e.push(
    /* @__PURE__ */ y(
      Pl,
      {
        type: "horz",
        scrollPos: n,
        scrollSize: r,
        clientSize: i,
        onScroll: R(this, Di),
        left: s,
        bottom: (u === "inside" ? 0 : -c) + h,
        size: c,
        wheelContainer: this.ref
      },
      "horz"
    )
  ), l > a && e.push(
    /* @__PURE__ */ y(
      Pl,
      {
        type: "vert",
        scrollPos: o,
        scrollSize: l,
        clientSize: a,
        onScroll: R(this, Di),
        right: 0,
        size: c,
        top: t.headerHeight,
        wheelContainer: this.ref
      },
      "vert"
    )
  ), e.length ? e : null;
};
hi = /* @__PURE__ */ new WeakSet();
xo = function() {
  var t;
  Q(this, Oe, !1), (t = this.options.afterRender) == null || t.call(this), R(this, nt).forEach((e) => {
    var n;
    return (n = e.afterRender) == null ? void 0 : n.call(this);
  });
};
Fa = /* @__PURE__ */ new WeakMap();
ja = /* @__PURE__ */ new WeakMap();
fr = /* @__PURE__ */ new WeakMap();
Di = /* @__PURE__ */ new WeakMap();
$o = /* @__PURE__ */ new WeakMap();
ko = /* @__PURE__ */ new WeakMap();
Ua = /* @__PURE__ */ new WeakSet();
vu = function() {
  if (R(this, Yt))
    return !1;
  const e = { ...pu(), ...R(this, Ne).reduce((n, s) => {
    const { defaultOptions: i } = s;
    return i && Object.assign(n, i), n;
  }, {}), ...this.props };
  return Q(this, nt, R(this, Ne).reduce((n, s) => {
    const { when: i, options: r } = s;
    let o = e;
    return r && (o = Object.assign({ ...o }, typeof r == "function" ? r.call(this, e) : r)), (!i || i(o)) && (o !== e && Object.assign(e, o), n.push(s)), n;
  }, [])), Q(this, Yt, e), Q(this, Li, [this.options.i18n, ...this.plugins.map((n) => n.i18n)].filter(Boolean)), !0;
};
qa = /* @__PURE__ */ new WeakSet();
bu = function() {
  var T, A;
  const { plugins: t } = this;
  let e = R(this, Yt);
  const n = {
    flex: /* @__PURE__ */ y("div", { style: "flex:auto" }),
    divider: /* @__PURE__ */ y("div", { style: "width:1px;margin:var(--space);background:var(--color-border);height:50%" })
  };
  t.forEach((x) => {
    var L;
    const $ = (L = x.beforeLayout) == null ? void 0 : L.call(this, e);
    $ && (e = { ...e, ...$ }), Object.assign(n, x.footer);
  });
  let s = e.width, i = 0;
  if (typeof s == "function" && (s = s.call(this)), s === "100%") {
    const { parent: x } = this;
    if (x)
      i = x.clientWidth;
    else {
      Q(this, Oe, !0);
      return;
    }
  }
  const r = Gp(this, e, t, i), { data: o, rowKey: a = "id", rowHeight: l } = e, h = [], c = (x, $, L) => {
    var H, B;
    const W = { data: L ?? { [a]: x }, id: x, index: h.length, top: 0 };
    if (L || (W.lazy = !0), h.push(W), ((H = e.onAddRow) == null ? void 0 : H.call(this, W, $)) !== !1) {
      for (const G of t)
        if (((B = G.onAddRow) == null ? void 0 : B.call(this, W, $)) === !1)
          return;
    }
  };
  if (typeof o == "number")
    for (let x = 0; x < o; x++)
      c(`${x}`, x);
  else
    Array.isArray(o) && o.forEach((x, $) => {
      typeof x == "object" ? c(`${x[a] ?? ""}`, $, x) : c(`${x ?? ""}`, $);
    });
  let u = h;
  const d = {};
  if (e.onAddRows) {
    const x = e.onAddRows.call(this, u);
    x && (u = x);
  }
  for (const x of t) {
    const $ = (T = x.onAddRows) == null ? void 0 : T.call(this, u);
    $ && (u = $);
  }
  u.forEach((x, $) => {
    d[x.id] = x, x.index = $, x.top = x.index * l;
  });
  const { header: f, footer: p } = e, m = f ? e.headerHeight || l : 0, v = p ? e.footerHeight || l : 0;
  let w = e.height, b = 0;
  const k = u.length * l, C = m + v + k;
  if (typeof w == "function" && (w = w.call(this, C)), w === "auto")
    b = C;
  else if (typeof w == "object")
    b = Math.min(w.max, Math.max(w.min, C));
  else if (w === "100%") {
    const { parent: x } = this;
    if (x)
      b = x.clientHeight;
    else {
      b = 0, Q(this, Oe, !0);
      return;
    }
  } else
    b = w;
  const E = b - m - v, P = {
    options: e,
    allRows: h,
    width: i,
    height: b,
    rows: u,
    rowsMap: d,
    rowHeight: l,
    rowsHeight: E,
    rowsHeightTotal: k,
    header: f,
    footer: p,
    footerGenerators: n,
    headerHeight: m,
    footerHeight: v,
    cols: r
  }, M = (A = e.onLayout) == null ? void 0 : A.call(this, P);
  M && Object.assign(P, M), t.forEach((x) => {
    if (x.onLayout) {
      const $ = x.onLayout.call(this, P);
      $ && Object.assign(P, $);
    }
  }), Q(this, Ut, P);
};
So = /* @__PURE__ */ new WeakSet();
_u = function() {
  (Ft(this, Ua, vu).call(this) || !R(this, Ut)) && Ft(this, qa, bu).call(this);
  const { layout: t } = this;
  if (!t)
    return;
  const { cols: { center: e } } = t;
  let { scrollLeft: n } = this.state;
  n = Math.min(Math.max(0, e.totalWidth - e.width), n);
  let s = 0;
  e.list.forEach((p) => {
    p.left = s, s += p.realWidth, p.visible = p.left + p.realWidth >= n && p.left <= n + e.width;
  });
  const { rowsHeightTotal: i, rowsHeight: r, rows: o, rowHeight: a } = t, l = Math.min(Math.max(0, i - r), this.state.scrollTop), h = Math.floor(l / a), c = l + r, u = Math.min(o.length, Math.ceil(c / a)), d = [], { rowDataGetter: f } = this.options;
  for (let p = h; p < u; p++) {
    const m = o[p];
    m.lazy && f && (m.data = f([m.id])[0], m.lazy = !1), d.push(m);
  }
  return t.visibleRows = d, t.scrollTop = l, t.scrollLeft = n, t;
};
Va.addPlugin = uu;
Va.removePlugin = du;
function Ol(t, e) {
  e !== void 0 ? t.data.hoverCol = e : e = t.data.hoverCol;
  const { current: n } = t.ref;
  if (!n)
    return;
  const s = "dtable-col-hover";
  n.querySelectorAll(`.${s}`).forEach((i) => i.classList.remove(s)), typeof e == "string" && e.length && n.querySelectorAll(`.dtable-cell[data-col="${e}"]`).forEach((i) => i.classList.add(s));
}
const Yp = {
  name: "col-hover",
  defaultOptions: {
    colHover: !1
  },
  when: (t) => !!t.colHover,
  events: {
    mouseover(t) {
      var i;
      const { colHover: e } = this.options;
      if (!e)
        return;
      const n = (i = t.target) == null ? void 0 : i.closest(".dtable-cell");
      if (!n || e === "header" && !n.closest(".dtable-header"))
        return;
      const s = (n == null ? void 0 : n.getAttribute("data-col")) ?? !1;
      Ol(this, s);
    },
    mouseleave() {
      Ol(this, !1);
    }
  }
}, Kp = yt(Yp, { buildIn: !0 });
function Xp(t, e) {
  var o, a;
  typeof t == "boolean" && (e = t, t = void 0);
  const n = this.state.checkedRows, s = {}, { canRowCheckable: i } = this.options, r = (l, h) => {
    i && !i.call(this, l) || !!n[l] === h || (h ? n[l] = !0 : delete n[l], s[l] = h);
  };
  if (t === void 0 ? (e === void 0 && (e = !xu.call(this)), (o = this.layout) == null || o.allRows.forEach(({ id: l }) => {
    r(l, !!e);
  })) : (Array.isArray(t) || (t = [t]), t.forEach((l) => {
    r(l, e ?? !n[l]);
  })), Object.keys(s).length) {
    const l = (a = this.options.beforeCheckRows) == null ? void 0 : a.call(this, t, s, n);
    l && Object.keys(l).forEach((h) => {
      l[h] ? n[h] = !0 : delete n[h];
    }), this.setState({ checkedRows: { ...n } }, () => {
      var h;
      (h = this.options.onCheckChange) == null || h.call(this, s);
    });
  }
  return s;
}
function Jp(t) {
  return this.state.checkedRows[t] ?? !1;
}
function xu() {
  var s, i;
  const t = (s = this.layout) == null ? void 0 : s.allRows.length;
  if (!t)
    return !1;
  const e = this.getChecks().length, { canRowCheckable: n } = this.options;
  return n ? e === ((i = this.layout) == null ? void 0 : i.allRows.reduce((r, o) => r + (n.call(this, o.id) ? 1 : 0), 0)) : e === t;
}
function Zp() {
  return Object.keys(this.state.checkedRows);
}
function Qp(t) {
  const { checkable: e } = this.options;
  t === void 0 && (t = !e), e !== t && this.setState({ forceCheckable: t });
}
function Il(t) {
  return /* @__PURE__ */ y("div", { class: `checkbox-primary dtable-checkbox${t ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) });
}
const tg = {
  name: "checkable",
  defaultOptions: {
    checkable: "auto",
    checkboxRender: Il
  },
  when: (t) => t.checkable !== void 0,
  options(t) {
    const { forceCheckable: e } = this.state;
    return e !== void 0 ? t.checkable = e : t.checkable === "auto" && (t.checkable = !!t.cols.some((n) => n.checkbox)), t;
  },
  state() {
    return { checkedRows: {} };
  },
  methods: {
    toggleCheckRows: Xp,
    isRowChecked: Jp,
    isAllRowChecked: xu,
    getChecks: Zp,
    toggleCheckable: Qp
  },
  i18n: {
    zh_cn: {
      checkedCountInfo: "已选择 {selected} 项",
      totalCountInfo: "共 {total} 项"
    },
    zh_tw: {
      checkedCountInfo: "已選擇 {selected} 項",
      totalCountInfo: "共 {total} 項"
    },
    en: {
      checkedCountInfo: "Selected {selected} items",
      totalCountInfo: "Total {total} items"
    }
  },
  footer: {
    checkbox() {
      const t = this.isAllRowChecked();
      return [
        /* @__PURE__ */ y("div", { style: { paddingRight: "calc(3*var(--space))", display: "flex", alignItems: "center" }, onClick: () => this.toggleCheckRows(), children: Il(t) })
      ];
    },
    checkedInfo(t, e) {
      const n = this.getChecks(), { checkInfo: s } = this.options;
      if (s)
        return [s.call(this, n)];
      const i = n.length, r = [];
      return i && r.push(this.i18n("checkedCountInfo", { selected: i })), r.push(this.i18n("totalCountInfo", { total: e.allRows.length })), [
        /* @__PURE__ */ y("div", { children: r.join(", ") })
      ];
    }
  },
  onRenderCell(t, { row: e, col: n }) {
    var a;
    const { id: s } = e, { canRowCheckable: i } = this.options;
    if (i && !i.call(this, s))
      return t;
    const { checkbox: r } = n.setting;
    if (typeof r == "function" ? r.call(this, s) : r) {
      const l = this.isRowChecked(s), h = (a = this.options.checkboxRender) == null ? void 0 : a.call(this, l, s);
      t.unshift(h), t.push({ className: "has-checkbox" });
    }
    return t;
  },
  onRenderHeaderCell(t, { row: e, col: n }) {
    var o;
    const { id: s } = e, { checkbox: i } = n.setting;
    if (typeof i == "function" ? i.call(this, s) : i) {
      const a = this.isAllRowChecked(), l = (o = this.options.checkboxRender) == null ? void 0 : o.call(this, a, s);
      t.unshift(l), t.push({ className: "has-checkbox" });
    }
    return t;
  },
  onRenderRow({ props: t, row: e }) {
    if (this.isRowChecked(e.id))
      return { className: N(t.className, "is-checked") };
  },
  onHeaderCellClick(t) {
    const e = t.target;
    if (!e)
      return;
    const n = e.closest('input[type="checkbox"],.dtable-checkbox');
    n && (this.toggleCheckRows(n.checked), t.stopPropagation());
  },
  onRowClick(t, { rowID: e }) {
    const n = g(t.target);
    if (!n.length || n.closest("btn,a,button").length)
      return;
    (n.closest('input[type="checkbox"],.dtable-checkbox').length || this.options.checkOnClickRow) && this.toggleCheckRows(e);
  }
}, eg = yt(tg);
var $u = /* @__PURE__ */ ((t) => (t.unknown = "", t.collapsed = "collapsed", t.expanded = "expanded", t.hidden = "hidden", t.normal = "normal", t))($u || {});
function Pi(t) {
  const e = this.data.nestedMap.get(t);
  if (!e || e.state !== "")
    return e ?? { state: "normal", level: -1 };
  if (!e.parent && !e.children)
    return e.state = "normal", e;
  const n = this.state.collapsedRows, s = e.children && n && n[t];
  let i = !1, { parent: r } = e;
  for (; r; ) {
    const o = Pi.call(this, r);
    if (o.state !== "expanded") {
      i = !0;
      break;
    }
    r = o.parent;
  }
  return e.state = i ? "hidden" : s ? "collapsed" : e.children ? "expanded" : "normal", e.level = e.parent ? Pi.call(this, e.parent).level + 1 : 0, e;
}
function ng(t) {
  return t !== void 0 ? Pi.call(this, t) : this.data.nestedMap;
}
function sg(t, e) {
  let n = this.state.collapsedRows ?? {};
  const { nestedMap: s } = this.data;
  if (t === "HEADER")
    if (e === void 0 && (e = !ku.call(this)), e) {
      const i = s.entries();
      for (const [r, o] of i)
        o.state === "expanded" && (n[r] = !0);
    } else
      n = {};
  else {
    const i = Array.isArray(t) ? t : [t];
    e === void 0 && (e = !n[i[0]]), i.forEach((r) => {
      const o = s.get(r);
      e && (o != null && o.children) ? n[r] = !0 : delete n[r];
    });
  }
  this.update({
    dirtyType: "layout",
    state: { collapsedRows: { ...n } }
  }, () => {
    var i;
    (i = this.options.onNestedChange) == null || i.call(this);
  });
}
function ku() {
  const t = this.data.nestedMap.values();
  for (const e of t)
    if (e.state === "expanded")
      return !1;
  return !0;
}
function Su(t, e = 0, n, s = 0) {
  var i;
  n || (n = [...t.keys()]);
  for (const r of n) {
    const o = t.get(r);
    o && (o.level === s && (o.order = e++), (i = o.children) != null && i.length && (e = Su(t, e, o.children, s + 1)));
  }
  return e;
}
function Cu(t, e, n, s) {
  const i = t.getNestedRowInfo(e);
  return !i || i.state === "" || !i.children || i.children.forEach((r) => {
    s[r] = n, Cu(t, r, n, s);
  }), i;
}
function Eu(t, e, n, s, i) {
  var a;
  const r = t.getNestedRowInfo(e);
  if (!r || r.state === "")
    return;
  ((a = r.children) == null ? void 0 : a.every((l) => {
    const h = !!(s[l] !== void 0 ? s[l] : i[l]);
    return n === h;
  })) && (s[e] = n), r.parent && Eu(t, r.parent, n, s, i);
}
const ig = {
  name: "nested",
  defaultOptions: {
    nested: "auto",
    nestedParentKey: "parent",
    asParentKey: "asParent",
    nestedIndent: 20,
    canSortTo(t, e) {
      const { nestedMap: n } = this.data, s = n.get(t.id), i = n.get(e.id);
      return (s == null ? void 0 : s.parent) === (i == null ? void 0 : i.parent);
    },
    beforeCheckRows(t, e, n) {
      if (!this.options.checkable || !(t != null && t.length))
        return;
      const s = {};
      return Object.entries(e).forEach(([i, r]) => {
        const o = Cu(this, i, r, s);
        o != null && o.parent && Eu(this, o.parent, r, s, n);
      }), s;
    }
  },
  options(t) {
    return t.nested === "auto" && (t.nested = !!t.cols.some((e) => e.nestedToggle)), t;
  },
  when: (t) => !!t.nested,
  data() {
    return { nestedMap: /* @__PURE__ */ new Map() };
  },
  methods: {
    getNestedInfo: ng,
    toggleRow: sg,
    isAllCollapsed: ku,
    getNestedRowInfo: Pi
  },
  beforeLayout() {
    var t;
    (t = this.data.nestedMap) == null || t.clear();
  },
  onAddRow(t) {
    var i, r;
    const { nestedMap: e } = this.data, n = String((i = t.data) == null ? void 0 : i[this.options.nestedParentKey ?? "parent"]), s = e.get(t.id) ?? {
      state: "",
      level: 0
    };
    if (s.parent = n === "0" ? void 0 : n, (r = t.data) != null && r[this.options.asParentKey ?? "asParent"] && (s.children = []), e.set(t.id, s), n) {
      let o = e.get(n);
      o || (o = {
        state: "",
        level: 0
      }, e.set(n, o)), o.children || (o.children = []), o.children.push(t.id);
    }
  },
  onAddRows(t) {
    return t = t.filter(
      (e) => this.getNestedRowInfo(e.id).state !== "hidden"
      /* hidden */
    ), Su(this.data.nestedMap), t.sort((e, n) => {
      const s = this.getNestedRowInfo(e.id), i = this.getNestedRowInfo(n.id), r = (s.order ?? 0) - (i.order ?? 0);
      return r === 0 ? e.index - n.index : r;
    }), t;
  },
  onRenderCell(t, { col: e, row: n }) {
    var a;
    const { id: s, data: i } = n, { nestedToggle: r } = e.setting, o = this.getNestedRowInfo(s);
    if (r && (o.children || o.parent) && t.unshift(((a = this.options.onRenderNestedToggle) == null ? void 0 : a.call(this, o, s, e, i)) ?? /* @__PURE__ */ y("a", { role: "button", className: `dtable-nested-toggle state${o.children ? "" : " is-no-child"}`, children: /* @__PURE__ */ y("span", { className: "toggle-icon" }) })), o.level) {
      let { nestedIndent: l = r } = e.setting;
      l && (l === !0 && (l = this.options.nestedIndent ?? 12), t.unshift(/* @__PURE__ */ y("div", { className: "dtable-nested-indent", style: { width: l * o.level + "px" } })));
    }
    return t;
  },
  onRenderHeaderCell(t, { row: e, col: n }) {
    var i;
    const { id: s } = e;
    return n.setting.nestedToggle && t.unshift(((i = this.options.onRenderNestedToggle) == null ? void 0 : i.call(this, void 0, s, n, void 0)) ?? /* @__PURE__ */ y("a", { type: "button", className: "dtable-nested-toggle state", children: /* @__PURE__ */ y("span", { className: "toggle-icon" }) })), t;
  },
  onRenderRow({ props: t, row: e }) {
    const n = this.getNestedRowInfo(e.id);
    return {
      className: N(t.className, `is-${n.state}`),
      "data-parent": n.parent
    };
  },
  onRenderHeaderRow({ props: t }) {
    return t.className = N(t.className, `is-${this.isAllCollapsed() ? "collapsed" : "expanded"}`), t;
  },
  onHeaderCellClick(t) {
    const e = t.target;
    if (!(!e || !e.closest(".dtable-nested-toggle")))
      return this.toggleRow("HEADER"), !0;
  },
  onCellClick(t, { rowID: e }) {
    const n = t.target;
    if (!(!n || !this.getNestedRowInfo(e).children || !n.closest(".dtable-nested-toggle")))
      return this.toggleRow(e), !0;
  }
}, rg = yt(ig);
function Ga(t, e, n, s) {
  if (typeof t == "function" && (t = t(e)), typeof t == "string" && t.length && (t = { url: t }), !t)
    return n;
  const { url: i, ...r } = t, { setting: o } = e.col, a = {};
  return o && Object.keys(o).forEach((l) => {
    l.startsWith("data-") && (a[l] = o[l]);
  }), /* @__PURE__ */ y("a", { href: X(i, e.row.data), ...s, ...r, ...a, children: n });
}
function Ya(t, e, n) {
  var s;
  if (t != null)
    return n = n ?? ((s = e.row.data) == null ? void 0 : s[e.col.name]), typeof t == "function" ? t(n, e) : X(t, n);
}
function Mu(t, e, n, s) {
  var i;
  return n = n ?? ((i = e.row.data) == null ? void 0 : i[e.col.name]), t === !1 ? n : (t === !0 && (t = "[yyyy-]MM-dd hh:mm"), typeof t == "function" && (t = t(n, e)), eo(n, t, s ?? n));
}
function Tu(t, e) {
  const { link: n } = e.col.setting, s = Ga(n, e, t[0]);
  return s && (t[0] = s), t;
}
function Ru(t, e) {
  const { format: n } = e.col.setting;
  return n && (t[0] = Ya(n, e, t[0])), t;
}
function Au(t, e) {
  const { map: n } = e.col.setting;
  return typeof n == "function" ? t[0] = n(t[0], e) : typeof n == "object" && n && (t[0] = n[t[0]] ?? t[0]), t;
}
function Nu(t, e, n = "[yyyy-]MM-dd hh:mm") {
  const { formatDate: s = n, invalidDate: i } = e.col.setting;
  return t[0] = Mu(s, e, t[0], i), t;
}
function Co(t, e, n = !1) {
  const { html: s = n } = e.col.setting;
  if (s === !1)
    return t;
  const i = t[0], r = s === !0 ? i : Ya(s, e, i);
  return t[0] = {
    html: r
  }, t;
}
const og = {
  name: "rich",
  colTypes: {
    html: {
      onRenderCell(t, e) {
        return Co(t, e, !0);
      }
    },
    progress: {
      align: "center",
      onRenderCell(t, { col: e }) {
        const { circleSize: n = 24, circleBorderSize: s = 1, circleBgColor: i = "var(--color-border)", circleColor: r = "var(--color-success-500)" } = e.setting, o = (n - s) / 2, a = n / 2, l = t[0];
        return t[0] = /* @__PURE__ */ y("svg", { width: n, height: n, children: [
          /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, "stroke-width": s, stroke: i, fill: "transparent" }),
          /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, "stroke-width": s, stroke: r, fill: "transparent", "stroke-linecap": "round", "stroke-dasharray": Math.PI * o * 2, "stroke-dashoffset": Math.PI * o * 2 * (100 - l) / 100, style: { transformOrigin: "center", transform: "rotate(-90deg)" } }),
          /* @__PURE__ */ y("text", { x: a, y: a + s, "dominant-baseline": "middle", "text-anchor": "middle", style: { fontSize: `${o}px` }, children: Math.round(l) })
        ] }), t;
      }
    },
    datetime: {
      formatDate: "[yyyy-]MM-dd hh:mm"
    },
    date: {
      formatDate: "yyyy-MM-dd"
    },
    time: {
      formatDate: "hh:mm"
    }
  },
  onRenderCell(t, e) {
    const { formatDate: n, html: s, hint: i } = e.col.setting;
    if (n && (t = Nu(t, e, n)), t = Au(t, e), t = Ru(t, e), s ? t = Co(t, e) : t = Tu(t, e), i) {
      let r = t[0];
      typeof i == "function" ? r = i.call(this, e) : typeof i == "string" && (r = X(i, e.row.data)), t.push({ attrs: { title: r } });
    }
    return t;
  }
}, ag = yt(og, { buildIn: !0 });
function Rr(t, { row: e, col: n }) {
  const { data: s } = e, i = s ? s[n.name] : void 0;
  if (!(i != null && i.length))
    return t;
  const { avatarClass: r = "rounded-full", avatarKey: o = `${n.name}Avatar`, avatarProps: a, avatarCodeKey: l, avatarNameKey: h = `${n.name}Name` } = n.setting, c = (s ? s[h] : i) || t[0], u = {
    size: "xs",
    className: N(r, a == null ? void 0 : a.className, "flex-none"),
    src: s ? s[o] : void 0,
    text: c,
    code: l ? s ? s[l] : void 0 : i,
    ...a
  };
  if (t[0] = /* @__PURE__ */ y(rh, { ...u }), n.type === "avatarBtn") {
    const { avatarBtnProps: d } = n.setting, f = typeof d == "function" ? d(n, e) : d;
    t[0] = /* @__PURE__ */ y("button", { type: "button", className: "btn btn-avatar", ...f, children: [
      t[0],
      /* @__PURE__ */ y("div", { children: c })
    ] });
  } else
    n.type === "avatarName" && (t[0] = /* @__PURE__ */ y("div", { className: "flex items-center gap-1", children: [
      t[0],
      /* @__PURE__ */ y("span", { children: c })
    ] }));
  return t;
}
const lg = {
  name: "avatar",
  colTypes: {
    avatar: {
      onRenderCell: Rr
    },
    avatarBtn: {
      onRenderCell: Rr
    },
    avatarName: {
      onRenderCell: Rr
    }
  }
}, cg = yt(lg, { buildIn: !0 }), hg = {
  name: "sort-type",
  onRenderHeaderCell(t, e) {
    const { col: n } = e, { sortType: s } = n.setting;
    if (s) {
      const i = s === !0 ? "none" : s;
      t.push(
        /* @__PURE__ */ y("div", { className: `dtable-sort dtable-sort-${i}` }),
        { outer: !0, attrs: { "data-sort": i } }
      );
      let { sortLink: r = this.options.sortLink } = n.setting;
      if (r) {
        const o = i === "asc" ? "desc" : "asc";
        typeof r == "function" && (r = r.call(this, n, o, i)), typeof r == "string" && (r = { url: r });
        const { url: a, ...l } = r;
        t[0] = /* @__PURE__ */ y("a", { href: X(a, { ...n.setting, sortType: o }), ...l, children: t[0] });
      }
    }
    return t;
  }
}, ug = yt(hg, { buildIn: !0 }), Ar = (t) => {
  t.length !== 1 && t.forEach((e, n) => {
    !n || e.setting.border || e.setting.group === t[n - 1].setting.group || (e.setting.border = "left");
  });
}, dg = {
  name: "group",
  defaultOptions: {
    groupDivider: !0
  },
  when: (t) => !!t.groupDivider,
  onLayout(t) {
    if (!this.options.groupDivider)
      return;
    const { cols: e } = t;
    Ar(e.left.list), Ar(e.center.list), Ar(e.right.list);
  }
}, fg = yt(dg), pg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  NestedRowState: $u,
  avatar: cg,
  checkable: eg,
  colHover: Kp,
  group: fg,
  nested: rg,
  renderDatetime: Mu,
  renderDatetimeCell: Nu,
  renderFormat: Ya,
  renderFormatCell: Ru,
  renderHtmlCell: Co,
  renderLink: Ga,
  renderLinkCell: Tu,
  renderMapCell: Au,
  rich: ag,
  sortType: ug
}, Symbol.toStringTag, { value: "Module" }));
class Rs extends J {
}
Rs.NAME = "DTable";
Rs.Component = Va;
Rs.definePlugin = yt;
Rs.removePlugin = du;
Rs.plugins = pg;
var Lu = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Hl = (t, e, n) => (Lu(t, e, "read from private field"), n ? n.call(t) : e.get(t)), gg = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Bl = (t, e, n, s) => (Lu(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), en;
const mg = "nav", Eo = '[data-toggle="tab"]', yg = "active";
class Du extends lt {
  constructor() {
    super(...arguments), gg(this, en, 0);
  }
  active(e) {
    const n = this.$element, s = n.find(Eo);
    let i = e ? g(e).first() : s.filter(`.${yg}`);
    if (!i.length && (i = n.find(Eo).first(), !i.length))
      return;
    s.removeClass("active"), i.addClass("active");
    const r = i.attr("href") || i.data("target"), o = g(r);
    o.length && (o.parent().children(".tab-pane").removeClass("active in"), o.addClass("active"), Hl(this, en) && clearTimeout(Hl(this, en)), Bl(this, en, setTimeout(() => {
      o.addClass("in"), Bl(this, en, 0);
    }, 10)));
  }
}
en = /* @__PURE__ */ new WeakMap();
Du.NAME = "Tabs";
g(document).on("click.tabs.zui", Eo, (t) => {
  t.preventDefault();
  const e = g(t.target), n = e.closest(`.${mg}`);
  n.length && Du.ensure(n).active(e);
});
var wg = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, js = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Wi = (t, e, n) => (wg(t, e, "access private method"), n), Mo, Pu, To, Wu, Ka, Ou, Xa, Iu;
class vg extends lt {
  constructor() {
    super(...arguments), js(this, Mo), js(this, To), js(this, Ka), js(this, Xa);
  }
  init() {
    g(this.element).on("submit", this.onSubmit.bind(this)).on("input mousedown change", this.onInput.bind(this));
  }
  enable(e = !0) {
    g(this.element).toggleClass("loading", !e);
  }
  disable() {
    this.enable(!1);
  }
  onInput(e) {
    const n = g(e.target).closest(".has-error");
    n.length && (n.removeClass("has-error"), n.closest(".form-group").find(`#${n.attr("id")}Tip`).remove());
  }
  onSubmit(e) {
    var o;
    e.preventDefault();
    const { element: n } = this, s = g.extend({}, this.options);
    this.emit("before", e, n, s);
    const i = () => {
      this.disable(), Wi(this, To, Wu).call(this, Wi(this, Mo, Pu).call(this)).finally(() => {
        this.enable();
      });
    }, r = (o = s.beforeSubmit) == null ? void 0 : o.call(s, e, n, s);
    if (r !== !1) {
      if (r instanceof Promise) {
        r.then((a) => a && i());
        return;
      }
      i();
    }
  }
  submit() {
    this.element.submit();
  }
  reset() {
    this.element.reset();
  }
}
Mo = /* @__PURE__ */ new WeakSet();
Pu = function() {
  const { element: t, options: e } = this;
  let n = new FormData(t), { submitEmptySelectValue: s = "" } = e;
  s !== !1 && (typeof s != "boolean" && (s = ""), g(t).find("select").each((r, o) => {
    const l = g(o).attr("name");
    n.has(l) || n.append(l, typeof s == "object" ? s[l] : s);
  }));
  const { beforeSend: i } = e;
  if (i) {
    const r = i(n);
    r instanceof FormData && (n = r);
  }
  return this.emit("send", n), n;
};
To = /* @__PURE__ */ new WeakSet();
Wu = async function(t) {
  var o, a;
  const { element: e, options: n } = this;
  let s, i, r;
  try {
    const l = await fetch(n.url || e.action, {
      method: e.method || "POST",
      body: t,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    });
    i = await l.text(), l.ok ? (r = JSON.parse(i), (!r || typeof r != "object") && (s = new Error("Invalid json format"))) : s = new Error(l.statusText);
  } catch (l) {
    s = l;
  }
  s ? (this.emit("error", s, i), (o = n.onError) == null || o.call(n, s, i)) : Wi(this, Xa, Iu).call(this, r), this.emit("complete", r, s), (a = n.onComplete) == null || a.call(n, r, s);
};
Ka = /* @__PURE__ */ new WeakSet();
Ou = function(t) {
  var n;
  let e;
  Object.entries(t).forEach(([s, i]) => {
    Array.isArray(i) && (i = i.join(""));
    const r = document.getElementById(s), o = r ? g(r) : g(this.element).find(`[name="${s}"]`);
    if (!o.length)
      return;
    o.addClass("has-error");
    const a = o.closest(".form-group,.form-batch-control");
    if (a.length) {
      const l = document.getElementById(`${s}Tip`);
      let h = l ? g(l) : null;
      h || (h = g(`<div class="form-tip ajax-form-tip text-danger" id="${s}Tip"></div>`).appendTo(a)), h.empty().text(i);
    }
    e || (e = o);
  }), e && ((n = e[0]) == null || n.focus());
};
Xa = /* @__PURE__ */ new WeakSet();
Iu = function(t) {
  var o, a;
  const { options: e } = this, { message: n } = t;
  if (t.result === "success") {
    if (this.emit("success", t), ((o = e.onSuccess) == null ? void 0 : o.call(e, t)) === !1)
      return;
    typeof n == "string" && n.length && g(document).trigger("zui.messager.show", { content: n, type: "success" });
  } else {
    if (this.emit("fail", t), ((a = e.onFail) == null ? void 0 : a.call(e, t)) === !1)
      return;
    n && (typeof n == "string" && n.length ? g(document).trigger("zui.messager.show", { content: n, type: "danger" }) : typeof n == "object" && Wi(this, Ka, Ou).call(this, n));
  }
  const s = t.closeModal || e.closeModal;
  s && g(this.element).trigger("to-hide.modal.zui", { target: typeof s == "string" ? s : void 0 });
  const i = t.callback || e.callback;
  if (typeof i == "string") {
    const l = i.indexOf("("), h = (l > 0 ? i.substring(0, l) : i).split(".");
    let c = window, u = h[0];
    h.length > 1 && (u = h[1], h[0] === "top" ? c = window.top : h[0] === "parent" ? c = window.parent : c = window[h[0]]);
    const d = c == null ? void 0 : c[u];
    if (typeof d == "function") {
      let f = [];
      return l > 0 && i[i.length - 1] == ")" ? f = JSON.parse("[" + i.substring(l + 1, i.length - 1) + "]") : f.push(t), d.apply(this, f);
    }
  } else
    i && typeof i == "object" && (i.target ? window[i.target] : window)[i.name].apply(this, Array.isArray(i.params) ? i.params : [i.params]);
  const r = t.load || e.load || t.locate;
  r && g(this.element).trigger("locate.zt", r);
};
vg.NAME = "ajaxform";
function bg(t, e) {
  var o, a, l, h;
  const { message: n } = t;
  if (t.result === "success") {
    if (((o = e.onSuccess) == null ? void 0 : o.call(e, t)) === !1)
      return;
    typeof n == "string" && n.length && ((a = e.onMessage) == null || a.call(e, n, t));
  } else {
    if (((l = e.onFail) == null ? void 0 : l.call(e, t)) === !1)
      return;
    n && ((h = e.onMessage) == null || h.call(e, n, t));
  }
  const s = t.closeModal || e.closeModal;
  s && g(e.element || document).trigger("to-hide.modal.zui", { target: typeof s == "string" ? s : void 0 });
  const i = t.callback || e.callback;
  if (typeof i == "string") {
    const c = i.indexOf("("), u = (c > 0 ? i.substring(0, c) : i).split(".");
    let d = window, f = u[0];
    u.length > 1 && (f = u[1], u[0] === "top" ? d = window.top : u[0] === "parent" ? d = window.parent : d = window[u[0]]);
    const p = d == null ? void 0 : d[f];
    if (typeof p == "function") {
      let m = [];
      return c > 0 && i[i.length - 1] == ")" ? m = JSON.parse("[" + i.substring(c + 1, i.length - 1) + "]") : m.push(t), p.apply(this, m);
    }
  } else
    i && typeof i == "object" && (i.target ? window[i.target] : window)[i.name].apply(this, Array.isArray(i.params) ? i.params : [i.params]);
  const r = t.load || e.load || t.locate;
  r && g(e.element || document).trigger("locate.zt", r);
}
async function Ja(t) {
  var h, c;
  if (t.confirm)
    return await or.confirm(t.confirm) ? Ja({ ...t, confirm: void 0 }) : [void 0, new Error("canceled")];
  if (t.beforeSubmit && await t.beforeSubmit(t) === !1)
    return [void 0, new Error("canceled")];
  const { loadingClass: e, element: n } = t;
  n && e && g(n).addClass(e);
  const { data: s } = t;
  let i;
  if (s instanceof FormData)
    i = s;
  else if (s) {
    i = new FormData();
    for (const [u, d] of Object.entries(s))
      if (Array.isArray(d)) {
        for (const f of d)
          i.append(u, f);
        continue;
      } else
        i.append(u, d);
  }
  const { beforeSend: r } = t;
  if (r) {
    const u = r(i);
    u instanceof FormData && (i = u);
  }
  let o, a, l;
  try {
    const u = await fetch(t.url, {
      method: t.method || "POST",
      body: i,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        ...t.headers
      }
    });
    a = await u.text(), u.ok ? (l = JSON.parse(a), (!l || typeof l != "object") && (o = new Error("Invalid json format"))) : o = new Error(u.statusText);
  } catch (u) {
    o = u;
  }
  return o ? (h = t.onError) == null || h.call(t, o, a) : bg(l, t), (c = t.onComplete) == null || c.call(t, l, o), n && e && g(n).removeClass(e), [l, o];
}
g.extend(g, { ajaxSubmit: Ja });
g(document).on("click.ajaxSubmit.zui", ".ajax-submit", function(t) {
  t.preventDefault();
  const e = g(this), n = e.data();
  !n.url && e.is("a") && (n.url = e.attr("href") || ""), n.url && (n.element = this[0], Ja(n));
});
function _g(t) {
  const [e, n] = t.split(":"), s = e[0] === "-" ? { name: e.substring(1), disabled: !0 } : { name: e };
  return n != null && n.length && (s.type = "dropdown", s.items = n.split(",").reduce((i, r) => (r = r.trim(), r.length && i.push(r[0] === "-" ? { name: r.substring(1), disabled: !0 } : { name: r }), i), [])), s;
}
const xg = (t, e) => {
  var n;
  return t.url && (t.url = X(t.url, e.row.data)), (n = t.dropdown) != null && n.items && (t.dropdown.items = t.dropdown.items.map((s) => (s.url && (s.url = X(s.url, e.row.data)), s))), t;
}, zl = (t) => t ? (typeof t == "string" && (t = t.split("|")), t.map((e) => typeof e == "string" ? _g(e) : e).filter(Boolean)) : [], $g = {
  name: "actions",
  colTypes: {
    actions: {
      onRenderCell(t, e) {
        var c;
        const { row: n, col: s } = e, i = zl(((c = n.data) == null ? void 0 : c[s.name]) || s.setting.actions);
        if (!i.length)
          return t;
        const { actionsSetting: r, actionsMap: o, actionsCreator: a = this.options.actionsCreator, actionItemCreator: l = this.options.actionItemCreator || xg } = s.setting, h = {
          items: (a == null ? void 0 : a(e)) ?? i.map((u) => {
            const { name: d, items: f, ...p } = u;
            if (o && d && (Object.assign(p, o[d], { ...p }), typeof p.buildProps == "function")) {
              const { buildProps: m } = p;
              delete p.buildProps, Object.assign(p, m(t, e));
            }
            if (f && p.type === "dropdown") {
              const { dropdown: m = { placement: "bottom-end" } } = p;
              m.menu = {
                className: "menu-dtable-actions",
                items: f.reduce((v, w) => {
                  const b = typeof w == "string" ? { name: w } : { ...w };
                  return b != null && b.name && (o && "name" in b && Object.assign(b, o[b.name], { ...b }), v.push(b)), v;
                }, [])
              }, p.dropdown = m;
            }
            return l ? l(p, e) : p;
          }),
          btnProps: { size: "sm", className: "text-primary" },
          ...r
        };
        return t[0] = /* @__PURE__ */ y(dt, { ...h }), t;
      }
    }
  },
  beforeLayout(t) {
    !Array.isArray(t.data) || !t.data.length || t.cols.forEach((e, n) => {
      if (e.type !== "actions" || e.width)
        return;
      const { actionsMap: s = {} } = e, r = zl(t.data[0][e.name]).reduce((o, a) => {
        const l = a.name ? s[a.name] : null;
        return l && l.type === "dropdown" && l.caret && !l.text ? o + 16 : o + 24;
      }, 24);
      t.cols[n] = {
        ...e,
        width: r
      };
    });
  }
}, kg = yt($g), Sg = {
  name: "toolbar",
  footer: {
    toolbar() {
      const { footToolbar: t, showToolbarOnChecked: e } = this.options;
      return e && !this.getChecks().length ? [] : [t ? /* @__PURE__ */ y(dt, { gap: 2, ...t }) : null];
    }
  }
}, Cg = yt(Sg), Eg = {
  name: "pager",
  footer: {
    pager() {
      const { footPager: t } = this.options;
      return [t ? /* @__PURE__ */ y(Ms, { ...t }) : null];
    }
  }
}, Mg = yt(Eg);
function Fl(t, e, n) {
  if (n) {
    if (typeof n == "object") {
      const s = n[t];
      return typeof s == "string" ? s : typeof s == "object" && s ? s.realname : "";
    }
    if (typeof n == "function")
      return n(t, e);
  }
}
const Tg = {
  name: "zentao",
  plugins: ["group", "checkable", "nested", kg, Cg, Mg],
  defaultOptions: {
    colHover: !1,
    rowHeight: 36,
    striped: !1,
    responsive: !0,
    checkable: "auto",
    nested: "auto",
    width: "100%",
    footer: void 0,
    showToolbarOnChecked: !0,
    checkOnClickRow: !0,
    groupDivider: !0,
    height(t) {
      return Math.min(t, window.innerHeight - 1 - (g("#header").outerHeight() || 0) - (g("#mainMenu").outerHeight() || 0) - (g("#mainNavbar").outerHeight() || 0));
    }
  },
  options(t) {
    const { checkable: e, footToolbar: n, footPager: s, footer: i, sortLink: r } = t;
    if (i === void 0) {
      const o = [];
      e && o.push("checkbox"), n && (o.push("toolbar"), n.btnProps = Object.assign({
        type: "primary",
        size: "sm"
      }, n.btnProps)), e && o.push("checkedInfo"), s && o.push("flex", "pager"), o.length && (t.footer = o);
    }
    return typeof r == "string" && (t.sortLink = { url: r, "data-load": "table" }), t;
  },
  i18n: {
    zh_cn: {
      unassigned: "未指派"
    },
    zh_tw: {
      unassigned: "未指派"
    },
    en: {
      unassigned: "Unassigned"
    }
  },
  colTypes: {
    id: {
      width: 60,
      // 默认宽度
      fixed: "left",
      // 固定左侧显示
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    checkID: {
      width: 80,
      // 默认宽度
      fixed: "left",
      // 固定左侧示
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      checkbox: !0
      // 显示 Checkbox
    },
    title: {
      width: 0.44,
      // 默认宽度 44%
      fixed: "left",
      // 固定左侧显示
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      flex: 1
      // 启用弹性设置
    },
    shortTitle: {
      width: 0.2,
      // 默认宽度 20%
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      fixed: "left",
      // 固定左侧显示
      checkbox: !0,
      // 显示 Checkbox
      flex: 1
      // 启用弹性设置
    },
    nestedTitle: {
      width: 0.5,
      // 默认宽度 50%
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      fixed: "left",
      // 固定左侧显示
      flex: 1,
      // 启用弹性设置
      nestedToggle: !0,
      // 显示折叠展开按钮
      nestedIndent: 20
      // 子项缩进 20px
    },
    shortNestedTitle: {
      width: 0.33,
      // 默认宽度 33%
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      fixed: "left",
      // 固定左侧显示
      flex: 1,
      // 启用弹性设置
      nestedToggle: !0,
      // 显示折叠展开按钮
      nestedIndent: 20
      // 子项缩进 20px
    },
    status: {
      width: 80,
      align: "center",
      sortType: !0,
      onRenderCell(t, { col: e, row: n }) {
        var o, a;
        const s = (o = n.data) == null ? void 0 : o[e.name];
        let i, r;
        return typeof s == "string" ? (i = s, r = (a = e.setting.statusMap) == null ? void 0 : a[s]) : typeof s == "object" && s && ({ name: i, label: r } = s), t[0] = /* @__PURE__ */ _("span", { class: `${e.setting.statusClassPrefix ?? "status-"}${i}` }, r ?? i), t;
      }
    },
    user: {
      width: 80,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0,
      // 启用排序
      onRenderCell(t, { col: e, row: n, value: s }) {
        const { userMap: i = this.options.userMap } = e.setting, r = Fl(s, n, i);
        return r !== void 0 && (t[0] = r), t;
      }
    },
    assign: {
      width: 108,
      // 默认宽度
      align: "left",
      // 居左对齐
      sortType: !0,
      // 启用排序
      cellClass: "px-1.5",
      // 单元格类，减少边距
      onRenderCell(t, e) {
        const { col: n, row: s, value: i } = e, { userMap: r = this.options.userMap, currentUser: o, assignLink: a, unassignedText: l = this.i18n("unassigned") } = n.setting, h = !i, c = h ? l : Fl(i, s, r) ?? i;
        return t[0] = Ga(a, e, [
          /* @__PURE__ */ _("i", { className: "icon icon-hand-right" }),
          /* @__PURE__ */ _("span", null, c)
        ], {
          "data-toggle": "modal",
          className: `dtable-assign-btn${o === i ? " is-me" : ""}${h ? " is-unassigned" : ""}`
        }), t;
      }
    },
    avatar: {
      width: 44,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    avatarName: {
      width: 108,
      // 默认宽度
      align: "left",
      // 居左对齐
      sortType: !0
      // 启用排序
    },
    avatarBtn: {
      width: 108,
      // 默认宽度
      align: "left",
      // 居左对齐
      sortType: !0
      // 启用排序
    },
    category: {
      width: 80,
      // 默认宽度
      align: "center",
      // 居中对齐
      flex: 1
      // 启用弹性设置
    },
    desc: {
      width: 160,
      // 默认宽度
      align: "left",
      // 居左对齐
      flex: 1
      // 启用弹性设置
    },
    text: {
      width: 136,
      // 默认宽度
      align: "left",
      // 居左对齐
      flex: 1
      // 启用弹性设置
    },
    icon: {
      width: 52,
      // 默认宽度
      align: "center",
      // 居中对齐
      cellClass: "px-1",
      onRenderCell(t, { row: e, col: n, value: s }) {
        const { iconRender: i } = n.setting;
        let r = {};
        if (typeof i == "function") {
          const o = i(s, e);
          typeof o == "string" ? s = o : typeof o == "object" && o && ({ icon: s, ...r } = o);
        }
        return typeof s == "string" ? r.className = N(s, r.className) : typeof s == "object" && s && Object.assign(r, s), t[0] = /* @__PURE__ */ _("i", { ...r }), t;
      }
    },
    pri: {
      width: 68,
      // 默认宽度
      align: "center",
      // 居中对齐
      cellClass: "px-1",
      // 减少左右内边距
      sortType: !0,
      // 启用排序
      onRenderCell(t, { value: e }) {
        return t[0] = /* @__PURE__ */ _("span", { className: `pri-${e}` }, e), t;
      }
    },
    severity: {
      width: 92,
      // 默认宽度
      align: "center",
      // 居中对齐
      cellClass: "px-1",
      // 减少左右内边距
      sortType: !0,
      // 启用排序
      onRenderCell(t) {
        const e = t[0], n = `${Number.parseInt(e)}` != `${e}`;
        return t[0] = /* @__PURE__ */ _("span", { className: `severity${n ? " severity-label" : ""}`, "data-severity": e }), t;
      }
    },
    burn: {
      width: 88,
      // 默认宽度
      align: "center",
      // 居中对齐
      onRenderCell(t, { col: e }) {
        const n = t[0];
        if (!n)
          return t;
        const { burn: s } = e.setting, i = {
          data: n,
          className: "border-b",
          width: 64,
          height: 24,
          responsive: !1,
          ...s
        };
        return t[0] = /* @__PURE__ */ _(Na, { ...i }), t;
      }
    },
    date: {
      width: 96,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    time: {
      width: 64,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    datetime: {
      width: 128,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    progress: {
      width: 64,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    money: {
      width: 96,
      // 默认宽度
      align: "right",
      // 居右对齐
      sortType: !0
      // 启用排序
    },
    count: {
      width: 92,
      // 默认宽度
      align: "right",
      // 居右对齐
      sortType: !0
      // 启用排序
    },
    number: {
      width: 64,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0
      // 启用排序
    },
    percent: {
      width: 64,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0,
      // 启用排序
      format(t) {
        return typeof t == "string" && t.endsWith("%") ? t : `${t}%`;
      }
    },
    actions: {
      minWidth: 120,
      // 最小列宽
      align: "left",
      // 居左对齐
      fixed: "right"
      // 固定在右侧
    }
  },
  onRenderCell(t, { row: e, col: n, value: s }) {
    const { iconRender: i } = n.setting;
    if (typeof i == "function" && n.type !== "icon") {
      const r = i(s, e);
      r && t.unshift(typeof r == "object" ? /* @__PURE__ */ _("i", { ...r }) : /* @__PURE__ */ _("i", { className: r }));
    }
    return t;
  },
  onRender() {
    const { customCols: t } = this.options;
    if (t)
      return { children: /* @__PURE__ */ _("div", { className: "absolute gap-3 m-1.5 top-0 right-0 z-20 row" }, /* @__PURE__ */ _("div", { class: "w-px border-l my-1" }), /* @__PURE__ */ _(Lt, { type: "ghost", icon: "cog-outline", "data-toggle": "modal", square: !0, size: "sm", ...t })) };
  }
}, am = yt(Tg, { buildIn: !0 });
g(() => {
  g(".disabled, [disabled]").on("click", (t) => {
    t.preventDefault(), t.stopImmediatePropagation();
  });
});
function Hu(t) {
  t = t || location.search, t[0] === "?" && (t = t.substring(1));
  try {
    const e = JSON.parse('{"' + decodeURI(t).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
    return Object.keys(e).forEach((n) => {
      e[n] = decodeURIComponent(e[n]);
    }), e;
  } catch {
    return {};
  }
}
function Rg(t) {
  if (!t)
    return { url: t };
  const { config: e } = window;
  if (/^https?:\/\//.test(t)) {
    const l = window.location.origin;
    if (!t.includes(l))
      return { external: !0, url: t };
    t = t.substring((l + e.webRoot).length);
  }
  const n = t.split("#"), s = n[0].split("?"), i = s[1], r = i ? Hu(i) : {};
  let o = s[0];
  const a = {
    url: t,
    isOnlyBody: r.onlybody === "yes",
    vars: [],
    hash: n[1] || "",
    params: r,
    tid: r.tid || ""
  };
  if (e.requestType === "GET") {
    a.moduleName = r[e.moduleVar] || "index", a.methodName = r[e.methodVar] || "index", a.viewType = r[e.viewVar] || e.defaultView;
    for (const l in r)
      l !== e.moduleVar && l !== e.methodVar && l !== e.viewVar && l !== "onlybody" && l !== "tid" && a.vars.push([l, r[l]]);
  } else {
    let l = o.lastIndexOf("/");
    l === o.length - 1 && (o = o.substring(0, l), l = o.lastIndexOf("/")), l >= 0 && (o = o.substring(l + 1));
    const h = o.lastIndexOf(".");
    h >= 0 ? (a.viewType = o.substring(h + 1), o = o.substring(0, h)) : a.viewType = e.defaultView;
    const c = o.split(e.requestFix);
    if (a.moduleName = c[0] || "index", a.methodName = c[1] || "index", c.length > 2)
      for (let u = 2; u < c.length; u++)
        a.vars.push(["", c[u]]), r["$" + (u - 1)] = c[u];
  }
  return a;
}
function Bu(t, e, n, s, i, r) {
  if (typeof t == "object")
    return Bu(t.moduleName, t.methodName, t.vars, t.viewType, t.hash, t.params);
  const o = window.config;
  if (s || (s = o.defaultView), n) {
    typeof n == "string" && (n = n.split("&"));
    for (let h = 0; h < n.length; h++) {
      const c = n[h];
      if (typeof c == "string") {
        const u = c.split("=");
        n[h] = [u.shift(), u.join("=")];
      }
    }
  }
  const a = [], l = o.requestType === "GET";
  if (l) {
    if (a.push(o.router, "?", o.moduleVar, "=", t, "&", o.methodVar, "=", e), n)
      for (let h = 0; h < n.length; h++)
        a.push("&", n[h][0], "=", n[h][1]);
    a.push("&", o.viewVar, "=", s);
  } else {
    if (o.requestType == "PATH_INFO" && a.push(o.webRoot, t, o.requestFix, e), o.requestType == "PATH_INFO2" && a.push(o.webRoot, "index.php/", t, o.requestFix, e), n)
      for (let h = 0; h < n.length; h++)
        a.push(o.requestFix + n[h][1]);
    a.push(".", s);
  }
  return r && Object.keys(r).forEach((h) => {
    const c = r[h];
    h[0] !== "$" && a.push(!l && !a.includes("?") ? "?" : "&", h, "=", c);
  }), typeof i == "string" && a.push(i.startsWith("#") ? "" : "#", i), a.join("");
}
const Ag = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  createLink: Bu,
  parseLink: Rg,
  parseUrlParams: Hu
}, Symbol.toStringTag, { value: "Module" })), Nr = /* @__PURE__ */ new Map();
function lm(t, e, n) {
  const { zui: s } = window;
  Nr.size || Object.keys(s).forEach((r) => {
    r[0] === r[0].toUpperCase() && Nr.set(r.toLowerCase(), s[r]);
  });
  const i = Nr.get(t.toLowerCase());
  return i ? new i(e, n) : null;
}
g(document).on("click.helpers.zt change.helpers.zt", "[data-on]", function(t) {
  const e = g(this), n = e.dataset();
  if (!(n.on || "click").split(" ").includes(t.type) || n.selector && !g(t.target).closest(n.selector).length)
    return;
  const s = (a) => a === "" ? !0 : a, i = (a) => {
    if (typeof a == "string")
      try {
        a = JSON.parse(a);
      } catch {
      }
    return a;
  };
  if (s(n.once)) {
    if (n.onceCalled)
      return;
    e.dataset("once-called", !0);
  }
  s(n.prevent) && t.preventDefault(), s(n.stop) && t.stopPropagation();
  const r = [["$element", e], ["event", t], ["options", n]];
  if (n.if && !g.runJS(n.if, ...r))
    return;
  const o = n.call;
  if (o) {
    let a = window[o];
    const l = /^[$A-Z_][0-9A-Z_$.]*$/i.test(o);
    if (a || (a = g.runJS(o, ...r)), !l || !g.isFunction(a))
      return;
    const h = [], c = n.params;
    n.params = h, typeof c == "string" && c.length && (c[0] === "[" ? h.push(...i(c)) : h.push(...c.split(", ").map((u) => (u = u.trim(), u === "$element" ? e : u === "event" ? t : u === "options" ? n : u.startsWith("$element.") || u.startsWith("$event.") || u.startsWith("$options.") ? g.runJS(u, ...r) : i(u))))), a(...h);
  }
  n.do && g.runJS(n.do, ...r);
});
window.$ && Object.assign(window.$, Ag);
export {
  g as $,
  Mc as ActionMenu,
  Rc as ActionMenuNested,
  vg as AjaxForm,
  oh as Avatar,
  Kh as BatchForm,
  ah as BtnGroup,
  Jh as Burn,
  lt as Component,
  J as ComponentFromReact,
  bt as ContextMenu,
  qo as CustomRender,
  Rs as DTable,
  lu as Dashboard,
  we as Dropdown,
  Dp as ECharts,
  ia as EventBus,
  Bd as HElement,
  kc as HtmlContent,
  ds as Icon,
  Ac as Menu,
  na as Messager,
  or as Modal,
  ne as ModalBase,
  fh as ModalTrigger,
  gh as Nav,
  mh as Pager,
  Sh as Picker,
  Ch as Popovers,
  Zc as ProgressCircle,
  U as ReactComponent,
  Zh as SearchForm,
  Qc as Switch,
  jt as TIME_DAY,
  Du as Tabs,
  Eh as Toolbar,
  Nt as Tooltip,
  qh as Tree,
  eu as Zinbar,
  Ug as ajax,
  Ja as ajaxSubmit,
  qg as bus,
  El as calculateTimestamp,
  g as cash,
  N as classes,
  Nr as componentsMap,
  Lg as convertBytes,
  Hf as cookie,
  lm as create,
  ft as createDate,
  qd as createPortal,
  $t as createRef,
  Dg as dom,
  br as formatBytes,
  eo as formatDate,
  Qg as formatDateSpan,
  X as formatString,
  lc as getClassList,
  tm as getTimeBeforeDesc,
  _ as h,
  Pg as hh,
  Hd as htm,
  Zt as i18n,
  Zg as isDBY,
  vr as isObject,
  Es as isSameDay,
  Qf as isSameMonth,
  Yg as isSameWeek,
  to as isSameYear,
  Kg as isToday,
  Jg as isTomorrow,
  it as isValidElement,
  Xg as isYesterday,
  Dr as mergeDeep,
  _l as nativeEvents,
  us as render,
  Fd as renderCustomResult,
  kf as store,
  cc as storeData,
  Dd as takeData,
  am as zentao,
  Tg as zentaoPlugin
};
//# sourceMappingURL=zui.zentao.js.map
