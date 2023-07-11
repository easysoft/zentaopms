var mr = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
};
var W = (t, e, n) => (mr(t, e, "read from private field"), n ? n.call(t) : e.get(t)), I = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, F = (t, e, n, s) => (mr(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n);
var at = (t, e, n) => (mr(t, e, "access private method"), n);
const Xt = document, fi = window, jl = Xt.documentElement, je = Xt.createElement.bind(Xt), Ul = je("div"), yr = je("table"), Fd = je("tbody"), Qa = je("tr"), { isArray: Gi, prototype: ql } = Array, { concat: jd, filter: To, indexOf: Vl, map: Gl, push: Ud, slice: Yl, some: Ro, splice: qd } = ql, Vd = /^#(?:[\w-]|\\.|[^\x00-\xa0])*$/, Gd = /^\.(?:[\w-]|\\.|[^\x00-\xa0])*$/, Yd = /<.+>/, Kd = /^\w+$/;
function Ao(t, e) {
  const n = Xd(e);
  return !t || !n && !Oe(e) && !X(e) ? [] : !n && Gd.test(t) ? e.getElementsByClassName(t.slice(1).replace(/\\/g, "")) : !n && Kd.test(t) ? e.getElementsByTagName(t) : e.querySelectorAll(t);
}
class Yi {
  constructor(e, n) {
    if (!e)
      return;
    if (Nr(e))
      return e;
    let s = e;
    if (rt(e)) {
      const i = n || Xt;
      if (s = Vd.test(e) && Oe(i) ? i.getElementById(e.slice(1).replace(/\\/g, "")) : Yd.test(e) ? Jl(e) : Nr(i) ? i.find(e) : rt(i) ? g(i).find(e) : Ao(e, i), !s)
        return;
    } else if (Ue(e))
      return this.ready(e);
    (s.nodeType || s === fi) && (s = [s]), this.length = s.length;
    for (let i = 0, r = this.length; i < r; i++)
      this[i] = s[i];
  }
  init(e, n) {
    return new Yi(e, n);
  }
}
const C = Yi.prototype, g = C.init;
g.fn = g.prototype = C;
C.length = 0;
C.splice = qd;
typeof Symbol == "function" && (C[Symbol.iterator] = ql[Symbol.iterator]);
function Nr(t) {
  return t instanceof Yi;
}
function Cn(t) {
  return !!t && t === t.window;
}
function Oe(t) {
  return !!t && t.nodeType === 9;
}
function Xd(t) {
  return !!t && t.nodeType === 11;
}
function X(t) {
  return !!t && t.nodeType === 1;
}
function Jd(t) {
  return !!t && t.nodeType === 3;
}
function Zd(t) {
  return typeof t == "boolean";
}
function Ue(t) {
  return typeof t == "function";
}
function rt(t) {
  return typeof t == "string";
}
function dt(t) {
  return t === void 0;
}
function cs(t) {
  return t === null;
}
function Kl(t) {
  return !isNaN(parseFloat(t)) && isFinite(t);
}
function No(t) {
  if (typeof t != "object" || t === null)
    return !1;
  const e = Object.getPrototypeOf(t);
  return e === null || e === Object.prototype;
}
g.isWindow = Cn;
g.isFunction = Ue;
g.isArray = Gi;
g.isNumeric = Kl;
g.isPlainObject = No;
function tt(t, e, n) {
  if (n) {
    let s = t.length;
    for (; s--; )
      if (e.call(t[s], s, t[s]) === !1)
        return t;
  } else if (No(t)) {
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
C.each = function(t) {
  return tt(this, t);
};
C.empty = function() {
  return this.each((t, e) => {
    for (; e.firstChild; )
      e.removeChild(e.firstChild);
  });
};
function pi(...t) {
  const e = Zd(t[0]) ? t.shift() : !1, n = t.shift(), s = t.length;
  if (!n)
    return {};
  if (!s)
    return pi(e, g, n);
  for (let i = 0; i < s; i++) {
    const r = t[i];
    for (const o in r)
      e && (Gi(r[o]) || No(r[o])) ? ((!n[o] || n[o].constructor !== r[o].constructor) && (n[o] = new r[o].constructor()), pi(e, n[o], r[o])) : n[o] = r[o];
  }
  return n;
}
g.extend = pi;
C.extend = function(t) {
  return pi(C, t);
};
const Qd = /\S+/g;
function Ki(t) {
  return rt(t) ? t.match(Qd) || [] : [];
}
C.toggleClass = function(t, e) {
  const n = Ki(t), s = !dt(e);
  return this.each((i, r) => {
    X(r) && tt(n, (o, a) => {
      s ? e ? r.classList.add(a) : r.classList.remove(a) : r.classList.toggle(a);
    });
  });
};
C.addClass = function(t) {
  return this.toggleClass(t, !0);
};
C.removeAttr = function(t) {
  const e = Ki(t);
  return this.each((n, s) => {
    X(s) && tt(e, (i, r) => {
      s.removeAttribute(r);
    });
  });
};
function tu(t, e) {
  if (t) {
    if (rt(t)) {
      if (arguments.length < 2) {
        if (!this[0] || !X(this[0]))
          return;
        const n = this[0].getAttribute(t);
        return cs(n) ? void 0 : n;
      }
      return dt(e) ? this : cs(e) ? this.removeAttr(t) : this.each((n, s) => {
        X(s) && s.setAttribute(t, e);
      });
    }
    for (const n in t)
      this.attr(n, t[n]);
    return this;
  }
}
C.attr = tu;
C.removeClass = function(t) {
  return arguments.length ? this.toggleClass(t, !1) : this.attr("class", "");
};
C.hasClass = function(t) {
  return !!t && Ro.call(this, (e) => X(e) && e.classList.contains(t));
};
C.get = function(t) {
  return dt(t) ? Yl.call(this) : (t = Number(t), this[t < 0 ? t + this.length : t]);
};
C.eq = function(t) {
  return g(this.get(t));
};
C.first = function() {
  return this.eq(0);
};
C.last = function() {
  return this.eq(-1);
};
function eu(t) {
  return dt(t) ? this.get().map((e) => X(e) || Jd(e) ? e.textContent : "").join("") : this.each((e, n) => {
    X(n) && (n.textContent = t);
  });
}
C.text = eu;
function Jt(t, e, n) {
  if (!X(t))
    return;
  const s = fi.getComputedStyle(t, null);
  return n ? s.getPropertyValue(e) || void 0 : s[e] || t.style[e];
}
function Tt(t, e) {
  return parseInt(Jt(t, e), 10) || 0;
}
function tl(t, e) {
  return Tt(t, `border${e ? "Left" : "Top"}Width`) + Tt(t, `padding${e ? "Left" : "Top"}`) + Tt(t, `padding${e ? "Right" : "Bottom"}`) + Tt(t, `border${e ? "Right" : "Bottom"}Width`);
}
const wr = {};
function nu(t) {
  if (wr[t])
    return wr[t];
  const e = je(t);
  Xt.body.insertBefore(e, null);
  const n = Jt(e, "display");
  return Xt.body.removeChild(e), wr[t] = n !== "none" ? n : "block";
}
function el(t) {
  return Jt(t, "display") === "none";
}
function Xl(t, e) {
  const n = t && (t.matches || t.webkitMatchesSelector || t.msMatchesSelector);
  return !!n && !!e && n.call(t, e);
}
function Xi(t) {
  return rt(t) ? (e, n) => Xl(n, t) : Ue(t) ? t : Nr(t) ? (e, n) => t.is(n) : t ? (e, n) => n === t : () => !1;
}
C.filter = function(t) {
  const e = Xi(t);
  return g(To.call(this, (n, s) => e.call(n, s, n)));
};
function xe(t, e) {
  return e ? t.filter(e) : t;
}
C.detach = function(t) {
  return xe(this, t).each((e, n) => {
    n.parentNode && n.parentNode.removeChild(n);
  }), this;
};
const su = /^\s*<(\w+)[^>]*>/, iu = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, nl = {
  "*": Ul,
  tr: Fd,
  td: Qa,
  th: Qa,
  thead: yr,
  tbody: yr,
  tfoot: yr
};
function Jl(t) {
  if (!rt(t))
    return [];
  if (iu.test(t))
    return [je(RegExp.$1)];
  const e = su.test(t) && RegExp.$1, n = nl[e] || nl["*"];
  return n.innerHTML = t, g(n.childNodes).detach().get();
}
g.parseHTML = Jl;
C.has = function(t) {
  const e = rt(t) ? (n, s) => Ao(t, s).length : (n, s) => s.contains(t);
  return this.filter(e);
};
C.not = function(t) {
  const e = Xi(t);
  return this.filter((n, s) => (!rt(t) || X(s)) && !e.call(s, n, s));
};
function ee(t, e, n, s) {
  const i = [], r = Ue(e), o = s && Xi(s);
  for (let a = 0, l = t.length; a < l; a++)
    if (r) {
      const h = e(t[a]);
      h.length && Ud.apply(i, h);
    } else {
      let h = t[a][e];
      for (; h != null && !(s && o(-1, h)); )
        i.push(h), h = n ? h[e] : null;
    }
  return i;
}
function Zl(t) {
  return t.multiple && t.options ? ee(To.call(t.options, (e) => e.selected && !e.disabled && !e.parentNode.disabled), "value") : t.value || "";
}
function ru(t) {
  return arguments.length ? this.each((e, n) => {
    const s = n.multiple && n.options;
    if (s || oc.test(n.type)) {
      const i = Gi(t) ? Gl.call(t, String) : cs(t) ? [] : [String(t)];
      s ? tt(n.options, (r, o) => {
        o.selected = i.indexOf(o.value) >= 0;
      }, !0) : n.checked = i.indexOf(n.value) >= 0;
    } else
      n.value = dt(t) || cs(t) ? "" : t;
  }) : this[0] && Zl(this[0]);
}
C.val = ru;
C.is = function(t) {
  const e = Xi(t);
  return Ro.call(this, (n, s) => e.call(n, s, n));
};
g.guid = 1;
function Dt(t) {
  return t.length > 1 ? To.call(t, (e, n, s) => Vl.call(s, e) === n) : t;
}
g.unique = Dt;
C.add = function(t, e) {
  return g(Dt(this.get().concat(g(t, e).get())));
};
C.children = function(t) {
  return xe(g(Dt(ee(this, (e) => e.children))), t);
};
C.parent = function(t) {
  return xe(g(Dt(ee(this, "parentNode"))), t);
};
C.index = function(t) {
  const e = t ? g(t)[0] : this[0], n = t ? this : g(e).parent().children();
  return Vl.call(n, e);
};
C.closest = function(t) {
  const e = this.filter(t);
  if (e.length)
    return e;
  const n = this.parent();
  return n.length ? n.closest(t) : e;
};
C.siblings = function(t) {
  return xe(g(Dt(ee(this, (e) => g(e).parent().children().not(e)))), t);
};
C.find = function(t) {
  return g(Dt(ee(this, (e) => Ao(t, e))));
};
const ou = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, au = /^$|^module$|\/(java|ecma)script/i, lu = ["type", "src", "nonce", "noModule"];
function cu(t, e) {
  const n = g(t);
  n.filter("script").add(n.find("script")).each((s, i) => {
    if (au.test(i.type) && jl.contains(i)) {
      const r = je("script");
      r.text = i.textContent.replace(ou, ""), tt(lu, (o, a) => {
        i[a] && (r[a] = i[a]);
      }), e.head.insertBefore(r, null), e.head.removeChild(r);
    }
  });
}
function hu(t, e, n, s, i) {
  s ? t.insertBefore(e, n ? t.firstChild : null) : t.nodeName === "HTML" ? t.parentNode.replaceChild(e, t) : t.parentNode.insertBefore(e, n ? t : t.nextSibling), i && cu(e, t.ownerDocument);
}
function $e(t, e, n, s, i, r, o, a) {
  return tt(t, (l, h) => {
    tt(g(h), (c, d) => {
      tt(g(e), (u, f) => {
        const p = n ? d : f, m = n ? f : d, v = n ? c : u;
        hu(p, v ? m.cloneNode(!0) : m, s, i, !v);
      }, a);
    }, o);
  }, r), e;
}
C.after = function() {
  return $e(arguments, this, !1, !1, !1, !0, !0);
};
C.append = function() {
  return $e(arguments, this, !1, !1, !0);
};
function du(t) {
  if (!arguments.length)
    return this[0] && this[0].innerHTML;
  if (dt(t))
    return this;
  const e = /<script[\s>]/.test(t);
  return this.each((n, s) => {
    X(s) && (e ? g(s).empty().append(t) : s.innerHTML = t);
  });
}
C.html = du;
C.appendTo = function(t) {
  return $e(arguments, this, !0, !1, !0);
};
C.wrapInner = function(t) {
  return this.each((e, n) => {
    const s = g(n), i = s.contents();
    i.length ? i.wrapAll(t) : s.append(t);
  });
};
C.before = function() {
  return $e(arguments, this, !1, !0);
};
C.wrapAll = function(t) {
  let e = g(t), n = e[0];
  for (; n.children.length; )
    n = n.firstElementChild;
  return this.first().before(e), this.appendTo(n);
};
C.wrap = function(t) {
  return this.each((e, n) => {
    const s = g(t)[0];
    g(n).wrapAll(e ? s.cloneNode(!0) : s);
  });
};
C.insertAfter = function(t) {
  return $e(arguments, this, !0, !1, !1, !1, !1, !0);
};
C.insertBefore = function(t) {
  return $e(arguments, this, !0, !0);
};
C.prepend = function() {
  return $e(arguments, this, !1, !0, !0, !0, !0);
};
C.prependTo = function(t) {
  return $e(arguments, this, !0, !0, !0, !1, !1, !0);
};
C.contents = function() {
  return g(Dt(ee(this, (t) => t.tagName === "IFRAME" ? [t.contentDocument] : t.tagName === "TEMPLATE" ? t.content.childNodes : t.childNodes)));
};
C.next = function(t, e, n) {
  return xe(g(Dt(ee(this, "nextElementSibling", e, n))), t);
};
C.nextAll = function(t) {
  return this.next(t, !0);
};
C.nextUntil = function(t, e) {
  return this.next(e, !0, t);
};
C.parents = function(t, e) {
  return xe(g(Dt(ee(this, "parentElement", !0, e))), t);
};
C.parentsUntil = function(t, e) {
  return this.parents(e, t);
};
C.prev = function(t, e, n) {
  return xe(g(Dt(ee(this, "previousElementSibling", e, n))), t);
};
C.prevAll = function(t) {
  return this.prev(t, !0);
};
C.prevUntil = function(t, e) {
  return this.prev(e, !0, t);
};
C.map = function(t) {
  return g(jd.apply([], Gl.call(this, (e, n) => t.call(e, n, e))));
};
C.clone = function() {
  return this.map((t, e) => e.cloneNode(!0));
};
C.offsetParent = function() {
  return this.map((t, e) => {
    let n = e.offsetParent;
    for (; n && Jt(n, "position") === "static"; )
      n = n.offsetParent;
    return n || jl;
  });
};
C.slice = function(t, e) {
  return g(Yl.call(this, t, e));
};
const uu = /-([a-z])/g;
function Lo(t) {
  return t.replace(uu, (e, n) => n.toUpperCase());
}
C.ready = function(t) {
  const e = () => setTimeout(t, 0, g);
  return Xt.readyState !== "loading" ? e() : Xt.addEventListener("DOMContentLoaded", e), this;
};
C.unwrap = function() {
  return this.parent().each((t, e) => {
    if (e.tagName === "BODY")
      return;
    const n = g(e);
    n.replaceWith(n.children());
  }), this;
};
C.offset = function() {
  const t = this[0];
  if (!t)
    return;
  const e = t.getBoundingClientRect();
  return {
    top: e.top + fi.pageYOffset,
    left: e.left + fi.pageXOffset
  };
};
C.position = function() {
  const t = this[0];
  if (!t)
    return;
  const e = Jt(t, "position") === "fixed", n = e ? t.getBoundingClientRect() : this.offset();
  if (!e) {
    const s = t.ownerDocument;
    let i = t.offsetParent || s.documentElement;
    for (; (i === s.body || i === s.documentElement) && Jt(i, "position") === "static"; )
      i = i.parentNode;
    if (i !== t && X(i)) {
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
C.prop = function(t, e) {
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
C.removeProp = function(t) {
  return this.each((e, n) => {
    delete n[Ql[t] || t];
  });
};
const fu = /^--/;
function Do(t) {
  return fu.test(t);
}
const vr = {}, { style: pu } = Ul, gu = ["webkit", "moz", "ms"];
function mu(t, e = Do(t)) {
  if (e)
    return t;
  if (!vr[t]) {
    const n = Lo(t), s = `${n[0].toUpperCase()}${n.slice(1)}`, i = `${n} ${gu.join(`${s} `)}${s}`.split(" ");
    tt(i, (r, o) => {
      if (o in pu)
        return vr[t] = o, !1;
    });
  }
  return vr[t];
}
const yu = {
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
function tc(t, e, n = Do(t)) {
  return !n && !yu[t] && Kl(e) ? `${e}px` : e;
}
function wu(t, e) {
  if (rt(t)) {
    const n = Do(t);
    return t = mu(t, n), arguments.length < 2 ? this[0] && Jt(this[0], t, n) : t ? (e = tc(t, e, n), this.each((s, i) => {
      X(i) && (n ? i.style.setProperty(t, e) : i.style[t] = e);
    })) : this;
  }
  for (const n in t)
    this.css(n, t[n]);
  return this;
}
C.css = wu;
function ec(t, e) {
  try {
    return t(e);
  } catch {
    return e;
  }
}
const vu = /^\s+|\s+$/;
function sl(t, e) {
  const n = t.dataset[e] || t.dataset[Lo(e)];
  return vu.test(n) ? n : ec(JSON.parse, n);
}
function bu(t, e, n) {
  n = ec(JSON.stringify, n), t.dataset[Lo(e)] = n;
}
function _u(t, e) {
  if (!t) {
    if (!this[0])
      return;
    const n = {};
    for (const s in this[0].dataset)
      n[s] = sl(this[0], s);
    return n;
  }
  if (rt(t))
    return arguments.length < 2 ? this[0] && sl(this[0], t) : dt(e) ? this : this.each((n, s) => {
      bu(s, t, e);
    });
  for (const n in t)
    this.data(n, t[n]);
  return this;
}
C.data = _u;
function nc(t, e) {
  const n = t.documentElement;
  return Math.max(t.body[`scroll${e}`], n[`scroll${e}`], t.body[`offset${e}`], n[`offset${e}`], n[`client${e}`]);
}
tt([!0, !1], (t, e) => {
  tt(["Width", "Height"], (n, s) => {
    const i = `${e ? "outer" : "inner"}${s}`;
    C[i] = function(r) {
      if (this[0])
        return Cn(this[0]) ? e ? this[0][`inner${s}`] : this[0].document.documentElement[`client${s}`] : Oe(this[0]) ? nc(this[0], s) : this[0][`${e ? "offset" : "client"}${s}`] + (r && e ? Tt(this[0], `margin${n ? "Top" : "Left"}`) + Tt(this[0], `margin${n ? "Bottom" : "Right"}`) : 0);
    };
  });
});
tt(["Width", "Height"], (t, e) => {
  const n = e.toLowerCase();
  C[n] = function(s) {
    if (!this[0])
      return dt(s) ? void 0 : this;
    if (!arguments.length)
      return Cn(this[0]) ? this[0].document.documentElement[`client${e}`] : Oe(this[0]) ? nc(this[0], e) : this[0].getBoundingClientRect()[n] - tl(this[0], !t);
    const i = parseInt(s, 10);
    return this.each((r, o) => {
      if (!X(o))
        return;
      const a = Jt(o, "boxSizing");
      o.style[n] = tc(n, i + (a === "border-box" ? tl(o, !t) : 0));
    });
  };
});
const il = "___cd";
C.toggle = function(t) {
  return this.each((e, n) => {
    if (!X(n))
      return;
    const s = el(n);
    (dt(t) ? s : t) ? (n.style.display = n[il] || "", el(n) && (n.style.display = nu(n.tagName))) : s || (n[il] = Jt(n, "display"), n.style.display = "none");
  });
};
C.hide = function() {
  return this.toggle(!1);
};
C.show = function() {
  return this.toggle(!0);
};
const rl = "___ce", Po = ".", Wo = { focus: "focusin", blur: "focusout" }, sc = { mouseenter: "mouseover", mouseleave: "mouseout" }, xu = /^(mouse|pointer|contextmenu|drag|drop|click|dblclick)/i;
function Io(t) {
  return sc[t] || Wo[t] || t;
}
function Oo(t) {
  const e = t.split(Po);
  return [e[0], e.slice(1).sort()];
}
C.trigger = function(t, e) {
  if (rt(t)) {
    const [s, i] = Oo(t), r = Io(s);
    if (!r)
      return this;
    const o = xu.test(r) ? "MouseEvents" : "HTMLEvents";
    t = Xt.createEvent(o), t.initEvent(r, !0, !0), t.namespace = i.join(Po), t.___ot = s;
  }
  t.___td = e;
  const n = t.___ot in Wo;
  return this.each((s, i) => {
    n && Ue(i[t.___ot]) && (i[`___i${t.type}`] = !0, i[t.___ot](), i[`___i${t.type}`] = !1), i.dispatchEvent(t);
  });
};
function ic(t) {
  return t[rl] = t[rl] || {};
}
function $u(t, e, n, s, i) {
  const r = ic(t);
  r[e] = r[e] || [], r[e].push([n, s, i]), t.addEventListener(e, i);
}
function rc(t, e) {
  return !e || !Ro.call(e, (n) => t.indexOf(n) < 0);
}
function gi(t, e, n, s, i) {
  const r = ic(t);
  if (e)
    r[e] && (r[e] = r[e].filter(([o, a, l]) => {
      if (i && l.guid !== i.guid || !rc(o, n) || s && s !== a)
        return !0;
      t.removeEventListener(e, l);
    }));
  else
    for (e in r)
      gi(t, e, n, s, i);
}
C.off = function(t, e, n) {
  if (dt(t))
    this.each((s, i) => {
      !X(i) && !Oe(i) && !Cn(i) || gi(i);
    });
  else if (rt(t))
    Ue(e) && (n = e, e = ""), tt(Ki(t), (s, i) => {
      const [r, o] = Oo(i), a = Io(r);
      this.each((l, h) => {
        !X(h) && !Oe(h) && !Cn(h) || gi(h, a, o, e, n);
      });
    });
  else
    for (const s in t)
      this.off(s, t[s]);
  return this;
};
C.remove = function(t) {
  return xe(this, t).detach().off(), this;
};
C.replaceWith = function(t) {
  return this.before(t).remove();
};
C.replaceAll = function(t) {
  return g(t).replaceWith(this), this;
};
function ku(t, e, n, s, i) {
  if (!rt(t)) {
    for (const r in t)
      this.on(r, e, n, t[r], i);
    return this;
  }
  return rt(e) || (dt(e) || cs(e) ? e = "" : dt(n) ? (n = e, e = "") : (s = n, n = e, e = "")), Ue(s) || (s = n, n = void 0), s ? (tt(Ki(t), (r, o) => {
    const [a, l] = Oo(o), h = Io(a), c = a in sc, d = a in Wo;
    h && this.each((u, f) => {
      if (!X(f) && !Oe(f) && !Cn(f))
        return;
      const p = function(m) {
        if (m.target[`___i${m.type}`])
          return m.stopImmediatePropagation();
        if (m.namespace && !rc(l, m.namespace.split(Po)) || !e && (d && (m.target !== f || m.___ot === h) || c && m.relatedTarget && f.contains(m.relatedTarget)))
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
        i && gi(f, h, l, e, p), w === !1 && (m.preventDefault(), m.stopPropagation());
      };
      p.guid = s.guid = s.guid || g.guid++, $u(f, h, l, e, p);
    });
  }), this) : this;
}
C.on = ku;
function Cu(t, e, n, s) {
  return this.on(t, e, n, s, !0);
}
C.one = Cu;
const Su = /\r?\n/g;
function Eu(t, e) {
  return `&${encodeURIComponent(t)}=${encodeURIComponent(e.replace(Su, `\r
`))}`;
}
const Mu = /file|reset|submit|button|image/i, oc = /radio|checkbox/i;
C.serialize = function() {
  let t = "";
  return this.each((e, n) => {
    tt(n.elements || [n], (s, i) => {
      if (i.disabled || !i.name || i.tagName === "FIELDSET" || Mu.test(i.type) || oc.test(i.type) && !i.checked)
        return;
      const r = Zl(i);
      if (!dt(r)) {
        const o = Gi(r) ? r : [r];
        tt(o, (a, l) => {
          t += Eu(i.name, l);
        });
      }
    });
  }), t.slice(1);
};
window.$ = g;
function Tu(t, e) {
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
function Ru(t, e, n) {
  try {
    const s = Tu(t, e), i = s[s.length - 1];
    return i === void 0 ? n : i;
  } catch {
    return n;
  }
}
function Y(t, ...e) {
  if (e.length === 0)
    return t;
  if (e.length === 1 && typeof e[0] == "object" && e[0]) {
    const n = e[0];
    return Object.keys(n).forEach((s) => {
      const i = n[s] ?? "";
      t = t.replace(new RegExp(`\\{${s}\\}`, "g"), `${i}`);
    }), t;
  }
  for (let n = 0; n < e.length; n++) {
    const s = e[n] ?? "";
    t = t.replace(new RegExp(`\\{${n}\\}`, "g"), `${s}`);
  }
  return t;
}
var Ho = /* @__PURE__ */ ((t) => (t[t.B = 1] = "B", t[t.KB = 1024] = "KB", t[t.MB = 1048576] = "MB", t[t.GB = 1073741824] = "GB", t[t.TB = 1099511627776] = "TB", t))(Ho || {});
function qs(t, e = 2, n) {
  return Number.isNaN(t) ? "?KB" : (n || (t < 1024 ? n = "B" : t < 1048576 ? n = "KB" : t < 1073741824 ? n = "MB" : t < 1099511627776 ? n = "GB" : n = "TB"), (t / Ho[n]).toFixed(e) + n);
}
const Lg = (t) => {
  const e = /^[0-9]*(B|KB|MB|GB|TB)$/;
  t = t.toUpperCase();
  const n = t.match(e);
  if (!n)
    return 0;
  const s = n[1];
  return t = t.replace(s, ""), Number.parseInt(t, 10) * Ho[s];
};
let Bo = (document.documentElement.getAttribute("lang") || "zh_cn").toLowerCase().replace("-", "_"), ce;
function Au() {
  return Bo;
}
function Nu(t) {
  Bo = t.toLowerCase();
}
function ac(t, e) {
  ce || (ce = {}), typeof t == "string" && (t = { [t]: e ?? {} }), g.extend(!0, ce, t);
}
function Zt(t, e, n, s, i, r) {
  Array.isArray(t) ? ce && t.unshift(ce) : t = ce ? [ce, t] : [t], typeof n == "string" && (r = i, i = s, s = n, n = void 0);
  const o = i || Bo;
  let a;
  for (const l of t) {
    if (!l)
      continue;
    const h = l[o];
    if (!h)
      continue;
    const c = r && l === ce ? `${r}.${e}` : e;
    if (a = Ru(h, c), a !== void 0)
      break;
  }
  return a === void 0 ? s : n ? Y(a, ...Array.isArray(n) ? n : [n]) : a;
}
function Lu(t, e, n, s) {
  return Zt(void 0, t, e, n, s);
}
Zt.addLang = ac;
Zt.getLang = Lu;
Zt.getCode = Au;
Zt.setCode = Nu;
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
const L = (...t) => lc(...t).reduce((e, [n, s]) => (s && e.push(n), e), []).join(" ");
g.classes = L;
g.fn.setClass = function(t, ...e) {
  return this.each((n, s) => {
    const i = g(s);
    t === !0 ? i.attr("class", L(i.attr("class"), ...e)) : i.addClass(L(t, ...e));
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
function Du(t, e) {
  let n = Wn.get(t) || {};
  return t instanceof Element && (n = Object.assign({}, g(t).dataset(), n)), e === void 0 ? n : n[e];
}
g.fn.dataset = g.fn.data;
g.fn.data = function(...t) {
  if (!this.length)
    return;
  const [e, n] = t;
  return !t.length || t.length === 1 && typeof e == "string" ? Du(this[0], e) : this.each((s, i) => cc(i, e, n));
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
  const { left: s, top: i, width: r, height: o } = n.getBoundingClientRect(), { innerHeight: a, innerWidth: l } = window, { clientHeight: h, clientWidth: c } = document.documentElement, d = a || h, u = l || c;
  if (e != null && e.fullyCheck)
    return s >= 0 && i >= 0 && s + r <= u && i + o <= d;
  const f = i <= d && i + o >= 0, p = s <= u && s + r >= 0;
  return f && p;
}
g.fn.isVisible = function(t) {
  return this.each((e, n) => {
    hc(n, t);
  });
};
function zo(t, e, n = !1) {
  const s = g(t);
  if (e !== void 0) {
    const i = `zui-runjs-${g.guid++}`;
    s.append(`<script id="${i}">${e}<\/script>`), n && s.find(`#${i}`).remove();
    return;
  }
  s.find("script").each((i, r) => {
    zo(s, r.innerHTML), r.remove();
  });
}
g.runJS = (t, ...e) => (t = t.trim(), t.startsWith("return ") || (t = `return ${t}`), new Function(...e.map(([s]) => s), t)(...e.map(([, s]) => s)));
g.fn.runJS = function(t) {
  return this.each((e, n) => {
    zo(n, t);
  });
};
const Dg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  isVisible: hc,
  runJS: zo
}, Symbol.toStringTag, { value: "Module" }));
var Ji, q, dc, it, Te, ol, uc, Lr, mi = {}, fc = [], Pu = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i, Fo = Array.isArray;
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
  if (arguments.length > 2 && (o.children = arguments.length > 3 ? Ji.call(arguments, 2) : n), typeof t == "function" && t.defaultProps != null)
    for (r in t.defaultProps)
      o[r] === void 0 && (o[r] = t.defaultProps[r]);
  return Vs(t, o, s, i, null);
}
function Vs(t, e, n, s, i) {
  var r = { type: t, props: e, key: n, ref: s, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++dc };
  return i == null && q.vnode != null && q.vnode(r), r;
}
function $t() {
  return { current: null };
}
function Zi(t) {
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
  (!t.__d && (t.__d = !0) && Te.push(t) && !yi.__r++ || ol !== q.debounceRendering) && ((ol = q.debounceRendering) || uc)(yi);
}
function yi() {
  var t, e, n, s, i, r, o, a;
  for (Te.sort(Lr); t = Te.shift(); )
    t.__d && (e = Te.length, s = void 0, i = void 0, o = (r = (n = t).__v).__e, (a = n.__P) && (s = [], (i = ge({}, r)).__v = r.__v + 1, jo(a, r, i, n.__n, a.ownerSVGElement !== void 0, r.__h != null ? [o] : null, s, o ?? hs(r), r.__h), bc(s, r), r.__e != o && gc(r)), Te.length > e && Te.sort(Lr));
  yi.__r = 0;
}
function mc(t, e, n, s, i, r, o, a, l, h) {
  var c, d, u, f, p, m, v, w = s && s.__k || fc, b = w.length;
  for (n.__k = [], c = 0; c < e.length; c++)
    if ((f = n.__k[c] = (f = e[c]) == null || typeof f == "boolean" || typeof f == "function" ? null : typeof f == "string" || typeof f == "number" || typeof f == "bigint" ? Vs(null, f, null, null, f) : Fo(f) ? Vs(Zi, { children: f }, null, null, null) : f.__b > 0 ? Vs(f.type, f.props, f.key, f.ref ? f.ref : null, f.__v) : f) != null) {
      if (f.__ = n, f.__b = n.__b + 1, (u = w[c]) === null || u && f.key == u.key && f.type === u.type)
        w[c] = void 0;
      else
        for (d = 0; d < b; d++) {
          if ((u = w[d]) && f.key == u.key && f.type === u.type) {
            w[d] = void 0;
            break;
          }
          u = null;
        }
      jo(t, f, u = u || mi, i, r, o, a, l, h), p = f.__e, (d = f.ref) && u.ref != d && (v || (v = []), u.ref && v.push(u.ref, null, f), v.push(d, f.__c || p, f)), p != null ? (m == null && (m = p), typeof f.type == "function" && f.__k === u.__k ? f.__d = l = yc(f, l, t) : l = wc(t, f, u, w, p, l), typeof n.type == "function" && (n.__d = l)) : l && u.__e == l && l.parentNode != t && (l = hs(u));
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
function Wu(t, e, n, s, i) {
  var r;
  for (r in n)
    r === "children" || r === "key" || r in e || wi(t, r, null, n[r], s);
  for (r in e)
    i && typeof e[r] != "function" || r === "children" || r === "key" || r === "value" || r === "checked" || n[r] === e[r] || wi(t, r, e[r], n[r], s);
}
function ll(t, e, n) {
  e[0] === "-" ? t.setProperty(e, n ?? "") : t[e] = n == null ? "" : typeof n != "number" || Pu.test(e) ? n : n + "px";
}
function wi(t, e, n, s, i) {
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
function jo(t, e, n, s, i, r, o, a, l) {
  var h, c, d, u, f, p, m, v, w, b, k, S, E, P, M, R = e.type;
  if (e.constructor !== void 0)
    return null;
  n.__h != null && (l = n.__h, a = e.__e = n.__e, e.__h = null, r = [a]), (h = q.__b) && h(e);
  try {
    t:
      if (typeof R == "function") {
        if (v = e.props, w = (h = R.contextType) && s[h.__c], b = h ? w ? w.props.value : h.__ : s, n.__c ? m = (c = e.__c = n.__c).__ = c.__E : ("prototype" in R && R.prototype.render ? e.__c = c = new R(v, b) : (e.__c = c = new U(v, b), c.constructor = R, c.render = Ou), w && w.sub(c), c.props = v, c.state || (c.state = {}), c.context = b, c.__n = s, d = c.__d = !0, c.__h = [], c._sb = []), c.__s == null && (c.__s = c.state), R.getDerivedStateFromProps != null && (c.__s == c.state && (c.__s = ge({}, c.__s)), ge(c.__s, R.getDerivedStateFromProps(v, c.__s))), u = c.props, f = c.state, c.__v = e, d)
          R.getDerivedStateFromProps == null && c.componentWillMount != null && c.componentWillMount(), c.componentDidMount != null && c.__h.push(c.componentDidMount);
        else {
          if (R.getDerivedStateFromProps == null && v !== u && c.componentWillReceiveProps != null && c.componentWillReceiveProps(v, b), !c.__e && c.shouldComponentUpdate != null && c.shouldComponentUpdate(v, c.__s, b) === !1 || e.__v === n.__v) {
            for (e.__v !== n.__v && (c.props = v, c.state = c.__s, c.__d = !1), c.__e = !1, e.__e = n.__e, e.__k = n.__k, e.__k.forEach(function(A) {
              A && (A.__ = e);
            }), k = 0; k < c._sb.length; k++)
              c.__h.push(c._sb[k]);
            c._sb = [], c.__h.length && o.push(c);
            break t;
          }
          c.componentWillUpdate != null && c.componentWillUpdate(v, c.__s, b), c.componentDidUpdate != null && c.__h.push(function() {
            c.componentDidUpdate(u, f, p);
          });
        }
        if (c.context = b, c.props = v, c.__P = t, S = q.__r, E = 0, "prototype" in R && R.prototype.render) {
          for (c.state = c.__s, c.__d = !1, S && S(e), h = c.render(c.props, c.state, c.context), P = 0; P < c._sb.length; P++)
            c.__h.push(c._sb[P]);
          c._sb = [];
        } else
          do
            c.__d = !1, S && S(e), h = c.render(c.props, c.state, c.context), c.state = c.__s;
          while (c.__d && ++E < 25);
        c.state = c.__s, c.getChildContext != null && (s = ge(ge({}, s), c.getChildContext())), d || c.getSnapshotBeforeUpdate == null || (p = c.getSnapshotBeforeUpdate(u, f)), mc(t, Fo(M = h != null && h.type === Zi && h.key == null ? h.props.children : h) ? M : [M], e, n, s, i, r, o, a, l), c.base = e.__e, e.__h = null, c.__h.length && o.push(c), m && (c.__E = c.__ = null), c.__e = !1;
      } else
        r == null && e.__v === n.__v ? (e.__k = n.__k, e.__e = n.__e) : e.__e = Iu(n.__e, e, n, s, i, r, o, l);
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
function Iu(t, e, n, s, i, r, o, a) {
  var l, h, c, d = n.props, u = e.props, f = e.type, p = 0;
  if (f === "svg" && (i = !0), r != null) {
    for (; p < r.length; p++)
      if ((l = r[p]) && "setAttribute" in l == !!f && (f ? l.localName === f : l.nodeType === 3)) {
        t = l, r[p] = null;
        break;
      }
  }
  if (t == null) {
    if (f === null)
      return document.createTextNode(u);
    t = i ? document.createElementNS("http://www.w3.org/2000/svg", f) : document.createElement(f, u.is && u), r = null, a = !1;
  }
  if (f === null)
    d === u || a && t.data === u || (t.data = u);
  else {
    if (r = r && Ji.call(t.childNodes), h = (d = n.props || mi).dangerouslySetInnerHTML, c = u.dangerouslySetInnerHTML, !a) {
      if (r != null)
        for (d = {}, p = 0; p < t.attributes.length; p++)
          d[t.attributes[p].name] = t.attributes[p].value;
      (c || h) && (c && (h && c.__html == h.__html || c.__html === t.innerHTML) || (t.innerHTML = c && c.__html || ""));
    }
    if (Wu(t, u, d, i, a), c)
      e.__k = [];
    else if (mc(t, Fo(p = e.props.children) ? p : [p], e, n, s, i && f !== "foreignObject", r, o, r ? r[0] : n.__k && hs(n, 0), a), r != null)
      for (p = r.length; p--; )
        r[p] != null && pc(r[p]);
    a || ("value" in u && (p = u.value) !== void 0 && (p !== t.value || f === "progress" && !p || f === "option" && p !== d.value) && wi(t, "value", p, d.value, !1), "checked" in u && (p = u.checked) !== void 0 && p !== t.checked && wi(t, "checked", p, d.checked, !1));
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
function Ou(t, e, n) {
  return this.constructor(t, n);
}
function ds(t, e, n) {
  var s, i, r;
  q.__ && q.__(t, e), i = (s = typeof n == "function") ? null : n && n.__k || e.__k, r = [], jo(e, t = (!s && n || e).__k = _(Zi, null, [t]), i || mi, mi, e.ownerSVGElement !== void 0, !s && n ? [n] : i ? null : e.firstChild ? Ji.call(e.childNodes) : null, r, !s && n ? n : i ? i.__e : e.firstChild, s), bc(r, t);
}
Ji = fc.slice, q = { __e: function(t, e, n, s) {
  for (var i, r, o; e = e.__; )
    if ((i = e.__c) && !i.__)
      try {
        if ((r = i.constructor) && r.getDerivedStateFromError != null && (i.setState(r.getDerivedStateFromError(t)), o = i.__d), i.componentDidCatch != null && (i.componentDidCatch(t, s || {}), o = i.__d), o)
          return i.__E = i;
      } catch (a) {
        t = a;
      }
  throw t;
} }, dc = 0, it = function(t) {
  return t != null && t.constructor === void 0;
}, U.prototype.setState = function(t, e) {
  var n;
  n = this.__s != null && this.__s !== this.state ? this.__s : this.__s = ge({}, this.state), typeof t == "function" && (t = t(ge({}, n), this.props)), t && ge(n, t), t != null && this.__v && (e && this._sb.push(e), al(this));
}, U.prototype.forceUpdate = function(t) {
  this.__v && (this.__e = !0, t && this.__h.push(t), al(this));
}, U.prototype.render = Zi, Te = [], uc = typeof Promise == "function" ? Promise.prototype.then.bind(Promise.resolve()) : setTimeout, Lr = function(t, e) {
  return t.__v.__b - e.__v.__b;
}, yi.__r = 0;
var $c = function(t, e, n, s) {
  var i;
  e[0] = 0;
  for (var r = 1; r < e.length; r++) {
    var o = e[r++], a = e[r] ? (e[0] |= o ? 1 : 2, n[e[r++]]) : e[++r];
    o === 3 ? s[0] = a : o === 4 ? s[1] = Object.assign(s[1] || {}, a) : o === 5 ? (s[1] = s[1] || {})[e[++r]] = a : o === 6 ? s[1][e[++r]] += a + "" : o ? (i = t.apply(a, $c(t, a, n, ["", null])), s.push(i), a[0] ? e[0] |= 2 : (e[r - 2] = 0, e[r] = i)) : s.push(a);
  }
  return s;
}, dl = /* @__PURE__ */ new Map();
function Hu(t) {
  var e = dl.get(this);
  return e || (e = /* @__PURE__ */ new Map(), dl.set(this, e)), (e = $c(this, e.get(t) || (e.set(t, e = function(n) {
    for (var s, i, r = 1, o = "", a = "", l = [0], h = function(u) {
      r === 1 && (u || (o = o.replace(/^\s*\n\s*|\s*\n\s*$/g, ""))) ? l.push(0, u, o) : r === 3 && (u || o) ? (l.push(3, u, o), r = 2) : r === 2 && o === "..." && u ? l.push(4, u, 0) : r === 2 && o && !u ? l.push(5, 0, !0, o) : r >= 5 && ((o || !u && r === 5) && (l.push(r, 0, o, i), r = 6), u && (l.push(r, u, 0, i), r = 6)), o = "";
    }, c = 0; c < n.length; c++) {
      c && (r === 1 && h(), h(c));
      for (var d = 0; d < n[c].length; d++)
        s = n[c][d], r === 1 ? s === "<" ? (h(), l = [l], r = 3) : o += s : r === 4 ? o === "--" && s === ">" ? (r = 1, o = "") : o = s + o[0] : a ? s === a ? a = "" : o += s : s === '"' || s === "'" ? a = s : s === ">" ? (h(), r = 1) : r && (s === "=" ? (r = 5, i = o, o = "") : s === "/" && (r < 5 || n[c][d + 1] === ">") ? (h(), r === 3 && (l = l[0]), r = l, (l = l[0]).push(2, 0, r), r = 0) : s === " " || s === "	" || s === `
` || s === "\r" ? (h(), r = 2) : o += s), r === 3 && o === "!--" && (r = 4, l = l[0]);
    }
    return h(), l;
  }(t)), e), arguments, [])).length > 1 ? e : e[0];
}
const Pg = Hu.bind(_);
function Bu(t) {
  const { tagName: e = "div", className: n, style: s, children: i, attrs: r, ...o } = t;
  return _(e, { class: L(n), style: s, ...o, ...r }, i);
}
var zu = 0;
function y(t, e, n, s, i, r) {
  var o, a, l = {};
  for (a in e)
    a == "ref" ? o = e[a] : l[a] = e[a];
  var h = { type: t, props: l, key: n, ref: o, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --zu, __source: i, __self: r };
  if (typeof t == "function" && (o = t.defaultProps))
    for (a in o)
      l[a] === void 0 && (l[a] = o[a]);
  return q.vnode && q.vnode(h), h;
}
var ws;
class kc extends U {
  constructor() {
    super(...arguments);
    I(this, ws, $t());
  }
  componentDidMount() {
    this.props.executeScript && g(W(this, ws).current).runJS();
  }
  render(n) {
    const { executeScript: s, html: i, ...r } = n;
    return /* @__PURE__ */ y(Bu, { ref: W(this, ws), dangerouslySetInnerHTML: { __html: i }, ...r });
  }
}
ws = new WeakMap();
function Fu(t) {
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
  } = t, d = [n], u = { ...s }, f = [], p = [];
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
        /* @__PURE__ */ y("div", { className: L(w.className), style: w.style, dangerouslySetInnerHTML: { __html: w.html }, ...w.attrs ?? {} })
      ) : w.__html ? p.push(w.__html) : (w.style && Object.assign(u, w.style), w.className && d.push(w.className), w.children && f.push(w.children), w.attrs && Object.assign(c, w.attrs)) : f.push(w));
    });
  }), p.length && Object.assign(c, { dangerouslySetInnerHTML: { __html: p } }), [{
    className: L(d),
    style: u,
    ...c
  }, f];
}
function Uo({
  tag: t = "div",
  ...e
}) {
  const [n, s] = Fu(e);
  return _(t, n, ...s);
}
function us(t) {
  const { icon: e, className: n, ...s } = t;
  if (!e)
    return null;
  if (it(e))
    return e;
  const i = ["icon", n];
  return typeof e == "string" ? i.push(e.startsWith("icon-") ? e : `icon-${e}`) : typeof e == "object" && (i.push(e.className), Object.assign(s, e)), /* @__PURE__ */ y("i", { className: L(i), ...s });
}
function ju(t) {
  return this.getChildContext = () => t.context, t.children;
}
function Uu(t) {
  const e = this, n = t._container;
  e.componentWillUnmount = function() {
    ds(null, e._temp), e._temp = null, e._container = null;
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
  }), ds(
    _(ju, { context: e.context }, t._vnode),
    e._temp
  )) : e._temp && e.componentWillUnmount();
}
function qu(t, e) {
  const n = _(Uu, { _vnode: t, _container: e });
  return n.containerInfo = e, n;
}
var qo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Wt = (t, e, n) => (qo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Rn = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ve = (t, e, n, s) => (qo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Ds = (t, e, n) => (qo(t, e, "access private method"), n), ke, In, Gs, Ce, Xe, On;
const Cc = class {
  /**
   * The component constructor.
   *
   * @param options The component initial options.
   */
  constructor(t, e) {
    Rn(this, Xe), Rn(this, ke, void 0), Rn(this, In, void 0), Rn(this, Gs, void 0), Rn(this, Ce, void 0);
    const { KEY: n, DATA_KEY: s, DEFAULT: i, MULTI_INSTANCE: r } = this.constructor, o = g(t);
    if (o.data(n) && !r)
      throw new Error("[ZUI] The component has been initialized on element.");
    const a = g.guid++;
    if (Ve(this, Gs, a), Ve(this, In, o[0]), o.on("DOMNodeRemovedFromDocument", () => {
      this.destroy();
    }), Ve(this, ke, { ...i, ...o.dataset() }), this.setOptions(e), Ve(this, Ce, this.options.key ?? `__${a}`), o.data(n, this).attr(s, `${a}`), r) {
      const l = `${n}:ALL`;
      let h = o.data(l);
      h || (h = /* @__PURE__ */ new Map(), o.data(l, h)), h.set(Wt(this, Ce), this);
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
    return Wt(this, In);
  }
  get key() {
    return Wt(this, Ce);
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
    return Wt(this, Gs);
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
        if (i.delete(Wt(this, Ce)), i.size === 0)
          this.$element.removeData(`${t}:ALL`);
        else {
          const r = i.values().next().value;
          s.data(t, r).attr(e, r.gid);
        }
    }
    Ve(this, ke, void 0), Ve(this, In, void 0);
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
    const n = g.Event(Ds(this, Xe, On).call(this, t));
    return this.$element.trigger(n, [this, ...e]), n;
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  on(t, e) {
    this.$element.on(Ds(this, Xe, On).call(this, t), e);
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  one(t, e) {
    this.$element.one(Ds(this, Xe, On).call(this, t), e);
  }
  /**
   * Stop listening to a component event.
   * @param event     The event name.
   * @param callback  The event callback.
   */
  off(t, e) {
    this.$element.off(Ds(this, Xe, On).call(this, t), e);
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
    return `.${Wt(this, Ce)}${this.constructor.NAMESPACE}`;
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
let ot = Cc;
ke = /* @__PURE__ */ new WeakMap();
In = /* @__PURE__ */ new WeakMap();
Gs = /* @__PURE__ */ new WeakMap();
Ce = /* @__PURE__ */ new WeakMap();
Xe = /* @__PURE__ */ new WeakSet();
On = function(t) {
  return t.split(" ").map((e) => e.includes(".") ? e : `${e}${this.namespace}`).join(" ");
};
ot.DEFAULT = {};
ot.NAME = Cc.name;
ot.MULTI_INSTANCE = !1;
class J extends ot {
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
    ds(
      _(this.constructor.Component, {
        ref: this.ref,
        ...this.setOptions(e)
      }),
      this.element
    );
  }
}
function Vu({
  component: t = "div",
  className: e,
  children: n,
  style: s,
  attrs: i
}) {
  return _(t, {
    className: L(e),
    style: s,
    ...i
  }, n);
}
function Sc({
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
  trailingIcon: d,
  hint: u,
  checked: f,
  onClick: p,
  ...m
}) {
  const v = [
    typeof f == "boolean" ? /* @__PURE__ */ y("div", { class: `checkbox-primary${f ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) }) : null,
    /* @__PURE__ */ y(us, { icon: l }),
    /* @__PURE__ */ y("span", { className: "text", children: h }),
    typeof s == "function" ? s() : s,
    /* @__PURE__ */ y(us, { icon: d })
  ];
  return _(e, {
    className: L(n, { disabled: o, active: a }),
    title: u,
    [e === "a" ? "href" : "data-url"]: r,
    [e === "a" ? "target" : "data-target"]: c,
    onClick: p,
    ...m,
    ...i
  }, ...v);
}
function Gu({
  component: t = "div",
  className: e,
  text: n,
  attrs: s,
  children: i,
  style: r,
  onClick: o
}) {
  return _(t, {
    className: L(e),
    style: r,
    onClick: o,
    ...s
  }, n, typeof i == "function" ? i() : i);
}
function Yu({
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
    className: L(e),
    style: { width: s, height: s, flex: i, ...n },
    onClick: o,
    ...r
  }, a);
}
function Ku({ type: t, ...e }) {
  return /* @__PURE__ */ y(Uo, { ...e });
}
function Ec({
  component: t = "div",
  className: e,
  children: n,
  style: s,
  attrs: i
}) {
  return _(t, {
    className: L(e),
    style: s,
    ...i
  }, n);
}
const Dr = class extends U {
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
    return s && Object.assign(r, s[e.type || "item"]), (i || e.onClick) && (r.onClick = this.handleItemClick.bind(this, r, n, e.onClick)), r.className = L(r.className), r;
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
    const { type: r = "item", component: o, key: a = n, rootAttrs: l, rootClass: h, rootStyle: c, rootChildren: d, ...u } = s;
    if (r === "html")
      return /* @__PURE__ */ y(
        "li",
        {
          className: L("action-menu-item", `${this.name}-html`, h, u.className),
          ...l,
          style: c || u.style,
          dangerouslySetInnerHTML: { __html: u.html }
        },
        a
      );
    const f = !o || typeof o == "string" ? this.constructor.ItemComponents && this.constructor.ItemComponents[r] || Dr.ItemComponents[r] : o;
    return Object.assign(u, {
      type: r,
      component: typeof o == "string" ? o : void 0
    }), t.checkbox && r === "item" && u.checked === void 0 && (u.checked = !!u.active), this.renderTypedItem(f, {
      className: L(h),
      children: d,
      style: c,
      key: a,
      ...l
    }, {
      ...u,
      type: r,
      component: typeof o == "string" ? o : void 0
    });
  }
  renderTypedItem(t, e, n) {
    const { children: s, className: i, key: r, ...o } = e;
    return /* @__PURE__ */ y(
      "li",
      {
        className: L(`${this.constructor.NAME}-item`, `${this.name}-${n.type}`, i),
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
      beforeDestroy: d,
      ...u
    } = t, f = this.constructor.ROOT_TAG;
    return /* @__PURE__ */ y(f, { class: L(this.name, i), style: n, ...u, ref: this.ref, children: [
      r && r.map(this.renderItem.bind(this, t)),
      o
    ] });
  }
};
let qe = Dr;
qe.ItemComponents = {
  divider: Vu,
  item: Sc,
  heading: Gu,
  space: Yu,
  custom: Ku,
  basic: Ec
};
qe.ROOT_TAG = "menu";
qe.NAME = "action-menu";
class Mc extends J {
}
Mc.NAME = "ActionMenu";
Mc.Component = qe;
function Xu({
  items: t,
  show: e,
  level: n,
  ...s
}) {
  return /* @__PURE__ */ y(Sc, { ...s });
}
var Tc = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, wt = (t, e, n) => (Tc(t, e, "read from private field"), n ? n.call(t) : e.get(t)), br = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ju = (t, e, n, s) => (Tc(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Ys, Ht, Hn;
let Qi = class extends qe {
  constructor(e) {
    super(e), br(this, Ys, /* @__PURE__ */ new Set()), br(this, Ht, void 0), br(this, Hn, (n, s, i) => {
      g(i.target).closest(".not-nested-toggle").length || (this.toggleNestedMenu(n, s), i.preventDefault());
    }), Ju(this, Ht, e.nestedShow === void 0), wt(this, Ht) && (this.state = { nestedShow: e.defaultNestedShow ?? {} });
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
    const { name: n, controlledMenu: s, nestedShow: i, beforeDestroy: r, beforeRender: o, itemRender: a, onClickItem: l, afterRender: h, commonItemProps: c, level: d } = this.props;
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
      level: (d || 0) + 1
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
    wt(this, Ys).add(r);
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
    if (typeof i == "boolean" && (i === !0 ? i = [...wt(this, Ys).values()].reduce((r, o) => (r[o] = !0, r), {}) : i = {}), n === void 0)
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
Ys = /* @__PURE__ */ new WeakMap();
Ht = /* @__PURE__ */ new WeakMap();
Hn = /* @__PURE__ */ new WeakMap();
Qi.ItemComponents = {
  item: Xu
};
class Rc extends J {
}
Rc.NAME = "ActionMenuNested";
Rc.Component = Qi;
let tr = class extends Qi {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "menu-nested";
  }
  beforeRender() {
    const e = super.beforeRender();
    let { hasIcons: n } = e;
    return n === void 0 && (n = e.items.some((s) => s.icon)), e.className = L(e.className, this.menuName, {
      "has-icons": n,
      "has-nested-items": e.items.some((s) => this.isNestedItem(s)),
      "menu-popup": e.popup
    }), e;
  }
  renderToggleIcon(e) {
    return /* @__PURE__ */ y("span", { class: `${this.name}-toggle-icon caret-${e ? "down" : "right"}` });
  }
};
tr.NAME = "menu";
class Ac extends J {
}
Ac.NAME = "Menu";
Ac.Component = tr;
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
      loading: d,
      loadingIcon: u,
      loadingText: f,
      icon: p,
      text: m,
      trailingIcon: v,
      caret: w,
      square: b,
      hint: k,
      ...S
    } = this.props, E = e || (a ? "a" : "button"), P = m == null || typeof m == "string" && !m.length || d && !f, M = w && P && !p && !v && !o && !d;
    return _(
      E,
      {
        className: L("btn", n, r, {
          "btn-caret": M,
          disabled: h || d,
          active: c,
          loading: d,
          square: b === void 0 ? !M && !o && P : b
        }, i ? `size-${i}` : ""),
        title: k,
        [E === "a" ? "href" : "data-url"]: a,
        [E === "a" ? "target" : "data-target"]: l,
        type: E === "button" ? s : void 0,
        ...S
      },
      /* @__PURE__ */ y(us, { icon: d ? `icon ${u || "icon-spinner-snake"} spin` : p }),
      P ? null : /* @__PURE__ */ y("span", { className: "text", children: d ? f : m }),
      d ? null : o,
      d ? null : typeof v == "string" ? /* @__PURE__ */ y("i", { class: `icon ${v}` }) : v,
      d ? null : w ? /* @__PURE__ */ y("span", { className: typeof w == "string" ? `caret-${w}` : "caret" }) : null
    );
  }
}
function Zu({
  key: t,
  type: e,
  btnType: n,
  ...s
}) {
  return /* @__PURE__ */ y(Lt, { type: n, ...s });
}
function Cs(t) {
  return t.split("-")[1];
}
function Vo(t) {
  return t === "y" ? "height" : "width";
}
function De(t) {
  return t.split("-")[0];
}
function Ss(t) {
  return ["top", "bottom"].includes(De(t)) ? "x" : "y";
}
function ul(t, e, n) {
  let { reference: s, floating: i } = t;
  const r = s.x + s.width / 2 - i.width / 2, o = s.y + s.height / 2 - i.height / 2, a = Ss(e), l = Vo(a), h = s[l] / 2 - i[l] / 2, c = a === "x";
  let d;
  switch (De(e)) {
    case "top":
      d = { x: r, y: s.y - i.height };
      break;
    case "bottom":
      d = { x: r, y: s.y + s.height };
      break;
    case "right":
      d = { x: s.x + s.width, y: o };
      break;
    case "left":
      d = { x: s.x - i.width, y: o };
      break;
    default:
      d = { x: s.x, y: s.y };
  }
  switch (Cs(e)) {
    case "start":
      d[a] -= h * (n && c ? -1 : 1);
      break;
    case "end":
      d[a] += h * (n && c ? -1 : 1);
  }
  return d;
}
const Qu = async (t, e, n) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: r = [], platform: o } = n, a = r.filter(Boolean), l = await (o.isRTL == null ? void 0 : o.isRTL(e));
  let h = await o.getElementRects({ reference: t, floating: e, strategy: i }), { x: c, y: d } = ul(h, s, l), u = s, f = {}, p = 0;
  for (let m = 0; m < a.length; m++) {
    const { name: v, fn: w } = a[m], { x: b, y: k, data: S, reset: E } = await w({ x: c, y: d, initialPlacement: s, placement: u, strategy: i, middlewareData: f, rects: h, platform: o, elements: { reference: t, floating: e } });
    c = b ?? c, d = k ?? d, f = { ...f, [v]: { ...f[v], ...S } }, E && p <= 50 && (p++, typeof E == "object" && (E.placement && (u = E.placement), E.rects && (h = E.rects === !0 ? await o.getElementRects({ reference: t, floating: e, strategy: i }) : E.rects), { x: c, y: d } = ul(h, u, l)), m = -1);
  }
  return { x: c, y: d, placement: u, strategy: i, middlewareData: f };
};
function Es(t, e) {
  return typeof t == "function" ? t(e) : t;
}
function Nc(t) {
  return typeof t != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(t) : { top: t, right: t, bottom: t, left: t };
}
function vi(t) {
  return { ...t, top: t.y, left: t.x, right: t.x + t.width, bottom: t.y + t.height };
}
async function Lc(t, e) {
  var n;
  e === void 0 && (e = {});
  const { x: s, y: i, platform: r, rects: o, elements: a, strategy: l } = t, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: d = "floating", altBoundary: u = !1, padding: f = 0 } = Es(e, t), p = Nc(f), m = a[u ? d === "floating" ? "reference" : "floating" : d], v = vi(await r.getClippingRect({ element: (n = await (r.isElement == null ? void 0 : r.isElement(m))) == null || n ? m : m.contextElement || await (r.getDocumentElement == null ? void 0 : r.getDocumentElement(a.floating)), boundary: h, rootBoundary: c, strategy: l })), w = d === "floating" ? { ...o.floating, x: s, y: i } : o.reference, b = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(a.floating)), k = await (r.isElement == null ? void 0 : r.isElement(b)) && await (r.getScale == null ? void 0 : r.getScale(b)) || { x: 1, y: 1 }, S = vi(r.convertOffsetParentRelativeRectToViewportRelativeRect ? await r.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: w, offsetParent: b, strategy: l }) : w);
  return { top: (v.top - S.top + p.top) / k.y, bottom: (S.bottom - v.bottom + p.bottom) / k.y, left: (v.left - S.left + p.left) / k.x, right: (S.right - v.right + p.right) / k.x };
}
const Pr = Math.min, tf = Math.max;
function Wr(t, e, n) {
  return tf(t, Pr(e, n));
}
const Ir = (t) => ({ name: "arrow", options: t, async fn(e) {
  const { x: n, y: s, placement: i, rects: r, platform: o, elements: a } = e, { element: l, padding: h = 0 } = Es(t, e) || {};
  if (l == null)
    return {};
  const c = Nc(h), d = { x: n, y: s }, u = Ss(i), f = Vo(u), p = await o.getDimensions(l), m = u === "y", v = m ? "top" : "left", w = m ? "bottom" : "right", b = m ? "clientHeight" : "clientWidth", k = r.reference[f] + r.reference[u] - d[u] - r.floating[f], S = d[u] - r.reference[u], E = await (o.getOffsetParent == null ? void 0 : o.getOffsetParent(l));
  let P = E ? E[b] : 0;
  P && await (o.isElement == null ? void 0 : o.isElement(E)) || (P = a.floating[b] || r.floating[f]);
  const M = k / 2 - S / 2, R = P / 2 - p[f] / 2 - 1, A = Pr(c[v], R), x = Pr(c[w], R), $ = A, N = P - p[f] - x, D = P / 2 - p[f] / 2 + M, O = Wr($, D, N), B = Cs(i) != null && D != O && r.reference[f] / 2 - (D < $ ? A : x) - p[f] / 2 < 0;
  return { [u]: d[u] - (B ? D < $ ? $ - D : N - D : 0), data: { [u]: O, centerOffset: D - O } };
} }), ef = ["top", "right", "bottom", "left"];
ef.reduce((t, e) => t.concat(e, e + "-start", e + "-end"), []);
const nf = { left: "right", right: "left", bottom: "top", top: "bottom" };
function bi(t) {
  return t.replace(/left|right|bottom|top/g, (e) => nf[e]);
}
function sf(t, e, n) {
  n === void 0 && (n = !1);
  const s = Cs(t), i = Ss(t), r = Vo(i);
  let o = i === "x" ? s === (n ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return e.reference[r] > e.floating[r] && (o = bi(o)), { main: o, cross: bi(o) };
}
const rf = { start: "end", end: "start" };
function _r(t) {
  return t.replace(/start|end/g, (e) => rf[e]);
}
const er = function(t) {
  return t === void 0 && (t = {}), { name: "flip", options: t, async fn(e) {
    var n;
    const { placement: s, middlewareData: i, rects: r, initialPlacement: o, platform: a, elements: l } = e, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: d, fallbackStrategy: u = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...m } = Es(t, e), v = De(s), w = De(o) === o, b = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), k = d || (w || !p ? [bi(o)] : function($) {
      const N = bi($);
      return [_r($), N, _r(N)];
    }(o));
    d || f === "none" || k.push(...function($, N, D, O) {
      const B = Cs($);
      let G = function(Pt, Mn, Ns) {
        const Tn = ["left", "right"], Ls = ["right", "left"], gr = ["top", "bottom"], zd = ["bottom", "top"];
        switch (Pt) {
          case "top":
          case "bottom":
            return Ns ? Mn ? Ls : Tn : Mn ? Tn : Ls;
          case "left":
          case "right":
            return Mn ? gr : zd;
          default:
            return [];
        }
      }(De($), D === "start", O);
      return B && (G = G.map((Pt) => Pt + "-" + B), N && (G = G.concat(G.map(_r)))), G;
    }(o, p, f, b));
    const S = [o, ...k], E = await Lc(e, m), P = [];
    let M = ((n = i.flip) == null ? void 0 : n.overflows) || [];
    if (h && P.push(E[v]), c) {
      const { main: $, cross: N } = sf(s, r, b);
      P.push(E[$], E[N]);
    }
    if (M = [...M, { placement: s, overflows: P }], !P.every(($) => $ <= 0)) {
      var R, A;
      const $ = (((R = i.flip) == null ? void 0 : R.index) || 0) + 1, N = S[$];
      if (N)
        return { data: { index: $, overflows: M }, reset: { placement: N } };
      let D = (A = M.filter((O) => O.overflows[0] <= 0).sort((O, B) => O.overflows[1] - B.overflows[1])[0]) == null ? void 0 : A.placement;
      if (!D)
        switch (u) {
          case "bestFit": {
            var x;
            const O = (x = M.map((B) => [B.placement, B.overflows.filter((G) => G > 0).reduce((G, Pt) => G + Pt, 0)]).sort((B, G) => B[1] - G[1])[0]) == null ? void 0 : x[0];
            O && (D = O);
            break;
          }
          case "initialPlacement":
            D = o;
        }
      if (s !== D)
        return { reset: { placement: D } };
    }
    return {};
  } };
}, Go = function(t) {
  return t === void 0 && (t = 0), { name: "offset", options: t, async fn(e) {
    const { x: n, y: s } = e, i = await async function(r, o) {
      const { placement: a, platform: l, elements: h } = r, c = await (l.isRTL == null ? void 0 : l.isRTL(h.floating)), d = De(a), u = Cs(a), f = Ss(a) === "x", p = ["left", "top"].includes(d) ? -1 : 1, m = c && f ? -1 : 1, v = Es(o, r);
      let { mainAxis: w, crossAxis: b, alignmentAxis: k } = typeof v == "number" ? { mainAxis: v, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...v };
      return u && typeof k == "number" && (b = u === "end" ? -1 * k : k), f ? { x: b * m, y: w * p } : { x: w * p, y: b * m };
    }(e, t);
    return { x: n + i.x, y: s + i.y, data: i };
  } };
};
function of(t) {
  return t === "x" ? "y" : "x";
}
const Or = function(t) {
  return t === void 0 && (t = {}), { name: "shift", options: t, async fn(e) {
    const { x: n, y: s, placement: i } = e, { mainAxis: r = !0, crossAxis: o = !1, limiter: a = { fn: (v) => {
      let { x: w, y: b } = v;
      return { x: w, y: b };
    } }, ...l } = Es(t, e), h = { x: n, y: s }, c = await Lc(e, l), d = Ss(De(i)), u = of(d);
    let f = h[d], p = h[u];
    if (r) {
      const v = d === "y" ? "bottom" : "right";
      f = Wr(f + c[d === "y" ? "top" : "left"], f, f - c[v]);
    }
    if (o) {
      const v = u === "y" ? "bottom" : "right";
      p = Wr(p + c[u === "y" ? "top" : "left"], p, p - c[v]);
    }
    const m = a.fn({ ...e, [d]: f, [u]: p });
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
function af(t) {
  return ["table", "td", "th"].includes(be(t));
}
function Hr(t) {
  const e = Yo(), n = _t(t);
  return n.transform !== "none" || n.perspective !== "none" || !e && !!n.backdropFilter && n.backdropFilter !== "none" || !e && !!n.filter && n.filter !== "none" || ["transform", "perspective", "filter"].some((s) => (n.willChange || "").includes(s)) || ["paint", "layout", "strict", "content"].some((s) => (n.contain || "").includes(s));
}
function Yo() {
  return !(typeof CSS > "u" || !CSS.supports) && CSS.supports("-webkit-backdrop-filter", "none");
}
function nr(t) {
  return ["html", "body", "#document"].includes(be(t));
}
const pl = Math.min, Qn = Math.max, _i = Math.round;
function Pc(t) {
  const e = _t(t);
  let n = parseFloat(e.width) || 0, s = parseFloat(e.height) || 0;
  const i = kt(t), r = i ? t.offsetWidth : n, o = i ? t.offsetHeight : s, a = _i(n) !== r || _i(s) !== o;
  return a && (n = r, s = o), { width: n, height: s, fallback: a };
}
function Wc(t) {
  return ht(t) ? t : t.contextElement;
}
const Ic = { x: 1, y: 1 };
function cn(t) {
  const e = Wc(t);
  if (!kt(e))
    return Ic;
  const n = e.getBoundingClientRect(), { width: s, height: i, fallback: r } = Pc(e);
  let o = (r ? _i(n.width) : n.width) / s, a = (r ? _i(n.height) : n.height) / i;
  return o && Number.isFinite(o) || (o = 1), a && Number.isFinite(a) || (a = 1), { x: o, y: a };
}
const gl = { x: 0, y: 0 };
function Oc(t, e, n) {
  var s, i;
  if (e === void 0 && (e = !0), !Yo())
    return gl;
  const r = t ? mt(t) : window;
  return !n || e && n !== r ? gl : { x: ((s = r.visualViewport) == null ? void 0 : s.offsetLeft) || 0, y: ((i = r.visualViewport) == null ? void 0 : i.offsetTop) || 0 };
}
function He(t, e, n, s) {
  e === void 0 && (e = !1), n === void 0 && (n = !1);
  const i = t.getBoundingClientRect(), r = Wc(t);
  let o = Ic;
  e && (s ? ht(s) && (o = cn(s)) : o = cn(t));
  const a = Oc(r, n, s);
  let l = (i.left + a.x) / o.x, h = (i.top + a.y) / o.y, c = i.width / o.x, d = i.height / o.y;
  if (r) {
    const u = mt(r), f = s && ht(s) ? mt(s) : s;
    let p = u.frameElement;
    for (; p && s && f !== u; ) {
      const m = cn(p), v = p.getBoundingClientRect(), w = getComputedStyle(p);
      v.x += (p.clientLeft + parseFloat(w.paddingLeft)) * m.x, v.y += (p.clientTop + parseFloat(w.paddingTop)) * m.y, l *= m.x, h *= m.y, c *= m.x, d *= m.y, l += v.x, h += v.y, p = mt(p).frameElement;
    }
  }
  return vi({ width: c, height: d, x: l, y: h });
}
function ye(t) {
  return ((Dc(t) ? t.ownerDocument : t.document) || window.document).documentElement;
}
function sr(t) {
  return ht(t) ? { scrollLeft: t.scrollLeft, scrollTop: t.scrollTop } : { scrollLeft: t.pageXOffset, scrollTop: t.pageYOffset };
}
function Hc(t) {
  return He(ye(t)).left + sr(t).scrollLeft;
}
function Sn(t) {
  if (be(t) === "html")
    return t;
  const e = t.assignedSlot || t.parentNode || fl(t) && t.host || ye(t);
  return fl(e) ? e.host : e;
}
function Bc(t) {
  const e = Sn(t);
  return nr(e) ? e.ownerDocument.body : kt(e) && fs(e) ? e : Bc(e);
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
      let h = a.clientWidth, c = a.clientHeight, d = 0, u = 0;
      if (l) {
        h = l.width, c = l.height;
        const f = Yo();
        (!f || f && r === "fixed") && (d = l.offsetLeft, u = l.offsetTop);
      }
      return { width: h, height: c, x: d, y: u };
    }(t, n);
  else if (e === "document")
    s = function(i) {
      const r = ye(i), o = sr(i), a = i.ownerDocument.body, l = Qn(r.scrollWidth, r.clientWidth, a.scrollWidth, a.clientWidth), h = Qn(r.scrollHeight, r.clientHeight, a.scrollHeight, a.clientHeight);
      let c = -o.scrollLeft + Hc(i);
      const d = -o.scrollTop;
      return _t(a).direction === "rtl" && (c += Qn(r.clientWidth, a.clientWidth) - l), { width: l, height: h, x: c, y: d };
    }(ye(t));
  else if (ht(e))
    s = function(i, r) {
      const o = He(i, !0, r === "fixed"), a = o.top + i.clientTop, l = o.left + i.clientLeft, h = kt(i) ? cn(i) : { x: 1, y: 1 };
      return { width: i.clientWidth * h.x, height: i.clientHeight * h.y, x: l * h.x, y: a * h.y };
    }(e, n);
  else {
    const i = Oc(t);
    s = { ...e, x: e.x - i.x, y: e.y - i.y };
  }
  return vi(s);
}
function zc(t, e) {
  const n = Sn(t);
  return !(n === e || !ht(n) || nr(n)) && (_t(n).position === "fixed" || zc(n, e));
}
function yl(t, e) {
  return kt(t) && _t(t).position !== "fixed" ? e ? e(t) : t.offsetParent : null;
}
function wl(t, e) {
  const n = mt(t);
  if (!kt(t))
    return n;
  let s = yl(t, e);
  for (; s && af(s) && _t(s).position === "static"; )
    s = yl(s, e);
  return s && (be(s) === "html" || be(s) === "body" && _t(s).position === "static" && !Hr(s)) ? n : s || function(i) {
    let r = Sn(i);
    for (; kt(r) && !nr(r); ) {
      if (Hr(r))
        return r;
      r = Sn(r);
    }
    return null;
  }(t) || n;
}
function lf(t, e, n) {
  const s = kt(e), i = ye(e), r = n === "fixed", o = He(t, !0, r, e);
  let a = { scrollLeft: 0, scrollTop: 0 };
  const l = { x: 0, y: 0 };
  if (s || !s && !r)
    if ((be(e) !== "body" || fs(i)) && (a = sr(e)), kt(e)) {
      const h = He(e, !0, r, e);
      l.x = h.x + e.clientLeft, l.y = h.y + e.clientTop;
    } else
      i && (l.x = Hc(i));
  return { x: o.left + a.scrollLeft - l.x, y: o.top + a.scrollTop - l.y, width: o.width, height: o.height };
}
const cf = { getClippingRect: function(t) {
  let { element: e, boundary: n, rootBoundary: s, strategy: i } = t;
  const r = n === "clippingAncestors" ? function(h, c) {
    const d = c.get(h);
    if (d)
      return d;
    let u = ts(h).filter((v) => ht(v) && be(v) !== "body"), f = null;
    const p = _t(h).position === "fixed";
    let m = p ? Sn(h) : h;
    for (; ht(m) && !nr(m); ) {
      const v = _t(m), w = Hr(m);
      w || v.position !== "fixed" || (f = null), (p ? !w && !f : !w && v.position === "static" && f && ["absolute", "fixed"].includes(f.position) || fs(m) && !w && zc(h, m)) ? u = u.filter((b) => b !== m) : f = v, m = Sn(m);
    }
    return c.set(h, u), u;
  }(e, this._c) : [].concat(n), o = [...r, s], a = o[0], l = o.reduce((h, c) => {
    const d = ml(e, c, i);
    return h.top = Qn(d.top, h.top), h.right = pl(d.right, h.right), h.bottom = pl(d.bottom, h.bottom), h.left = Qn(d.left, h.left), h;
  }, ml(e, a, i));
  return { width: l.right - l.left, height: l.bottom - l.top, x: l.left, y: l.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(t) {
  let { rect: e, offsetParent: n, strategy: s } = t;
  const i = kt(n), r = ye(n);
  if (n === r)
    return e;
  let o = { scrollLeft: 0, scrollTop: 0 }, a = { x: 1, y: 1 };
  const l = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((be(n) !== "body" || fs(r)) && (o = sr(n)), kt(n))) {
    const h = He(n);
    a = cn(n), l.x = h.x + n.clientLeft, l.y = h.y + n.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - o.scrollLeft * a.x + l.x, y: e.y * a.y - o.scrollTop * a.y + l.y };
}, isElement: ht, getDimensions: function(t) {
  return Pc(t);
}, getOffsetParent: wl, getDocumentElement: ye, getScale: cn, async getElementRects(t) {
  let { reference: e, floating: n, strategy: s } = t;
  const i = this.getOffsetParent || wl, r = this.getDimensions;
  return { reference: lf(e, await i(n), s), floating: { x: 0, y: 0, ...await r(n) } };
}, getClientRects: (t) => Array.from(t.getClientRects()), isRTL: (t) => _t(t).direction === "rtl" };
function Ko(t, e, n, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: r = !0, elementResize: o = !0, animationFrame: a = !1 } = s, l = i || r ? [...ht(t) ? ts(t) : t.contextElement ? ts(t.contextElement) : [], ...ts(e)] : [];
  l.forEach((u) => {
    const f = !ht(u) && u.toString().includes("V");
    !i || a && !f || u.addEventListener("scroll", n, { passive: !0 }), r && u.addEventListener("resize", n);
  });
  let h, c = null;
  o && (c = new ResizeObserver(() => {
    n();
  }), ht(t) && !a && c.observe(t), ht(t) || !t.contextElement || a || c.observe(t.contextElement), c.observe(e));
  let d = a ? He(t) : null;
  return a && function u() {
    const f = He(t);
    !d || f.x === d.x && f.y === d.y && f.width === d.width && f.height === d.height || n(), d = f, h = requestAnimationFrame(u);
  }(), n(), () => {
    var u;
    l.forEach((f) => {
      i && f.removeEventListener("scroll", n), r && f.removeEventListener("resize", n);
    }), (u = c) == null || u.disconnect(), c = null, a && cancelAnimationFrame(h);
  };
}
const ir = (t, e, n) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: cf, ...n }, r = { ...i.platform, _c: s };
  return Qu(t, e, { ...i, platform: r });
};
let hf = class extends tr {
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
      middleware: [er()],
      placement: "right-start"
    };
  }
  getPopperElement() {
    var e;
    return (e = this.ref.current) == null ? void 0 : e.parentElement;
  }
  createPopper() {
    const e = this.getPopperOptions();
    this.ref.current && ir(this.getPopperElement(), this.ref.current, e).then(({ x: n, y: s }) => {
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
    return e.className = L(e.className, "menu-popup"), e;
  }
  renderToggleIcon() {
    return /* @__PURE__ */ y("span", { class: "contextmenu-toggle-icon caret-right" });
  }
};
var Xo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, It = (t, e, n) => (Xo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ge = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ps = (t, e, n, s) => (Xo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), vl = (t, e, n) => (Xo(t, e, "access private method"), n), oe, Bn, Ks, Xs, Br, Fc, zr, jc;
const xr = "show", df = '[data-toggle="contextmenu"]';
class bt extends ot {
  constructor() {
    super(...arguments), Ge(this, Br), Ge(this, zr), Ge(this, oe, void 0), Ge(this, Bn, void 0), Ge(this, Ks, void 0), Ge(this, Xs, void 0);
  }
  get isShown() {
    return It(this, oe) && g(It(this, oe)).hasClass(xr);
  }
  get menu() {
    return It(this, oe) || this.ensureMenu();
  }
  get trigger() {
    return It(this, Ks) || this.element;
  }
  get isDynamic() {
    return this.options.items || this.options.menu;
  }
  init() {
    const { $element: e } = this;
    !e.is("body") && !e.attr("data-toggle") && e.attr("data-toggle", this.constructor.NAME.toLowerCase());
  }
  show(e) {
    return this.isShown || (Ps(this, Ks, e), this.emit("show", this.trigger).defaultPrevented) || this.isDynamic && !this.renderMenu() ? !1 : (g(this.menu).addClass(xr), this.createPopper(), this.emit("shown"), !0);
  }
  hide() {
    var e;
    return !this.isShown || ((e = It(this, Xs)) == null || e.call(this), this.emit("hide").defaultPrevented) ? !1 : (g(It(this, oe)).removeClass(xr), this.emit("hidden"), !0);
  }
  toggle(e) {
    return this.isShown ? this.hide() : this.show(e);
  }
  destroy() {
    var e;
    super.destroy(), this.hide(), (e = It(this, oe)) == null || e.remove();
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
    }), Ps(this, oe, s[0]), s[0];
  }
  getPopperOptions() {
    var i;
    const { placement: e, strategy: n } = this.options, s = {
      middleware: [],
      placement: e,
      strategy: n
    };
    return this.options.flip && ((i = s.middleware) == null || i.push(er())), s;
  }
  createPopper() {
    const e = this.getPopperOptions(), n = this.getPopperElement(), s = this.menu;
    Ps(this, Xs, Ko(n, s, () => {
      ir(n, s, e).then(({ x: i, y: r, middlewareData: o, placement: a }) => {
        g(s).css({ left: `${i}px`, top: `${r}px` });
        const l = a.split("-")[0], h = vl(this, Br, Fc).call(this, l);
        if (o.arrow && this.arrowEl) {
          const { x: c, y: d } = o.arrow;
          g(this.arrowEl).css({
            left: c != null ? `${c}px` : "",
            top: d != null ? `${d}px` : "",
            [h]: `${-this.arrowEl.offsetWidth / 2}px`,
            background: "inherit",
            border: "inherit",
            ...vl(this, zr, jc).call(this, l)
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
    return !e || this.emit("updateMenu", e, this.trigger).defaultPrevented ? !1 : (ds(_(hf, e), this.menu), !0);
  }
  getPopperElement() {
    return It(this, Bn) || Ps(this, Bn, {
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
    }), It(this, Bn);
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
Ks = /* @__PURE__ */ new WeakMap();
Xs = /* @__PURE__ */ new WeakMap();
Br = /* @__PURE__ */ new WeakSet();
Fc = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
};
zr = /* @__PURE__ */ new WeakSet();
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
var Jo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, zn = (t, e, n) => (Jo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ws = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Fr = (t, e, n, s) => (Jo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), uf = (t, e, n) => (Jo(t, e, "access private method"), n), es, Fn, xi, jr, Uc;
const bl = '[data-toggle="dropdown"]', qc = class extends bt {
  constructor() {
    super(...arguments), Ws(this, jr), Ws(this, es, !1), Ws(this, Fn, 0), this.hideLater = () => {
      zn(this, xi).call(this), Fr(this, Fn, window.setTimeout(this.hide.bind(this), 100));
    }, Ws(this, xi, () => {
      clearTimeout(zn(this, Fn)), Fr(this, Fn, 0);
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
    return n && (!zn(this, es) && this.isHover && uf(this, jr, Uc).call(this), this.$element.addClass(this.elementShowClass)), n;
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
    return e && this.arrowEl && ((n = t.middleware) == null || n.push(Go(e)), (s = t.middleware) == null || s.push(Ir({ element: this.arrowEl }))), t;
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
xi = /* @__PURE__ */ new WeakMap();
jr = /* @__PURE__ */ new WeakSet();
Uc = function() {
  g(this.menu).on(`mouseenter${this.constructor.NAMESPACE}`, zn(this, xi)).on(`mouseleave${this.constructor.NAMESPACE}`, this.hideLater), this.on("mouseleave", this.hideLater), Fr(this, es, !0);
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
let Is = 0;
window.addEventListener("scroll", (t) => {
  Is && clearTimeout(Is), Is = window.setTimeout(() => {
    we.clear({ event: t }), Is = 0;
  }, 50);
}, !0);
var vs, pn;
class ff extends U {
  constructor(n) {
    var s;
    super(n);
    I(this, vs, void 0);
    I(this, pn, $t());
    this.state = { placement: ((s = n.dropdown) == null ? void 0 : s.placement) || "", show: !1 };
  }
  get ref() {
    return W(this, pn);
  }
  get triggerElement() {
    return W(this, pn).current;
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
    (n = W(this, vs)) == null || n.destroy();
  }
  beforeRender() {
    const { className: n, children: s, dropdown: i, ...r } = this.props;
    return {
      className: L("dropdown", n),
      children: typeof s == "function" ? s(this.state) : s,
      ...r,
      "data-toggle": "dropdown",
      "data-dropdown-placement": this.state.placement,
      ref: W(this, pn)
    };
  }
  render() {
    const { children: n, ...s } = this.beforeRender();
    return /* @__PURE__ */ y("div", { ...s, children: n });
  }
}
vs = new WeakMap(), pn = new WeakMap();
class pf extends ff {
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
  return /* @__PURE__ */ y(pf, { type: n, ...s });
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
      afterRender: d,
      beforeDestroy: u,
      ...f
    } = e;
    return /* @__PURE__ */ y(
      "div",
      {
        className: L("btn-group", i ? `size-${i}` : "", n),
        ...f,
        children: [
          s && s.map(this.renderItem.bind(this, e)),
          a
        ]
      }
    );
  }
};
function gf({
  key: t,
  type: e,
  btnType: n,
  ...s
}) {
  return /* @__PURE__ */ y(Gc, { type: n, ...s });
}
let ut = class extends qe {
  beforeRender() {
    const { gap: e, btnProps: n, wrap: s, ...i } = super.beforeRender();
    return i.className = L(i.className, s ? "flex-wrap" : "", typeof e == "number" ? `gap-${e}` : ""), typeof e == "string" && (i.style ? i.style.gap = e : i.style = { gap: e }), i;
  }
  isBtnItem(e) {
    return e === "item" || e === "dropdown";
  }
  renderTypedItem(e, n, s) {
    const { type: i } = s, r = this.props.btnProps, o = this.isBtnItem(i) ? { btnType: "ghost", ...r } : {}, a = {
      ...n,
      ...o,
      ...s,
      className: L(`${this.name}-${i}`, n.className, o.className, s.className),
      style: Object.assign({}, n.style, o.style, s.style)
    };
    return i === "btn-group" && (a.btnProps = r), /* @__PURE__ */ y(e, { ...a });
  }
};
ut.ItemComponents = {
  item: Zu,
  dropdown: Vc,
  "btn-group": gf
};
ut.ROOT_TAG = "nav";
ut.NAME = "toolbar";
ut.defaultProps = {
  btnProps: {
    btnType: "ghost"
  }
};
function mf({
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
  let d;
  a === !0 ? d = /* @__PURE__ */ y(Lt, { className: "alert-close btn ghost", square: !0, onClick: l, children: /* @__PURE__ */ y("span", { class: "close" }) }) : it(a) ? d = a : typeof a == "object" && (d = /* @__PURE__ */ y(Lt, { ...a, onClick: l }));
  const u = it(n) ? n : n ? /* @__PURE__ */ y(ut, { ...n }) : null;
  return /* @__PURE__ */ y("div", { className: L("alert", t), style: e, ...c, children: [
    it(h) ? h : typeof h == "string" ? /* @__PURE__ */ y("i", { className: `icon ${h}` }) : null,
    it(i) ? i : /* @__PURE__ */ y("div", { className: L("alert-content", r), children: [
      it(s) ? s : s && /* @__PURE__ */ y("div", { className: "alert-heading", children: s }),
      /* @__PURE__ */ y("div", { className: "alert-text", children: i }),
      s ? u : null
    ] }),
    s ? null : u,
    d,
    o
  ] });
}
function yf(t) {
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
let wf = class extends U {
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
      mf,
      {
        className: L("messager", l, i, o === !0 ? yf(r) : o, a ? "in" : ""),
        ...c
      }
    );
  }
};
var vf = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, bf = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, An = (t, e, n) => (vf(t, e, "access private method"), n), Se, Je;
class Zo extends J {
  constructor() {
    super(...arguments), bf(this, Se), this._show = !1, this._showTimer = 0, this._afterRender = ({ firstRender: e }) => {
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
    this.render(), this.emit("show"), An(this, Se, Je).call(this, () => {
      this._show = !0, this.render(), An(this, Se, Je).call(this, () => {
        this.emit("shown");
        const { time: e } = this.options;
        e && An(this, Se, Je).call(this, () => this.hide(), e);
      });
    }, 100);
  }
  hide() {
    this._show && An(this, Se, Je).call(this, () => {
      this.emit("hide"), this._show = !1, this.render(), An(this, Se, Je).call(this, () => {
        this.emit("hidden");
      });
    }, 50);
  }
}
Se = /* @__PURE__ */ new WeakSet();
Je = function(t, e = 200) {
  this._showTimer && clearTimeout(this._showTimer), this._showTimer = window.setTimeout(() => {
    t(), this._showTimer = 0;
  }, e);
};
Zo.NAME = "MessagerItem";
Zo.Component = wf;
var Qo = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Pe = (t, e, n) => (Qo(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Os = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Js = (t, e, n, s) => (Qo(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Yc = (t, e, n) => (Qo(t, e, "access private method"), n), hn, Vt, Ur, Kc, ta, Xc;
const Jc = class extends ot {
  constructor() {
    super(...arguments), Os(this, Ur), Os(this, ta), Os(this, hn, void 0), Os(this, Vt, void 0);
  }
  get isShown() {
    var t;
    return !!((t = Pe(this, Vt)) != null && t.isShown);
  }
  show(t) {
    this.setOptions(t), Yc(this, Ur, Kc).call(this).show();
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
let ea = Jc;
hn = /* @__PURE__ */ new WeakMap();
Vt = /* @__PURE__ */ new WeakMap();
Ur = /* @__PURE__ */ new WeakSet();
Kc = function() {
  if (Pe(this, Vt))
    Pe(this, Vt).setOptions(this.options);
  else {
    const t = Yc(this, ta, Xc).call(this), e = new Zo(t, this.options);
    e.on("hidden", () => {
      e.destroy(), t == null || t.remove(), Js(this, hn, void 0), Js(this, Vt, void 0);
    }), Js(this, Vt, e);
  }
  return Pe(this, Vt);
};
ta = /* @__PURE__ */ new WeakSet();
Xc = function() {
  if (Pe(this, hn))
    return Pe(this, hn);
  const { placement: t = "top" } = this.options;
  let e = this.$element.find(`.messagers-${t}`);
  e.length || (e = g(`<div class="messagers messagers-${t}"></div>`).appendTo(this.$element));
  let n = e.find(`#messager-${this.gid}`);
  return n.length || (n = g(`<div class="messager-holder" id="messager-${this.gid}"></div>`).appendTo(e), Js(this, hn, n[0])), n[0];
};
ea.NAME = "messager";
ea.DEFAULT = {
  placement: "top",
  animation: !0,
  close: !0,
  margin: 6,
  time: 5e3
};
g(document).on("zui.messager.show", (t, e) => {
  e && ea.show(e);
});
let na = class extends U {
  render() {
    const { percent: e, circleSize: n, circleBorderSize: s, circleBgColor: i, circleColor: r } = this.props, o = (n - s) / 2, a = n / 2;
    return /* @__PURE__ */ y("svg", { width: n, height: n, class: "progress-circle", children: [
      /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, stroke: i, "stroke-width": s }),
      /* @__PURE__ */ y("circle", { cx: a, cy: a, r: o, stroke: r, "stroke-dasharray": Math.PI * o * 2, "stroke-dashoffset": Math.PI * o * 2 * (100 - e) / 100, "stroke-width": s }),
      /* @__PURE__ */ y("text", { x: a, y: a + s / 4, "dominant-baseline": "middle", style: { fontSize: `${o}px` }, children: Math.round(e) })
    ] });
  }
};
na.NAME = "zui.progress-circle";
na.defaultProps = {
  circleSize: 24,
  circleBorderSize: 2,
  circleBgColor: "var(--progress-circle-bg)",
  circleColor: "var(--progress-circle-bar-color)"
};
class Zc extends J {
}
Zc.NAME = "ProgressCircle";
Zc.Component = na;
let _f = class extends U {
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
    } = this.props, d = this.state.checked ? 1 : 0, u = e || "div", f = typeof r == "string" ? /* @__PURE__ */ y("i", { class: `icon ${r}` }) : r, p = typeof o == "string" ? /* @__PURE__ */ y("i", { class: `icon ${o}` }) : o, m = [
      /* @__PURE__ */ y("input", { onChange: h, type: "checkbox", value: d, checked: !!this.state.checked }),
      /* @__PURE__ */ y("label", { children: [
        f,
        i,
        p
      ] })
    ];
    return _(
      u,
      {
        className: L("switch", n, { disabled: a }),
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
Qc.Component = _f;
class th extends J {
}
th.NAME = "BtnGroup";
th.Component = Gc;
class xf extends ot {
  init() {
    this.fileMap = /* @__PURE__ */ new Map(), this.itemMap = /* @__PURE__ */ new Map(), this.dataTransfer = new DataTransfer();
    const { multiple: e, defaultFileList: n } = this.options;
    e || (this.options.maxCount = 1), this.initInputCash(), this.initUploadCash(), n && this.addFileItem(n);
  }
  initUploadCash() {
    const { name: e, uploadText: n, listPosition: s } = this.options;
    this.$label = g(`<label class="btn primary" for="${e}">${n}</label>`), this.$list = g('<div class="file-list"></div>');
    const i = s === "bottom" ? [this.$label, this.$list] : [this.$list, this.$label];
    this.$element.append(this.$input, ...i);
  }
  initInputCash() {
    const { name: e, multiple: n, accept: s, onChange: i } = this.options;
    this.$input = g("<input />").prop("type", "file").prop("name", e).prop("id", e).prop("multiple", n).on("change", (r) => {
      const o = r.target.files;
      if (!o)
        return;
      const a = [...o];
      this.addFileItem(a), i == null || i(a);
    }), s && this.$input.prop("accept", s);
  }
  addFile(e) {
    this.options.multiple || (this.fileMap.clear(), this.dataTransfer.items.clear()), this.fileMap.set(e.name, e), this.dataTransfer.items.add(e), this.$input.prop("files", this.dataTransfer.files);
  }
  addFileItem(e) {
    const { multiple: n, maxCount: s } = this.options;
    if (n) {
      for (const o of e) {
        if (s && this.fileMap.size >= s)
          return;
        this.addFile(o);
        const a = this.createFileItem(o);
        this.itemMap.set(o.name, a), this.$list.append(a);
      }
      return;
    }
    const i = e[0];
    this.addFile(i);
    const r = this.createFileItem(i);
    this.itemMap.clear(), this.itemMap.set(i.name, r), this.$list.empty().append(r);
  }
  deleteFile(e) {
    var n, s;
    (s = (n = this.options).onDelete) == null || s.call(n, e), this.fileMap.delete(e.name), this.dataTransfer = new DataTransfer(), this.fileMap.forEach((i) => this.dataTransfer.items.add(i)), this.$input.prop("files", this.dataTransfer.files);
  }
  deleteFileItem(e) {
    var s;
    const n = this.fileMap.get(e);
    n && ((s = this.itemMap.get(n.name)) == null || s.remove(), this.itemMap.delete(n.name), this.deleteFile(n));
  }
  renameFile(e, n) {
    var s, i;
    (i = (s = this.options).onRename) == null || i.call(s, n, e.name), this.fileMap.delete(e.name), this.dataTransfer = new DataTransfer(), e = new File([e], n), this.fileMap.set(n, e).forEach((r) => this.dataTransfer.items.add(r)), this.$input.prop("files", this.dataTransfer.files);
  }
  renameFileItem(e, n) {
    const s = this.itemMap.get(e.name);
    s && (this.itemMap.set(n, s).delete(e.name), this.renameFile(e, n));
  }
  createFileItem(e) {
    const { showIcon: n } = this.options;
    return g('<li class="file-item"></li>').append(n ? this.fileIcon() : null).append(this.fileInfo(e)).append(this.renameInput(e));
  }
  fileIcon() {
    const { icon: e } = this.options;
    return g(e || '<i class="icon icon-paper-clip"></i>');
  }
  fileInfo(e) {
    const n = g(`<span class="file-name">${e.name}</span>`), s = g(`<span class="file-size text-gray">${qs(e.size)}</span>`), i = g('<div class="file-info"></div>').append(n).append(s), { renameBtn: r, renameText: o, deleteBtn: a, deleteText: l } = this.options;
    return r && i.append(
      g("<span />").addClass("btn size-sm rounded-sm text-primary canvas file-action file-rename").html(o).on("click", () => {
        i.addClass("hidden").closest(".file-item").find(".input-group.hidden").removeClass("hidden");
      })
    ), a && i.append(
      g("<span />").html(l).addClass("btn size-sm rounded-sm text-primary canvas file-action file-delete").on("click", () => this.deleteFileItem(n.html()))
    ), i;
  }
  renameInput(e) {
    const { renameConfirmText: n, renameCancelText: s } = this.options, i = g('<div class="input-group hidden"></div>'), r = g("<input />").addClass("form-control").prop("type", "text").prop("autofocus", !0).prop("defaultValue", e.name), o = g("<button />").addClass("btn").prop("type", "button").html(n).on("click", () => {
      i.addClass("hidden"), this.renameFileItem(e, r.val()), i.closest(".file-item").find(".file-info.hidden").removeClass("hidden").find(".file-name").html(r.val());
    }), a = g("<button />").prop("type", "button").addClass("btn").html(s).on("click", () => {
      r.val(e.name), i.addClass("hidden").closest(".file-item").find(".file-info.hidden").removeClass("hidden");
    }), l = g('<div class="btn-group"></div').append(o).append(a);
    return i.append(r).append(l);
  }
}
xf.DEFAULT = {
  name: "file",
  icon: null,
  uploadText: "上传文件",
  renameText: "重命名",
  deleteText: "删除",
  renameBtn: !1,
  deleteBtn: !1,
  showIcon: !0,
  renameConfirmText: "确定",
  renameCancelText: "取消",
  multiple: !1,
  listPosition: "bottom"
};
var qt;
class $f {
  constructor(e = "") {
    I(this, qt, void 0);
    typeof e == "object" ? F(this, qt, e) : F(this, qt, document.appendChild(document.createComment(e)));
  }
  on(e, n, s) {
    W(this, qt).addEventListener(e, n, s);
  }
  once(e, n, s) {
    W(this, qt).addEventListener(e, n, { once: !0, ...s });
  }
  off(e, n, s) {
    W(this, qt).removeEventListener(e, n, s);
  }
  emit(e) {
    return W(this, qt).dispatchEvent(e), e;
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
class sa extends $f {
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
    return typeof e == "string" && (_l.has(e) ? (e = new Event(e), Object.assign(e, { detail: n })) : e = new CustomEvent(e, { detail: n })), super.emit(sa.createEvent(e, n));
  }
  static createEvent(e, n) {
    return typeof e == "string" && (_l.has(e) ? (e = new Event(e), Object.assign(e, { detail: n })) : e = new CustomEvent(e, { detail: n })), e;
  }
}
let ia = (t = 21) => crypto.getRandomValues(new Uint8Array(t)).reduce((e, n) => (n &= 63, n < 36 ? e += n.toString(36) : n < 62 ? e += (n - 26).toString(36).toUpperCase() : n > 62 ? e += "-" : e += "_", e), "");
var bs, de, Et, gn, mn, Zs;
const Za = class {
  /**
   * Create new store instance
   * @param name Name of store
   * @param type Store type
   */
  constructor(e, n = "local") {
    I(this, mn);
    I(this, bs, void 0);
    I(this, de, void 0);
    I(this, Et, void 0);
    I(this, gn, void 0);
    F(this, bs, n), F(this, de, `ZUI_STORE:${e ?? ia()}`), F(this, Et, n === "local" ? localStorage : sessionStorage);
  }
  /**
   * Get store type
   */
  get type() {
    return W(this, bs);
  }
  /**
   * Get session type store instance
   */
  get session() {
    return this.type === "session" ? this : (W(this, gn) || F(this, gn, new Za(W(this, de), "session")), W(this, gn));
  }
  /**
   * Get value from store
   * @param key Key to get
   * @param defaultValue default value to return if key is not found
   * @returns Value of key or defaultValue if key is not found
   */
  get(e, n) {
    const s = W(this, Et).getItem(at(this, mn, Zs).call(this, e));
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
    W(this, Et).setItem(at(this, mn, Zs).call(this, e), JSON.stringify(n));
  }
  /**
   * Remove key-value pair from store
   * @param key Key to remove
   */
  remove(e) {
    W(this, Et).removeItem(at(this, mn, Zs).call(this, e));
  }
  /**
   * Iterate all key-value pairs in store
   * @param callback Callback function to call for each key-value pair in the store
   */
  each(e) {
    for (let n = 0; n < W(this, Et).length; n++) {
      const s = W(this, Et).key(n);
      if (s != null && s.startsWith(W(this, de))) {
        const i = W(this, Et).getItem(s);
        typeof i == "string" && e(s.substring(W(this, de).length + 1), JSON.parse(i));
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
let $i = Za;
bs = new WeakMap(), de = new WeakMap(), Et = new WeakMap(), gn = new WeakMap(), mn = new WeakSet(), Zs = function(e) {
  return `${W(this, de)}:${e}`;
};
const kf = new $i("DEFAULT");
function Cf(t, e = "local") {
  return new $i(t, e);
}
Object.assign(kf, { create: Cf });
const H = g, ra = window.document;
let Hs, se;
const Sf = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, Ef = /^(?:text|application)\/javascript/i, Mf = /^(?:text|application)\/xml/i, eh = "application/json", nh = "text/html", Tf = /^\s*$/, qr = ra.createElement("a");
qr.href = window.location.href;
function Rf(t, e, n) {
  const s = new CustomEvent(e, { detail: n });
  return H(t).trigger(s, n), !s.defaultPrevented;
}
function Be(t, e, n, s) {
  if (t.global)
    return Rf(e || ra, n, s);
}
H.active = 0;
function Af(t) {
  t.global && H.active++ === 0 && Be(t, null, "ajaxStart");
}
function Nf(t) {
  t.global && !--H.active && Be(t, null, "ajaxStop");
}
function Lf(t, e) {
  const n = e.context;
  if (e.beforeSend.call(n, t, e) === !1 || Be(e, n, "ajaxBeforeSend", [t, e]) === !1)
    return !1;
  Be(e, n, "ajaxSend", [t, e]);
}
function Df(t, e, n) {
  const s = n.context, i = "success";
  n.success.call(s, t, i, e), Be(n, s, "ajaxSuccess", [e, n, t]), sh(i, e, n);
}
function Bs(t, e, n, s) {
  const i = s.context;
  s.error.call(i, n, e, t), Be(s, i, "ajaxError", [n, s, t || e]), sh(e, n, s);
}
function sh(t, e, n) {
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
H.ajaxSettings = {
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
    json: eh,
    xml: "application/xml, text/xml",
    html: nh,
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
  return t && (t = t.split(";", 2)[0]), t && (t == nh ? "html" : t == eh ? "json" : Ef.test(t) ? "script" : Mf.test(t) && "xml") || "text";
}
function ih(t, e) {
  return e == "" ? t : (t + "&" + e).replace(/[&?]{1,2}/, "?");
}
function If(t) {
  t.processData && t.data && typeof t.data != "string" && (t.data = H.param(t.data, t.traditional)), t.data && (!t.type || t.type.toUpperCase() == "GET" || t.dataType == "jsonp") && (t.url = ih(t.url, t.data), t.data = void 0);
}
H.ajax = function(t) {
  var m;
  const e = H.extend({}, t || {});
  let n, s;
  for (Hs in H.ajaxSettings)
    e[Hs] === void 0 && (e[Hs] = H.ajaxSettings[Hs]);
  Af(e), e.crossDomain || (n = ra.createElement("a"), n.href = e.url, n.href = n.href, e.crossDomain = qr.protocol + "//" + qr.host != n.protocol + "//" + n.host);
  const i = e.type.toUpperCase() === "GET";
  e.url || (e.url = window.location.toString()), (s = e.url.indexOf("#")) > -1 && (e.url = e.url.slice(0, s)), i ? If(e) : e.data instanceof FormData && e.contentType === void 0 && (e.contentType = !1);
  let r = e.dataType;
  /\?.+=\?/.test(e.url) && (r = "jsonp"), (e.cache === !1 || (!t || t.cache !== !0) && (r == "script" || r == "jsonp")) && (e.url = ih(e.url, "_=" + Date.now()));
  let a = e.accepts[r];
  const l = {}, h = function(v, w) {
    l[v.toLowerCase()] = [v, w];
  }, c = /^([\w-]+:)\/\//.test(e.url) ? RegExp.$1 : window.location.protocol, d = e.xhr(), u = d.setRequestHeader;
  let f;
  if (e.crossDomain || h("X-Requested-With", "XMLHttpRequest"), h("Accept", a || "*/*"), a = e.mimeType, a && (a.indexOf(",") > -1 && (a = a.split(",", 2)[0]), (m = d.overrideMimeType) == null || m.call(d, a)), (e.contentType || e.contentType !== !1 && e.data && !i) && h("Content-Type", e.contentType || "application/x-www-form-urlencoded"), e.headers)
    for (se in e.headers)
      h(se, e.headers[se]);
  if (d.setRequestHeader = h, d.onreadystatechange = function() {
    if (d.readyState == 4) {
      d.onreadystatechange = he, clearTimeout(f);
      let v, w = !1;
      if (d.status >= 200 && d.status < 300 || d.status == 304 || d.status == 0 && c == "file:") {
        if (r = r || Wf(e.mimeType || d.getResponseHeader("content-type")), d.responseType == "arraybuffer" || d.responseType == "blob")
          v = d.response;
        else {
          v = d.responseText;
          try {
            v = Pf(v, r, e), r == "xml" ? v = d.responseXML : r == "json" && (v = Tf.test(v) ? null : JSON.parse(v));
          } catch (b) {
            w = b;
          }
          if (w)
            return Bs(w, "parsererror", d, e);
        }
        Df(v, d, e);
      } else
        Bs(d.statusText || null, d.status ? "error" : "abort", d, e);
    }
  }, Lf(d, e) === !1)
    return d.abort(), Bs(null, "abort", d, e), d;
  const p = "async" in e ? e.async : !0;
  if (d.open(e.type, e.url, p, e.username, e.password), e.xhrFields)
    for (se in e.xhrFields)
      d[se] = e.xhrFields[se];
  for (se in l)
    u.apply(d, l[se]);
  return e.timeout > 0 && (f = setTimeout(function() {
    d.onreadystatechange = he, d.abort(), Bs(null, "timeout", d, e);
  }, e.timeout)), d.send(e.data ? e.data : null), d;
};
function rr(t, e, n, s) {
  return H.isFunction(e) && (s = n, n = e, e = void 0), H.isFunction(n) || (s = n, n = void 0), {
    url: t,
    data: e,
    success: n,
    dataType: s
  };
}
H.get = function(t, e, n, s) {
  return H.ajax(rr(t, e, n, s));
};
H.post = function(t, e, n, s) {
  const i = rr(t, e, n, s);
  return H.ajax(Object.assign(i, { type: "POST" }));
};
H.getJSON = function(t, e, n, s) {
  const i = rr(t, e, n, s);
  return i.dataType = "json", H.ajax(i);
};
H.fn.load = function(t, e, n) {
  if (!this.length)
    return this;
  const s = t.split(/\s/);
  let i;
  const r = rr(t, e, n), o = r.success;
  return s.length > 1 && (r.url = s[0], i = s[1]), r.success = (a, ...l) => {
    this.html(i ? H("<div>").html(a.replace(Sf, "")).find(i) : a), o == null || o.call(this, a, ...l);
  }, H.ajax(r), this;
};
const xl = encodeURIComponent;
function rh(t, e, n, s) {
  const i = H.isArray(e), r = H.isPlainObject(e);
  H.each(e, function(o, a) {
    const l = Array.isArray(a) ? "array" : typeof a;
    s && (o = n ? s : s + "[" + (r || l == "object" || l == "array" ? o : "") + "]"), !s && i ? t.add(a.name, a.value) : l == "array" || !n && l == "object" ? rh(t, a, n, o) : t.add(o, a);
  });
}
H.param = function(t, e) {
  const n = [];
  return n.add = function(s, i) {
    H.isFunction(i) && (i = i()), i == null && (i = ""), this.push(xl(s) + "=" + xl(i));
  }, rh(n, t, e), n.join("&").replace(/%20/g, "+");
};
const Ug = Object.assign(H.ajax, {
  get: H.get,
  post: H.post,
  getJSON: H.getJSON,
  param: H.param,
  ajaxSettings: H.ajaxSettings
}), qg = new sa();
/*! js-cookie v3.0.1 | MIT */
function zs(t) {
  for (var e = 1; e < arguments.length; e++) {
    var n = arguments[e];
    for (var s in n)
      t[s] = n[s];
  }
  return t;
}
var Of = {
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
function Vr(t, e) {
  function n(i, r, o) {
    if (!(typeof document > "u")) {
      o = zs({}, e, o), typeof o.expires == "number" && (o.expires = new Date(Date.now() + o.expires * 864e5)), o.expires && (o.expires = o.expires.toUTCString()), i = encodeURIComponent(i).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
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
          zs({}, r, {
            expires: -1
          })
        );
      },
      withAttributes: function(i) {
        return Vr(this.converter, zs({}, this.attributes, i));
      },
      withConverter: function(i) {
        return Vr(zs({}, this.converter, i), this.attributes);
      }
    },
    {
      attributes: { value: Object.freeze(e) },
      converter: { value: Object.freeze(t) }
    }
  );
}
var Hf = Vr(Of, { path: "/" });
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
let oh = class extends U {
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
      src: d,
      hueDistance: u = 43,
      saturation: f = 0.4,
      lightness: p = 0.6,
      children: m,
      ...v
    } = this.props, w = ["avatar", e], b = { ...n, background: o, color: a };
    let k = 32;
    s && (typeof s == "number" ? (b.width = `${s}px`, b.height = `${s}px`, b.fontSize = `${Math.max(12, Math.round(s / 2))}px`, k = s) : (w.push(`size-${s}`), k = { xs: 20, sm: 24, lg: 48, xl: 80 }[s])), i ? w.push("circle") : r && (typeof r == "number" ? b.borderRadius = `${r}px` : w.push(`rounded-${r}`));
    let S;
    if (d)
      w.push("has-img"), S = /* @__PURE__ */ y("img", { className: "avatar-img", src: d, alt: l });
    else if (l != null && l.length) {
      const E = Uf(l, c);
      if (w.push("has-text", `has-text-${E.length}`), o)
        !a && o && (b.color = $l(o));
      else {
        const M = h ?? l, R = (typeof M == "number" ? M : jf(M)) * u % 360;
        if (b.background = `hsl(${R},${f * 100}%,${p * 100}%)`, !a) {
          const A = Ff(R, f, p);
          b.color = $l(A);
        }
      }
      let P;
      k && k < 14 * E.length && (P = { transform: `scale(${k / (14 * E.length)})`, whiteSpace: "nowrap" }), S = /* @__PURE__ */ y("div", { "data-actualSize": k, className: "avatar-text", style: P, children: E });
    }
    return /* @__PURE__ */ y(
      "div",
      {
        className: L(w),
        style: b,
        ...v,
        children: [
          S,
          m
        ]
      }
    );
  }
};
class ah extends J {
}
ah.NAME = "Avatar";
ah.Component = oh;
var oa = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Re = (t, e, n) => (oa(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Nn = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, ns = (t, e, n, s) => (oa(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), $r = (t, e, n) => (oa(t, e, "access private method"), n), nn, Qs, Ee, Gr, jn, ti;
const kr = "show", Cl = "in", qf = '[data-dismiss="modal"]', ei = class extends ot {
  constructor() {
    super(...arguments), Nn(this, jn), Nn(this, nn, 0), Nn(this, Qs, void 0), Nn(this, Ee, void 0), Nn(this, Gr, (t) => {
      const e = t.target;
      (e.closest(qf) || this.options.backdrop === !0 && !e.closest(".modal-dialog") && e.closest(".modal")) && (t.stopPropagation(), this.hide());
    });
  }
  get modalElement() {
    return this.element;
  }
  get shown() {
    return this.modalElement.classList.contains(kr);
  }
  get dialog() {
    return this.modalElement.querySelector(".modal-dialog");
  }
  afterInit() {
    if (this.on("click", Re(this, Gr)), this.options.responsive && typeof ResizeObserver < "u") {
      const { dialog: t } = this;
      if (t) {
        const e = new ResizeObserver(() => {
          if (!this.shown)
            return;
          const n = t.clientWidth, s = t.clientHeight;
          (!Re(this, Ee) || Re(this, Ee)[0] !== n || Re(this, Ee)[1] !== s) && (ns(this, Ee, [n, s]), this.layout());
        });
        e.observe(t), ns(this, Qs, e);
      }
    }
    this.options.show && this.show();
  }
  destroy() {
    var t;
    super.destroy(), (t = Re(this, Qs)) == null || t.disconnect();
  }
  show(t) {
    if (this.shown)
      return !1;
    this.setOptions(t);
    const { modalElement: e } = this, { animation: n, backdrop: s, className: i, style: r } = this.options;
    return g(e).setClass({
      "modal-trans": n,
      "modal-no-backdrop": !s
    }, kr, i).css({
      zIndex: `${ei.zIndex++}`,
      ...r
    }), this.layout(), this.emit("show"), $r(this, jn, ti).call(this, () => {
      g(e).addClass(Cl), $r(this, jn, ti).call(this, () => {
        this.emit("shown");
      });
    }, 50), !0;
  }
  hide() {
    return this.shown ? (g(this.modalElement).removeClass(Cl), this.emit("hide"), $r(this, jn, ti).call(this, () => {
      g(this.modalElement).removeClass(kr), this.emit("hidden");
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
    (e = ei.query(t)) == null || e.hide();
  }
  static show(t) {
    var e;
    (e = ei.query(t)) == null || e.show();
  }
};
let ne = ei;
nn = /* @__PURE__ */ new WeakMap();
Qs = /* @__PURE__ */ new WeakMap();
Ee = /* @__PURE__ */ new WeakMap();
Gr = /* @__PURE__ */ new WeakMap();
jn = /* @__PURE__ */ new WeakSet();
ti = function(t, e) {
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
      e ? /* @__PURE__ */ y(ut, { ...e }) : null,
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
    return it(e) ? e : e === !1 || !n ? null : /* @__PURE__ */ y("div", { className: "modal-footer", children: n ? /* @__PURE__ */ y(ut, { ...n }) : null });
  }
  render() {
    const {
      className: e,
      style: n,
      children: s
    } = this.props;
    return /* @__PURE__ */ y("div", { className: L("modal-dialog", e), style: n, children: /* @__PURE__ */ y("div", { className: "modal-content", children: [
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
    I(this, yn, void 0);
    I(this, wn, void 0);
    I(this, vn, void 0);
    F(this, yn, $t()), this.state = {}, F(this, vn, () => {
      var i, r;
      const n = (r = (i = W(this, yn).current) == null ? void 0 : i.contentWindow) == null ? void 0 : r.document;
      if (!n)
        return;
      let s = W(this, wn);
      s == null || s.disconnect(), s = new ResizeObserver(() => {
        const o = n.body, a = n.documentElement, l = Math.ceil(Math.max(o.scrollHeight, o.offsetHeight, a.offsetHeight));
        this.setState({ height: l });
      }), s.observe(n.body), s.observe(n.documentElement), F(this, wn, s);
    });
  }
  componentDidMount() {
    W(this, vn).call(this);
  }
  componentWillUnmount() {
    var n;
    (n = W(this, wn)) == null || n.disconnect();
  }
  render() {
    const { url: n } = this.props;
    return /* @__PURE__ */ y(
      "iframe",
      {
        className: "modal-iframe",
        style: this.state,
        src: n,
        ref: W(this, yn),
        onLoad: W(this, vn)
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
}, Ke = (t, e, n, s) => (aa(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), sn = (t, e, n) => (aa(t, e, "access private method"), n), Me, ni, Bt, ps, or, Yr, ch, si, Kr;
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
      const d = JSON.parse(c);
      return {
        title: o,
        ...r,
        ...d
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
    super(...arguments), Ye(this, ps), Ye(this, Yr), Ye(this, si), Ye(this, Me, void 0), Ye(this, ni, void 0), Ye(this, Bt, void 0);
  }
  get id() {
    return ie(this, ni);
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
    super.afterInit(), Ke(this, ni, this.options.id || `modal-${ia()}`), this.on("hidden", () => {
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
    t.classList.add(ae.LOADING_CLASS), await sn(this, Yr, ch).call(this), s && Ke(this, Bt, window.setTimeout(() => {
      Ke(this, Bt, 0), sn(this, si, Kr).call(this, this.options.timeoutTip);
    }, s));
    const r = await i.call(this, t, e);
    return r === !1 ? await sn(this, si, Kr).call(this, this.options.failedTip) : r && typeof r == "object" && await sn(this, ps, or).call(this, r), ie(this, Bt) && (clearTimeout(ie(this, Bt)), Ke(this, Bt, 0)), t.classList.remove(ae.LOADING_CLASS), !0;
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
    const d = [];
    (Array.isArray(r) ? r : [r]).forEach((p) => {
      p = {
        ...typeof p == "string" ? { key: p } : p
      }, typeof p.key == "string" && (p.text || (p.text = Zt.getLang(p.key, p.key)), p.btnType || (p.btnType = `btn-wide ${p.key === "confirm" ? "primary" : "btn-default"}`)), p && d.push(p);
    }, []);
    let u;
    const f = d.length ? {
      gap: 4,
      items: d,
      onClickItem: ({ item: p, event: m }) => {
        const v = ae.query(m.target, l);
        u = p.key, (o == null ? void 0 : o(p, v)) !== !1 && v && v.hide();
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
    }), u;
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
let ar = ae;
Me = /* @__PURE__ */ new WeakMap();
ni = /* @__PURE__ */ new WeakMap();
Bt = /* @__PURE__ */ new WeakMap();
ps = /* @__PURE__ */ new WeakSet();
or = function(t) {
  return new Promise((e) => {
    if (Array.isArray(t))
      return this.modalElement.innerHTML = t[0], g(this.modalElement).runJS(), e();
    const { afterRender: n, ...s } = t;
    t = {
      afterRender: (i) => {
        this.layout(), n == null || n(i), e();
      },
      ...s
    }, ds(
      /* @__PURE__ */ y(lh, { ...t }),
      this.modalElement
    );
  });
};
Yr = /* @__PURE__ */ new WeakSet();
ch = function() {
  const { loadingText: t } = this.options;
  return sn(this, ps, or).call(this, {
    body: /* @__PURE__ */ y("div", { className: "modal-loading-indicator", children: [
      /* @__PURE__ */ y("span", { className: "spinner" }),
      t ? /* @__PURE__ */ y("span", { className: "modal-loading-text", children: t }) : null
    ] })
  });
};
si = /* @__PURE__ */ new WeakSet();
Kr = function(t) {
  if (t)
    return sn(this, ps, or).call(this, {
      body: /* @__PURE__ */ y("div", { className: "modal-load-failed", children: t })
    });
};
ar.LOADING_CLASS = "loading";
ar.DEFAULT = {
  ...ne.DEFAULT,
  loadTimeout: 1e4,
  destoryOnHide: !0
};
var la = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Xr = (t, e, n) => (la(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Fs = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Sl = (t, e, n, s) => (la(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Jr = (t, e, n) => (la(t, e, "access private method"), n), We, ca, hh, Zr, dh, ha, uh;
const Jf = '[data-toggle="modal"]';
class fh extends ot {
  constructor() {
    super(...arguments), Fs(this, ca), Fs(this, Zr), Fs(this, ha), Fs(this, We, void 0);
  }
  get modal() {
    return Xr(this, We);
  }
  get container() {
    const { container: e } = this.options;
    return typeof e == "string" ? document.querySelector(e) : e instanceof HTMLElement ? e : document.body;
  }
  show() {
    var e;
    return (e = Jr(this, Zr, dh).call(this)) == null ? void 0 : e.show();
  }
  hide() {
    var e;
    return (e = Xr(this, We)) == null ? void 0 : e.hide();
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
Zr = /* @__PURE__ */ new WeakSet();
dh = function() {
  const t = Jr(this, ca, hh).call(this);
  let e = Xr(this, We);
  if (e)
    return e.setOptions(t), e;
  if (t.type === "static") {
    const n = Jr(this, ha, uh).call(this);
    if (!n)
      return;
    e = ne.ensure(n, t);
  } else
    e = ar.ensure(this.container, t);
  return Sl(this, We, e), e.on("destroyed", () => {
    Sl(this, We, void 0);
  }), e;
};
ha = /* @__PURE__ */ new WeakSet();
uh = function() {
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
    return e.className = L(e.className, e.type ? `nav-${e.type}` : "", {
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
  return a.text === void 0 && !a.icon && i && (a.text = typeof i == "function" ? i(l) : Y(i, l)), a.url === void 0 && o && (a.url = typeof o == "function" ? o(l) : Y(o, l)), a.disabled === void 0 && (a.disabled = s !== void 0 && l.page === r.page), /* @__PURE__ */ y(Lt, { type: n, ...a });
}
const jt = 24 * 60 * 60 * 1e3, ft = (t) => t ? (t instanceof Date || (typeof t == "string" && (t = t.trim(), /^\d+$/.test(t) && (t = Number.parseInt(t, 10))), typeof t == "number" && t < 1e10 && (t *= 1e3), t = new Date(t)), t) : /* @__PURE__ */ new Date(), Ms = (t, e = /* @__PURE__ */ new Date()) => (t = ft(t), e = ft(e), t.getFullYear() === e.getFullYear() && t.getMonth() === e.getMonth() && t.getDate() === e.getDate()), Qr = (t, e = /* @__PURE__ */ new Date()) => ft(t).getFullYear() === ft(e).getFullYear(), Qf = (t, e = /* @__PURE__ */ new Date()) => (t = ft(t), e = ft(e), t.getFullYear() === e.getFullYear() && t.getMonth() === e.getMonth()), Yg = (t, e = /* @__PURE__ */ new Date()) => {
  t = ft(t), e = ft(e);
  const n = 1e3 * 60 * 60 * 24, s = Math.floor(t.getTime() / n), i = Math.floor(e.getTime() / n);
  return Math.floor((s + 4) / 7) === Math.floor((i + 4) / 7);
}, Kg = (t, e) => Ms(ft(e), t), Xg = (t, e) => Ms(ft(e).getTime() - jt, t), Jg = (t, e) => Ms(ft(e).getTime() + jt, t), Zg = (t, e) => Ms(ft(e).getTime() - 2 * jt, t), to = (t, e = "yyyy-MM-dd hh:mm", n = "") => {
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
  return /(y+)/i.test(e) && (e.includes("[yyyy-]") && (e = e.replace("[yyyy-]", Qr(t) ? "" : "yyyy-")), e = e.replace(RegExp.$1, `${t.getFullYear()}`.substring(4 - RegExp.$1.length))), Object.keys(s).forEach((i) => {
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
  }, i = to(t, Qr(t) ? s.month : s.full);
  if (Ms(t, e))
    return i;
  const r = to(e, Qr(t, e) ? Qf(t, e) ? s.day : s.month : s.full);
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
  return s = typeof s == "function" ? s(a) : Y(s, a), /* @__PURE__ */ y(Ec, { ...o, children: [
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
  const l = { ...a, square: !0 }, h = () => (l.text = "", l.icon = "icon-ellipsis-h", l.disabled = !0, /* @__PURE__ */ y(Lt, { type: n, ...l })), c = (u, f) => {
    const p = [];
    for (let m = u; m <= f; m++) {
      l.text = m, delete l.icon, l.disabled = !1;
      const v = gs(i, m);
      o && (l.url = typeof o == "function" ? o(v) : Y(o, v)), p.push(/* @__PURE__ */ y(Lt, { type: n, ...l, onClick: r }));
    }
    return p;
  };
  let d = [];
  return d = [...c(1, 1)], i.pageTotal <= 1 || (i.pageTotal <= s ? d = [...d, ...c(2, i.pageTotal)] : i.page < s - 2 ? d = [...d, ...c(2, s - 2), h(), ...c(i.pageTotal, i.pageTotal)] : i.page > i.pageTotal - s + 3 ? d = [...d, h(), ...c(i.pageTotal - s + 3, i.pageTotal)] : d = [...d, h(), ...c(i.page - Math.ceil((s - 4) / 2), i.page + Math.floor((s - 4) / 2)), h(), ...c(i.pageTotal, i.pageTotal)]), d;
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
      url: typeof n == "function" ? n(c) : Y(n, c)
    };
  });
  const { text: a = "" } = o;
  return o.text = typeof a == "function" ? a(e) : Y(a, e), i.menu = { ...i.menu, className: L((l = i.menu) == null ? void 0 : l.className, "pager-size-menu") }, /* @__PURE__ */ y(Vc, { type: "dropdown", dropdown: i, ...o });
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
  let d;
  const u = (m) => {
    var v;
    d = Number((v = m.target) == null ? void 0 : v.value) || 1, d = d > i.pageTotal ? i.pageTotal : d;
  }, f = (m) => {
    if (!(m != null && m.target))
      return;
    d = d <= i.pageTotal ? d : i.pageTotal;
    const v = gs(i, d);
    a && !a({ info: v, event: m }) || (m.target.href = c.url = typeof l == "function" ? l(v) : Y(l, v));
  }, p = gs(i, e || 0);
  return c.url = typeof l == "function" ? l(p) : Y(l, p), /* @__PURE__ */ y("div", { className: L("input-group", "pager-goto-group", r ? `size-${r}` : ""), children: [
    /* @__PURE__ */ y("input", { type: "number", class: "form-control", max: i.pageTotal, min: "1", onInput: u }),
    /* @__PURE__ */ y(Lt, { type: s, ...c, onClick: f })
  ] });
}
let Ts = class extends ut {
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
Ts.NAME = "pager";
Ts.defaultProps = {
  btnProps: {
    btnType: "ghost",
    size: "sm"
  }
};
Ts.ItemComponents = {
  ...ut.ItemComponents,
  link: Zf,
  info: tp,
  nav: ep,
  "size-menu": np,
  goto: sp
};
class mh extends J {
}
mh.NAME = "Pager";
mh.Component = Ts;
var Hi, Bi, yh;
class ip extends U {
  constructor() {
    super(...arguments);
    I(this, Bi);
    I(this, Hi, (n) => {
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
        className: L("picker-select picker-select-multi form-control", n, { disabled: i, focused: r }),
        style: s,
        onClick: o,
        children: [
          at(this, Bi, yh).call(this),
          a,
          /* @__PURE__ */ y("span", { class: "caret" })
        ]
      }
    );
  }
}
Hi = new WeakMap(), Bi = new WeakSet(), yh = function() {
  const { selections: n = [], placeholder: s } = this.props;
  return n.length ? /* @__PURE__ */ y("div", { className: "picker-multi-selections", children: n.map((i, r) => /* @__PURE__ */ y("div", { className: "picker-multi-selection", children: [
    i.text ?? i.value,
    /* @__PURE__ */ y("div", { className: "picker-deselect-btn btn size-xs ghost", onClick: W(this, Hi), "data-idx": r, children: /* @__PURE__ */ y("span", { className: "close" }) })
  ] })) }) : /* @__PURE__ */ y("span", { className: "picker-select-placeholder", children: s });
};
var zi;
class rp extends U {
  constructor() {
    super(...arguments);
    I(this, zi, (n) => {
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
    } = this.props, [d] = a, u = d ? /* @__PURE__ */ y("span", { className: "picker-single-selection", children: d.text ?? d.value }) : /* @__PURE__ */ y("span", { className: "picker-select-placeholder", children: r }), f = d && l ? /* @__PURE__ */ y("button", { type: "button", className: "btn picker-deselect-btn size-sm square ghost", onClick: W(this, zi), children: /* @__PURE__ */ y("span", { className: "close" }) }) : null;
    return /* @__PURE__ */ y(
      "div",
      {
        className: L("picker-select picker-select-single form-control", n, { disabled: i, focused: o }),
        style: s,
        onClick: h,
        children: [
          u,
          c,
          f,
          /* @__PURE__ */ y("span", { class: "caret" })
        ]
      }
    );
  }
}
zi = new WeakMap();
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
      var h = l[0], c = l[1], d = r.get(h) || h;
      (function(u, f) {
        return !(f[1].toUpperCase() !== u.key.toUpperCase() && f[1] !== u.code || f[0].find(function(p) {
          return !Cr(u, p);
        }) || op.find(function(p) {
          return !f[0].includes(p) && f[1] !== p && Cr(u, p);
        }));
      })(a, d[0]) ? d.length > 1 ? r.set(h, d.slice(1)) : (r.delete(h), c(a)) : Cr(a, a.key) || r.delete(h);
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
const dp = (t, e) => t.reduce((n, s) => [...n].reduce((i, r) => {
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
var ue, bn, _n, _s, xn, ii, Le, Un, Fi, wh, $n, xs, kn, $s, ji, vh;
class up extends U {
  constructor() {
    super(...arguments);
    I(this, xn);
    I(this, Le);
    I(this, Fi);
    I(this, ji);
    I(this, ue, void 0);
    I(this, bn, void 0);
    I(this, _n, void 0);
    I(this, _s, void 0);
    I(this, $n, void 0);
    I(this, xs, void 0);
    I(this, kn, void 0);
    I(this, $s, void 0);
    this.state = { keys: "", show: !1 }, F(this, ue, 0), F(this, bn, $t()), F(this, _n, $t()), F(this, $n, (n) => {
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
    g(document).on("click", W(this, $n)), this.show(this.focus.bind(this)), F(this, _s, hp(window, {
      Escape: () => {
        this.state.show && (this.state.keys ? this.setState({ keys: "" }) : this.hide());
      },
      Enter: () => {
        if (!this.state.show)
          return;
        const s = at(this, Le, Un).call(this);
        s != null && s.length && this.select(s.dataset("value"));
      },
      ArrowUp: () => {
        var r;
        if (!this.state.show)
          return;
        const s = (r = at(this, Le, Un).call(this)) == null ? void 0 : r.parent();
        if (!(s != null && s.length))
          return;
        let i = s.prev();
        i.length || (i = s.parent().children().last()), this.setHoverItem(i.children("a").dataset("value"));
      },
      ArrowDown: () => {
        var r;
        if (!this.state.show)
          return;
        const s = (r = at(this, Le, Un).call(this)) == null ? void 0 : r.parent();
        if (!(s != null && s.length))
          return;
        let i = s.next();
        i.length || (i = s.parent().children().first()), this.setHoverItem(i.children("a").dataset("value"));
      }
    }));
    const n = at(this, xn, ii).call(this);
    n && g(n).on("mouseenter.pickerMenu.zui", ".menu-item", (s) => {
      const i = g(s.currentTarget);
      this.setHoverItem(i.children("a").dataset("value"));
    });
  }
  componentWillUnmount() {
    var s;
    g(document).off("click", W(this, $n)), (s = W(this, _s)) == null || s.call(this);
    const n = at(this, xn, ii).call(this);
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
    (n = W(this, bn).current) == null || n.focus();
  }
  hide() {
    this.state.show && (W(this, ue) && window.clearTimeout(W(this, ue)), this.setState({ show: !1 }, () => {
      F(this, ue, window.setTimeout(() => {
        var n, s;
        F(this, ue, 0), (s = (n = this.props).onRequestHide) == null || s.call(n);
      }, 200));
    }));
  }
  select(n) {
    const s = this.props.items.find((i) => i.value === n);
    s && this.props.onSelectItem(s);
  }
  setHoverItem(n) {
    this.setState({ hover: n }, () => {
      const s = at(this, Le, Un).call(this);
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
    } = this.props, { show: c, keys: d } = this.state, u = d.trim().length;
    return /* @__PURE__ */ y(
      "div",
      {
        className: L("picker-menu menu-popup", s, { shown: c, "has-search": u }),
        id: `picker-menu-${n}`,
        style: { maxHeight: r, maxWidth: o, width: a, ...i },
        children: [
          at(this, ji, vh).call(this),
          /* @__PURE__ */ y(
            tr,
            {
              ref: W(this, _n),
              className: "picker-menu-list",
              items: at(this, Fi, wh).call(this),
              onClickItem: W(this, xs),
              checkbox: h,
              ...l
            }
          )
        ]
      }
    );
  }
}
ue = new WeakMap(), bn = new WeakMap(), _n = new WeakMap(), _s = new WeakMap(), xn = new WeakSet(), ii = function() {
  var n;
  return (n = W(this, _n).current) == null ? void 0 : n.ref.current;
}, Le = new WeakSet(), Un = function() {
  const n = at(this, xn, ii).call(this);
  if (n)
    return g(n).find(".menu-item>a.hover");
}, Fi = new WeakSet(), wh = function() {
  const { selections: n, items: s } = this.props, i = new Set(n), { keys: r, hover: o } = this.state, a = r.toLowerCase().split(" ").filter((c) => c.length);
  let l = !1;
  const h = s.reduce((c, d) => {
    const {
      value: u,
      keys: f,
      text: p,
      className: m,
      ...v
    } = d;
    if (!a.length || a.every((w) => u.toLowerCase().includes(w) || (f == null ? void 0 : f.toLowerCase().includes(w)) || typeof p == "string" && p.toLowerCase().includes(w))) {
      let w = p ?? u;
      typeof w == "string" && a.length && (w = dp(a, [w])), u === o && (l = !0), c.push({
        key: u,
        active: i.has(u),
        text: w,
        className: L(m, { hover: u === o }),
        "data-value": u,
        ...v
      });
    }
    return c;
  }, []);
  return !l && h.length && (h[0].className = L(h[0].className, "hover")), h;
}, $n = new WeakMap(), xs = new WeakMap(), kn = new WeakMap(), $s = new WeakMap(), ji = new WeakSet(), vh = function() {
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
        onChange: W(this, kn),
        onInput: W(this, kn),
        ref: W(this, bn)
      }
    ),
    r ? /* @__PURE__ */ y("button", { type: "button", className: "btn picker-menu-search-clear square size-sm ghost", onClick: W(this, $s), children: /* @__PURE__ */ y("span", { className: "close" }) }) : /* @__PURE__ */ y("span", { className: "magnifier" })
  ] }) : null;
};
var da = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, st = (t, e, n) => (da(t, e, "read from private field"), n ? n.call(t) : e.get(t)), et = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, ki = (t, e, n, s) => (da(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), fp = (t, e, n, s) => ({
  set _(i) {
    ki(t, e, i, n);
  },
  get _() {
    return st(t, e, s);
  }
}), lt = (t, e, n) => (da(t, e, "access private method"), n), ri, En, Ci, Gt, Ze, qn, Si, ua, oi, eo, no, bh, fa, pa, ga, ma, ya, _h, so, xh, wa, $h, ai, io;
let kh = class extends U {
  constructor(e) {
    super(e), et(this, Ze), et(this, Si), et(this, oi), et(this, no), et(this, ya), et(this, so), et(this, wa), et(this, ai), et(this, ri, 0), et(this, En, g.guid++), et(this, Ci, $t()), et(this, Gt, void 0), et(this, fa, (n) => {
      const { valueList: s } = this, i = new Set(n.map((o) => o.value)), r = s.filter((o) => !i.has(o));
      this.setValue(r);
    }), et(this, pa, () => {
      requestAnimationFrame(() => this.toggle());
    }), et(this, ga, () => {
      this.close();
    }), et(this, ma, (n) => {
      this.props.multiple ? this.toggleValue(n.value) : this.setValue(n.value).then(() => {
        var s;
        (s = st(this, Ci).current) == null || s.hide();
      });
    }), this.state = {
      value: lt(this, oi, eo).call(this, e.defaultValue) ?? "",
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
    return lt(this, Si, ua).call(this, this.state.value);
  }
  componentDidMount() {
    lt(this, ai, io).call(this, !0);
  }
  componentDidUpdate() {
    lt(this, ai, io).call(this);
  }
  componentWillUnmount() {
    var n;
    var e;
    (n = this.props.beforeDestroy) == null || n.call(this), (e = st(this, Gt)) == null || e.call(this), ki(this, Gt, void 0);
  }
  async loadItems() {
    let { items: e } = this.props;
    if (typeof e == "function") {
      const s = ++fp(this, ri)._;
      if (await lt(this, Ze, qn).call(this, { loading: !0, items: [] }), e = await e(), st(this, ri) !== s)
        return [];
    }
    const n = {};
    return Array.isArray(e) && this.state.items !== e && (n.items = e), this.state.loading && (n.loading = !1), Object.keys(n).length && await lt(this, Ze, qn).call(this, n), e;
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
    await lt(this, Ze, qn).call(this, { open: e }), e && this.loadItems();
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
    await lt(this, Ze, qn).call(this, { value: lt(this, oi, eo).call(this, e), ...n }), (s = this.props.onChange) == null || s.call(this, this.getValue());
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
    } = this.props, a = r || (i ? ip : rp), l = lt(this, no, bh).call(this);
    return /* @__PURE__ */ y(
      "div",
      {
        id: `picker-${st(this, En)}`,
        className: L("picker", e),
        style: n,
        children: [
          /* @__PURE__ */ y(a, { ...l }),
          s,
          lt(this, so, xh).call(this),
          o ? /* @__PURE__ */ y("input", { type: "hidden", className: "picker-value", name: o, value: this.state.value }) : null
        ]
      }
    );
  }
};
ri = /* @__PURE__ */ new WeakMap();
En = /* @__PURE__ */ new WeakMap();
Ci = /* @__PURE__ */ new WeakMap();
Gt = /* @__PURE__ */ new WeakMap();
Ze = /* @__PURE__ */ new WeakSet();
qn = function(t) {
  return new Promise((e) => {
    this.setState(t, e);
  });
};
Si = /* @__PURE__ */ new WeakSet();
ua = function(t) {
  return typeof t == "string" ? t.length ? g.unique(t.split(this.props.valueSplitter ?? ",")) : [] : Array.isArray(t) ? g.unique(t) : [];
};
oi = /* @__PURE__ */ new WeakSet();
eo = function(t) {
  const e = lt(this, Si, ua).call(this, t);
  return e.length ? e.join(this.props.valueSplitter ?? ",") : void 0;
};
no = /* @__PURE__ */ new WeakSet();
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
    id: `${st(this, En)}`,
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
so = /* @__PURE__ */ new WeakSet();
xh = function() {
  const { open: t } = this.state;
  if (!t)
    return null;
  const e = g(this.props.container || "body");
  let n = e.find(".pickers-container");
  n.length || (n = g("<div>").addClass("pickers-container").appendTo(e));
  const { Menu: s = up } = this.props;
  return qu(/* @__PURE__ */ y(s, { ...lt(this, ya, _h).call(this), ref: st(this, Ci) }), n[0]);
};
wa = /* @__PURE__ */ new WeakSet();
$h = function() {
  const t = g(`#picker-${st(this, En)}`)[0], e = g(`#picker-menu-${st(this, En)}`)[0];
  if (!e || !t || !this.state.open) {
    st(this, Gt) && (st(this, Gt).call(this), ki(this, Gt, void 0));
    return;
  }
  st(this, Gt) || ki(this, Gt, Ko(t, e, () => {
    const { menuDirection: n, menuWidth: s } = this.props;
    ir(t, e, {
      placement: `${n === "top" ? "top" : "bottom"}-start`,
      middleware: [n === "auto" ? er() : null, Or(), Go(1)].filter(Boolean)
    }).then(({ x: i, y: r }) => {
      g(e).css({ left: i, top: r, width: s === "100%" ? g(t).width() : void 0 });
    }), s === "100%" && g(e).css({ width: g(t).width() });
  }));
};
ai = /* @__PURE__ */ new WeakSet();
io = function(t = !1) {
  var e;
  (e = this.props.afterRender) == null || e.call(this, { firstRender: t }), lt(this, wa, $h).call(this);
};
kh.defaultProps = {
  container: "body",
  valueSplitter: ",",
  search: !0,
  menuWidth: "100%",
  menuDirection: "auto",
  menuMaxHeight: 300
};
class Ch extends J {
}
Ch.NAME = "Picker";
Ch.Component = kh;
class Sh extends ot {
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
    return i && s.middleware.push(er()), r && s.middleware.push(r === !0 ? Or() : Or(r)), o && s.middleware.push(Ir({ element: this.$arrow[0] })), a && s.middleware.push(Go(a)), s;
  }
  createPopper() {
    const e = this.element, n = this.$target[0];
    this.cleanup = Ko(e, n, () => {
      ir(e, n, this.computePositionConfig()).then(({ x: s, y: i, placement: r, middlewareData: o }) => {
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
Sh.NAME = "Popovers";
Sh.DEFAULT = {
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
Eh.Component = ut;
function Rs(t) {
  return t.split("-")[1];
}
function va(t) {
  return t === "y" ? "height" : "width";
}
function dn(t) {
  return t.split("-")[0];
}
function lr(t) {
  return ["top", "bottom"].includes(dn(t)) ? "x" : "y";
}
function Ml(t, e, n) {
  let { reference: s, floating: i } = t;
  const r = s.x + s.width / 2 - i.width / 2, o = s.y + s.height / 2 - i.height / 2, a = lr(e), l = va(a), h = s[l] / 2 - i[l] / 2, c = a === "x";
  let d;
  switch (dn(e)) {
    case "top":
      d = { x: r, y: s.y - i.height };
      break;
    case "bottom":
      d = { x: r, y: s.y + s.height };
      break;
    case "right":
      d = { x: s.x + s.width, y: o };
      break;
    case "left":
      d = { x: s.x - i.width, y: o };
      break;
    default:
      d = { x: s.x, y: s.y };
  }
  switch (Rs(e)) {
    case "start":
      d[a] -= h * (n && c ? -1 : 1);
      break;
    case "end":
      d[a] += h * (n && c ? -1 : 1);
  }
  return d;
}
const pp = async (t, e, n) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: r = [], platform: o } = n, a = r.filter(Boolean), l = await (o.isRTL == null ? void 0 : o.isRTL(e));
  let h = await o.getElementRects({ reference: t, floating: e, strategy: i }), { x: c, y: d } = Ml(h, s, l), u = s, f = {}, p = 0;
  for (let m = 0; m < a.length; m++) {
    const { name: v, fn: w } = a[m], { x: b, y: k, data: S, reset: E } = await w({ x: c, y: d, initialPlacement: s, placement: u, strategy: i, middlewareData: f, rects: h, platform: o, elements: { reference: t, floating: e } });
    c = b ?? c, d = k ?? d, f = { ...f, [v]: { ...f[v], ...S } }, E && p <= 50 && (p++, typeof E == "object" && (E.placement && (u = E.placement), E.rects && (h = E.rects === !0 ? await o.getElementRects({ reference: t, floating: e, strategy: i }) : E.rects), { x: c, y: d } = Ml(h, u, l)), m = -1);
  }
  return { x: c, y: d, placement: u, strategy: i, middlewareData: f };
};
function Mh(t) {
  return typeof t != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(t) : { top: t, right: t, bottom: t, left: t };
}
function Ei(t) {
  return { ...t, top: t.y, left: t.x, right: t.x + t.width, bottom: t.y + t.height };
}
async function gp(t, e) {
  var n;
  e === void 0 && (e = {});
  const { x: s, y: i, platform: r, rects: o, elements: a, strategy: l } = t, { boundary: h = "clippingAncestors", rootBoundary: c = "viewport", elementContext: d = "floating", altBoundary: u = !1, padding: f = 0 } = e, p = Mh(f), m = a[u ? d === "floating" ? "reference" : "floating" : d], v = Ei(await r.getClippingRect({ element: (n = await (r.isElement == null ? void 0 : r.isElement(m))) == null || n ? m : m.contextElement || await (r.getDocumentElement == null ? void 0 : r.getDocumentElement(a.floating)), boundary: h, rootBoundary: c, strategy: l })), w = d === "floating" ? { ...o.floating, x: s, y: i } : o.reference, b = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(a.floating)), k = await (r.isElement == null ? void 0 : r.isElement(b)) && await (r.getScale == null ? void 0 : r.getScale(b)) || { x: 1, y: 1 }, S = Ei(r.convertOffsetParentRelativeRectToViewportRelativeRect ? await r.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: w, offsetParent: b, strategy: l }) : w);
  return { top: (v.top - S.top + p.top) / k.y, bottom: (S.bottom - v.bottom + p.bottom) / k.y, left: (v.left - S.left + p.left) / k.x, right: (S.right - v.right + p.right) / k.x };
}
const mp = Math.min, yp = Math.max;
function wp(t, e, n) {
  return yp(t, mp(e, n));
}
const vp = (t) => ({ name: "arrow", options: t, async fn(e) {
  const { element: n, padding: s = 0 } = t || {}, { x: i, y: r, placement: o, rects: a, platform: l } = e;
  if (n == null)
    return {};
  const h = Mh(s), c = { x: i, y: r }, d = lr(o), u = va(d), f = await l.getDimensions(n), p = d === "y" ? "top" : "left", m = d === "y" ? "bottom" : "right", v = a.reference[u] + a.reference[d] - c[d] - a.floating[u], w = c[d] - a.reference[d], b = await (l.getOffsetParent == null ? void 0 : l.getOffsetParent(n));
  let k = b ? d === "y" ? b.clientHeight || 0 : b.clientWidth || 0 : 0;
  k === 0 && (k = a.floating[u]);
  const S = v / 2 - w / 2, E = h[p], P = k - f[u] - h[m], M = k / 2 - f[u] / 2 + S, R = wp(E, M, P), A = Rs(o) != null && M != R && a.reference[u] / 2 - (M < E ? h[p] : h[m]) - f[u] / 2 < 0;
  return { [d]: c[d] - (A ? M < E ? E - M : P - M : 0), data: { [d]: R, centerOffset: M - R } };
} }), bp = ["top", "right", "bottom", "left"];
bp.reduce((t, e) => t.concat(e, e + "-start", e + "-end"), []);
const _p = { left: "right", right: "left", bottom: "top", top: "bottom" };
function Mi(t) {
  return t.replace(/left|right|bottom|top/g, (e) => _p[e]);
}
function xp(t, e, n) {
  n === void 0 && (n = !1);
  const s = Rs(t), i = lr(t), r = va(i);
  let o = i === "x" ? s === (n ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return e.reference[r] > e.floating[r] && (o = Mi(o)), { main: o, cross: Mi(o) };
}
const $p = { start: "end", end: "start" };
function Sr(t) {
  return t.replace(/start|end/g, (e) => $p[e]);
}
const kp = function(t) {
  return t === void 0 && (t = {}), { name: "flip", options: t, async fn(e) {
    var n;
    const { placement: s, middlewareData: i, rects: r, initialPlacement: o, platform: a, elements: l } = e, { mainAxis: h = !0, crossAxis: c = !0, fallbackPlacements: d, fallbackStrategy: u = "bestFit", fallbackAxisSideDirection: f = "none", flipAlignment: p = !0, ...m } = t, v = dn(s), w = dn(o) === o, b = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), k = d || (w || !p ? [Mi(o)] : function(x) {
      const $ = Mi(x);
      return [Sr(x), $, Sr($)];
    }(o));
    d || f === "none" || k.push(...function(x, $, N, D) {
      const O = Rs(x);
      let B = function(G, Pt, Mn) {
        const Ns = ["left", "right"], Tn = ["right", "left"], Ls = ["top", "bottom"], gr = ["bottom", "top"];
        switch (G) {
          case "top":
          case "bottom":
            return Mn ? Pt ? Tn : Ns : Pt ? Ns : Tn;
          case "left":
          case "right":
            return Pt ? Ls : gr;
          default:
            return [];
        }
      }(dn(x), N === "start", D);
      return O && (B = B.map((G) => G + "-" + O), $ && (B = B.concat(B.map(Sr)))), B;
    }(o, p, f, b));
    const S = [o, ...k], E = await gp(e, m), P = [];
    let M = ((n = i.flip) == null ? void 0 : n.overflows) || [];
    if (h && P.push(E[v]), c) {
      const { main: x, cross: $ } = xp(s, r, b);
      P.push(E[x], E[$]);
    }
    if (M = [...M, { placement: s, overflows: P }], !P.every((x) => x <= 0)) {
      var R;
      const x = (((R = i.flip) == null ? void 0 : R.index) || 0) + 1, $ = S[x];
      if ($)
        return { data: { index: x, overflows: M }, reset: { placement: $ } };
      let N = "bottom";
      switch (u) {
        case "bestFit": {
          var A;
          const D = (A = M.map((O) => [O, O.overflows.filter((B) => B > 0).reduce((B, G) => B + G, 0)]).sort((O, B) => O[1] - B[1])[0]) == null ? void 0 : A[0].placement;
          D && (N = D);
          break;
        }
        case "initialPlacement":
          N = o;
      }
      if (s !== N)
        return { reset: { placement: N } };
    }
    return {};
  } };
}, Cp = function(t) {
  return t === void 0 && (t = 0), { name: "offset", options: t, async fn(e) {
    const { x: n, y: s } = e, i = await async function(r, o) {
      const { placement: a, platform: l, elements: h } = r, c = await (l.isRTL == null ? void 0 : l.isRTL(h.floating)), d = dn(a), u = Rs(a), f = lr(a) === "x", p = ["left", "top"].includes(d) ? -1 : 1, m = c && f ? -1 : 1, v = typeof o == "function" ? o(r) : o;
      let { mainAxis: w, crossAxis: b, alignmentAxis: k } = typeof v == "number" ? { mainAxis: v, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ...v };
      return u && typeof k == "number" && (b = u === "end" ? -1 * k : k), f ? { x: b * m, y: w * p } : { x: w * p, y: b * m };
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
let js;
function Th() {
  if (js)
    return js;
  const t = navigator.userAgentData;
  return t && Array.isArray(t.brands) ? (js = t.brands.map((e) => e.brand + "/" + e.version).join(" "), js) : navigator.userAgent;
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
function cr(t) {
  const { overflow: e, overflowX: n, overflowY: s, display: i } = At(t);
  return /auto|scroll|overlay|hidden|clip/.test(e + s + n) && !["inline", "contents"].includes(i);
}
function Sp(t) {
  return ["table", "td", "th"].includes(_e(t));
}
function ro(t) {
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
const Rl = Math.min, ss = Math.max, Ti = Math.round;
function Nh(t) {
  const e = At(t);
  let n = parseFloat(e.width), s = parseFloat(e.height);
  const i = t.offsetWidth, r = t.offsetHeight, o = Ti(n) !== i || Ti(s) !== r;
  return o && (n = i, s = r), { width: n, height: s, fallback: o };
}
function Lh(t) {
  return xt(t) ? t : t.contextElement;
}
const Dh = { x: 1, y: 1 };
function un(t) {
  const e = Lh(t);
  if (!Qt(e))
    return Dh;
  const n = e.getBoundingClientRect(), { width: s, height: i, fallback: r } = Nh(e);
  let o = (r ? Ti(n.width) : n.width) / s, a = (r ? Ti(n.height) : n.height) / i;
  return o && Number.isFinite(o) || (o = 1), a && Number.isFinite(a) || (a = 1), { x: o, y: a };
}
function ze(t, e, n, s) {
  var i, r;
  e === void 0 && (e = !1), n === void 0 && (n = !1);
  const o = t.getBoundingClientRect(), a = Lh(t);
  let l = Dh;
  e && (s ? xt(s) && (l = un(s)) : l = un(t));
  const h = a ? gt(a) : window, c = !Ah() && n;
  let d = (o.left + (c && ((i = h.visualViewport) == null ? void 0 : i.offsetLeft) || 0)) / l.x, u = (o.top + (c && ((r = h.visualViewport) == null ? void 0 : r.offsetTop) || 0)) / l.y, f = o.width / l.x, p = o.height / l.y;
  if (a) {
    const m = gt(a), v = s && xt(s) ? gt(s) : s;
    let w = m.frameElement;
    for (; w && s && v !== m; ) {
      const b = un(w), k = w.getBoundingClientRect(), S = getComputedStyle(w);
      k.x += (w.clientLeft + parseFloat(S.paddingLeft)) * b.x, k.y += (w.clientTop + parseFloat(S.paddingTop)) * b.y, d *= b.x, u *= b.y, f *= b.x, p *= b.y, d += k.x, u += k.y, w = gt(w).frameElement;
    }
  }
  return { width: f, height: p, top: u, right: d + f, bottom: u + p, left: d, x: d, y: u };
}
function ve(t) {
  return ((Rh(t) ? t.ownerDocument : t.document) || window.document).documentElement;
}
function hr(t) {
  return xt(t) ? { scrollLeft: t.scrollLeft, scrollTop: t.scrollTop } : { scrollLeft: t.pageXOffset, scrollTop: t.pageYOffset };
}
function Ph(t) {
  return ze(ve(t)).left + hr(t).scrollLeft;
}
function Ep(t, e, n) {
  const s = Qt(e), i = ve(e), r = ze(t, !0, n === "fixed", e);
  let o = { scrollLeft: 0, scrollTop: 0 };
  const a = { x: 0, y: 0 };
  if (s || !s && n !== "fixed")
    if ((_e(e) !== "body" || cr(i)) && (o = hr(e)), Qt(e)) {
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
  for (; n && Sp(n) && At(n).position === "static"; )
    n = Al(n);
  return n && (_e(n) === "html" || _e(n) === "body" && At(n).position === "static" && !ro(n)) ? e : n || function(s) {
    let i = ms(s);
    for (; Qt(i) && !ba(i); ) {
      if (ro(i))
        return i;
      i = ms(i);
    }
    return null;
  }(t) || e;
}
function Wh(t) {
  const e = ms(t);
  return ba(e) ? t.ownerDocument.body : Qt(e) && cr(e) ? e : Wh(e);
}
function is(t, e) {
  var n;
  e === void 0 && (e = []);
  const s = Wh(t), i = s === ((n = t.ownerDocument) == null ? void 0 : n.body), r = gt(s);
  return i ? e.concat(r, r.visualViewport || [], cr(s) ? s : []) : e.concat(s, is(s));
}
function Ll(t, e, n) {
  return e === "viewport" ? Ei(function(s, i) {
    const r = gt(s), o = ve(s), a = r.visualViewport;
    let l = o.clientWidth, h = o.clientHeight, c = 0, d = 0;
    if (a) {
      l = a.width, h = a.height;
      const u = Ah();
      (u || !u && i === "fixed") && (c = a.offsetLeft, d = a.offsetTop);
    }
    return { width: l, height: h, x: c, y: d };
  }(t, n)) : xt(e) ? function(s, i) {
    const r = ze(s, !0, i === "fixed"), o = r.top + s.clientTop, a = r.left + s.clientLeft, l = Qt(s) ? un(s) : { x: 1, y: 1 }, h = s.clientWidth * l.x, c = s.clientHeight * l.y, d = a * l.x, u = o * l.y;
    return { top: u, left: d, right: d + h, bottom: u + c, x: d, y: u, width: h, height: c };
  }(e, n) : Ei(function(s) {
    var i;
    const r = ve(s), o = hr(s), a = (i = s.ownerDocument) == null ? void 0 : i.body, l = ss(r.scrollWidth, r.clientWidth, a ? a.scrollWidth : 0, a ? a.clientWidth : 0), h = ss(r.scrollHeight, r.clientHeight, a ? a.scrollHeight : 0, a ? a.clientHeight : 0);
    let c = -o.scrollLeft + Ph(s);
    const d = -o.scrollTop;
    return At(a || r).direction === "rtl" && (c += ss(r.clientWidth, a ? a.clientWidth : 0) - l), { width: l, height: h, x: c, y: d };
  }(ve(t)));
}
const Mp = { getClippingRect: function(t) {
  let { element: e, boundary: n, rootBoundary: s, strategy: i } = t;
  const r = n === "clippingAncestors" ? function(h, c) {
    const d = c.get(h);
    if (d)
      return d;
    let u = is(h).filter((v) => xt(v) && _e(v) !== "body"), f = null;
    const p = At(h).position === "fixed";
    let m = p ? ms(h) : h;
    for (; xt(m) && !ba(m); ) {
      const v = At(m), w = ro(m);
      (p ? w || f : w || v.position !== "static" || !f || !["absolute", "fixed"].includes(f.position)) ? f = v : u = u.filter((b) => b !== m), m = ms(m);
    }
    return c.set(h, u), u;
  }(e, this._c) : [].concat(n), o = [...r, s], a = o[0], l = o.reduce((h, c) => {
    const d = Ll(e, c, i);
    return h.top = ss(d.top, h.top), h.right = Rl(d.right, h.right), h.bottom = Rl(d.bottom, h.bottom), h.left = ss(d.left, h.left), h;
  }, Ll(e, a, i));
  return { width: l.right - l.left, height: l.bottom - l.top, x: l.left, y: l.top };
}, convertOffsetParentRelativeRectToViewportRelativeRect: function(t) {
  let { rect: e, offsetParent: n, strategy: s } = t;
  const i = Qt(n), r = ve(n);
  if (n === r)
    return e;
  let o = { scrollLeft: 0, scrollTop: 0 }, a = { x: 1, y: 1 };
  const l = { x: 0, y: 0 };
  if ((i || !i && s !== "fixed") && ((_e(n) !== "body" || cr(r)) && (o = hr(n)), Qt(n))) {
    const h = ze(n);
    a = un(n), l.x = h.x + n.clientLeft, l.y = h.y + n.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - o.scrollLeft * a.x + l.x, y: e.y * a.y - o.scrollTop * a.y + l.y };
}, isElement: xt, getDimensions: function(t) {
  return Nh(t);
}, getOffsetParent: Nl, getDocumentElement: ve, getScale: un, async getElementRects(t) {
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
  let c, d = null;
  if (o) {
    let f = !0;
    d = new ResizeObserver(() => {
      f || n(), f = !1;
    }), xt(t) && !a && d.observe(t), xt(t) || !t.contextElement || a || d.observe(t.contextElement), d.observe(e);
  }
  let u = a ? ze(t) : null;
  return a && function f() {
    const p = ze(t);
    !u || p.x === u.x && p.y === u.y && p.width === u.width && p.height === u.height || n(), u = p, c = requestAnimationFrame(f);
  }(), n(), () => {
    var f;
    h.forEach((p) => {
      l && p.removeEventListener("scroll", n), r && p.removeEventListener("resize", n);
    }), (f = d) == null || f.disconnect(), d = null, a && cancelAnimationFrame(c);
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
}, Fe = (t, e, n, s) => (_a(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Rt = (t, e, n) => (_a(t, e, "access private method"), n), rs, os, Vn, rn, ct, oo, Ri, dr, xa, $a, Ih, ao, Oh, ka, Hh, Ca, Bh, Sa, zh, lo, Fh, Ea, jh, as, co, Uh;
const on = class extends ot {
  constructor() {
    super(...arguments), Z(this, dr), Z(this, $a), Z(this, ao), Z(this, ka), Z(this, Ca), Z(this, Sa), Z(this, lo), Z(this, Ea), Z(this, co), Z(this, rs, !1), Z(this, os, void 0), Z(this, Vn, 0), Z(this, rn, void 0), Z(this, ct, void 0), Z(this, oo, void 0), Z(this, Ri, void 0), this.hideLater = () => {
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
    return V(this, rn) || Rt(this, ao, Oh).call(this);
  }
  get trigger() {
    return V(this, oo) || this.element;
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
    return this.setOptions(t), !V(this, rs) && this.isHover && Rt(this, co, Uh).call(this), this.options.animation && this.tooltip.classList.add("fade"), this.element.classList.add(this.elementShowClass), this.tooltip.classList.add(on.CLASS_SHOW), Rt(this, lo, Fh).call(this), !0;
  }
  hide() {
    var e;
    var t;
    return (t = V(this, Ri)) == null || t.call(this), this.element.classList.remove(this.elementShowClass), (e = V(this, rn)) == null || e.classList.remove(on.CLASS_SHOW), !0;
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
oo = /* @__PURE__ */ new WeakMap();
Ri = /* @__PURE__ */ new WeakMap();
dr = /* @__PURE__ */ new WeakSet();
xa = function() {
  const { arrow: t } = this.options;
  return typeof t == "number" ? t : 8;
};
$a = /* @__PURE__ */ new WeakSet();
Ih = function() {
  const t = Rt(this, dr, xa).call(this);
  return Fe(this, ct, document.createElement("div")), V(this, ct).style.position = this.options.strategy, V(this, ct).style.width = `${t}px`, V(this, ct).style.height = `${t}px`, V(this, ct).style.transform = "rotate(45deg)", V(this, ct);
};
ao = /* @__PURE__ */ new WeakSet();
Oh = function() {
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
  if (this.options.arrow && (e == null || e.append(Rt(this, $a, Ih).call(this))), !e)
    throw new Error("Tooltip: Cannot find tooltip element");
  return e.style.width = "max-content", e.style.position = "absolute", e.style.top = "0", e.style.left = "0", document.body.appendChild(e), Fe(this, rn, e), e;
};
ka = /* @__PURE__ */ new WeakSet();
Hh = function() {
  var i;
  const t = Rt(this, dr, xa).call(this), { strategy: e, placement: n } = this.options, s = {
    middleware: [Cp(t), kp()],
    strategy: e,
    placement: n
  };
  return this.options.arrow && V(this, ct) && ((i = s.middleware) == null || i.push(vp({ element: V(this, ct) }))), s;
};
Ca = /* @__PURE__ */ new WeakSet();
Bh = function(t) {
  return {
    top: "bottom",
    right: "left",
    bottom: "top",
    left: "right"
  }[t];
};
Sa = /* @__PURE__ */ new WeakSet();
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
lo = /* @__PURE__ */ new WeakSet();
Fh = function() {
  const t = Rt(this, ka, Hh).call(this), e = Rt(this, Ea, jh).call(this);
  Fe(this, Ri, Tp(e, this.tooltip, () => {
    Rp(e, this.tooltip, t).then(({ x: n, y: s, middlewareData: i, placement: r }) => {
      Object.assign(this.tooltip.style, {
        left: `${n}px`,
        top: `${s}px`
      });
      const o = r.split("-")[0], a = Rt(this, Ca, Bh).call(this, o);
      if (i.arrow && V(this, ct)) {
        const { x: l, y: h } = i.arrow;
        Object.assign(V(this, ct).style, {
          left: l != null ? `${l}px` : "",
          top: h != null ? `${h}px` : "",
          [a]: `${-V(this, ct).offsetWidth / 2}px`,
          background: "inherit",
          border: "inherit",
          ...Rt(this, Sa, zh).call(this, o)
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
co = /* @__PURE__ */ new WeakSet();
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
  target: d,
  trailingIcon: u,
  hint: f,
  checked: p,
  actions: m,
  show: v,
  level: w = 0,
  items: b,
  ...k
}) {
  const S = Array.isArray(m) ? { items: m } : m;
  return S && (S.btnProps || (S.btnProps = { size: "sm" }), S.className = L("tree-actions not-nested-toggle", S.className)), /* @__PURE__ */ y(
    "div",
    {
      className: L("tree-item-content", n, { disabled: a, active: l }),
      title: f,
      "data-target": d,
      style: Object.assign({ paddingLeft: `${w * 20}px` }, i),
      "data-level": w,
      ...r,
      ...k,
      children: [
        /* @__PURE__ */ y("span", { class: `tree-toggle-icon${b ? " state" : ""}`, children: b ? /* @__PURE__ */ y("span", { class: `caret-${v ? "down" : "right"}` }) : null }),
        typeof p == "boolean" ? /* @__PURE__ */ y("div", { class: `tree-checkbox checkbox-primary${p ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) }) : null,
        /* @__PURE__ */ y(us, { icon: h, className: "tree-icon" }),
        o ? /* @__PURE__ */ y("a", { className: "text tree-link not-nested-toggle", href: o, children: c }) : /* @__PURE__ */ y("span", { class: "text", children: c }),
        typeof s == "function" ? s() : s,
        S ? /* @__PURE__ */ y(ut, { ...S }) : null,
        /* @__PURE__ */ y(us, { icon: u, className: "tree-trailing-icon" })
      ]
    }
  );
}
let Ma = class extends Qi {
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
    return n && (e.className = L(e.className, "tree-hover")), e;
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
}, j = (t, e, n) => (Ta(t, e, "read from private field"), n ? n.call(t) : e.get(t)), St = (t, e, n) => {
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
}), Ae = (t, e, n) => (Ta(t, e, "access private method"), n), me, fn, ys, an, Ai, K, ho, Vh, ln, ls, Ra, Gh, Aa, Yh;
class Kh extends ot {
  constructor() {
    super(...arguments), St(this, ho), St(this, ln), St(this, Ra), St(this, Aa), St(this, me, void 0), St(this, fn, void 0), St(this, ys, null), St(this, an, []), St(this, Ai, 0), St(this, K, []);
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
    g(this.element).off("click change"), Mt(this, me, void 0), Mt(this, fn, void 0), j(this, an).length = 0, j(this, K).length = 0;
  }
  render(e) {
    super.render(e), j(this, K).length ? Ae(this, ln, ls).call(this) : (Mt(this, ys, null), Ae(this, ho, Vh).call(this));
  }
  addRow(e) {
    const n = Np(this, Ai)._++;
    typeof e == "number" && e >= 0 && e <= j(this, K).length ? j(this, K).splice(e + 1, 0, n) : (e = j(this, K).length, j(this, K).push(n)), Ae(this, ln, ls).call(this, void 0, e);
  }
  deleteRow(e) {
    var s;
    if (j(this, K).length <= 1 || typeof e != "number" || e < 0 || e >= j(this, K).length)
      return !1;
    const n = j(this, K)[e];
    j(this, K).splice(e, 1), (s = j(this, me)) == null || s.children(`[data-gid="${n}"]`).remove(), Ae(this, ln, ls).call(this, void 0, e);
  }
  deleteRowByGid(e) {
    return this.deleteRow(j(this, K).indexOf(e));
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
Ai = /* @__PURE__ */ new WeakMap();
K = /* @__PURE__ */ new WeakMap();
ho = /* @__PURE__ */ new WeakSet();
Vh = function() {
  const t = j(this, fn), e = j(this, me);
  if (!t || !(e != null && e.length))
    return;
  const { data: n = [], minRows: s, maxRows: i, mode: r } = this.options, a = r === "add" ? Math.min(Math.max(1, i ?? 100), Math.max(1, 10, s ?? 10, n.length)) : n.length;
  Mt(this, K, Array(a).fill(0).map((l, h) => h)), Mt(this, Ai, j(this, K).length), Ae(this, ln, ls).call(this, n);
};
ln = /* @__PURE__ */ new WeakSet();
ls = function(t = [], e = 0) {
  var s;
  const n = j(this, K).length;
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
    n !== !1 && e.append(g(Y(i, { type: "add", icon: n, text: this.i18n("add") }))[0]), s !== !1 && e.append(g(Y(i, { type: "delete", icon: s, text: this.i18n("delete") }))[0]), Mt(this, ys, e);
  }
  t.empty().append(e.cloneNode(!0));
};
Aa = /* @__PURE__ */ new WeakSet();
Yh = function(t, e) {
  var h;
  const n = j(this, me), s = String(j(this, K)[t]);
  let i = n.children(`[data-gid="${s}"]`);
  if (i.length) {
    if (!e && i.data("index") === t)
      return;
  } else {
    const c = j(this, fn), u = document.importNode(c.content, !0).querySelector("tr");
    i = g(u).attr("data-gid", s);
  }
  if (i.attr("data-index", `${t}`), t) {
    const c = j(this, K)[t - 1], d = n.children(`[data-gid="${c}"]`);
    d.length ? d.after(i) : i.appendTo(n);
  } else
    i.prependTo(n);
  const { idKey: r = "id", mode: o } = this.options, a = o === "add", l = String(a || !e ? t : e[r]);
  j(this, an).forEach((c) => {
    var u, f;
    let d = i.find(`td[data-name="${c.name}"]`);
    if (d.length || (d = g(`<td data-name="${c.name}"></td>`).appendTo(i)), c.index) {
      d.find(".form-control-static").text(l).attr("id", `${c.name}_${s}`), (u = this.options.onRenderRowCol) == null || u.call(this, d, c, e);
      return;
    }
    if (!d.data("init") || e) {
      if (c.name === "ACTIONS") {
        if (d.addClass("form-batch-row-actions"), !a)
          return;
        Ae(this, Ra, Gh).call(this, d);
        return;
      }
      d.data("init", 1).find("[name],.form-control-static").each((p, m) => {
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
          }).addClass("form-batch-input"), d.find(`label[for="${b}"]`).each((k, S) => {
            g(S).attr("for", `${b}_${s}`);
          }), e && v.val(String(e[w] ?? ""));
        }
      });
    }
    if (c.ditto)
      if (d.addClass("form-batch-ditto"), t) {
        const p = g(`<div class="input-control-suffix form-batch-ditto-btn"><button type="button" class="btn ghost form-batch-btn" data-type="ditto">${this.i18n("ditto")}</button></div>`), m = g('<div class="input-control input-control-ditto has-suffix"></div>').append(d.children()).append(p).appendTo(d);
        requestAnimationFrame(() => m.css("--input-control-suffix", `${p.find(".btn").outerWidth()}px`)), d.attr("data-ditto", c.defaultDitto ?? "on");
      } else {
        d.attr("data-ditto", "");
        const p = d.find(".input-control-ditto");
        p.length && (p.children().not(".form-batch-ditto-btn").appendTo(d), p.remove());
      }
    (f = this.options.onRenderRowCol) == null || f.call(this, d, c, e);
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
}, Ot = (t, e, n) => (Xh(t, e, "read from private field"), n ? n.call(t) : e.get(t)), Ln = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Dn = (t, e, n, s) => (Xh(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Gn, Qe, Yn, li, ci;
function Lp(t) {
  return typeof t == "string" ? t.split(",").map((e) => {
    const n = parseFloat(e);
    return Number.isNaN(n) ? null : n;
  }) : t;
}
let Na = class extends U {
  constructor() {
    super(...arguments), Ln(this, Gn, $t()), Ln(this, Qe, 0), Ln(this, Yn, void 0), Ln(this, li, void 0), Ln(this, ci, !1);
  }
  componentDidMount() {
    var n;
    this.tryDraw = this.tryDraw.bind(this), this.tryDraw();
    const e = (n = Ot(this, Gn).current) == null ? void 0 : n.parentElement;
    if (this.props.responsive !== !1) {
      if (e && typeof ResizeObserver < "u") {
        const s = new ResizeObserver(this.tryDraw);
        s.observe(e), Dn(this, Yn, s);
      }
      Ot(this, Yn) || window.addEventListener("resize", this.tryDraw);
    }
    if (e && typeof IntersectionObserver < "u") {
      const s = new IntersectionObserver((i) => {
        Ot(this, ci) && i.some((r) => r.isIntersecting) && this.tryDraw();
      });
      s.observe(e), Dn(this, li, s);
    }
  }
  componentWillUnmount() {
    var e;
    (e = Ot(this, Yn)) == null || e.disconnect(), window.removeEventListener("resize", this.tryDraw);
  }
  tryDraw() {
    Ot(this, Qe) && cancelAnimationFrame(Ot(this, Qe)), Dn(this, Qe, requestAnimationFrame(() => {
      this.draw(), Dn(this, Qe, 0);
    }));
  }
  draw() {
    const e = Ot(this, Gn).current;
    if (!e)
      return;
    const n = e.parentElement, { width: s, height: i, responsive: r = !0 } = this.props;
    let o = s || n.clientWidth, a = i || n.clientHeight;
    if (s && i && r && (o = n.clientWidth, a = Math.floor(i * o / s)), e.style.width = `${o}px`, e.style.height = `${a}px`, o = o * (window.devicePixelRatio || 1), a = a * (window.devicePixelRatio || 1), e.width = o, e.height = a, !g(n).isVisible() && Ot(this, li)) {
      Dn(this, ci, !0);
      return;
    }
    const {
      lineSize: l = 1,
      scaleLine: h = !1,
      scaleLineSize: c,
      scaleLineGap: d = 1,
      scaleLineDash: u,
      referenceLine: f,
      referenceLineSize: p,
      referenceLineDash: m,
      color: v = "#2c78f1",
      fillColor: w = ["rgba(46, 127, 255, 0.3)", "rgba(46, 127, 255, 0.05)"],
      lineDash: b = [],
      bezier: k
    } = this.props, S = Lp(this.props.data), E = Math.floor(o / (S.length - 1)), P = Math.max(...S.filter((x) => x !== null)), M = S.map((x, $) => {
      const N = typeof x != "number";
      return {
        x: $ * E,
        y: N ? a : Math.round((1 - x / P) * (a - l)),
        empty: N
      };
    });
    let R = M[0];
    const A = e.getContext("2d");
    if (h) {
      const x = typeof h == "string" ? h : "rgba(100,100,100,.1)";
      A.strokeStyle = x, A.lineWidth = c || l, u && A.setLineDash(u);
      for (let $ = 0; $ < M.length; ++$) {
        if ($ % d !== 0)
          continue;
        const N = M[$];
        A.moveTo(N.x, 0), A.lineTo(N.x, a);
      }
      A.stroke();
    }
    if (f && M.length > 1) {
      const x = typeof f == "string" ? f : "rgba(100,100,100,.2)", $ = M[M.length - 1];
      A.moveTo($.x, $.y), A.strokeStyle = x, A.lineWidth = p || l, A.lineTo(R.x, R.y), m && A.setLineDash(m), A.stroke();
    }
    for (A.setLineDash(b); M.length && M[M.length - 1].empty; )
      M.pop();
    if (w) {
      const x = M[M.length - 1];
      if (A.beginPath(), A.moveTo(0, a), A.lineTo(R.x, R.y), k) {
        const $ = Math.round(E / 2);
        for (let N = 1; N < M.length; ++N) {
          const D = M[N], O = Math.round((D.y - R.y) / 5);
          A.bezierCurveTo(R.x + $, R.y + O, D.x - $, D.y - O, D.x, D.y), R = D;
        }
      } else
        for (let $ = 1; $ < M.length; ++$)
          R = M[$], A.lineTo(R.x, R.y);
      if (A.lineTo(x.x, a), Array.isArray(w)) {
        const $ = A.createLinearGradient(0, 0, 0, a);
        for (let N = 0; N < w.length; ++N)
          $.addColorStop(N / (w.length - 1), w[N]);
        A.fillStyle = $;
      } else
        A.fillStyle = w;
      A.fill();
    }
    if (R = M[0], A.beginPath(), A.moveTo(R.x, R.y), k) {
      const x = Math.round(E / 2);
      for (let $ = 1; $ < M.length; ++$) {
        const N = M[$], D = Math.round((N.y - R.y) / 5);
        A.bezierCurveTo(R.x + x, R.y + D, N.x - x, N.y - D, N.x, N.y), R = N;
      }
    } else
      for (let x = 1; x < M.length; ++x)
        R = M[x], A.lineTo(R.x, R.y);
    A.strokeStyle = v, A.lineWidth = l, A.stroke();
  }
  render() {
    const { style: e, className: n, canvasClass: s } = this.props;
    return /* @__PURE__ */ _("div", { class: "center burn-chart", className: n, style: e }, /* @__PURE__ */ _("canvas", { className: s, ref: Ot(this, Gn) }));
  }
};
Gn = /* @__PURE__ */ new WeakMap();
Qe = /* @__PURE__ */ new WeakMap();
Yn = /* @__PURE__ */ new WeakMap();
li = /* @__PURE__ */ new WeakMap();
ci = /* @__PURE__ */ new WeakMap();
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
class Dp extends ot {
  init() {
    const { echarts: e } = window;
    if (!e)
      return;
    const { responsive: n = !0, theme: s, ...i } = this.options, r = e.init(this.element, s);
    r.setOption(i), n && g(window).on(`resize.${this.gid}.ECharts.zt`, r.resize), this.myChart = r;
  }
  destroy() {
    var s;
    const { echarts: e } = window;
    if (!e) {
      super.destroy();
      return;
    }
    const { responsive: n = !0 } = this.options;
    n && g(window).off(`resize.${this.gid}.ECharts.zt`), (s = this.myChart) == null || s.dispose(), super.destroy();
  }
}
Dp.NAME = "ECharts";
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
  onApplyQuery: d,
  onDeleteQuery: u,
  groupName: f,
  handleSelect: p,
  toggleMore: m,
  toggleHistory: v,
  resetForm: w,
  submitForm: b,
  actionURL: k,
  module: S,
  groupItems: E
}) => {
  const M = [e, ...["search-form"]], R = [1, 2, 3], A = o ? o.groupAndOr : "", x = ($) => {
    const N = o ? o[`andOr${$}`] : "";
    return /* @__PURE__ */ _("div", { class: [1, 4].includes($) ? "search-group" : "search-group hidden", "data-id": $ }, /* @__PURE__ */ _("div", { class: "group-name" }, [1, 4].includes($) ? $ === 1 ? f[0] : f[1] : /* @__PURE__ */ _("select", { class: "form-control", id: `andOr${$}`, name: `andOr${$}` }, r.map((D) => /* @__PURE__ */ _("option", { value: D.value, selected: N === D.value, title: D.value }, D.title)))), /* @__PURE__ */ _("div", { class: "group-select" }, /* @__PURE__ */ _("select", { class: "form-control field-select", id: `field${$}`, name: `field${$}`, onChange: p.bind(void 0) }, " ", n == null ? void 0 : n.map((D) => /* @__PURE__ */ _("option", { value: D.name, selected: !1, title: D.name, control: D.control }, D.label)))), /* @__PURE__ */ _("div", { class: "group-select" }, /* @__PURE__ */ _("select", { class: "form-control search-method", id: `operator${$}`, name: `operator${$}` }, s.map((D) => /* @__PURE__ */ _("option", { key: D.value, value: D.value, title: D.value }, D.title)))), /* @__PURE__ */ _("div", { class: "group-value" }, /* @__PURE__ */ _("input", { type: "text", class: "form-control value-input", value: n[$ - 1].defaultValue, placeholder: n[$ - 1].placeholder }), /* @__PURE__ */ _("select", { class: "form-control value-select hidden" }), /* @__PURE__ */ _("input", { type: "datetime-local", class: "form-control value-date hidden" })));
  };
  return /* @__PURE__ */ _(
    "form",
    {
      id: "searchForm",
      className: L(M),
      ...t
    },
    /* @__PURE__ */ _("div", { class: "search-form-content" }, /* @__PURE__ */ _("div", { class: "search-form-items" }, /* @__PURE__ */ _("div", { class: "search-col" }, R.map(($) => x($))), /* @__PURE__ */ _("div", { class: "search-col" }, /* @__PURE__ */ _("select", { class: "form-control", id: "groupAndOr", name: "groupAndOr" }, r.map(($) => /* @__PURE__ */ _("option", { value: $.value, selected: A === $.value, title: $.value }, $.title)))), /* @__PURE__ */ _("div", { class: "search-col" }, R.map(($) => x($ + 3)))), /* @__PURE__ */ _("div", { class: "search-form-footer" }, /* @__PURE__ */ _("div", { class: "inline-block flex items-center justify-center" }, /* @__PURE__ */ _("button", { class: "btn primary btn-submit-form", type: "button", onClick: b }, a || "搜索"), /* @__PURE__ */ _("button", { class: "btn btn-reset-form", type: "button", onClick: w }, l || "重置")), /* @__PURE__ */ _("div", { class: "save-bar" }, (h == null ? void 0 : h.hasPriv) && /* @__PURE__ */ _("a", { class: "btn save-query", ...h.config }, /* @__PURE__ */ _("i", { class: "icon icon-save" }), h.text || "保存搜索条件"), /* @__PURE__ */ _("a", { class: "btn toggle-more", onClick: m }, /* @__PURE__ */ _("i", { class: "icon icon-chevron-double-down" }))))),
    /* @__PURE__ */ _("div", null, /* @__PURE__ */ _("button", { class: "btn search-toggle-btn", type: "button", onClick: v }, /* @__PURE__ */ _("i", { class: "icon icon-angle-left" }))),
    /* @__PURE__ */ _("div", { class: "history-record hidden" }, /* @__PURE__ */ _("p", null, c), /* @__PURE__ */ _("div", { class: "labels" }, (i == null ? void 0 : i.length) && i.map(($) => {
      if ($)
        return /* @__PURE__ */ _("div", { class: "label-btn", "data-id": $.id }, /* @__PURE__ */ _("span", { class: "label lighter-pale bd-lighter", onClick: (N) => d(N, Number($.id)) }, $.title, " ", $.hasPriv ? /* @__PURE__ */ _("i", { onClick: (N) => u(N, Number($.id)), class: "icon icon-close" }) : ""));
    }))),
    k ? /* @__PURE__ */ _("input", { type: "hidden", name: "actionURL", value: k }) : "",
    S ? /* @__PURE__ */ _("input", { type: "hidden", name: "module", value: S }) : "",
    E ? /* @__PURE__ */ _("input", { type: "hidden", name: "groupItems", value: E }) : ""
  );
}, hi = class extends U {
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
    const e = t.target, n = (s = e.closest(hi.FORM_ID)) == null ? void 0 : s.querySelector(".history-record");
    n && (this.toggleElementDisplay(e, n, "icon-angle-right", "icon-angle-left"), n.classList.toggle("hidden", !n.classList.contains("hidden")));
  }
  resetForm(t) {
    if (!(t != null && t.target))
      return;
    const n = t.target.closest(hi.FORM_ID);
    if (!n)
      return;
    n.querySelectorAll('.group-value [id^="value"]:not(.hidden), #searchForm .group-value [id*=" value"]:not(.hidden)').forEach((i) => {
      i.value = "";
    });
  }
  submitForm(t) {
    if (!(t != null && t.target))
      return;
    const n = t.target.closest(hi.FORM_ID);
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
let La = hi;
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
function td(t) {
  return t = Qh(t), t.includes("error") ? "error" : t.includes("warning") ? "warning" : "info";
}
function Ip({ errors: t, ...e }) {
  const n = t.reduce((s, i) => (s[td(i.level)]++, s), { error: 0, warning: 0, info: 0 });
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
        /* @__PURE__ */ _("div", { class: "px-0.5 state", "data-hint": "Loaded data size", onClick: pt.bind(null, "Trace", "Perf", t, t.id) }, /* @__PURE__ */ _("span", { class: "muted" }, /* @__PURE__ */ _("i", { class: "icon icon-cube muted" }), " ", qs(t.dataSize, 1)))
      ), r) {
        const h = a - r.timeUsed, c = 1e3 * t.dataSize / h;
        n.push(
          /* @__PURE__ */ _("div", { class: "muted" }, "/"),
          /* @__PURE__ */ _("div", { class: `px-0.5 state text-${c < 102400 ? "danger" : c < 1024e3 ? "warning" : "success"}`, "data-hint": "Download speed(B<100KB<=N<1MB<=G)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("i", { class: "icon icon-arrow-down" }), qs(c, 1), "/s")
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
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h, 1024e3, 102400)}`, "data-hint": "Server memory usage(G<10KB<=N<100KB<=B)", onClick: pt.bind(null, "Trace", "Request", r, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "M"), qs(h))
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
      if (l.forEach((d) => {
        d.Duration > 0.3 && h++, d.Duration > c.Duration && (c = d);
      }), n.push(
        /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(h, 3, 1)}`, "data-hint": "Server slow SQL queries count (G<3<=N<1<=B)", onClick: pt.bind(null, "SQL Query", `${l.length} SQL profiles`, l, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "LQ"), h)
      ), c.Duration) {
        const d = c.Duration * 1e3;
        n.push(
          /* @__PURE__ */ _("div", { class: `px-0.5 state text-${re(d, 600, 300)}`, "data-hint": "Server lowest SQL query duration (G<600<=N<300<=B)", onClick: pt.bind(null, "SQL Query", `Slowest SQL query: ${d}ms`, c, t.id) }, /* @__PURE__ */ _("span", { class: "scale-95 font-bold inline-block" }, "MLQ"), Pn(d))
        );
      }
    }
  } else
    n.push(/* @__PURE__ */ _("div", { class: "muted px-0.5" }, "loading..."));
  return /* @__PURE__ */ _("div", { class: "zin-perf-btn-list row items-center bg-black text-sm" }, /* @__PURE__ */ _("div", { class: "px-1 bg-canvas bg-opacity-20 self-stretch flex items-center", "data-hint": `REQUEST: ${t.id} URL: ${t.url}` }, /* @__PURE__ */ _("span", { class: "muted" }, e)), n, i ? /* @__PURE__ */ _("a", { class: "state text-secondary px-0.5", href: i, target: "_blank", "data-hint": "Visit xhprof page" }, "XHP") : null);
}
function Op(t) {
  pt("Trace", "Error", t, t.message), navigator.clipboard.writeText(`vim +${t.line} ${t.file}`);
}
function Hp({ errors: t = [], show: e, basePath: n }) {
  t.length || (e = !1);
  const s = t.map((i) => {
    const r = Qh(i.level), o = td(r), a = o === "error" ? "danger" : o === "info" ? "important" : "warning";
    return /* @__PURE__ */ _("div", { class: `zin-error-item state ${a}-pale text-fore px-2 py-1 ring ring-darker`, onClick: Op.bind(null, i) }, /* @__PURE__ */ _("div", { class: "zin-error-msg font-bold text-base" }, /* @__PURE__ */ _("strong", { class: `text-${a}`, style: "text-transform: uppercase;" }, r), " ", i.message), /* @__PURE__ */ _("div", { class: "zin-error-info text-sm opacity-60 break-all" }, /* @__PURE__ */ _("strong", null, "vim +", i.line), " ", /* @__PURE__ */ _("span", { className: "underline" }, n ? i.file.substring(n.length) : i.file)));
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
        class: L(
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
      e != null && e.length ? /* @__PURE__ */ _(Ip, { errors: e, onClick: this.togglePanel }) : null,
      n ? /* @__PURE__ */ _(Dl, { perf: n }) : null,
      s ? /* @__PURE__ */ _(Dl, { perf: s }) : null,
      /* @__PURE__ */ _(Hp, { show: this.state.showPanel, basePath: r, errors: e })
    );
  }
};
class ed extends J {
}
ed.NAME = "Zinbar";
ed.Component = Bp;
var ks, Ui, qi, Vi;
class zp extends U {
  constructor(n) {
    super(n);
    I(this, ks, $t());
    I(this, Ui, (n) => {
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
    I(this, qi, (n) => {
      var r, o, a;
      const { element: s } = this, i = s.getBoundingClientRect();
      if (n.clientY - i.top > 48) {
        n.preventDefault();
        return;
      }
      this.setState({ dragging: !0 }), (r = n.dataTransfer) == null || r.setData("application/id", this.props.block.id), (a = (o = this.props).onDragStart) == null || a.call(o, n);
    });
    I(this, Vi, (n) => {
      var s, i;
      this.setState({ dragging: !1 }), (i = (s = this.props).onDragEnd) == null || i.call(s, n);
    });
    this.state = { content: /* @__PURE__ */ y("div", { class: "dashboard-block-body", children: n.block.content }) };
  }
  get element() {
    return W(this, ks).current;
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
      fetch(Y(i, n), {
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
    const { left: n, top: s, width: i, height: r, style: o, block: a } = this.props, { title: l, menu: h, id: c } = a, { loading: d, content: u, dragging: f } = this.state;
    return /* @__PURE__ */ y("div", { class: "dashboard-block-cell", style: { left: n, top: s, width: i, height: r, ...o }, children: /* @__PURE__ */ y(
      "div",
      {
        class: `dashboard-block load-indicator${d ? " loading" : ""}${h ? " has-more-menu" : ""}${f ? " is-dragging" : ""}`,
        draggable: !0,
        onDragStart: W(this, qi),
        onDragEnd: W(this, Vi),
        "data-id": c,
        ref: W(this, ks),
        children: [
          /* @__PURE__ */ y("div", { class: "dashboard-block-header", children: [
            /* @__PURE__ */ y("div", { class: "dashboard-block-title", children: l }),
            h ? /* @__PURE__ */ y("div", { class: "dashboard-block-actions toolbar", children: /* @__PURE__ */ y("button", { class: "toolbar-item dashboard-block-action btn square ghost rounded size-sm", "data-type": "more", onClick: W(this, Ui), children: /* @__PURE__ */ y("div", { class: "more-vert" }) }) }) : null
          ] }),
          u
        ]
      }
    ) });
  }
}
ks = new WeakMap(), Ui = new WeakMap(), qi = new WeakMap(), Vi = new WeakMap();
var nd = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Kt = (t, e, n) => (nd(t, e, "read from private field"), n ? n.call(t) : e.get(t)), vt = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Ct = (t, e, n) => (nd(t, e, "access private method"), n), te, Da, sd, Pa, id, uo, rd, Wa, od, Ni, fo, ur, po, Ia, ad, go, mo, fr, Oa;
const Ha = class extends U {
  constructor() {
    super(...arguments), vt(this, Da), vt(this, Pa), vt(this, uo), vt(this, Wa), vt(this, Ni), vt(this, ur), vt(this, Ia), vt(this, te, /* @__PURE__ */ new Map()), this.state = {}, vt(this, go, (t) => {
      var n;
      const e = (n = t.dataTransfer) == null ? void 0 : n.getData("application/id");
      e !== void 0 && (this.setState({ dragging: e }), console.log("handleBlockDragStart", t));
    }), vt(this, mo, (t) => {
      this.setState({ dragging: void 0 }), console.log("handleBlockDragEnd", t);
    });
  }
  render() {
    const { blocks: t, height: e } = Ct(this, uo, rd).call(this), { cellHeight: n, grid: s } = this.props, i = Kt(this, te);
    return console.log("Dashboard.render", { blocks: t, map: i }, this), /* @__PURE__ */ y("div", { class: "dashboard", children: /* @__PURE__ */ y("div", { class: "dashboard-blocks", style: { height: e * n }, children: t.map((r, o) => {
      const { id: a } = r, [l, h, c, d] = i.get(a) || [0, 0, r.width, r.height];
      return /* @__PURE__ */ y(
        zp,
        {
          id: a,
          index: o,
          left: `${100 * l / s}%`,
          top: n * h,
          height: n * d,
          width: `${100 * c / s}%`,
          block: r,
          moreMenu: !0,
          onDragStart: Kt(this, go),
          onDragEnd: Kt(this, mo)
        },
        r.id
      );
    }) }) });
  }
};
let Ba = Ha;
te = /* @__PURE__ */ new WeakMap();
Da = /* @__PURE__ */ new WeakSet();
sd = function(t) {
  const { blockDefaultSize: e, blockSizeMap: n } = this.props;
  return t = t ?? e, typeof t == "string" && (t = n[t]), t = t || e, Array.isArray(t) || (t = [t.width, t.height]), t;
};
Pa = /* @__PURE__ */ new WeakSet();
id = function() {
  const { blocks: t, blockFetch: e, blockMenu: n } = this.props;
  return t.map((i) => {
    const {
      id: r,
      size: o,
      left: a = -1,
      top: l = -1,
      fetch: h = e,
      menu: c = n,
      ...d
    } = i, [u, f] = Ct(this, Da, sd).call(this, o);
    return {
      id: `${r}`,
      width: u,
      height: f,
      left: a,
      top: l,
      fetch: h,
      menu: c,
      ...d
    };
  });
};
uo = /* @__PURE__ */ new WeakSet();
rd = function() {
  Kt(this, te).clear();
  let t = 0;
  const e = Ct(this, Pa, id).call(this);
  return e.forEach((n) => {
    Ct(this, Wa, od).call(this, n);
    const [, s, , i] = Kt(this, te).get(n.id);
    t = Math.max(t, s + i);
  }), { blocks: e, height: t };
};
Wa = /* @__PURE__ */ new WeakSet();
od = function(t) {
  const e = Kt(this, te), { id: n, left: s, top: i, width: r, height: o } = t;
  if (s < 0 || i < 0) {
    const [a, l] = Ct(this, Ia, ad).call(this, r, o, s, i);
    e.set(n, [a, l, r, o]);
  } else
    Ct(this, ur, po).call(this, n, [s, i, r, o]);
};
Ni = /* @__PURE__ */ new WeakSet();
fo = function(t) {
  var e;
  const { dragging: n } = this.state;
  for (const [s, i] of Kt(this, te).entries())
    if (s !== n && Ct(e = Ha, fr, Oa).call(e, i, t))
      return !1;
  return !0;
};
ur = /* @__PURE__ */ new WeakSet();
po = function(t, e) {
  var n;
  Kt(this, te).set(t, e);
  for (const [s, i] of Kt(this, te).entries())
    s !== t && Ct(n = Ha, fr, Oa).call(n, i, e) && (i[1] = e[1] + e[3], Ct(this, ur, po).call(this, s, i));
};
Ia = /* @__PURE__ */ new WeakSet();
ad = function(t, e, n, s) {
  if (n >= 0 && s >= 0) {
    if (Ct(this, Ni, fo).call(this, [n, s, t, e]))
      return [n, s];
    s = -1;
  }
  let i = n < 0 ? 0 : n, r = s < 0 ? 0 : s, o = !1;
  const a = this.props.grid;
  for (; !o; ) {
    if (Ct(this, Ni, fo).call(this, [i, r, t, e])) {
      o = !0;
      break;
    }
    n < 0 ? (i += 1, i + t > a && (i = 0, r += 1)) : r += 1;
  }
  return [i, r];
};
go = /* @__PURE__ */ new WeakMap();
mo = /* @__PURE__ */ new WeakMap();
fr = /* @__PURE__ */ new WeakSet();
Oa = function([t, e, n, s], [i, r, o, a]) {
  return !(t + n <= i || i + o <= t || e + s <= r || r + a <= e);
};
vt(Ba, fr);
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
class ld extends J {
}
ld.NAME = "Dashboard";
ld.Component = Ba;
var fe, pe;
class Pl extends U {
  constructor(n) {
    super(n);
    I(this, fe, void 0);
    I(this, pe, void 0);
    F(this, fe, 0), F(this, pe, null), this._handleWheel = (s) => {
      const { wheelContainer: i } = this.props, r = s.target;
      if (!(!r || !i) && (typeof i == "string" && r.closest(i) || typeof i == "object")) {
        const o = (this.props.type === "horz" ? s.deltaX : s.deltaY) * (this.props.wheelSpeed ?? 1);
        this.scrollOffset(o) && s.preventDefault();
      }
    }, this._handleMouseMove = (s) => {
      const { dragStart: i } = this.state;
      i && (W(this, fe) && cancelAnimationFrame(W(this, fe)), F(this, fe, requestAnimationFrame(() => {
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
    n && (F(this, pe, typeof n == "string" ? document : n.current), W(this, pe).addEventListener("wheel", this._handleWheel, { passive: !1 }));
  }
  componentWillUnmount() {
    document.removeEventListener("mousemove", this._handleMouseMove), document.removeEventListener("mouseup", this._handleMouseUp), W(this, pe) && W(this, pe).removeEventListener("wheel", this._handleWheel);
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
    } = this.props, { maxScrollPos: d, scrollPos: u } = this, { dragStart: f } = this.state, p = {
      left: a,
      top: l,
      bottom: h,
      right: c,
      ...o
    }, m = {};
    return s === "horz" ? (p.height = i, p.width = n, m.width = this.barSize, m.left = Math.round(Math.min(d, u) * (n - m.width) / d)) : (p.width = i, p.height = n, m.height = this.barSize, m.top = Math.round(Math.min(d, u) * (n - m.height) / d)), /* @__PURE__ */ y(
      "div",
      {
        className: L("scrollbar", r, {
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
function cd({ col: t, className: e, height: n, row: s, onRenderCell: i, style: r, outerStyle: o, children: a, outerClass: l, ...h }) {
  var A;
  const c = {
    left: t.left,
    width: t.realWidth,
    height: n,
    ...o
  }, { align: d, border: u } = t.setting, f = {
    justifyContent: d ? d === "left" ? "start" : d === "right" ? "end" : d : void 0,
    ...t.setting.cellStyle,
    ...r
  }, p = ["dtable-cell", l, e, t.setting.className, {
    "has-border-left": u === !0 || u === "left",
    "has-border-right": u === !0 || u === "right"
  }], m = ["dtable-cell-content", t.setting.cellClass], v = (A = s.data) == null ? void 0 : A[t.name], w = [a ?? v ?? ""], b = i ? i(w, { row: s, col: t, value: v }, _) : w, k = [], S = [], E = {}, P = {};
  let M = "div";
  b == null || b.forEach((x) => {
    if (typeof x == "object" && x && !it(x) && ("html" in x || "className" in x || "style" in x || "attrs" in x || "children" in x || "tagName" in x)) {
      const $ = x.outer ? k : S;
      x.html ? $.push(/* @__PURE__ */ y("div", { className: L("dtable-cell-html", x.className), style: x.style, dangerouslySetInnerHTML: { __html: x.html }, ...x.attrs ?? {} })) : (x.style && Object.assign(x.outer ? c : f, x.style), x.className && (x.outer ? p : m).push(x.className), x.children && $.push(x.children), x.attrs && Object.assign(x.outer ? E : P, x.attrs)), x.tagName && !x.outer && (M = x.tagName);
    } else
      S.push(x);
  });
  const R = M;
  return /* @__PURE__ */ y(
    "div",
    {
      className: L(p),
      style: c,
      "data-col": t.name,
      "data-type": t.type,
      ...h,
      ...E,
      children: [
        S.length > 0 && /* @__PURE__ */ y(R, { className: L(m), style: f, ...P, children: S }),
        k
      ]
    }
  );
}
function Er({ row: t, className: e, top: n = 0, left: s = 0, width: i, height: r, cols: o, CellComponent: a = cd, onRenderCell: l }) {
  return /* @__PURE__ */ y("div", { className: L("dtable-cells", e), style: { top: n, left: s, width: i, height: r }, children: o.map((h) => h.visible ? /* @__PURE__ */ y(
    a,
    {
      col: h,
      row: t,
      onRenderCell: l
    },
    h.name
  ) : null) });
}
function hd({
  row: t,
  className: e,
  top: n,
  height: s,
  cols: { left: i, center: r, right: o },
  scrollLeft: a,
  CellComponent: l = cd,
  onRenderCell: h,
  style: c,
  ...d
}) {
  let u = null;
  i.list.length && (u = /* @__PURE__ */ y(
    Er,
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
    Er,
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
    Er,
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
      className: L("dtable-row", e),
      style: m,
      "data-id": t.id,
      ...d,
      children: [
        u,
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
  return /* @__PURE__ */ y("div", { className: "dtable-header", style: { height: t }, children: /* @__PURE__ */ y(hd, { ...s }) });
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
  return e = { ...e, top: n, height: i }, /* @__PURE__ */ y("div", { className: L("dtable-rows", t), style: e, children: s.map((h) => {
    const c = {
      className: `dtable-row-${h.index % 2 ? "odd" : "even"}`,
      row: h,
      top: h.top - o,
      height: r,
      ...l
    }, d = a == null ? void 0 : a({ props: c, row: h }, _);
    return d && Object.assign(c, d), /* @__PURE__ */ y(hd, { ...c }, h.id);
  }) });
}
const Li = /* @__PURE__ */ new Map(), Di = [];
function dd(t, e) {
  const { name: n } = t;
  if (!(e != null && e.override) && Li.has(n))
    throw new Error(`DTable: Plugin with name ${n} already exists`);
  Li.set(n, t), e != null && e.buildIn && !Di.includes(n) && Di.push(n);
}
function yt(t, e) {
  dd(t, e);
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
function ud(t) {
  return Li.delete(t);
}
function Up(t) {
  if (typeof t == "string") {
    const e = Li.get(t);
    return e || console.warn(`DTable: Cannot found plugin "${t}"`), e;
  }
  if (typeof t == "function" && "plugin" in t)
    return t.plugin;
  if (typeof t == "object")
    return t;
  console.warn("DTable: Invalid plugin", t);
}
function fd(t, e, n) {
  return e.forEach((s) => {
    var r;
    if (!s)
      return;
    const i = Up(s);
    i && (n.has(i.name) || ((r = i.plugins) != null && r.length && fd(t, i.plugins, n), t.push(i), n.add(i.name)));
  }), t;
}
function qp(t = [], e = !0) {
  return e && Di.length && t.unshift(...Di), t != null && t.length ? fd([], t, /* @__PURE__ */ new Set()) : [];
}
function pd() {
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
function Mr(t, e = !1) {
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
  }, d = {
    ...c,
    list: [],
    flexList: [],
    widthSetting: h(a)
  }, u = {
    ...c,
    list: [],
    flexList: [],
    widthSetting: h(l)
  }, f = [], p = {};
  let m = !1;
  const v = [], w = {};
  if (n.forEach((b) => {
    const { colTypes: k, onAddCol: S } = b;
    k && Object.entries(k).forEach(([E, P]) => {
      w[E] || (w[E] = []), w[E].push(P);
    }), S && v.push(S);
  }), e.cols.forEach((b) => {
    if (b.hidden)
      return;
    const { type: k = "", name: S } = b, E = {
      fixed: !1,
      flex: !1,
      width: i,
      minWidth: r,
      maxWidth: o,
      ...b,
      type: k
    }, P = {
      name: S,
      type: k,
      setting: E,
      flex: 0,
      left: 0,
      width: 0,
      realWidth: 0,
      visible: !0,
      index: f.length
    }, M = w[k];
    M && M.forEach((O) => {
      const B = typeof O == "function" ? O.call(t, E) : O;
      B && Object.assign(E, B, b);
    });
    const { fixed: R, flex: A, minWidth: x = r, maxWidth: $ = o } = E, N = Wl(E.width || i, i);
    P.flex = A === !0 ? 1 : typeof A == "number" ? A : 0, P.width = Vp(N < 1 ? Math.round(N * s) : N, x, $), v.forEach((O) => O.call(t, P)), f.push(P), p[P.name] = P;
    const D = R ? R === "left" ? d : u : c;
    D.list.push(P), D.totalWidth += P.width, D.width = D.totalWidth, P.flex && D.flexList.push(P), typeof E.order == "number" && (m = !0);
  }), m) {
    const b = (k, S) => (k.setting.order ?? 0) - (S.setting.order ?? 0);
    f.sort(b), d.list.sort(b), c.list.sort(b), u.list.sort(b);
  }
  return Mr(d, !0), Mr(u, !0), c.widthSetting = s - d.width - u.width, Mr(c), {
    list: f,
    map: p,
    left: d,
    center: c,
    right: u
  };
}
var za = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, T = (t, e, n) => (za(t, e, "read from private field"), n ? n.call(t) : e.get(t)), z = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Q = (t, e, n, s) => (za(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), Ft = (t, e, n) => (za(t, e, "access private method"), n), tn, Kn, Ie, Yt, Ne, nt, Ut, zt, Xn, di, Pi, le, Jn, Zn, yo, gd, wo, md, vo, yd, bo, wd, ui, _o, Fa, ja, pr, Wi, xo, $o, Ua, vd, qa, bd, ko, _d;
let Va = class extends U {
  constructor(e) {
    super(e), z(this, yo), z(this, wo), z(this, vo), z(this, bo), z(this, ui), z(this, Ua), z(this, qa), z(this, ko), this.ref = $t(), z(this, tn, 0), z(this, Kn, void 0), z(this, Ie, !1), z(this, Yt, void 0), z(this, Ne, void 0), z(this, nt, []), z(this, Ut, void 0), z(this, zt, /* @__PURE__ */ new Map()), z(this, Xn, {}), z(this, di, void 0), z(this, Pi, []), this.updateLayout = () => {
      T(this, tn) && cancelAnimationFrame(T(this, tn)), Q(this, tn, requestAnimationFrame(() => {
        this.update({ dirtyType: "layout" }), Q(this, tn, 0);
      }));
    }, z(this, le, (n, s) => {
      s = s || n.type;
      const i = T(this, zt).get(s);
      if (i != null && i.length) {
        for (const r of i)
          if (r.call(this, n) === !1) {
            n.stopPropagation(), n.preventDefault();
            break;
          }
      }
    }), z(this, Jn, (n) => {
      T(this, le).call(this, n, `window_${n.type}`);
    }), z(this, Zn, (n) => {
      T(this, le).call(this, n, `document_${n.type}`);
    }), z(this, Fa, (n, s) => {
      if (this.options.onRenderRow) {
        const i = this.options.onRenderRow.call(this, n, s);
        i && Object.assign(n.props, i);
      }
      return T(this, nt).forEach((i) => {
        if (i.onRenderRow) {
          const r = i.onRenderRow.call(this, n, s);
          r && Object.assign(n.props, r);
        }
      }), n.props;
    }), z(this, ja, (n, s) => (this.options.onRenderHeaderRow && (n.props = this.options.onRenderHeaderRow.call(this, n, s)), T(this, nt).forEach((i) => {
      i.onRenderHeaderRow && (n.props = i.onRenderHeaderRow.call(this, n, s));
    }), n.props)), z(this, pr, (n, s, i) => {
      const { row: r, col: o } = s;
      s.value = this.getCellValue(r, o), n[0] = s.value;
      const a = r.id === "HEADER" ? "onRenderHeaderCell" : "onRenderCell";
      return T(this, nt).forEach((l) => {
        l[a] && (n = l[a].call(this, n, s, i));
      }), this.options[a] && (n = this.options[a].call(this, n, s, i)), o.setting[a] && (n = o.setting[a].call(this, n, s, i)), n;
    }), z(this, Wi, (n, s) => {
      s === "horz" ? this.scroll({ scrollLeft: n }) : this.scroll({ scrollTop: n });
    }), z(this, xo, (n) => {
      var a, l, h, c, d;
      const s = this.getPointerInfo(n);
      if (!s)
        return;
      const { rowID: i, colName: r, cellElement: o } = s;
      if (i === "HEADER")
        o && ((a = this.options.onHeaderCellClick) == null || a.call(this, n, { colName: r, element: o }), T(this, nt).forEach((u) => {
          var f;
          (f = u.onHeaderCellClick) == null || f.call(this, n, { colName: r, element: o });
        }));
      else {
        const { rowElement: u } = s, f = this.layout.visibleRows.find((p) => p.id === i);
        if (o) {
          if (((l = this.options.onCellClick) == null ? void 0 : l.call(this, n, { colName: r, rowID: i, rowInfo: f, element: o, rowElement: u })) === !0)
            return;
          for (const p of T(this, nt))
            if (((h = p.onCellClick) == null ? void 0 : h.call(this, n, { colName: r, rowID: i, rowInfo: f, element: o, rowElement: u })) === !0)
              return;
        }
        if (((c = this.options.onRowClick) == null ? void 0 : c.call(this, n, { rowID: i, rowInfo: f, element: u })) === !0)
          return;
        for (const p of T(this, nt))
          if (((d = p.onRowClick) == null ? void 0 : d.call(this, n, { rowID: i, rowInfo: f, element: u })) === !0)
            return;
      }
    }), z(this, $o, (n) => {
      const s = n.key.toLowerCase();
      if (["pageup", "pagedown", "home", "end"].includes(s))
        return !this.scroll({ to: s.replace("page", "") });
    }), Q(this, Kn, e.id ?? `dtable-${ia(10)}`), this.state = { scrollTop: 0, scrollLeft: 0, renderCount: 0 }, Q(this, Ne, Object.freeze(qp(e.plugins))), T(this, Ne).forEach((n) => {
      var o;
      const { methods: s, data: i, state: r } = n;
      s && Object.entries(s).forEach(([a, l]) => {
        typeof l == "function" && Object.assign(this, { [a]: l.bind(this) });
      }), i && Object.assign(T(this, Xn), i.call(this)), r && Object.assign(this.state, r.call(this)), (o = n.onCreate) == null || o.call(this, n);
    });
  }
  get options() {
    var e;
    return ((e = T(this, Ut)) == null ? void 0 : e.options) || T(this, Yt) || pd();
  }
  get plugins() {
    return T(this, nt);
  }
  get layout() {
    return T(this, Ut);
  }
  get id() {
    return T(this, Kn);
  }
  get data() {
    return T(this, Xn);
  }
  get parent() {
    var e;
    return this.props.parent ?? ((e = this.ref.current) == null ? void 0 : e.parentElement);
  }
  componentWillReceiveProps() {
    Q(this, Yt, void 0);
  }
  componentDidMount() {
    if (T(this, Ie) ? this.forceUpdate() : Ft(this, ui, _o).call(this), T(this, nt).forEach((e) => {
      let { events: n } = e;
      n && (typeof n == "function" && (n = n.call(this)), Object.entries(n).forEach(([s, i]) => {
        i && this.on(s, i);
      }));
    }), this.on("click", T(this, xo)), this.on("keydown", T(this, $o)), this.options.responsive)
      if (typeof ResizeObserver < "u") {
        const { parent: e } = this;
        if (e) {
          const n = new ResizeObserver(this.updateLayout);
          n.observe(e), Q(this, di, n);
        }
      } else
        this.on("window_resize", this.updateLayout);
    T(this, nt).forEach((e) => {
      var n;
      (n = e.onMounted) == null || n.call(this);
    });
  }
  componentDidUpdate() {
    T(this, Ie) ? Ft(this, ui, _o).call(this) : T(this, nt).forEach((e) => {
      var n;
      (n = e.onUpdated) == null || n.call(this);
    });
  }
  componentWillUnmount() {
    var n;
    (n = T(this, di)) == null || n.disconnect();
    const { current: e } = this.ref;
    if (e)
      for (const s of T(this, zt).keys())
        s.startsWith("window_") ? window.removeEventListener(s.replace("window_", ""), T(this, Jn)) : s.startsWith("document_") ? document.removeEventListener(s.replace("document_", ""), T(this, Zn)) : e.removeEventListener(s, T(this, le));
    T(this, nt).forEach((s) => {
      var i;
      (i = s.onUnmounted) == null || i.call(this);
    }), T(this, Ne).forEach((s) => {
      var i;
      (i = s.onDestory) == null || i.call(this);
    }), Q(this, Xn, {}), T(this, zt).clear();
  }
  on(e, n, s) {
    var r;
    s && (e = `${s}_${e}`);
    const i = T(this, zt).get(e);
    i ? i.push(n) : (T(this, zt).set(e, [n]), e.startsWith("window_") ? window.addEventListener(e.replace("window_", ""), T(this, Jn)) : e.startsWith("document_") ? document.addEventListener(e.replace("document_", ""), T(this, Zn)) : (r = this.ref.current) == null || r.addEventListener(e, T(this, le)));
  }
  off(e, n, s) {
    var o;
    s && (e = `${s}_${e}`);
    const i = T(this, zt).get(e);
    if (!i)
      return;
    const r = i.indexOf(n);
    r >= 0 && i.splice(r, 1), i.length || (T(this, zt).delete(e), e.startsWith("window_") ? window.removeEventListener(e.replace("window_", ""), T(this, Jn)) : e.startsWith("document_") ? document.removeEventListener(e.replace("document_", ""), T(this, Zn)) : (o = this.ref.current) == null || o.removeEventListener(e, T(this, le)));
  }
  emitCustomEvent(e, n) {
    T(this, le).call(this, n instanceof Event ? n : new CustomEvent(e, { detail: n }), e);
  }
  scroll(e, n) {
    const { scrollLeft: s, scrollTop: i, rowsHeightTotal: r, rowsHeight: o, rowHeight: a, cols: { center: { totalWidth: l, width: h } } } = this.layout, { to: c } = e;
    let { scrollLeft: d, scrollTop: u } = e;
    if (c === "up" || c === "down")
      u = i + (c === "down" ? 1 : -1) * Math.floor(o / a) * a;
    else if (c === "left" || c === "right")
      d = s + (c === "right" ? 1 : -1) * h;
    else if (c === "home")
      u = 0;
    else if (c === "end")
      u = r - o;
    else if (c === "left-begin")
      d = 0;
    else if (c === "right-end")
      d = l - h;
    else {
      const { offsetLeft: p, offsetTop: m } = e;
      typeof p == "number" && (d = s + p), typeof m == "number" && (d = i + m);
    }
    const f = {};
    return typeof d == "number" && (d = Math.max(0, Math.min(d, l - h)), d !== s && (f.scrollLeft = d)), typeof u == "number" && (u = Math.max(0, Math.min(u, r - o)), u !== i && (f.scrollTop = u)), Object.keys(f).length ? (this.setState(f, () => {
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
    if (!T(this, Yt))
      return;
    typeof e == "function" && (n = e, e = {});
    const { dirtyType: s, state: i } = e;
    if (s === "layout")
      Q(this, Ut, void 0);
    else if (s === "options") {
      if (Q(this, Yt, void 0), !T(this, Ut))
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
    return Zt(T(this, Pi), e, n, s, this.options.lang) ?? `{i18n:${e}}`;
  }
  getPlugin(e) {
    return this.plugins.find((n) => n.name === e);
  }
  render() {
    const e = Ft(this, ko, _d).call(this), { className: n, rowHover: s, colHover: i, cellHover: r, bordered: o, striped: a, scrollbarHover: l } = this.options, h = { width: e == null ? void 0 : e.width, height: e == null ? void 0 : e.height }, c = ["dtable", n, {
      "dtable-hover-row": s,
      "dtable-hover-col": i,
      "dtable-hover-cell": r,
      "dtable-bordered": o,
      "dtable-striped": a,
      "dtable-scrolled-down": ((e == null ? void 0 : e.scrollTop) ?? 0) > 0,
      "scrollbar-hover": l
    }], d = [];
    return e && (d.push(
      Ft(this, yo, gd).call(this, e),
      Ft(this, wo, md).call(this, e),
      Ft(this, vo, yd).call(this, e),
      Ft(this, bo, wd).call(this, e)
    ), T(this, nt).forEach((u) => {
      var p;
      const f = (p = u.onRender) == null ? void 0 : p.call(this, e);
      f && (f.style && Object.assign(h, f.style), f.className && c.push(f.className), f.children && d.push(f.children));
    })), /* @__PURE__ */ y(
      "div",
      {
        id: T(this, Kn),
        className: L(c),
        style: h,
        ref: this.ref,
        tabIndex: -1,
        children: d
      }
    );
  }
};
tn = /* @__PURE__ */ new WeakMap();
Kn = /* @__PURE__ */ new WeakMap();
Ie = /* @__PURE__ */ new WeakMap();
Yt = /* @__PURE__ */ new WeakMap();
Ne = /* @__PURE__ */ new WeakMap();
nt = /* @__PURE__ */ new WeakMap();
Ut = /* @__PURE__ */ new WeakMap();
zt = /* @__PURE__ */ new WeakMap();
Xn = /* @__PURE__ */ new WeakMap();
di = /* @__PURE__ */ new WeakMap();
Pi = /* @__PURE__ */ new WeakMap();
le = /* @__PURE__ */ new WeakMap();
Jn = /* @__PURE__ */ new WeakMap();
Zn = /* @__PURE__ */ new WeakMap();
yo = /* @__PURE__ */ new WeakSet();
gd = function(t) {
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
        onRenderCell: T(this, pr),
        onRenderRow: T(this, ja)
      },
      "header"
    );
  const r = Array.isArray(e) ? e : [e];
  return /* @__PURE__ */ y(
    Uo,
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
wo = /* @__PURE__ */ new WeakSet();
md = function(t) {
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
      onRenderCell: T(this, pr),
      onRenderRow: T(this, Fa)
    },
    "rows"
  );
};
vo = /* @__PURE__ */ new WeakSet();
yd = function(t) {
  let { footer: e } = t;
  if (typeof e == "function" && (e = e.call(this, t)), !e)
    return null;
  const n = Array.isArray(e) ? e : [e];
  return /* @__PURE__ */ y(
    Uo,
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
bo = /* @__PURE__ */ new WeakSet();
wd = function(t) {
  const e = [], { scrollLeft: n, cols: { left: { width: s }, center: { width: i, totalWidth: r } }, scrollTop: o, rowsHeight: a, rowsHeightTotal: l, footerHeight: h } = t, { scrollbarSize: c = 12, horzScrollbarPos: d } = this.options;
  return r > i && e.push(
    /* @__PURE__ */ y(
      Pl,
      {
        type: "horz",
        scrollPos: n,
        scrollSize: r,
        clientSize: i,
        onScroll: T(this, Wi),
        left: s,
        bottom: (d === "inside" ? 0 : -c) + h,
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
        onScroll: T(this, Wi),
        right: 0,
        size: c,
        top: t.headerHeight,
        wheelContainer: this.ref
      },
      "vert"
    )
  ), e.length ? e : null;
};
ui = /* @__PURE__ */ new WeakSet();
_o = function() {
  var t;
  Q(this, Ie, !1), (t = this.options.afterRender) == null || t.call(this), T(this, nt).forEach((e) => {
    var n;
    return (n = e.afterRender) == null ? void 0 : n.call(this);
  });
};
Fa = /* @__PURE__ */ new WeakMap();
ja = /* @__PURE__ */ new WeakMap();
pr = /* @__PURE__ */ new WeakMap();
Wi = /* @__PURE__ */ new WeakMap();
xo = /* @__PURE__ */ new WeakMap();
$o = /* @__PURE__ */ new WeakMap();
Ua = /* @__PURE__ */ new WeakSet();
vd = function() {
  if (T(this, Yt))
    return !1;
  const e = { ...pd(), ...T(this, Ne).reduce((n, s) => {
    const { defaultOptions: i } = s;
    return i && Object.assign(n, i), n;
  }, {}), ...this.props };
  return Q(this, nt, T(this, Ne).reduce((n, s) => {
    const { when: i, options: r } = s;
    let o = e;
    return r && (o = Object.assign({ ...o }, typeof r == "function" ? r.call(this, e) : r)), (!i || i(o)) && (o !== e && Object.assign(e, o), n.push(s)), n;
  }, [])), Q(this, Yt, e), Q(this, Pi, [this.options.i18n, ...this.plugins.map((n) => n.i18n)].filter(Boolean)), !0;
};
qa = /* @__PURE__ */ new WeakSet();
bd = function() {
  var R, A;
  const { plugins: t } = this;
  let e = T(this, Yt);
  const n = {
    flex: /* @__PURE__ */ y("div", { style: "flex:auto" }),
    divider: /* @__PURE__ */ y("div", { style: "width:1px;margin:var(--space);background:var(--color-border);height:50%" })
  };
  t.forEach((x) => {
    var N;
    const $ = (N = x.beforeLayout) == null ? void 0 : N.call(this, e);
    $ && (e = { ...e, ...$ }), Object.assign(n, x.footer);
  });
  let s = e.width, i = 0;
  if (typeof s == "function" && (s = s.call(this)), s === "100%") {
    const { parent: x } = this;
    if (x)
      i = x.clientWidth;
    else {
      Q(this, Ie, !0);
      return;
    }
  }
  const r = Gp(this, e, t, i), { data: o, rowKey: a = "id", rowHeight: l } = e, h = [], c = (x, $, N) => {
    var O, B;
    const D = { data: N ?? { [a]: x }, id: x, index: h.length, top: 0 };
    if (N || (D.lazy = !0), h.push(D), ((O = e.onAddRow) == null ? void 0 : O.call(this, D, $)) !== !1) {
      for (const G of t)
        if (((B = G.onAddRow) == null ? void 0 : B.call(this, D, $)) === !1)
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
  let d = h;
  const u = {};
  if (e.onAddRows) {
    const x = e.onAddRows.call(this, d);
    x && (d = x);
  }
  for (const x of t) {
    const $ = (R = x.onAddRows) == null ? void 0 : R.call(this, d);
    $ && (d = $);
  }
  d.forEach((x, $) => {
    u[x.id] = x, x.index = $, x.top = x.index * l;
  });
  const { header: f, footer: p } = e, m = f ? e.headerHeight || l : 0, v = p ? e.footerHeight || l : 0;
  let w = e.height, b = 0;
  const k = d.length * l, S = m + v + k;
  if (typeof w == "function" && (w = w.call(this, S)), w === "auto")
    b = S;
  else if (typeof w == "object")
    b = Math.min(w.max, Math.max(w.min, S));
  else if (w === "100%") {
    const { parent: x } = this;
    if (x)
      b = x.clientHeight;
    else {
      b = 0, Q(this, Ie, !0);
      return;
    }
  } else
    b = w;
  const E = b - m - v, P = {
    options: e,
    allRows: h,
    width: i,
    height: b,
    rows: d,
    rowsMap: u,
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
ko = /* @__PURE__ */ new WeakSet();
_d = function() {
  (Ft(this, Ua, vd).call(this) || !T(this, Ut)) && Ft(this, qa, bd).call(this);
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
  const { rowsHeightTotal: i, rowsHeight: r, rows: o, rowHeight: a } = t, l = Math.min(Math.max(0, i - r), this.state.scrollTop), h = Math.floor(l / a), c = l + r, d = Math.min(o.length, Math.ceil(c / a)), u = [], { rowDataGetter: f } = this.options;
  for (let p = h; p < d; p++) {
    const m = o[p];
    m.lazy && f && (m.data = f([m.id])[0], m.lazy = !1), u.push(m);
  }
  return t.visibleRows = u, t.scrollTop = l, t.scrollLeft = n, t;
};
Va.addPlugin = dd;
Va.removePlugin = ud;
function Il(t, e) {
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
      Il(this, s);
    },
    mouseleave() {
      Il(this, !1);
    }
  }
}, Kp = yt(Yp, { buildIn: !0 });
function Xp(t, e) {
  var o, a;
  typeof t == "boolean" && (e = t, t = void 0);
  const n = this.state.checkedRows, s = {}, { canRowCheckable: i } = this.options, r = (l, h) => {
    i && !i.call(this, l) || !!n[l] === h || (h ? n[l] = !0 : delete n[l], s[l] = h);
  };
  if (t === void 0 ? (e === void 0 && (e = !xd.call(this)), (o = this.layout) == null || o.allRows.forEach(({ id: l }) => {
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
function xd() {
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
function Ol(t) {
  return /* @__PURE__ */ y("div", { class: `checkbox-primary dtable-checkbox${t ? " checked" : ""}`, children: /* @__PURE__ */ y("label", {}) });
}
const tg = {
  name: "checkable",
  defaultOptions: {
    checkable: "auto",
    checkboxRender: Ol
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
    isAllRowChecked: xd,
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
        /* @__PURE__ */ y("div", { style: { paddingRight: "calc(3*var(--space))", display: "flex", alignItems: "center" }, onClick: () => this.toggleCheckRows(), children: Ol(t) })
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
      return { className: L(t.className, "is-checked") };
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
var $d = /* @__PURE__ */ ((t) => (t.unknown = "", t.collapsed = "collapsed", t.expanded = "expanded", t.hidden = "hidden", t.normal = "normal", t))($d || {});
function Ii(t) {
  const e = this.data.nestedMap.get(t);
  if (!e || e.state !== "")
    return e ?? { state: "normal", level: -1 };
  if (!e.parent && !e.children)
    return e.state = "normal", e;
  const n = this.state.collapsedRows, s = e.children && n && n[t];
  let i = !1, { parent: r } = e;
  for (; r; ) {
    const o = Ii.call(this, r);
    if (o.state !== "expanded") {
      i = !0;
      break;
    }
    r = o.parent;
  }
  return e.state = i ? "hidden" : s ? "collapsed" : e.children ? "expanded" : "normal", e.level = e.parent ? Ii.call(this, e.parent).level + 1 : 0, e;
}
function ng(t) {
  return t !== void 0 ? Ii.call(this, t) : this.data.nestedMap;
}
function sg(t, e) {
  let n = this.state.collapsedRows ?? {};
  const { nestedMap: s } = this.data;
  if (t === "HEADER")
    if (e === void 0 && (e = !kd.call(this)), e) {
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
function kd() {
  const t = this.data.nestedMap.values();
  for (const e of t)
    if (e.state === "expanded")
      return !1;
  return !0;
}
function Cd(t, e = 0, n, s = 0) {
  var i;
  n || (n = [...t.keys()]);
  for (const r of n) {
    const o = t.get(r);
    o && (o.level === s && (o.order = e++), (i = o.children) != null && i.length && (e = Cd(t, e, o.children, s + 1)));
  }
  return e;
}
function Sd(t, e, n, s) {
  const i = t.getNestedRowInfo(e);
  return !i || i.state === "" || !i.children || i.children.forEach((r) => {
    s[r] = n, Sd(t, r, n, s);
  }), i;
}
function Ed(t, e, n, s, i) {
  var a;
  const r = t.getNestedRowInfo(e);
  if (!r || r.state === "")
    return;
  ((a = r.children) == null ? void 0 : a.every((l) => {
    const h = !!(s[l] !== void 0 ? s[l] : i[l]);
    return n === h;
  })) && (s[e] = n), r.parent && Ed(t, r.parent, n, s, i);
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
        const o = Sd(this, i, r, s);
        o != null && o.parent && Ed(this, o.parent, r, s, n);
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
    isAllCollapsed: kd,
    getNestedRowInfo: Ii
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
    ), Cd(this.data.nestedMap), t.sort((e, n) => {
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
      className: L(t.className, `is-${n.state}`),
      "data-parent": n.parent
    };
  },
  onRenderHeaderRow({ props: t }) {
    return t.className = L(t.className, `is-${this.isAllCollapsed() ? "collapsed" : "expanded"}`), t;
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
  }), /* @__PURE__ */ y("a", { href: Y(i, e.row.data), ...s, ...r, ...a, children: n });
}
function Ya(t, e, n) {
  var s;
  if (t != null)
    return n = n ?? ((s = e.row.data) == null ? void 0 : s[e.col.name]), typeof t == "function" ? t(n, e) : Y(t, n);
}
function Md(t, e, n, s) {
  var i;
  return n = n ?? ((i = e.row.data) == null ? void 0 : i[e.col.name]), t === !1 ? n : (t === !0 && (t = "[yyyy-]MM-dd hh:mm"), typeof t == "function" && (t = t(n, e)), to(n, t, s ?? n));
}
function Td(t, e) {
  const { link: n } = e.col.setting, s = Ga(n, e, t[0]);
  return s && (t[0] = s), t;
}
function Rd(t, e) {
  const { format: n } = e.col.setting;
  return n && (t[0] = Ya(n, e, t[0])), t;
}
function Ad(t, e) {
  const { map: n } = e.col.setting;
  return typeof n == "function" ? t[0] = n(t[0], e) : typeof n == "object" && n && (t[0] = n[t[0]] ?? t[0]), t;
}
function Nd(t, e, n = "[yyyy-]MM-dd hh:mm") {
  const { formatDate: s = n, invalidDate: i } = e.col.setting;
  return t[0] = Md(s, e, t[0], i), t;
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
    if (n && (t = Nd(t, e, n)), t = Ad(t, e), t = Rd(t, e), s ? t = Co(t, e) : t = Td(t, e), i) {
      let r = t[0];
      typeof i == "function" ? r = i.call(this, e) : typeof i == "string" && (r = Y(i, e.row.data)), t.push({ attrs: { title: r } });
    }
    return t;
  }
}, ag = yt(og, { buildIn: !0 });
function Tr(t, { row: e, col: n }) {
  const { data: s } = e, i = s ? s[n.name] : void 0;
  if (!(i != null && i.length))
    return t;
  const { avatarClass: r = "rounded-full", avatarKey: o = `${n.name}Avatar`, avatarProps: a, avatarCodeKey: l, avatarNameKey: h = `${n.name}Name` } = n.setting, c = (s ? s[h] : i) || t[0], d = {
    size: "xs",
    className: L(r, a == null ? void 0 : a.className, "flex-none"),
    src: s ? s[o] : void 0,
    text: c,
    code: l ? s ? s[l] : void 0 : i,
    ...a
  };
  if (t[0] = /* @__PURE__ */ y(oh, { ...d }), n.type === "avatarBtn") {
    const { avatarBtnProps: u } = n.setting, f = typeof u == "function" ? u(n, e) : u;
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
      onRenderCell: Tr
    },
    avatarBtn: {
      onRenderCell: Tr
    },
    avatarName: {
      onRenderCell: Tr
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
        t[0] = /* @__PURE__ */ y("a", { href: Y(a, { ...n.setting, sortType: o }), ...l, children: t[0] });
      }
    }
    return t;
  }
}, dg = yt(hg, { buildIn: !0 }), Rr = (t) => {
  t.length !== 1 && t.forEach((e, n) => {
    !n || e.setting.border || e.setting.group === t[n - 1].setting.group || (e.setting.border = "left");
  });
}, ug = {
  name: "group",
  defaultOptions: {
    groupDivider: !0
  },
  when: (t) => !!t.groupDivider,
  onLayout(t) {
    if (!this.options.groupDivider)
      return;
    const { cols: e } = t;
    Rr(e.left.list), Rr(e.center.list), Rr(e.right.list);
  }
}, fg = yt(ug), pg = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  NestedRowState: $d,
  avatar: cg,
  checkable: eg,
  colHover: Kp,
  group: fg,
  nested: rg,
  renderDatetime: Md,
  renderDatetimeCell: Nd,
  renderFormat: Ya,
  renderFormatCell: Rd,
  renderHtmlCell: Co,
  renderLink: Ga,
  renderLinkCell: Td,
  renderMapCell: Ad,
  rich: ag,
  sortType: dg
}, Symbol.toStringTag, { value: "Module" }));
class As extends J {
}
As.NAME = "DTable";
As.Component = Va;
As.definePlugin = yt;
As.removePlugin = ud;
As.plugins = pg;
var Ld = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Hl = (t, e, n) => (Ld(t, e, "read from private field"), n ? n.call(t) : e.get(t)), gg = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Bl = (t, e, n, s) => (Ld(t, e, "write to private field"), s ? s.call(t, n) : e.set(t, n), n), en;
const mg = "nav", So = '[data-toggle="tab"]', yg = "active";
class Dd extends ot {
  constructor() {
    super(...arguments), gg(this, en, 0);
  }
  active(e) {
    const n = this.$element, s = n.find(So);
    let i = e ? g(e).first() : s.filter(`.${yg}`);
    if (!i.length && (i = n.find(So).first(), !i.length))
      return;
    s.removeClass("active"), i.addClass("active");
    const r = i.attr("href") || i.data("target"), o = g(r);
    o.length && (o.parent().children(".tab-pane").removeClass("active in"), o.addClass("active"), Hl(this, en) && clearTimeout(Hl(this, en)), Bl(this, en, setTimeout(() => {
      o.addClass("in"), Bl(this, en, 0);
    }, 10)));
  }
}
en = /* @__PURE__ */ new WeakMap();
Dd.NAME = "Tabs";
g(document).on("click.tabs.zui", So, (t) => {
  t.preventDefault();
  const e = g(t.target), n = e.closest(`.${mg}`);
  n.length && Dd.ensure(n).active(e);
});
var wg = (t, e, n) => {
  if (!e.has(t))
    throw TypeError("Cannot " + n);
}, Us = (t, e, n) => {
  if (e.has(t))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(t) : e.set(t, n);
}, Oi = (t, e, n) => (wg(t, e, "access private method"), n), Eo, Pd, Mo, Wd, Ka, Id, Xa, Od;
class vg extends ot {
  constructor() {
    super(...arguments), Us(this, Eo), Us(this, Mo), Us(this, Ka), Us(this, Xa);
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
      this.disable(), Oi(this, Mo, Wd).call(this, Oi(this, Eo, Pd).call(this)).finally(() => {
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
Eo = /* @__PURE__ */ new WeakSet();
Pd = function() {
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
Mo = /* @__PURE__ */ new WeakSet();
Wd = async function(t) {
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
  s ? (this.emit("error", s, i), (o = n.onError) == null || o.call(n, s, i)) : Oi(this, Xa, Od).call(this, r), this.emit("complete", r, s), (a = n.onComplete) == null || a.call(n, r, s);
};
Ka = /* @__PURE__ */ new WeakSet();
Id = function(t) {
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
Od = function(t) {
  var o, a;
  const { options: e } = this, { message: n } = t;
  if (t.result === "success") {
    if (this.emit("success", t), ((o = e.onSuccess) == null ? void 0 : o.call(e, t)) === !1)
      return;
    typeof n == "string" && n.length && g(document).trigger("zui.messager.show", { content: n, type: "success" });
  } else {
    if (this.emit("fail", t), ((a = e.onFail) == null ? void 0 : a.call(e, t)) === !1)
      return;
    n && (typeof n == "string" && n.length ? g(document).trigger("zui.messager.show", { content: n, type: "danger" }) : typeof n == "object" && Oi(this, Ka, Id).call(this, n));
  }
  const s = t.closeModal || e.closeModal;
  s && g(this.element).trigger("to-hide.modal.zui", { target: typeof s == "string" ? s : void 0 });
  const i = t.callback || e.callback;
  if (typeof i == "string") {
    const l = i.indexOf("("), h = (l > 0 ? i.substring(0, l) : i).split(".");
    let c = window, d = h[0];
    h.length > 1 && (d = h[1], h[0] === "top" ? c = window.top : h[0] === "parent" ? c = window.parent : c = window[h[0]]);
    const u = c == null ? void 0 : c[d];
    if (typeof u == "function") {
      let f = [];
      return l > 0 && i[i.length - 1] == ")" ? f = JSON.parse("[" + i.substring(l + 1, i.length - 1) + "]") : f.push(t), u.apply(this, f);
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
    const c = i.indexOf("("), d = (c > 0 ? i.substring(0, c) : i).split(".");
    let u = window, f = d[0];
    d.length > 1 && (f = d[1], d[0] === "top" ? u = window.top : d[0] === "parent" ? u = window.parent : u = window[d[0]]);
    const p = u == null ? void 0 : u[f];
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
    return await ar.confirm(t.confirm) ? Ja({ ...t, confirm: void 0 }) : [void 0, new Error("canceled")];
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
    for (const [d, u] of Object.entries(s))
      if (Array.isArray(u)) {
        for (const f of u)
          i.append(d, f);
        continue;
      } else
        i.append(d, u);
  }
  const { beforeSend: r } = t;
  if (r) {
    const d = r(i);
    d instanceof FormData && (i = d);
  }
  let o, a, l;
  try {
    const d = await fetch(t.url, {
      method: t.method || "POST",
      body: i,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        ...t.headers
      }
    });
    a = await d.text(), d.ok ? (l = JSON.parse(a), (!l || typeof l != "object") && (o = new Error("Invalid json format"))) : o = new Error(d.statusText);
  } catch (d) {
    o = d;
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
  return t.url && (t.url = Y(t.url, e.row.data)), (n = t.dropdown) != null && n.items && (t.dropdown.items = t.dropdown.items.map((s) => (s.url && (s.url = Y(s.url, e.row.data)), s))), t;
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
          items: (a == null ? void 0 : a(e)) ?? i.map((d) => {
            const { name: u, items: f, ...p } = d;
            if (o && u) {
              Object.assign(p, o[u], { ...p });
              const { buildProps: m } = p;
              typeof m == "function" && (delete p.buildProps, Object.assign(p, m(t, e)));
            }
            if (p.disabled && (delete p.url, delete p["data-toggle"]), f && p.type === "dropdown") {
              const { dropdown: m = { placement: "bottom-end" } } = p;
              m.menu = {
                className: "menu-dtable-actions",
                items: f.reduce((v, w) => {
                  const b = typeof w == "string" ? { name: w } : { ...w };
                  return b != null && b.name && (o && "name" in b && Object.assign(b, o[b.name], { ...b }), v.push(b)), b.disabled ? (delete b.url, delete b["data-toggle"]) : b.url && (b.url = Y(b.url, n.data)), v;
                }, [])
              }, p.dropdown = m;
            }
            return l ? l(p, e) : p;
          }),
          btnProps: { size: "sm", className: "text-primary" },
          ...r
        };
        return t[0] = /* @__PURE__ */ y(ut, { ...h }), t;
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
}, kg = yt($g), Cg = {
  name: "toolbar",
  footer: {
    toolbar() {
      const { footToolbar: t, showToolbarOnChecked: e } = this.options;
      return e && !this.getChecks().length ? [] : [t ? /* @__PURE__ */ y(ut, { gap: 2, ...t }) : null];
    }
  }
}, Sg = yt(Cg), Eg = {
  name: "pager",
  footer: {
    pager() {
      const { footPager: t } = this.options;
      return [t ? /* @__PURE__ */ y(Ts, { ...t }) : null];
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
  plugins: ["group", "checkable", "nested", kg, Sg, Mg],
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
        return typeof s == "string" ? r.className = L(s, r.className) : typeof s == "object" && s && Object.assign(r, s), t[0] = /* @__PURE__ */ _("i", { ...r }), t;
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
function Hd(t) {
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
  const n = t.split("#"), s = n[0].split("?"), i = s[1], r = i ? Hd(i) : {};
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
      for (let d = 2; d < c.length; d++)
        a.vars.push(["", c[d]]), r["$" + (d - 1)] = c[d];
  }
  return a;
}
function Bd(t, e, n, s, i, r) {
  if (typeof t == "object")
    return Bd(t.moduleName, t.methodName, t.vars, t.viewType, t.hash, t.params);
  const o = window.config;
  if (s || (s = o.defaultView), n) {
    typeof n == "string" && (n = n.split("&"));
    for (let h = 0; h < n.length; h++) {
      const c = n[h];
      if (typeof c == "string") {
        const d = c.split("=");
        n[h] = [d.shift(), d.join("=")];
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
  createLink: Bd,
  parseLink: Rg,
  parseUrlParams: Hd
}, Symbol.toStringTag, { value: "Module" })), Ar = /* @__PURE__ */ new Map();
function lm(t, e, n) {
  const { zui: s } = window;
  Ar.size || Object.keys(s).forEach((r) => {
    r[0] === r[0].toUpperCase() && Ar.set(r.toLowerCase(), s[r]);
  });
  const i = Ar.get(t.toLowerCase());
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
    n.params = h, typeof c == "string" && c.length && (c[0] === "[" ? h.push(...i(c)) : h.push(...c.split(", ").map((d) => (d = d.trim(), d === "$element" ? e : d === "event" ? t : d === "options" ? n : d.startsWith("$element.") || d.startsWith("$event.") || d.startsWith("$options.") ? g.runJS(d, ...r) : i(d))))), a(...h);
  }
  n.do && g.runJS(n.do, ...r);
});
window.$ && Object.assign(window.$, Ag);
export {
  g as $,
  Mc as ActionMenu,
  Rc as ActionMenuNested,
  vg as AjaxForm,
  ah as Avatar,
  Kh as BatchForm,
  th as BtnGroup,
  Jh as Burn,
  ot as Component,
  J as ComponentFromReact,
  bt as ContextMenu,
  Uo as CustomRender,
  As as DTable,
  ld as Dashboard,
  we as Dropdown,
  Dp as ECharts,
  sa as EventBus,
  Bu as HElement,
  kc as HtmlContent,
  us as Icon,
  Ac as Menu,
  ea as Messager,
  ar as Modal,
  ne as ModalBase,
  fh as ModalTrigger,
  gh as Nav,
  mh as Pager,
  Ch as Picker,
  Sh as Popovers,
  Zc as ProgressCircle,
  U as ReactComponent,
  Zh as SearchForm,
  Qc as Switch,
  jt as TIME_DAY,
  Dd as Tabs,
  Eh as Toolbar,
  Nt as Tooltip,
  qh as Tree,
  xf as Upload,
  ed as Zinbar,
  Ug as ajax,
  Ja as ajaxSubmit,
  qg as bus,
  El as calculateTimestamp,
  g as cash,
  L as classes,
  Ar as componentsMap,
  Lg as convertBytes,
  Hf as cookie,
  lm as create,
  ft as createDate,
  qu as createPortal,
  $t as createRef,
  Ru as deepGet,
  Tu as deepGetPath,
  Dg as dom,
  qs as formatBytes,
  to as formatDate,
  Qg as formatDateSpan,
  Y as formatString,
  lc as getClassList,
  tm as getTimeBeforeDesc,
  _ as h,
  Pg as hh,
  Hu as htm,
  Zt as i18n,
  Zg as isDBY,
  Ms as isSameDay,
  Qf as isSameMonth,
  Yg as isSameWeek,
  Qr as isSameYear,
  Kg as isToday,
  Jg as isTomorrow,
  it as isValidElement,
  Xg as isYesterday,
  _l as nativeEvents,
  ds as render,
  Fu as renderCustomResult,
  kf as store,
  cc as storeData,
  Du as takeData,
  am as zentao,
  Tg as zentaoPlugin
};
//# sourceMappingURL=zui.zentao.js.map
