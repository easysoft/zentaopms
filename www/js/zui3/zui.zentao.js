var Go = (n, e, t) => {
  if (!e.has(n))
    throw TypeError("Cannot " + t);
};
var b = (n, e, t) => (Go(n, e, "read from private field"), t ? t.call(n) : e.get(n)), $ = (n, e, t) => {
  if (e.has(n))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(n) : e.set(n, t);
}, C = (n, e, t, s) => (Go(n, e, "write to private field"), s ? s.call(n, t) : e.set(n, t), t);
var O = (n, e, t) => (Go(n, e, "access private method"), t);
const zt = document, Kn = window, sc = zt.documentElement, Pe = zt.createElement.bind(zt), nc = Pe("div"), Yo = Pe("table"), hd = Pe("tbody"), Ea = Pe("tr"), { isArray: Ao, prototype: ic } = Array, { concat: dd, filter: Wr, indexOf: oc, map: rc, push: ud, slice: ac, some: zr, splice: fd } = ic, pd = /^#(?:[\w-]|\\.|[^\x00-\xa0])*$/, md = /^\.(?:[\w-]|\\.|[^\x00-\xa0])*$/, gd = /<.+>/, yd = /^\w+$/;
function Fr(n, e) {
  const t = bd(e);
  return !n || !t && !De(e) && !Z(e) ? [] : !t && md.test(n) ? e.getElementsByClassName(n.slice(1).replace(/\\/g, "")) : !t && yd.test(n) ? e.getElementsByTagName(n) : e.querySelectorAll(n);
}
class Po {
  constructor(e, t) {
    if (!e)
      return;
    if (ar(e))
      return e;
    let s = e;
    if (ot(e)) {
      const i = t || zt;
      if (s = pd.test(e) && De(i) ? i.getElementById(e.slice(1).replace(/\\/g, "")) : gd.test(e) ? hc(e) : ar(i) ? i.find(e) : ot(i) ? u(i).find(e) : Fr(e, i), !s)
        return;
    } else if (Le(e))
      return this.ready(e);
    (s.nodeType || s === Kn) && (s = [s]), this.length = s.length;
    for (let i = 0, o = this.length; i < o; i++)
      this[i] = s[i];
  }
  init(e, t) {
    return new Po(e, t);
  }
}
const I = Po.prototype, u = I.init;
u.fn = u.prototype = I;
I.length = 0;
I.splice = fd;
typeof Symbol == "function" && (I[Symbol.iterator] = ic[Symbol.iterator]);
function ar(n) {
  return n instanceof Po;
}
function hs(n) {
  return !!n && n === n.window;
}
function De(n) {
  return !!n && n.nodeType === 9;
}
function bd(n) {
  return !!n && n.nodeType === 11;
}
function Z(n) {
  return !!n && n.nodeType === 1;
}
function wd(n) {
  return !!n && n.nodeType === 3;
}
function vd(n) {
  return typeof n == "boolean";
}
function Le(n) {
  return typeof n == "function";
}
function ot(n) {
  return typeof n == "string";
}
function ht(n) {
  return n === void 0;
}
function Cs(n) {
  return n === null;
}
function lc(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function Br(n) {
  if (typeof n != "object" || n === null)
    return !1;
  const e = Object.getPrototypeOf(n);
  return e === null || e === Object.prototype;
}
u.isWindow = hs;
u.isFunction = Le;
u.isArray = Ao;
u.isNumeric = lc;
u.isPlainObject = Br;
function Q(n, e, t) {
  if (t) {
    let s = n.length;
    for (; s--; )
      if (e.call(n[s], s, n[s]) === !1)
        return n;
  } else if (Br(n)) {
    const s = Object.keys(n);
    for (let i = 0, o = s.length; i < o; i++) {
      const r = s[i];
      if (e.call(n[r], r, n[r]) === !1)
        return n;
    }
  } else
    for (let s = 0, i = n.length; s < i; s++)
      if (e.call(n[s], s, n[s]) === !1)
        return n;
  return n;
}
u.each = Q;
I.each = function(n) {
  return Q(this, n);
};
I.empty = function() {
  return this.each((n, e) => {
    for (; e.firstChild; )
      e.removeChild(e.firstChild);
  });
};
function Zn(...n) {
  const e = vd(n[0]) ? n.shift() : !1, t = n.shift(), s = n.length;
  if (!t)
    return {};
  if (!s)
    return Zn(e, u, t);
  for (let i = 0; i < s; i++) {
    const o = n[i];
    for (const r in o)
      e && (Ao(o[r]) || Br(o[r])) ? ((!t[r] || t[r].constructor !== o[r].constructor) && (t[r] = new o[r].constructor()), Zn(e, t[r], o[r])) : t[r] = o[r];
  }
  return t;
}
u.extend = Zn;
I.extend = function(n) {
  return Zn(I, n);
};
const _d = /\S+/g;
function Lo(n) {
  return ot(n) ? n.match(_d) || [] : [];
}
I.toggleClass = function(n, e) {
  const t = Lo(n), s = !ht(e);
  return this.each((i, o) => {
    Z(o) && Q(t, (r, a) => {
      s ? e ? o.classList.add(a) : o.classList.remove(a) : o.classList.toggle(a);
    });
  });
};
I.addClass = function(n) {
  return this.toggleClass(n, !0);
};
I.removeAttr = function(n) {
  const e = Lo(n);
  return this.each((t, s) => {
    Z(s) && Q(e, (i, o) => {
      s.removeAttribute(o);
    });
  });
};
function xd(n, e) {
  if (n) {
    if (ot(n)) {
      if (arguments.length < 2) {
        if (!this[0] || !Z(this[0]))
          return;
        const t = this[0].getAttribute(n);
        return Cs(t) ? void 0 : t;
      }
      return ht(e) ? this : Cs(e) ? this.removeAttr(n) : this.each((t, s) => {
        Z(s) && s.setAttribute(n, e);
      });
    }
    for (const t in n)
      this.attr(t, n[t]);
    return this;
  }
}
I.attr = xd;
I.removeClass = function(n) {
  return arguments.length ? this.toggleClass(n, !1) : this.attr("class", "");
};
I.hasClass = function(n) {
  return !!n && zr.call(this, (e) => Z(e) && e.classList.contains(n));
};
I.get = function(n) {
  return ht(n) ? ac.call(this) : (n = Number(n), this[n < 0 ? n + this.length : n]);
};
I.eq = function(n) {
  return u(this.get(n));
};
I.first = function() {
  return this.eq(0);
};
I.last = function() {
  return this.eq(-1);
};
function $d(n) {
  return ht(n) ? this.get().map((e) => Z(e) || wd(e) ? e.textContent : "").join("") : this.each((e, t) => {
    Z(t) && (t.textContent = n);
  });
}
I.text = $d;
function Ft(n, e, t) {
  if (!Z(n))
    return;
  const s = Kn.getComputedStyle(n, null);
  return t ? s.getPropertyValue(e) || void 0 : s[e] || n.style[e];
}
function St(n, e) {
  return parseInt(Ft(n, e), 10) || 0;
}
function Na(n, e) {
  return St(n, `border${e ? "Left" : "Top"}Width`) + St(n, `padding${e ? "Left" : "Top"}`) + St(n, `padding${e ? "Right" : "Bottom"}`) + St(n, `border${e ? "Right" : "Bottom"}Width`);
}
const Xo = {};
function Cd(n) {
  if (Xo[n])
    return Xo[n];
  const e = Pe(n);
  zt.body.insertBefore(e, null);
  const t = Ft(e, "display");
  return zt.body.removeChild(e), Xo[n] = t !== "none" ? t : "block";
}
function Ma(n) {
  return Ft(n, "display") === "none";
}
function cc(n, e) {
  const t = n && (n.matches || n.webkitMatchesSelector || n.msMatchesSelector);
  return !!t && !!e && t.call(n, e);
}
function Ho(n) {
  return ot(n) ? (e, t) => cc(t, n) : Le(n) ? n : ar(n) ? (e, t) => n.is(t) : n ? (e, t) => t === n : () => !1;
}
I.filter = function(n) {
  const e = Ho(n);
  return u(Wr.call(this, (t, s) => e.call(t, s, t)));
};
function le(n, e) {
  return e ? n.filter(e) : n;
}
I.detach = function(n) {
  return le(this, n).each((e, t) => {
    t.parentNode && t.parentNode.removeChild(t);
  }), this;
};
const Td = /^\s*<(\w+)[^>]*>/, kd = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, Da = {
  "*": nc,
  tr: hd,
  td: Ea,
  th: Ea,
  thead: Yo,
  tbody: Yo,
  tfoot: Yo
};
function hc(n) {
  if (!ot(n))
    return [];
  if (kd.test(n))
    return [Pe(RegExp.$1)];
  const e = Td.test(n) && RegExp.$1, t = Da[e] || Da["*"];
  return t.innerHTML = n, u(t.childNodes).detach().get();
}
u.parseHTML = hc;
I.has = function(n) {
  const e = ot(n) ? (t, s) => Fr(n, s).length : (t, s) => s.contains(n);
  return this.filter(e);
};
I.not = function(n) {
  const e = Ho(n);
  return this.filter((t, s) => (!ot(n) || Z(s)) && !e.call(s, t, s));
};
function Vt(n, e, t, s) {
  const i = [], o = Le(e), r = s && Ho(s);
  for (let a = 0, l = n.length; a < l; a++)
    if (o) {
      const c = e(n[a]);
      c.length && ud.apply(i, c);
    } else {
      let c = n[a][e];
      for (; c != null && !(s && r(-1, c)); )
        i.push(c), c = t ? c[e] : null;
    }
  return i;
}
function dc(n) {
  return n.multiple && n.options ? Vt(Wr.call(n.options, (e) => e.selected && !e.disabled && !e.parentNode.disabled), "value") : n.value || "";
}
function Sd(n) {
  return arguments.length ? this.each((e, t) => {
    const s = t.multiple && t.options;
    if (s || wc.test(t.type)) {
      const i = Ao(n) ? rc.call(n, String) : Cs(n) ? [] : [String(n)];
      s ? Q(t.options, (o, r) => {
        r.selected = i.indexOf(r.value) >= 0;
      }, !0) : t.checked = i.indexOf(t.value) >= 0;
    } else
      t.value = ht(n) || Cs(n) ? "" : n;
  }) : this[0] && dc(this[0]);
}
I.val = Sd;
I.is = function(n) {
  const e = Ho(n);
  return zr.call(this, (t, s) => e.call(t, s, t));
};
u.guid = 1;
function It(n) {
  return n.length > 1 ? Wr.call(n, (e, t, s) => oc.call(s, e) === t) : n;
}
u.unique = It;
I.add = function(n, e) {
  return u(It(this.get().concat(u(n, e).get())));
};
I.children = function(n) {
  return le(u(It(Vt(this, (e) => e.children))), n);
};
I.parent = function(n) {
  return le(u(It(Vt(this, "parentNode"))), n);
};
I.index = function(n) {
  const e = n ? u(n)[0] : this[0], t = n ? this : u(e).parent().children();
  return oc.call(t, e);
};
I.closest = function(n) {
  const e = this.filter(n);
  if (e.length)
    return e;
  const t = this.parent();
  return t.length ? t.closest(n) : e;
};
I.siblings = function(n) {
  return le(u(It(Vt(this, (e) => u(e).parent().children().not(e)))), n);
};
I.find = function(n) {
  return u(It(Vt(this, (e) => Fr(n, e))));
};
const Ed = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, Nd = /^$|^module$|\/(java|ecma)script/i, Md = ["type", "src", "nonce", "noModule"];
function Dd(n, e) {
  const t = u(n);
  t.filter("script").add(t.find("script")).each((s, i) => {
    if (Nd.test(i.type) && sc.contains(i)) {
      const o = Pe("script");
      o.text = i.textContent.replace(Ed, ""), Q(Md, (r, a) => {
        i[a] && (o[a] = i[a]);
      }), e.head.insertBefore(o, null), e.head.removeChild(o);
    }
  });
}
function Id(n, e, t, s, i) {
  s ? n.insertBefore(e, t ? n.firstChild : null) : n.nodeName === "HTML" ? n.parentNode.replaceChild(e, n) : n.parentNode.insertBefore(e, t ? n : n.nextSibling), i && Dd(e, n.ownerDocument);
}
function ce(n, e, t, s, i, o, r, a) {
  return Q(n, (l, c) => {
    Q(u(c), (d, h) => {
      Q(u(e), (f, m) => {
        const p = t ? h : m, y = t ? m : h, _ = t ? d : f;
        Id(p, _ ? y.cloneNode(!0) : y, s, i, !_);
      }, a);
    }, r);
  }, o), e;
}
I.after = function() {
  return ce(arguments, this, !1, !1, !1, !0, !0);
};
I.append = function() {
  return ce(arguments, this, !1, !1, !0);
};
function Rd(n) {
  if (!arguments.length)
    return this[0] && this[0].innerHTML;
  if (ht(n))
    return this;
  const e = /<script[\s>]/.test(n);
  return this.each((t, s) => {
    Z(s) && (e ? u(s).empty().append(n) : s.innerHTML = n);
  });
}
I.html = Rd;
I.appendTo = function(n) {
  return ce(arguments, this, !0, !1, !0);
};
I.wrapInner = function(n) {
  return this.each((e, t) => {
    const s = u(t), i = s.contents();
    i.length ? i.wrapAll(n) : s.append(n);
  });
};
I.before = function() {
  return ce(arguments, this, !1, !0);
};
I.wrapAll = function(n) {
  let e = u(n), t = e[0];
  for (; t.children.length; )
    t = t.firstElementChild;
  return this.first().before(e), this.appendTo(t);
};
I.wrap = function(n) {
  return this.each((e, t) => {
    const s = u(n)[0];
    u(t).wrapAll(e ? s.cloneNode(!0) : s);
  });
};
I.insertAfter = function(n) {
  return ce(arguments, this, !0, !1, !1, !1, !1, !0);
};
I.insertBefore = function(n) {
  return ce(arguments, this, !0, !0);
};
I.prepend = function() {
  return ce(arguments, this, !1, !0, !0, !0, !0);
};
I.prependTo = function(n) {
  return ce(arguments, this, !0, !0, !0, !1, !1, !0);
};
I.contents = function() {
  return u(It(Vt(this, (n) => n.tagName === "IFRAME" ? [n.contentDocument] : n.tagName === "TEMPLATE" ? n.content.childNodes : n.childNodes)));
};
I.next = function(n, e, t) {
  return le(u(It(Vt(this, "nextElementSibling", e, t))), n);
};
I.nextAll = function(n) {
  return this.next(n, !0);
};
I.nextUntil = function(n, e) {
  return this.next(e, !0, n);
};
I.parents = function(n, e) {
  return le(u(It(Vt(this, "parentElement", !0, e))), n);
};
I.parentsUntil = function(n, e) {
  return this.parents(e, n);
};
I.prev = function(n, e, t) {
  return le(u(It(Vt(this, "previousElementSibling", e, t))), n);
};
I.prevAll = function(n) {
  return this.prev(n, !0);
};
I.prevUntil = function(n, e) {
  return this.prev(e, !0, n);
};
I.map = function(n) {
  return u(dd.apply([], rc.call(this, (e, t) => n.call(e, t, e))));
};
I.clone = function() {
  return this.map((n, e) => e.cloneNode(!0));
};
I.offsetParent = function() {
  return this.map((n, e) => {
    let t = e.offsetParent;
    for (; t && Ft(t, "position") === "static"; )
      t = t.offsetParent;
    return t || sc;
  });
};
I.slice = function(n, e) {
  return u(ac.call(this, n, e));
};
const Ad = /-([a-z])/g;
function Vr(n) {
  return n.replace(Ad, (e, t) => t.toUpperCase());
}
I.ready = function(n) {
  const e = () => setTimeout(n, 0, u);
  return zt.readyState !== "loading" ? e() : zt.addEventListener("DOMContentLoaded", e), this;
};
I.unwrap = function() {
  return this.parent().each((n, e) => {
    if (e.tagName === "BODY")
      return;
    const t = u(e);
    t.replaceWith(t.children());
  }), this;
};
I.offset = function() {
  const n = this[0];
  if (!n)
    return;
  const e = n.getBoundingClientRect();
  return {
    top: e.top + Kn.pageYOffset,
    left: e.left + Kn.pageXOffset
  };
};
I.position = function() {
  const n = this[0];
  if (!n)
    return;
  const e = Ft(n, "position") === "fixed", t = e ? n.getBoundingClientRect() : this.offset();
  if (!e) {
    const s = n.ownerDocument;
    let i = n.offsetParent || s.documentElement;
    for (; (i === s.body || i === s.documentElement) && Ft(i, "position") === "static"; )
      i = i.parentNode;
    if (i !== n && Z(i)) {
      const o = u(i).offset();
      t.top -= o.top + St(i, "borderTopWidth"), t.left -= o.left + St(i, "borderLeftWidth");
    }
  }
  return {
    top: t.top - St(n, "marginTop"),
    left: t.left - St(n, "marginLeft")
  };
};
const uc = {
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
I.prop = function(n, e) {
  if (n) {
    if (ot(n))
      return n = uc[n] || n, arguments.length < 2 ? this[0] && this[0][n] : this.each((t, s) => {
        s[n] = e;
      });
    for (const t in n)
      this.prop(t, n[t]);
    return this;
  }
};
I.removeProp = function(n) {
  return this.each((e, t) => {
    delete t[uc[n] || n];
  });
};
const Pd = /^--/;
function Ur(n) {
  return Pd.test(n);
}
const Ko = {}, { style: Ld } = nc, Hd = ["webkit", "moz", "ms"];
function Od(n, e = Ur(n)) {
  if (e)
    return n;
  if (!Ko[n]) {
    const t = Vr(n), s = `${t[0].toUpperCase()}${t.slice(1)}`, i = `${t} ${Hd.join(`${s} `)}${s}`.split(" ");
    Q(i, (o, r) => {
      if (r in Ld)
        return Ko[n] = r, !1;
    });
  }
  return Ko[n];
}
const jd = {
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
function fc(n, e, t = Ur(n)) {
  return !t && !jd[n] && lc(e) ? `${e}px` : e;
}
function Wd(n, e) {
  if (ot(n)) {
    const t = Ur(n);
    return n = Od(n, t), arguments.length < 2 ? this[0] && Ft(this[0], n, t) : n ? (e = fc(n, e, t), this.each((s, i) => {
      Z(i) && (t ? i.style.setProperty(n, e) : i.style[n] = e);
    })) : this;
  }
  for (const t in n)
    this.css(t, n[t]);
  return this;
}
I.css = Wd;
function pc(n, e) {
  try {
    return n(e);
  } catch {
    return e;
  }
}
const zd = /^\s+|\s+$/;
function Ia(n, e) {
  const t = n.dataset[e] || n.dataset[Vr(e)];
  return zd.test(t) ? t : pc(JSON.parse, t);
}
function Fd(n, e, t) {
  t = pc(JSON.stringify, t), n.dataset[Vr(e)] = t;
}
function Bd(n, e) {
  if (!n) {
    if (!this[0])
      return;
    const t = {};
    for (const s in this[0].dataset)
      t[s] = Ia(this[0], s);
    return t;
  }
  if (ot(n))
    return arguments.length < 2 ? this[0] && Ia(this[0], n) : ht(e) ? this : this.each((t, s) => {
      Fd(s, n, e);
    });
  for (const t in n)
    this.data(t, n[t]);
  return this;
}
I.data = Bd;
function mc(n, e) {
  const t = n.documentElement;
  return Math.max(n.body[`scroll${e}`], t[`scroll${e}`], n.body[`offset${e}`], t[`offset${e}`], t[`client${e}`]);
}
Q([!0, !1], (n, e) => {
  Q(["Width", "Height"], (t, s) => {
    const i = `${e ? "outer" : "inner"}${s}`;
    I[i] = function(o) {
      if (this[0])
        return hs(this[0]) ? e ? this[0][`inner${s}`] : this[0].document.documentElement[`client${s}`] : De(this[0]) ? mc(this[0], s) : this[0][`${e ? "offset" : "client"}${s}`] + (o && e ? St(this[0], `margin${t ? "Top" : "Left"}`) + St(this[0], `margin${t ? "Bottom" : "Right"}`) : 0);
    };
  });
});
Q(["Width", "Height"], (n, e) => {
  const t = e.toLowerCase();
  I[t] = function(s) {
    if (!this[0])
      return ht(s) ? void 0 : this;
    if (!arguments.length)
      return hs(this[0]) ? this[0].document.documentElement[`client${e}`] : De(this[0]) ? mc(this[0], e) : this[0].getBoundingClientRect()[t] - Na(this[0], !n);
    const i = parseInt(s, 10);
    return this.each((o, r) => {
      if (!Z(r))
        return;
      const a = Ft(r, "boxSizing");
      r.style[t] = fc(t, i + (a === "border-box" ? Na(r, !n) : 0));
    });
  };
});
const Ra = "___cd";
I.toggle = function(n) {
  return this.each((e, t) => {
    if (!Z(t))
      return;
    const s = Ma(t);
    (ht(n) ? s : n) ? (t.style.display = t[Ra] || "", Ma(t) && (t.style.display = Cd(t.tagName))) : s || (t[Ra] = Ft(t, "display"), t.style.display = "none");
  });
};
I.hide = function() {
  return this.toggle(!1);
};
I.show = function() {
  return this.toggle(!0);
};
const Aa = "___ce", qr = ".", Gr = { focus: "focusin", blur: "focusout" }, gc = { mouseenter: "mouseover", mouseleave: "mouseout" }, Vd = /^(mouse|pointer|contextmenu|drag|drop|click|dblclick)/i;
function Yr(n) {
  return gc[n] || Gr[n] || n;
}
function Xr(n) {
  const e = n.split(qr);
  return [e[0], e.slice(1).sort()];
}
I.trigger = function(n, e) {
  if (ot(n)) {
    const [s, i] = Xr(n), o = Yr(s);
    if (!o)
      return this;
    const r = Vd.test(o) ? "MouseEvents" : "HTMLEvents";
    n = zt.createEvent(r), n.initEvent(o, !0, !0), n.namespace = i.join(qr), n.___ot = s;
  }
  n.___td = e;
  const t = n.___ot in Gr;
  return this.each((s, i) => {
    t && Le(i[n.___ot]) && (i[`___i${n.type}`] = !0, i[n.___ot](), i[`___i${n.type}`] = !1), i.dispatchEvent(n);
  });
};
function yc(n) {
  return n[Aa] = n[Aa] || {};
}
function Ud(n, e, t, s, i) {
  const o = yc(n);
  o[e] = o[e] || [], o[e].push([t, s, i]), n.addEventListener(e, i);
}
function bc(n, e) {
  return !e || !zr.call(e, (t) => n.indexOf(t) < 0);
}
function Jn(n, e, t, s, i) {
  const o = yc(n);
  if (e)
    o[e] && (o[e] = o[e].filter(([r, a, l]) => {
      if (i && l.guid !== i.guid || !bc(r, t) || s && s !== a)
        return !0;
      n.removeEventListener(e, l);
    }));
  else
    for (e in o)
      Jn(n, e, t, s, i);
}
I.off = function(n, e, t) {
  if (ht(n))
    this.each((s, i) => {
      !Z(i) && !De(i) && !hs(i) || Jn(i);
    });
  else if (ot(n))
    Le(e) && (t = e, e = ""), Q(Lo(n), (s, i) => {
      const [o, r] = Xr(i), a = Yr(o);
      this.each((l, c) => {
        !Z(c) && !De(c) && !hs(c) || Jn(c, a, r, e, t);
      });
    });
  else
    for (const s in n)
      this.off(s, n[s]);
  return this;
};
I.remove = function(n) {
  return le(this, n).detach().off(), this;
};
I.replaceWith = function(n) {
  return this.before(n).remove();
};
I.replaceAll = function(n) {
  return u(n).replaceWith(this), this;
};
function qd(n, e, t, s, i) {
  if (!ot(n)) {
    for (const o in n)
      this.on(o, e, t, n[o], i);
    return this;
  }
  return ot(e) || (ht(e) || Cs(e) ? e = "" : ht(t) ? (t = e, e = "") : (s = t, t = e, e = "")), Le(s) || (s = t, t = void 0), s ? (Q(Lo(n), (o, r) => {
    const [a, l] = Xr(r), c = Yr(a), d = a in gc, h = a in Gr;
    c && this.each((f, m) => {
      if (!Z(m) && !De(m) && !hs(m))
        return;
      const p = function(y) {
        if (y.target[`___i${y.type}`])
          return y.stopImmediatePropagation();
        if (y.namespace && !bc(l, y.namespace.split(qr)) || !e && (h && (y.target !== m || y.___ot === c) || d && y.relatedTarget && m.contains(y.relatedTarget)))
          return;
        let _ = m;
        if (e) {
          let x = y.target;
          for (; !cc(x, e); )
            if (x === m || (x = x.parentNode, !x))
              return;
          _ = x;
        }
        Object.defineProperty(y, "currentTarget", {
          configurable: !0,
          get() {
            return _;
          }
        }), Object.defineProperty(y, "delegateTarget", {
          configurable: !0,
          get() {
            return m;
          }
        }), Object.defineProperty(y, "data", {
          configurable: !0,
          get() {
            return t;
          }
        });
        const w = s.call(_, y, y.___td);
        i && Jn(m, c, l, e, p), w === !1 && (y.preventDefault(), y.stopPropagation());
      };
      p.guid = s.guid = s.guid || u.guid++, Ud(m, c, l, e, p);
    });
  }), this) : this;
}
I.on = qd;
function Gd(n, e, t, s) {
  return this.on(n, e, t, s, !0);
}
I.one = Gd;
const Yd = /\r?\n/g;
function Xd(n, e) {
  return `&${encodeURIComponent(n)}=${encodeURIComponent(e.replace(Yd, `\r
`))}`;
}
const Kd = /file|reset|submit|button|image/i, wc = /radio|checkbox/i;
I.serialize = function() {
  let n = "";
  return this.each((e, t) => {
    Q(t.elements || [t], (s, i) => {
      if (i.disabled || !i.name || i.tagName === "FIELDSET" || Kd.test(i.type) || wc.test(i.type) && !i.checked)
        return;
      const o = dc(i);
      if (!ht(o)) {
        const r = Ao(o) ? o : [o];
        Q(r, (a, l) => {
          n += Xd(i.name, l);
        });
      }
    });
  }), n.slice(1);
};
window.$ = u;
function Zd(n, e) {
  if (n == null)
    return [n, void 0];
  typeof e == "string" && (e = e.split("."));
  const t = e.join(".");
  let s = n;
  const i = [s];
  for (; typeof s == "object" && s !== null && e.length; ) {
    let o = e.shift(), r;
    const a = o.indexOf("[");
    if (a > 0 && a < o.length - 1 && o.endsWith("]") && (r = o.substring(a + 1, o.length - 1), o = o.substring(0, a)), s = s[o], i.push(s), r !== void 0)
      if (typeof s == "object" && s !== null)
        s instanceof Map ? s = s.get(r) : s = s[r], i.push(s);
      else
        throw new Error(`Cannot access property "${o}[${r}]", the full path is "${t}".`);
  }
  if (e.length)
    throw new Error(`Cannot access property with rest path "${e.join(".")}", the full path is "${t}".`);
  return i;
}
function Jd(n, e, t) {
  try {
    const s = Zd(n, e), i = s[s.length - 1];
    return i === void 0 ? t : i;
  } catch {
    return t;
  }
}
function U(n, ...e) {
  if (e.length === 0)
    return n;
  if (e.length === 1 && typeof e[0] == "object" && e[0]) {
    const t = e[0];
    return Object.keys(t).forEach((s) => {
      const i = t[s] ?? "";
      n = n.replace(new RegExp(`\\{${s}\\}`, "g"), `${i}`);
    }), n;
  }
  for (let t = 0; t < e.length; t++) {
    const s = e[t] ?? "";
    n = n.replace(new RegExp(`\\{${t}\\}`, "g"), `${s}`);
  }
  return n;
}
var Kr = /* @__PURE__ */ ((n) => (n[n.B = 1] = "B", n[n.KB = 1024] = "KB", n[n.MB = 1048576] = "MB", n[n.GB = 1073741824] = "GB", n[n.TB = 1099511627776] = "TB", n))(Kr || {});
function Bn(n, e = 2, t) {
  return Number.isNaN(n) ? "?KB" : (t || (n < 1024 ? t = "B" : n < 1048576 ? t = "KB" : n < 1073741824 ? t = "MB" : n < 1099511627776 ? t = "GB" : t = "TB"), (n / Kr[t]).toFixed(e) + t);
}
const Qd = (n) => {
  const e = /^[0-9]*(B|KB|MB|GB|TB)$/;
  n = n.toUpperCase();
  const t = n.match(e);
  if (!t)
    return 0;
  const s = t[1];
  return n = n.replace(s, ""), Number.parseInt(n, 10) * Kr[s];
};
let Zr = (document.documentElement.getAttribute("lang") || "zh_cn").toLowerCase().replace("-", "_"), Yt;
function tu() {
  return Zr;
}
function eu(n) {
  Zr = n.toLowerCase();
}
function vc(n, e) {
  Yt || (Yt = {}), typeof n == "string" && (n = { [n]: e ?? {} }), u.extend(!0, Yt, n);
}
function tt(n, e, t, s, i, o) {
  Array.isArray(n) ? Yt && n.unshift(Yt) : n = Yt ? [Yt, n] : [n], typeof t == "string" && (o = i, i = s, s = t, t = void 0);
  const r = i || Zr;
  let a;
  for (const l of n) {
    if (!l)
      continue;
    const c = l[r];
    if (!c)
      continue;
    const d = o && l === Yt ? `${o}.${e}` : e;
    if (a = Jd(c, d), a !== void 0)
      break;
  }
  return a === void 0 ? s : t ? U(a, ...Array.isArray(t) ? t : [t]) : a;
}
function su(n, e, t, s) {
  return tt(void 0, n, e, t, s);
}
tt.addLang = vc;
tt.getLang = su;
tt.getCode = tu;
tt.setCode = eu;
vc({
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
function _c(...n) {
  const e = [], t = /* @__PURE__ */ new Map(), s = (i, o) => {
    if (Array.isArray(i) && (o = i[1], i = i[0]), !i.length)
      return;
    const r = t.get(i);
    typeof r == "number" ? e[r][1] = !!o : (t.set(i, e.length), e.push([i, !!o]));
  };
  return n.forEach((i) => {
    typeof i == "function" && (i = i()), Array.isArray(i) ? _c(...i).forEach(s) : i && typeof i == "object" ? Object.entries(i).forEach(s) : typeof i == "string" && i.split(" ").forEach((o) => s(o, !0));
  }), e.sort((i, o) => (t.get(i[0]) || 0) - (t.get(o[0]) || 0));
}
const N = (...n) => _c(...n).reduce((e, [t, s]) => (s && e.push(t), e), []).join(" ");
u.classes = N;
u.fn.setClass = function(n, ...e) {
  return this.each((t, s) => {
    const i = u(s);
    n === !0 ? i.attr("class", N(i.attr("class"), ...e)) : i.addClass(N(n, ...e));
  });
};
const ws = /* @__PURE__ */ new WeakMap();
function xc(n, e, t) {
  const s = ws.has(n), i = s ? ws.get(n) : {};
  typeof e == "string" ? i[e] = t : e === null ? Object.keys(i).forEach((o) => {
    delete i[o];
  }) : Object.assign(i, e), Object.keys(i).forEach((o) => {
    i[o] === void 0 && delete i[o];
  }), Object.keys(i).length ? (!s && n instanceof Element && Object.assign(i, u(n).dataset(), i), ws.set(n, i)) : ws.delete(n);
}
function $c(n, e, t) {
  let s = ws.get(n) || {};
  return !t && n instanceof Element && (s = Object.assign({}, u(n).dataset(), s)), e === void 0 ? s : s[e];
}
u.fn.dataset = u.fn.data;
u.fn.data = function(...n) {
  if (!this.length)
    return;
  const [e, t] = n;
  return !n.length || n.length === 1 && typeof e == "string" ? $c(this[0], e) : this.each((s, i) => xc(i, e, t));
};
u.fn.removeData = function(n = null) {
  return this.each((e, t) => xc(t, n));
};
u.fn._attr = u.fn.attr;
u.fn.extend({
  attr(...n) {
    const [e, t] = n;
    return !n.length || n.length === 1 && typeof e == "string" ? this._attr.apply(this, n) : typeof e == "object" ? (e && Object.keys(e).forEach((s) => {
      const i = e[s];
      i === null ? this.removeAttr(s) : this._attr(s, i);
    }), this) : t === null ? this.removeAttr(e) : this._attr(e, t);
  }
});
u.Event = (n, e) => {
  const [t, ...s] = n.split("."), i = new Event(t, {
    bubbles: !0,
    cancelable: !0
  });
  return i.namespace = s.join("."), i.___ot = t, i.___td = e, i;
};
const Qn = (n, e) => new Promise((t) => {
  const s = window.setTimeout(t, n);
  e && e(s);
}), nu = {};
u.share = nu;
const vs = /* @__PURE__ */ new Map();
function ti(n) {
  const { zui: e } = window;
  return (!vs.size || n && !vs.has(n.toUpperCase())) && Object.keys(e).forEach((t) => {
    const s = e[t];
    !s.NAME || !s.ZUI || vs.set(t.toLowerCase(), s);
  }), n ? vs.get(n.toLowerCase()) : void 0;
}
function iu(n, e, t) {
  const s = ti(n);
  return s ? !s.MULTI_INSTANCE && s.get(e) ? (console.error(`[ZUI] cannot create component "${n}" on element which already has a component instance.`, { element: e, options: t }), null) : new s(e, t) : null;
}
function Up(n) {
  if (n) {
    const e = ti(n);
    e && e.defineFn();
  } else
    ti(), vs.forEach((e) => {
      e.defineFn();
    });
}
u.fn.zuiInit = function() {
  return this.find("[data-zui]").each(function() {
    const n = u(this);
    let e = n.dataset();
    const [t, s] = e.zui.split(":");
    n.zui(t) || (s ? e = u.share[s] : delete e.zui, requestAnimationFrame(() => iu(t, this, e)));
  }), this;
};
u.fn.zui = function(n, e) {
  const t = this[0];
  if (!t)
    return;
  if (typeof n != "string") {
    const i = $c(t, void 0, !0), o = {};
    let r;
    return Object.keys(i).forEach((a) => {
      if (a.startsWith("zui.")) {
        const l = i[a];
        o[a] = l, (!r || r.gid < l.gid) && (r = o[a]);
      }
    }), n === !0 ? o : r;
  }
  const s = ti(n);
  if (s)
    return e === !0 ? s.getAll(t) : s.query(t, e);
};
u(() => {
  u("body").zuiInit();
});
function Jr(n, e) {
  const t = u(n)[0];
  if (!t)
    return !1;
  let { viewport: s } = e || {};
  const { left: i, top: o, width: r, height: a } = t.getBoundingClientRect();
  if (!s) {
    const { innerHeight: p, innerWidth: y } = window, { clientHeight: _, clientWidth: w } = document.documentElement;
    s = { left: 0, top: 0, width: y || w, height: p || _ };
  }
  const { left: l, top: c, width: d, height: h } = s;
  if (e != null && e.fullyCheck)
    return i >= l && o >= c && i + r <= d && o + a <= h;
  const f = i <= d && i + r >= l;
  return o <= h && o + a >= c && f;
}
u.fn.isVisible = function(n) {
  return Jr(this, n);
};
function Qr(n, e, t = !1) {
  const s = u(n);
  if (e !== void 0) {
    if (e.length) {
      const i = `zui-runjs-${u.guid++}`;
      s.append(`<script id="${i}">${e}<\/script>`), t && s.find(`#${i}`).remove();
    }
    return;
  }
  s.find("script").each((i, o) => {
    Qr(s, o.innerHTML), o.remove();
  });
}
u.runJS = (n, ...e) => (n = n.trim(), !n.startsWith("return ") && !n.endsWith(";") && (n = `return ${n}`), new Function(...e.map(([s]) => s), n)(...e.map(([, s]) => s)));
u.fn.runJS = function(n) {
  return this.each((e, t) => {
    Qr(t, n);
  });
};
function Cc(n, e) {
  const t = u(n), { ifNeeded: s = !0, ...i } = e || {};
  return t.each((o, r) => {
    s && Jr(r, { viewport: r.getBoundingClientRect() }) || r.scrollIntoView(i);
  }), t;
}
u.fn.scrollIntoView = function(n) {
  return this.each((e, t) => {
    Cc(t, n);
  });
};
u.setLibRoot = function(n) {
  u.libRoot = n;
};
u.registerLib = function(n, e) {
  u.libMap || (u.libMap = {}), !e.name && e.id && (e.id = `zui-lib-${n}`), u.libMap[n] = e;
};
u.getLib = function(n, e, t) {
  return new Promise((s, i) => {
    let o = typeof n == "string" ? { src: n } : u.extend({}, n);
    typeof e == "function" ? o.success = e : e && u.extend(o, e), t && (o.success = t);
    let { src: r } = o;
    if (!r)
      return i(new Error("[ZUI] No src provided for $.getLib."));
    const a = u.libMap && u.libMap[r];
    a && (o = u.extend({}, a, o), r = a.src || o.src);
    const { root: l = u.libRoot } = o;
    l && (r = `${l}/${r}`.replace("//", "/"));
    const { success: c, name: d } = o, h = () => d ? window[d] : void 0, f = () => {
      s(h()), c == null || c();
    };
    if (h()) {
      f();
      return;
    }
    const { id: m } = o, p = u(m ? `#${m}` : `script[src="${r}"]`);
    if (p.length) {
      if (p.dataset("loaded"))
        f();
      else {
        const E = p.data("loadCalls") || [];
        E.push(f), p.data("loadCalls", E);
      }
      return;
    }
    const { async: y = !0, defer: _ = !1, noModule: w = !1, type: x, integrity: T } = o, k = document.createElement("script");
    k.async = y, k.defer = _, k.noModule = w, x && (k.type = x), T && (k.integrity = T), k.onload = () => {
      f(), (u(k).dataset("loaded", !0).data("loadCalls") || []).forEach((D) => D()), u(k).removeData("loadCalls");
    }, k.onerror = () => {
      i(new Error(`[ZUI] Failed to load lib from: ${r}`));
    }, k.src = r, u("head").append(k);
  });
};
u.getScript = u.getLib;
const qp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  isVisible: Jr,
  runJS: Qr,
  scrollIntoView: Cc
}, Symbol.toStringTag, { value: "Module" }));
var Oo, j, Tc, st, de, Pa, kc, lr, ze = {}, Sc = [], ou = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i, ta = Array.isArray;
function ne(n, e) {
  for (var t in e)
    n[t] = e[t];
  return n;
}
function Ec(n) {
  var e = n.parentNode;
  e && e.removeChild(n);
}
function v(n, e, t) {
  var s, i, o, r = {};
  for (o in e)
    o == "key" ? s = e[o] : o == "ref" ? i = e[o] : r[o] = e[o];
  if (arguments.length > 2 && (r.children = arguments.length > 3 ? Oo.call(arguments, 2) : t), typeof n == "function" && n.defaultProps != null)
    for (o in n.defaultProps)
      r[o] === void 0 && (r[o] = n.defaultProps[o]);
  return Vn(n, r, s, i, null);
}
function Vn(n, e, t, s, i) {
  var o = { type: n, props: e, key: t, ref: s, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++Tc };
  return i == null && j.vnode != null && j.vnode(o), o;
}
function q() {
  return { current: null };
}
function oe(n) {
  return n.children;
}
function F(n, e) {
  this.props = n, this.context = e;
}
function ei(n, e) {
  if (e == null)
    return n.__ ? ei(n.__, n.__.__k.indexOf(n) + 1) : null;
  for (var t; e < n.__k.length; e++)
    if ((t = n.__k[e]) != null && t.__e != null)
      return t.__e;
  return typeof n.type == "function" ? ei(n) : null;
}
function Nc(n) {
  var e, t;
  if ((n = n.__) != null && n.__c != null) {
    for (n.__e = n.__c.base = null, e = 0; e < n.__k.length; e++)
      if ((t = n.__k[e]) != null && t.__e != null) {
        n.__e = n.__c.base = t.__e;
        break;
      }
    return Nc(n);
  }
}
function La(n) {
  (!n.__d && (n.__d = !0) && de.push(n) && !si.__r++ || Pa !== j.debounceRendering) && ((Pa = j.debounceRendering) || kc)(si);
}
function si() {
  var n, e, t, s, i, o, r, a, l;
  for (de.sort(lr); n = de.shift(); )
    n.__d && (e = de.length, s = void 0, i = void 0, o = void 0, a = (r = (t = n).__v).__e, (l = t.__P) && (s = [], i = [], (o = ne({}, r)).__v = r.__v + 1, ea(l, r, o, t.__n, l.ownerSVGElement !== void 0, r.__h != null ? [a] : null, s, a ?? ei(r), r.__h, i), Rc(s, r, i), r.__e != a && Nc(r)), de.length > e && de.sort(lr));
  si.__r = 0;
}
function Mc(n, e, t, s, i, o, r, a, l, c, d) {
  var h, f, m, p, y, _, w, x, T, k, E = 0, D = s && s.__k || Sc, L = D.length, R = L, A = e.length;
  for (t.__k = [], h = 0; h < A; h++)
    (p = t.__k[h] = (p = e[h]) == null || typeof p == "boolean" || typeof p == "function" ? null : typeof p == "string" || typeof p == "number" || typeof p == "bigint" ? Vn(null, p, null, null, p) : ta(p) ? Vn(oe, { children: p }, null, null, null) : p.__b > 0 ? Vn(p.type, p.props, p.key, p.ref ? p.ref : null, p.__v) : p) != null && (p.__ = t, p.__b = t.__b + 1, (x = ru(p, D, w = h + E, R)) === -1 ? m = ze : (m = D[x] || ze, D[x] = void 0, R--), ea(n, p, m, i, o, r, a, l, c, d), y = p.__e, (f = p.ref) && m.ref != f && (m.ref && sa(m.ref, null, p), d.push(f, p.__c || y, p)), y != null && (_ == null && (_ = y), k = !(T = m === ze || m.__v === null) && x === w, T ? x == -1 && E-- : x !== w && (x === w + 1 ? (E++, k = !0) : x > w ? R > A - w ? (E += x - w, k = !0) : E-- : E = x < w && x == w - 1 ? x - w : 0), w = h + E, k = k || x == h && !T, typeof p.type != "function" || x === w && m.__k !== p.__k ? typeof p.type == "function" || k ? p.__d !== void 0 ? (l = p.__d, p.__d = void 0) : l = y.nextSibling : l = Ic(n, y, l) : l = Dc(p, l, n), typeof t.type == "function" && (t.__d = l)));
  for (t.__e = _, h = L; h--; )
    D[h] != null && (typeof t.type == "function" && D[h].__e != null && D[h].__e == t.__d && (t.__d = D[h].__e.nextSibling), Ac(D[h], D[h]));
}
function Dc(n, e, t) {
  for (var s, i = n.__k, o = 0; i && o < i.length; o++)
    (s = i[o]) && (s.__ = n, e = typeof s.type == "function" ? Dc(s, e, t) : Ic(t, s.__e, e));
  return e;
}
function Ic(n, e, t) {
  return t == null || t.parentNode !== n ? n.insertBefore(e, null) : e == t && e.parentNode != null || n.insertBefore(e, t), e.nextSibling;
}
function ru(n, e, t, s) {
  var i = n.key, o = n.type, r = t - 1, a = t + 1, l = e[t];
  if (l === null || l && i == l.key && o === l.type)
    return t;
  if (s > (l != null ? 1 : 0))
    for (; r >= 0 || a < e.length; ) {
      if (r >= 0) {
        if ((l = e[r]) && i == l.key && o === l.type)
          return r;
        r--;
      }
      if (a < e.length) {
        if ((l = e[a]) && i == l.key && o === l.type)
          return a;
        a++;
      }
    }
  return -1;
}
function au(n, e, t, s, i) {
  var o;
  for (o in t)
    o === "children" || o === "key" || o in e || ni(n, o, null, t[o], s);
  for (o in e)
    i && typeof e[o] != "function" || o === "children" || o === "key" || o === "value" || o === "checked" || t[o] === e[o] || ni(n, o, e[o], t[o], s);
}
function Ha(n, e, t) {
  e[0] === "-" ? n.setProperty(e, t ?? "") : n[e] = t == null ? "" : typeof t != "number" || ou.test(e) ? t : t + "px";
}
function ni(n, e, t, s, i) {
  var o;
  t:
    if (e === "style")
      if (typeof t == "string")
        n.style.cssText = t;
      else {
        if (typeof s == "string" && (n.style.cssText = s = ""), s)
          for (e in s)
            t && e in t || Ha(n.style, e, "");
        if (t)
          for (e in t)
            s && t[e] === s[e] || Ha(n.style, e, t[e]);
      }
    else if (e[0] === "o" && e[1] === "n")
      o = e !== (e = e.replace(/Capture$/, "")), e = e.toLowerCase() in n ? e.toLowerCase().slice(2) : e.slice(2), n.l || (n.l = {}), n.l[e + o] = t, t ? s || n.addEventListener(e, o ? ja : Oa, o) : n.removeEventListener(e, o ? ja : Oa, o);
    else if (e !== "dangerouslySetInnerHTML") {
      if (i)
        e = e.replace(/xlink(H|:h)/, "h").replace(/sName$/, "s");
      else if (e !== "width" && e !== "height" && e !== "href" && e !== "list" && e !== "form" && e !== "tabIndex" && e !== "download" && e !== "rowSpan" && e !== "colSpan" && e in n)
        try {
          n[e] = t ?? "";
          break t;
        } catch {
        }
      typeof t == "function" || (t == null || t === !1 && e[4] !== "-" ? n.removeAttribute(e) : n.setAttribute(e, t));
    }
}
function Oa(n) {
  return this.l[n.type + !1](j.event ? j.event(n) : n);
}
function ja(n) {
  return this.l[n.type + !0](j.event ? j.event(n) : n);
}
function ea(n, e, t, s, i, o, r, a, l, c) {
  var d, h, f, m, p, y, _, w, x, T, k, E, D, L, R, A = e.type;
  if (e.constructor !== void 0)
    return null;
  t.__h != null && (l = t.__h, a = e.__e = t.__e, e.__h = null, o = [a]), (d = j.__b) && d(e);
  try {
    t:
      if (typeof A == "function") {
        if (w = e.props, x = (d = A.contextType) && s[d.__c], T = d ? x ? x.props.value : d.__ : s, t.__c ? _ = (h = e.__c = t.__c).__ = h.__E : ("prototype" in A && A.prototype.render ? e.__c = h = new A(w, T) : (e.__c = h = new F(w, T), h.constructor = A, h.render = cu), x && x.sub(h), h.props = w, h.state || (h.state = {}), h.context = T, h.__n = s, f = h.__d = !0, h.__h = [], h._sb = []), h.__s == null && (h.__s = h.state), A.getDerivedStateFromProps != null && (h.__s == h.state && (h.__s = ne({}, h.__s)), ne(h.__s, A.getDerivedStateFromProps(w, h.__s))), m = h.props, p = h.state, h.__v = e, f)
          A.getDerivedStateFromProps == null && h.componentWillMount != null && h.componentWillMount(), h.componentDidMount != null && h.__h.push(h.componentDidMount);
        else {
          if (A.getDerivedStateFromProps == null && w !== m && h.componentWillReceiveProps != null && h.componentWillReceiveProps(w, T), !h.__e && (h.shouldComponentUpdate != null && h.shouldComponentUpdate(w, h.__s, T) === !1 || e.__v === t.__v)) {
            for (e.__v !== t.__v && (h.props = w, h.state = h.__s, h.__d = !1), e.__e = t.__e, e.__k = t.__k, e.__k.forEach(function(P) {
              P && (P.__ = e);
            }), k = 0; k < h._sb.length; k++)
              h.__h.push(h._sb[k]);
            h._sb = [], h.__h.length && r.push(h);
            break t;
          }
          h.componentWillUpdate != null && h.componentWillUpdate(w, h.__s, T), h.componentDidUpdate != null && h.__h.push(function() {
            h.componentDidUpdate(m, p, y);
          });
        }
        if (h.context = T, h.props = w, h.__P = n, h.__e = !1, E = j.__r, D = 0, "prototype" in A && A.prototype.render) {
          for (h.state = h.__s, h.__d = !1, E && E(e), d = h.render(h.props, h.state, h.context), L = 0; L < h._sb.length; L++)
            h.__h.push(h._sb[L]);
          h._sb = [];
        } else
          do
            h.__d = !1, E && E(e), d = h.render(h.props, h.state, h.context), h.state = h.__s;
          while (h.__d && ++D < 25);
        h.state = h.__s, h.getChildContext != null && (s = ne(ne({}, s), h.getChildContext())), f || h.getSnapshotBeforeUpdate == null || (y = h.getSnapshotBeforeUpdate(m, p)), Mc(n, ta(R = d != null && d.type === oe && d.key == null ? d.props.children : d) ? R : [R], e, t, s, i, o, r, a, l, c), h.base = e.__e, e.__h = null, h.__h.length && r.push(h), _ && (h.__E = h.__ = null);
      } else
        o == null && e.__v === t.__v ? (e.__k = t.__k, e.__e = t.__e) : e.__e = lu(t.__e, e, t, s, i, o, r, l, c);
    (d = j.diffed) && d(e);
  } catch (P) {
    e.__v = null, (l || o != null) && (e.__e = a, e.__h = !!l, o[o.indexOf(a)] = null), j.__e(P, e, t);
  }
}
function Rc(n, e, t) {
  for (var s = 0; s < t.length; s++)
    sa(t[s], t[++s], t[++s]);
  j.__c && j.__c(e, n), n.some(function(i) {
    try {
      n = i.__h, i.__h = [], n.some(function(o) {
        o.call(i);
      });
    } catch (o) {
      j.__e(o, i.__v);
    }
  });
}
function lu(n, e, t, s, i, o, r, a, l) {
  var c, d, h, f = t.props, m = e.props, p = e.type, y = 0;
  if (p === "svg" && (i = !0), o != null) {
    for (; y < o.length; y++)
      if ((c = o[y]) && "setAttribute" in c == !!p && (p ? c.localName === p : c.nodeType === 3)) {
        n = c, o[y] = null;
        break;
      }
  }
  if (n == null) {
    if (p === null)
      return document.createTextNode(m);
    n = i ? document.createElementNS("http://www.w3.org/2000/svg", p) : document.createElement(p, m.is && m), o = null, a = !1;
  }
  if (p === null)
    f === m || a && n.data === m || (n.data = m);
  else {
    if (o = o && Oo.call(n.childNodes), d = (f = t.props || ze).dangerouslySetInnerHTML, h = m.dangerouslySetInnerHTML, !a) {
      if (o != null)
        for (f = {}, y = 0; y < n.attributes.length; y++)
          f[n.attributes[y].name] = n.attributes[y].value;
      (h || d) && (h && (d && h.__html == d.__html || h.__html === n.innerHTML) || (n.innerHTML = h && h.__html || ""));
    }
    if (au(n, m, f, i, a), h)
      e.__k = [];
    else if (Mc(n, ta(y = e.props.children) ? y : [y], e, t, s, i && p !== "foreignObject", o, r, o ? o[0] : t.__k && ei(t, 0), a, l), o != null)
      for (y = o.length; y--; )
        o[y] != null && Ec(o[y]);
    a || ("value" in m && (y = m.value) !== void 0 && (y !== n.value || p === "progress" && !y || p === "option" && y !== f.value) && ni(n, "value", y, f.value, !1), "checked" in m && (y = m.checked) !== void 0 && y !== n.checked && ni(n, "checked", y, f.checked, !1));
  }
  return n;
}
function sa(n, e, t) {
  try {
    typeof n == "function" ? n(e) : n.current = e;
  } catch (s) {
    j.__e(s, t);
  }
}
function Ac(n, e, t) {
  var s, i;
  if (j.unmount && j.unmount(n), (s = n.ref) && (s.current && s.current !== n.__e || sa(s, null, e)), (s = n.__c) != null) {
    if (s.componentWillUnmount)
      try {
        s.componentWillUnmount();
      } catch (o) {
        j.__e(o, e);
      }
    s.base = s.__P = null, n.__c = void 0;
  }
  if (s = n.__k)
    for (i = 0; i < s.length; i++)
      s[i] && Ac(s[i], e, t || typeof n.type != "function");
  t || n.__e == null || Ec(n.__e), n.__ = n.__e = n.__d = void 0;
}
function cu(n, e, t) {
  return this.constructor(n, t);
}
function Ts(n, e, t) {
  var s, i, o, r;
  j.__ && j.__(n, e), i = (s = typeof t == "function") ? null : t && t.__k || e.__k, o = [], r = [], ea(e, n = (!s && t || e).__k = v(oe, null, [n]), i || ze, ze, e.ownerSVGElement !== void 0, !s && t ? [t] : i ? null : e.firstChild ? Oo.call(e.childNodes) : null, o, !s && t ? t : i ? i.__e : e.firstChild, s, r), Rc(o, n, r);
}
Oo = Sc.slice, j = { __e: function(n, e, t, s) {
  for (var i, o, r; e = e.__; )
    if ((i = e.__c) && !i.__)
      try {
        if ((o = i.constructor) && o.getDerivedStateFromError != null && (i.setState(o.getDerivedStateFromError(n)), r = i.__d), i.componentDidCatch != null && (i.componentDidCatch(n, s || {}), r = i.__d), r)
          return i.__E = i;
      } catch (a) {
        n = a;
      }
  throw n;
} }, Tc = 0, st = function(n) {
  return n != null && n.constructor === void 0;
}, F.prototype.setState = function(n, e) {
  var t;
  t = this.__s != null && this.__s !== this.state ? this.__s : this.__s = ne({}, this.state), typeof n == "function" && (n = n(ne({}, t), this.props)), n && ne(t, n), n != null && this.__v && (e && this._sb.push(e), La(this));
}, F.prototype.forceUpdate = function(n) {
  this.__v && (this.__e = !0, n && this.__h.push(n), La(this));
}, F.prototype.render = oe, de = [], kc = typeof Promise == "function" ? Promise.prototype.then.bind(Promise.resolve()) : setTimeout, lr = function(n, e) {
  return n.__v.__b - e.__v.__b;
}, si.__r = 0;
var Pc = function(n, e, t, s) {
  var i;
  e[0] = 0;
  for (var o = 1; o < e.length; o++) {
    var r = e[o++], a = e[o] ? (e[0] |= r ? 1 : 2, t[e[o++]]) : e[++o];
    r === 3 ? s[0] = a : r === 4 ? s[1] = Object.assign(s[1] || {}, a) : r === 5 ? (s[1] = s[1] || {})[e[++o]] = a : r === 6 ? s[1][e[++o]] += a + "" : r ? (i = n.apply(a, Pc(n, a, t, ["", null])), s.push(i), a[0] ? e[0] |= 2 : (e[o - 2] = 0, e[o] = i)) : s.push(a);
  }
  return s;
}, Wa = /* @__PURE__ */ new Map();
function hu(n) {
  var e = Wa.get(this);
  return e || (e = /* @__PURE__ */ new Map(), Wa.set(this, e)), (e = Pc(this, e.get(n) || (e.set(n, e = function(t) {
    for (var s, i, o = 1, r = "", a = "", l = [0], c = function(f) {
      o === 1 && (f || (r = r.replace(/^\s*\n\s*|\s*\n\s*$/g, ""))) ? l.push(0, f, r) : o === 3 && (f || r) ? (l.push(3, f, r), o = 2) : o === 2 && r === "..." && f ? l.push(4, f, 0) : o === 2 && r && !f ? l.push(5, 0, !0, r) : o >= 5 && ((r || !f && o === 5) && (l.push(o, 0, r, i), o = 6), f && (l.push(o, f, 0, i), o = 6)), r = "";
    }, d = 0; d < t.length; d++) {
      d && (o === 1 && c(), c(d));
      for (var h = 0; h < t[d].length; h++)
        s = t[d][h], o === 1 ? s === "<" ? (c(), l = [l], o = 3) : r += s : o === 4 ? r === "--" && s === ">" ? (o = 1, r = "") : r = s + r[0] : a ? s === a ? a = "" : r += s : s === '"' || s === "'" ? a = s : s === ">" ? (c(), o = 1) : o && (s === "=" ? (o = 5, i = r, r = "") : s === "/" && (o < 5 || t[d][h + 1] === ">") ? (c(), o === 3 && (l = l[0]), o = l, (l = l[0]).push(2, 0, o), o = 0) : s === " " || s === "	" || s === `
` || s === "\r" ? (c(), o = 2) : r += s), o === 3 && r === "!--" && (o = 4, l = l[0]);
    }
    return c(), l;
  }(n)), e), arguments, [])).length > 1 ? e : e[0];
}
const Gp = hu.bind(v);
class na extends F {
  _getClassName(e) {
    return [e.className, e.class];
  }
  _getProps(e) {
    const { className: t, class: s, attrs: i, data: o, forwardRef: r, children: a, style: l, ...c } = e, d = Object.keys(c).reduce((h, f) => ((f === "dangerouslySetInnerHTML" || /^(on[A-Z]|data-)[a-zA-Z-]+/.test(f)) && (h[f] = c[f]), h), {});
    return { ref: r, class: N(this._getClassName(e)), style: l, ...d, ...i };
  }
  _getComponent(e) {
    return e.component || "div";
  }
  _getChildren(e) {
    return e.children;
  }
  _beforeRender(e) {
    return e;
  }
  render(e) {
    return e = this._beforeRender(e) || e, v(this._getComponent(e), this._getProps(e), this._getChildren(e));
  }
}
var du = 0;
function g(n, e, t, s, i, o) {
  var r, a, l = {};
  for (a in e)
    a == "ref" ? r = e[a] : l[a] = e[a];
  var c = { type: n, props: l, key: t, ref: r, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --du, __source: i, __self: o };
  if (typeof n == "function" && (r = n.defaultProps))
    for (a in r)
      l[a] === void 0 && (l[a] = r[a]);
  return j.vnode && j.vnode(c), c;
}
class Rn extends F {
  constructor() {
    super(...arguments), this._ref = q();
  }
  _runJS() {
    this.props.executeScript && u(this._ref.current).runJS();
  }
  componentDidMount() {
    this._runJS();
  }
  componentDidUpdate(e) {
    this.props.html !== e.html && this._runJS();
  }
  render(e) {
    const { executeScript: t, html: s, ...i } = e;
    return /* @__PURE__ */ g(na, { forwardRef: this._ref, dangerouslySetInnerHTML: { __html: s }, ...i });
  }
}
function uu(n) {
  const {
    tag: e,
    className: t,
    style: s,
    renders: i,
    generateArgs: o = [],
    generatorThis: r,
    generators: a,
    onGenerate: l,
    onRenderItem: c,
    ...d
  } = n, h = [t], f = { ...s }, m = [], p = [];
  return i.forEach((y) => {
    const _ = [];
    if (typeof y == "string" && a && a[y] && (y = a[y]), typeof y == "function")
      if (l)
        _.push(...l.call(r, y, m, ...o));
      else {
        const w = y.call(r, m, ...o);
        w && (Array.isArray(w) ? _.push(...w) : _.push(w));
      }
    else
      _.push(y);
    _.forEach((w) => {
      w != null && (typeof w == "object" && !st(w) && ("html" in w || "__html" in w || "className" in w || "style" in w || "attrs" in w || "children" in w) ? w.html ? m.push(
        /* @__PURE__ */ g("div", { className: N(w.className), style: w.style, dangerouslySetInnerHTML: { __html: w.html }, ...w.attrs ?? {} })
      ) : w.__html ? p.push(w.__html) : (w.style && Object.assign(f, w.style), w.className && h.push(w.className), w.children && m.push(w.children), w.attrs && Object.assign(d, w.attrs)) : m.push(w));
    });
  }), p.length && Object.assign(d, { dangerouslySetInnerHTML: { __html: p } }), [{
    className: N(h),
    style: f,
    ...d
  }, m];
}
function cr({
  tag: n = "div",
  ...e
}) {
  const [t, s] = uu(e);
  return v(n, t, ...s);
}
function Lc(n, e, t) {
  return typeof n == "function" ? n.call(e, ...t || []) : Array.isArray(n) ? n.map((s) => Lc(s, e, t)) : st(n) || n === null ? n : typeof n == "object" ? n.html ? /* @__PURE__ */ g(Rn, { ...n }) : /* @__PURE__ */ g(na, { ...n }) : n;
}
function he(n) {
  const { content: e, generatorThis: t, generatorArgs: s } = n, i = Lc(e, t, s);
  return i == null || typeof i == "boolean" ? null : st(i) ? i : /* @__PURE__ */ g(oe, { children: i });
}
const za = (n) => n.startsWith("icon-") ? n : `icon-${n}`;
function K(n) {
  const { icon: e, className: t, ...s } = n;
  if (!e)
    return null;
  if (st(e))
    return e;
  const i = ["icon", t];
  if (typeof e == "string")
    i.push(za(e));
  else if (typeof e == "object") {
    const { className: o, icon: r, ...a } = e;
    i.push(o, r ? za(r) : ""), Object.assign(s, a);
  }
  return /* @__PURE__ */ g("i", { className: N(i), ...s });
}
function fu(n) {
  return this.getChildContext = () => n.context, n.children;
}
function pu(n) {
  const e = this, t = n._container;
  e.componentWillUnmount = function() {
    Ts(null, e._temp), e._temp = null, e._container = null;
  }, e._container && e._container !== t && e.componentWillUnmount(), n._vnode ? (e._temp || (e._container = t, e._temp = {
    nodeType: 1,
    parentNode: t,
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
  }), Ts(
    v(fu, { context: e.context }, n._vnode),
    e._temp
  )) : e._temp && e.componentWillUnmount();
}
function mu(n, e) {
  const t = v(pu, { _vnode: n, _container: e });
  return t.containerInfo = e, t;
}
function Hc(n) {
  return n.parentNode === document ? !1 : n.parentNode ? Hc(n.parentNode) : !0;
}
const gi = class gi {
  /**
   * The component constructor.
   *
   * @param options The component initial options.
   */
  constructor(e, t) {
    this._inited = !1, this._autoDestory = 0;
    const { KEY: s, DATA_KEY: i, DEFAULT: o, MULTI_INSTANCE: r, NAME: a } = this.constructor;
    if (!a)
      throw new Error('[ZUI] The component must have a "NAME" static property.');
    const l = u(e);
    if (l.data(s) && !r)
      throw new Error("[ZUI] The component has been initialized on element.");
    const c = l[0], d = u.guid++;
    if (this._gid = d, this._element = c, l.on("DOMNodeRemovedFromDocument", () => {
      this._autoDestory && clearTimeout(this._autoDestory), this._autoDestory = window.setTimeout(() => {
        this._autoDestory = 0, Hc(c) && this.destroy();
      }, 100);
    }), this._options = { ...o, ...l.dataset() }, this.setOptions(t), this._key = this.options.key ?? `__${d}`, l.data(s, this).attr(i, `${d}`), r) {
      const h = `${s}:ALL`;
      let f = l.data(h);
      f || (f = /* @__PURE__ */ new Map(), l.data(h, f)), f.set(this._key, this);
    }
    this.init(), requestAnimationFrame(async () => {
      this._inited = !0, await this.afterInit(), this.emit("inited", this.options);
    });
  }
  /**
   * ZUI name
   */
  static get ZUI() {
    return this.NAME.replace(/(^[A-Z]+)/, (e) => e.toLowerCase());
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
    return `.zui.${this.ZUI}`;
  }
  static get DATA_KEY() {
    return `data-zui-${this.NAME}`;
  }
  get inited() {
    return this._inited;
  }
  /**
   * Get the component element.
   */
  get element() {
    return this._element;
  }
  get key() {
    return this._key;
  }
  /**
   * Get the component options.
   */
  get options() {
    return this._options;
  }
  /**
   * Get the component global id.
   */
  get gid() {
    return this._gid;
  }
  /**
   * Get the component element as a jQuery like object.
   */
  get $element() {
    return u(this.element);
  }
  /**
   * Get the component event emitter.
   */
  get $emitter() {
    return this.$element;
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
  render(e) {
    this.setOptions(e);
  }
  /**
   * Destroy the component.
   */
  destroy() {
    const { KEY: e, DATA_KEY: t, MULTI_INSTANCE: s } = this.constructor, { $element: i } = this;
    if (this.emit("destroyed"), i.off(this.namespace).removeData(e).attr(t, null), s) {
      const o = this.$element.data(`${e}:ALL`);
      if (o)
        if (o.delete(this._key), o.size === 0)
          this.$element.removeData(`${e}:ALL`);
        else {
          const r = o.values().next().value;
          i.data(e, r).attr(t, r.gid);
        }
    }
    this._options = void 0, this._element = void 0;
  }
  /**
   * Set the component options.
   *
   * @param options  The component options to set.
   * @returns The component options.
   */
  setOptions(e) {
    return e && u.extend(this._options, e), this._options;
  }
  /**
   * Emit a component event.
   * @param event  The event name.
   * @param args   The event arguments.
   */
  emit(e, ...t) {
    const s = u.Event(e);
    return s.__src = this, this.$emitter.trigger(s, [this, ...t]), s;
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  on(e, t, s) {
    const i = this;
    this.$element[s != null && s.once ? "one" : "on"](this._wrapEvent(e), function(o, r) {
      (!o.__src || o.__src === i) && t.call(this, o, r);
    });
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  one(e, t) {
    this.on(e, t, { once: !0 });
  }
  /**
   * Stop listening to a component event.
   * @param event     The event name.
   * @param callback  The event callback.
   */
  off(e) {
    this.$element.off(this._wrapEvent(e));
  }
  /**
   * Get the i18n text.
   *
   * @param key          The i18n key.
   * @param args         The i18n arguments or the default value.
   * @param defaultValue The default value if the key is not found.
   * @returns            The i18n text.
   */
  i18n(e, t, s) {
    return tt(this.options.i18n, e, t, s, this.options.lang, this.constructor.NAME) ?? tt(this.options.i18n, e, t, s, this.options.lang) ?? `{i18n:${e}}`;
  }
  /**
   * Get event namespace.
   * @returns Event namespace.
   */
  get namespace() {
    return `${this.constructor.NAMESPACE}.${this._key}`;
  }
  /**
   * Wrap event names with component namespace.
   *
   * @param names The event names.
   * @returns     The wrapped event names.
   */
  _wrapEvent(e) {
    return e.split(" ").map((t) => t.includes(".") ? t : `${t}${this.namespace}`).join(" ");
  }
  /**
   * Get the component instance of the given element.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        The component instance.
   */
  static get(e, t) {
    const s = u(e);
    if (this.MULTI_INSTANCE && t !== void 0) {
      const i = s.data(`${this.KEY}:ALL`);
      return i ? i.get(t) : void 0;
    }
    return s.data(this.KEY);
  }
  /**
   * Ensure the component instance of the given element.
   *
   * @param this      Current component constructor.
   * @param selector  The component element selector.
   * @param options   The component options.
   * @returns         The component instance.
   */
  static ensure(e, t) {
    const s = this.get(e, t == null ? void 0 : t.key);
    return s ? (t && s.setOptions(t), s) : new this(e, t);
  }
  /**
   * Get all component instances.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        All component instances.
   */
  static getAll(e) {
    const { MULTI_INSTANCE: t, DATA_KEY: s } = this, i = [];
    return u(e || document).find(`[${s}]`).each((o, r) => {
      if (t) {
        const l = u(r).data(`${this.KEY}:ALL`);
        if (l) {
          i.push(...l.values());
          return;
        }
      }
      const a = u(r).data(this.KEY);
      a && i.push(a);
    }), i;
  }
  /**
   * Query the component instance.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        The component instance.
   */
  static query(e, t) {
    return e === void 0 ? this.getAll().sort((s, i) => s.gid - i.gid)[0] : this.get(u(e).closest(`[${this.DATA_KEY}]`), t);
  }
  /**
   * Create cash fn.method for current component.
   *
   * @param name The method name.
   */
  static defineFn(e) {
    let t = e || this.ZUI;
    u.fn[t] && (t = `zui${this.NAME}`);
    const s = this;
    u.fn.extend({
      [t](i, ...o) {
        const r = typeof i == "object" ? i : void 0, a = typeof i == "string" ? i : void 0;
        let l;
        return this.each((c, d) => {
          let h = s.get(d);
          if (h ? r && h.render(r) : h = new s(d, r), a) {
            let f = h[a], m = h;
            f === void 0 && (m = h.$, f = m[a]), typeof f == "function" ? l = f.call(m, ...o) : l = f;
          }
        }), l !== void 0 ? l : this;
      }
    });
  }
};
gi.DEFAULT = {}, gi.MULTI_INSTANCE = !1;
let J = gi;
class B extends J {
  constructor() {
    super(...arguments), this.ref = q();
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
    var e, t;
    (t = (e = this.$) == null ? void 0 : e.componentWillUnmount) == null || t.call(e), this.element && (this.element.innerHTML = ""), super.destroy();
  }
  /**
   * Render component.
   *
   * @param options new options.
   */
  render(e) {
    Ts(
      v(this.constructor.Component, {
        ref: this.ref,
        ...this.setOptions(e)
      }),
      this.element
    );
  }
}
function gu({
  component: n = "div",
  className: e,
  children: t,
  style: s,
  attrs: i
}) {
  return v(n, {
    className: N(e),
    style: s,
    ...i
  }, t);
}
function Oc({
  type: n,
  component: e = "a",
  className: t,
  children: s,
  content: i,
  attrs: o,
  url: r,
  disabled: a,
  active: l,
  icon: c,
  text: d,
  target: h,
  trailingIcon: f,
  hint: m,
  checked: p,
  onClick: y,
  data: _,
  ...w
}) {
  const x = [
    typeof p == "boolean" ? /* @__PURE__ */ g("div", { class: `checkbox-primary${p ? " checked" : ""}`, children: /* @__PURE__ */ g("label", {}) }) : null,
    /* @__PURE__ */ g(K, { icon: c }),
    d ? /* @__PURE__ */ g("span", { className: "text", children: d }) : null,
    /* @__PURE__ */ g(he, { content: i }),
    s,
    /* @__PURE__ */ g(K, { icon: f })
  ];
  return v(e, {
    className: N(t, { disabled: a, active: l }),
    title: m,
    [e === "a" ? "href" : "data-url"]: a ? void 0 : r,
    [e === "a" ? "target" : "data-target"]: a ? void 0 : h,
    onClick: y,
    ...w,
    ...o
  }, ...x);
}
function yu({
  component: n = "div",
  className: e,
  text: t,
  attrs: s,
  children: i,
  content: o,
  style: r,
  onClick: a
}) {
  return v(n, {
    className: N(e),
    style: r,
    onClick: a,
    ...s
  }, t, /* @__PURE__ */ g(he, { content: o }), i);
}
function bu({
  component: n = "div",
  className: e,
  style: t,
  space: s,
  flex: i,
  attrs: o,
  onClick: r,
  children: a
}) {
  return v(n, {
    className: N(e),
    style: { width: s, height: s, flex: i, ...t },
    onClick: r,
    ...o
  }, a);
}
function wu({ type: n, ...e }) {
  return /* @__PURE__ */ g(cr, { ...e });
}
function jc({
  component: n = "div",
  className: e,
  children: t,
  content: s,
  style: i,
  attrs: o
}) {
  return v(n, {
    className: N(e),
    style: i,
    ...o
  }, /* @__PURE__ */ g(he, { content: s }), t);
}
var Ot;
let jo = (Ot = class extends F {
  constructor() {
    super(...arguments), this.ref = q();
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
    var e, t;
    (t = (e = this.props).beforeDestroy) == null || t.call(e, { menu: this });
  }
  afterRender(e) {
    var t, s;
    (s = (t = this.props).afterRender) == null || s.call(t, { menu: this, firstRender: e });
  }
  handleItemClick(e, t, s, i) {
    s && s.call(i.target, i, e, t);
    const { onClickItem: o } = this.props;
    o && o({ menu: this, item: e, index: t, event: i });
  }
  beforeRender() {
    var s;
    const e = { ...this.props };
    typeof e.items == "function" && (e.items = e.items(this)), e.items || (e.items = []);
    const t = (s = e.beforeRender) == null ? void 0 : s.call(e, { menu: this, options: e });
    return t && Object.assign(e, t), e;
  }
  getItemRenderProps(e, t, s) {
    const { commonItemProps: i, onClickItem: o, itemRenderProps: r } = e;
    let a = { ...t };
    return i && Object.assign(a, i[t.type || "item"]), (o || t.onClick) && (a.onClick = this.handleItemClick.bind(this, a, s, t.onClick)), a.className = N(a.className), r && (a = r(a)), a;
  }
  renderItem(e, t, s) {
    if (!t)
      return null;
    const i = this.getItemRenderProps(e, t, s), { itemRender: o } = e;
    if (o) {
      if (typeof o == "object") {
        const y = o[t.type || "item"];
        if (y)
          return /* @__PURE__ */ g(y, { ...i });
      } else if (typeof o == "function") {
        const y = o.call(this, i, v);
        if (st(y))
          return y;
        typeof y == "object" && Object.assign(i, y);
      }
    }
    const { type: r = "item", component: a, key: l = s, rootAttrs: c, rootClass: d, rootStyle: h, rootChildren: f, ...m } = i;
    if (r === "html")
      return /* @__PURE__ */ g(
        "li",
        {
          className: N("action-menu-item", `${this.name}-html`, d, m.className),
          ...c,
          style: h || m.style,
          dangerouslySetInnerHTML: { __html: m.html }
        },
        l
      );
    const p = !a || typeof a == "string" ? this.constructor.ItemComponents && this.constructor.ItemComponents[r] || Ot.ItemComponents[r] : a;
    return Object.assign(m, {
      type: r,
      component: typeof a == "string" ? a : void 0
    }), e.checkbox && r === "item" && m.checked === void 0 && (m.checked = !!m.active), this.renderTypedItem(p, {
      className: N(d),
      children: f,
      style: h,
      key: l,
      ...c
    }, {
      ...m,
      type: r,
      component: typeof a == "string" ? a : void 0
    });
  }
  renderTypedItem(e, t, s) {
    const { children: i, className: o, key: r, ...a } = t;
    return /* @__PURE__ */ g(
      "li",
      {
        className: N(`${this.constructor.NAME}-item`, `${this.name}-${s.type}`, o),
        ...a,
        children: [
          /* @__PURE__ */ g(e, { ...s }),
          typeof i == "function" ? i() : i
        ]
      },
      r
    );
  }
  render() {
    const e = this.beforeRender(), {
      name: t,
      style: s,
      commonItemProps: i,
      className: o,
      items: r,
      children: a,
      itemRender: l,
      onClickItem: c,
      beforeRender: d,
      afterRender: h,
      beforeDestroy: f,
      compact: m,
      ...p
    } = e, y = this.constructor.ROOT_TAG;
    return /* @__PURE__ */ g(y, { class: N(this.name, o, m ? "compact" : ""), style: s, ...p, ref: this.ref, children: [
      r && r.map(this.renderItem.bind(this, e)),
      a
    ] });
  }
}, Ot.ItemComponents = {
  divider: gu,
  item: Oc,
  heading: yu,
  space: bu,
  custom: wu,
  basic: jc
}, Ot.ROOT_TAG = "menu", Ot.NAME = "action-menu", Ot);
const yi = class yi extends B {
};
yi.NAME = "ActionMenu", yi.Component = jo;
let Fa = yi;
function vu({
  items: n,
  show: e,
  level: t,
  ...s
}) {
  return /* @__PURE__ */ g(Oc, { ...s });
}
var Ms, yt, Ve, Ds;
let ia = (Ds = class extends jo {
  constructor(t) {
    super(t);
    $(this, Ms, /* @__PURE__ */ new Set());
    $(this, yt, void 0);
    $(this, Ve, (t, s, i) => {
      u(i.target).closest(".not-nested-toggle").length || (this.toggle(t, s), i.preventDefault());
    });
    C(this, yt, t.nestedShow === void 0), b(this, yt) && (this.state = { nestedShow: t.defaultNestedShow ?? {} });
  }
  get nestedTrigger() {
    return this.props.nestedTrigger;
  }
  beforeRender() {
    const t = super.beforeRender(), { nestedShow: s, nestedTrigger: i, defaultNestedShow: o, controlledMenu: r, indent: a, ...l } = t;
    return typeof l.items == "function" && (l.items = l.items(this)), l.items || (l.items = []), l.items.some((c) => c.items) || (l.className = N(l.className, "no-nested-items")), !r && a && (l.style = Object.assign({
      [`--${this.name}-indent`]: `${a}px`
    }, l.style)), l;
  }
  getNestedMenuProps(t) {
    const { name: s, controlledMenu: i, nestedShow: o, beforeDestroy: r, beforeRender: a, itemRender: l, onClickItem: c, afterRender: d, commonItemProps: h, level: f, itemRenderProps: m } = this.props;
    return {
      items: t,
      name: s,
      nestedShow: b(this, yt) ? this.state.nestedShow : o,
      nestedTrigger: this.nestedTrigger,
      controlledMenu: i || this,
      commonItemProps: h,
      onClickItem: c,
      afterRender: d,
      beforeRender: a,
      beforeDestroy: r,
      itemRender: l,
      itemRenderProps: m,
      level: (f || 0) + 1
    };
  }
  renderNestedMenu(t) {
    let { items: s } = t;
    if (!s || (typeof s == "function" && (s = s(t, this)), !s.length))
      return;
    const i = this.constructor, o = this.getNestedMenuProps(s);
    return /* @__PURE__ */ g(i, { ...o, "data-level": o.level });
  }
  isNestedItem(t) {
    return (!t.type || t.type === "item") && !!t.items;
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  renderToggleIcon(t, s) {
  }
  getItemRenderProps(t, s, i) {
    const o = super.getItemRenderProps(t, s, i);
    if (o.level = t.level || 0, !this.isNestedItem(o))
      return o;
    const r = o.key ?? o.id ?? `${t.level || 0}:${i}`;
    b(this, Ms).add(r);
    const a = this.isExpanded(r);
    if (a && (o.rootChildren = [
      o.rootChildren,
      this.renderNestedMenu(s)
    ]), this.nestedTrigger === "hover")
      o.rootAttrs = {
        ...o.rootAttrs,
        onMouseEnter: b(this, Ve).bind(this, r, !0),
        onMouseLeave: b(this, Ve).bind(this, r, !1)
      };
    else if (this.nestedTrigger === "click") {
      const { onClick: c } = o;
      o.onClick = (d) => {
        b(this, Ve).call(this, r, void 0, d), c == null || c(d);
      };
    }
    const l = this.renderToggleIcon(a, o);
    return l && (o.children = [o.children, l]), o.show = a, o.rootClass = [o.rootClass, "has-nested-menu", a ? "show" : ""], o;
  }
  isExpanded(t) {
    const s = b(this, yt) ? this.state.nestedShow : this.props.nestedShow;
    return s && typeof s == "object" ? s[t] : !!s;
  }
  toggle(t, s) {
    const { controlledMenu: i } = this.props;
    if (i)
      return i.toggle(t, s);
    if (!b(this, yt))
      return !1;
    let { nestedShow: o = {} } = this.state;
    if (typeof o == "boolean" && (o === !0 ? o = [...b(this, Ms).values()].reduce((r, a) => (r[a] = !0, r), {}) : o = {}), s === void 0)
      s = !o[t];
    else if (!!o[t] == !!s)
      return !1;
    return s ? o[t] = s : delete o[t], this.setState({ nestedShow: { ...o } }), !0;
  }
  expand(t) {
    return this.toggle(t, !0);
  }
  collapse(t) {
    return this.toggle(t, !1);
  }
  expandAll() {
    b(this, yt) && this.setState({ nestedShow: !0 });
  }
  collapseAll() {
    b(this, yt) && this.setState({ nestedShow: !1 });
  }
}, Ms = new WeakMap(), yt = new WeakMap(), Ve = new WeakMap(), Ds.ItemComponents = {
  item: vu
}, Ds);
const bi = class bi extends B {
};
bi.NAME = "ActionMenuNested", bi.Component = ia;
let Ba = bi;
var Is;
let Ie = (Is = class extends ia {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "menu-nested";
  }
  beforeRender() {
    const e = super.beforeRender();
    let { hasIcons: t } = e;
    return t === void 0 && (t = e.items.some((s) => s.icon)), e.className = N(e.className, this.menuName, {
      "has-icons": t,
      "has-nested-items": e.items.some((s) => this.isNestedItem(s)),
      popup: e.popup
    }), e;
  }
  renderToggleIcon(e) {
    return /* @__PURE__ */ g("span", { class: `${this.name}-toggle-icon caret-${e ? "down" : "right"}` });
  }
}, Is.NAME = "menu", Is);
const wi = class wi extends B {
};
wi.NAME = "Menu", wi.Component = Ie;
let Va = wi;
class et extends na {
  _beforeRender(e) {
    const { text: t, loading: s, loadingText: i, caret: o, icon: r, trailingIcon: a, children: l } = e;
    this._isEmptyText = t == null || typeof t == "string" && !t.length || s && !i, this._onlyCaret = o && this._isEmptyText && !r && !a && !l && !s;
  }
  _getChildren(e) {
    const { loading: t, loadingIcon: s, loadingText: i, icon: o, text: r, children: a, trailingIcon: l, caret: c } = e;
    return [
      t ? /* @__PURE__ */ g(K, { icon: s || "icon-spinner-snake", className: "spin" }) : /* @__PURE__ */ g(K, { icon: o }),
      this._isEmptyText ? null : /* @__PURE__ */ g("span", { className: "text", children: t ? i : r }),
      t ? null : a,
      t ? null : /* @__PURE__ */ g(K, { icon: l }),
      t ? null : c ? /* @__PURE__ */ g("span", { className: typeof c == "string" ? `caret-${c}` : "caret" }) : null
    ];
  }
  _getClassName(e) {
    const { type: t, className: s, disabled: i, loading: o, active: r, children: a, square: l, size: c, rounded: d } = e;
    return N("btn", t, s, {
      "btn-caret": this._onlyCaret,
      disabled: i || o,
      active: r,
      loading: o,
      square: l === void 0 ? !this._onlyCaret && !a && this._isEmptyText : l
    }, c ? `size-${c}` : "", typeof d == "string" ? d : { rounded: d });
  }
  _getComponent(e) {
    return e.component || (e.url ? "a" : "button");
  }
  _getProps(e) {
    const t = this._getComponent(e), { url: s, target: i, disabled: o, btnType: r = "button", hint: a } = e, l = {
      ...super._getProps(e),
      disabled: o,
      title: a,
      type: t === "button" ? r : void 0
    };
    return o || (s !== void 0 && (l[t === "a" ? "href" : "data-url"] = s), i !== void 0 && (l[t === "a" ? "target" : "data-target"] = i)), l;
  }
}
function _u({
  key: n,
  type: e,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ g(et, { type: t, ...s });
}
let xu = class extends F {
  render(e) {
    const {
      id: t,
      popup: s,
      title: i,
      content: o,
      style: r,
      className: a,
      closeBtn: l,
      arrow: c,
      headingClass: d,
      titleClass: h,
      contentClass: f,
      arrowStyle: m,
      onlyInner: p
    } = e;
    let y = /* @__PURE__ */ g(he, { content: o }, "content");
    (f || i) && (y = /* @__PURE__ */ g("div", { className: f, children: y }, "content"));
    const _ = [], w = l ? /* @__PURE__ */ g("button", { className: "btn ghost square size-sm btn-close", "data-dismiss": "popover", children: /* @__PURE__ */ g("span", { className: "close" }) }) : null;
    return i ? _.push(/* @__PURE__ */ g("div", { className: d, children: [
      i ? /* @__PURE__ */ g("div", { className: h, children: i }) : null,
      w
    ] }, "heading")) : _.push(w), _.push(y), c && _.push(/* @__PURE__ */ g("div", { className: typeof c == "string" ? c : "arrow", style: m }, "arrow")), p ? _ : /* @__PURE__ */ g("div", { id: t, className: N("popover", a, { popup: s }), style: r, children: _ });
  }
};
const vi = class vi extends B {
};
vi.NAME = "PopoverPanel", vi.Component = xu;
let hr = vi;
function An(n) {
  return n.split("-")[1];
}
function oa(n) {
  return n === "y" ? "height" : "width";
}
function ke(n) {
  return n.split("-")[0];
}
function Pn(n) {
  return ["top", "bottom"].includes(ke(n)) ? "x" : "y";
}
function Ua(n, e, t) {
  let { reference: s, floating: i } = n;
  const o = s.x + s.width / 2 - i.width / 2, r = s.y + s.height / 2 - i.height / 2, a = Pn(e), l = oa(a), c = s[l] / 2 - i[l] / 2, d = a === "x";
  let h;
  switch (ke(e)) {
    case "top":
      h = { x: o, y: s.y - i.height };
      break;
    case "bottom":
      h = { x: o, y: s.y + s.height };
      break;
    case "right":
      h = { x: s.x + s.width, y: r };
      break;
    case "left":
      h = { x: s.x - i.width, y: r };
      break;
    default:
      h = { x: s.x, y: s.y };
  }
  switch (An(e)) {
    case "start":
      h[a] -= c * (t && d ? -1 : 1);
      break;
    case "end":
      h[a] += c * (t && d ? -1 : 1);
  }
  return h;
}
const $u = async (n, e, t) => {
  const { placement: s = "bottom", strategy: i = "absolute", middleware: o = [], platform: r } = t, a = o.filter(Boolean), l = await (r.isRTL == null ? void 0 : r.isRTL(e));
  let c = await r.getElementRects({ reference: n, floating: e, strategy: i }), { x: d, y: h } = Ua(c, s, l), f = s, m = {}, p = 0;
  for (let y = 0; y < a.length; y++) {
    const { name: _, fn: w } = a[y], { x, y: T, data: k, reset: E } = await w({ x: d, y: h, initialPlacement: s, placement: f, strategy: i, middlewareData: m, rects: c, platform: r, elements: { reference: n, floating: e } });
    d = x ?? d, h = T ?? h, m = { ...m, [_]: { ...m[_], ...k } }, E && p <= 50 && (p++, typeof E == "object" && (E.placement && (f = E.placement), E.rects && (c = E.rects === !0 ? await r.getElementRects({ reference: n, floating: e, strategy: i }) : E.rects), { x: d, y: h } = Ua(c, f, l)), y = -1);
  }
  return { x: d, y: h, placement: f, strategy: i, middlewareData: m };
};
function Ln(n, e) {
  return typeof n == "function" ? n(e) : n;
}
function Wc(n) {
  return typeof n != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(n) : { top: n, right: n, bottom: n, left: n };
}
function ii(n) {
  return { ...n, top: n.y, left: n.x, right: n.x + n.width, bottom: n.y + n.height };
}
async function zc(n, e) {
  var t;
  e === void 0 && (e = {});
  const { x: s, y: i, platform: o, rects: r, elements: a, strategy: l } = n, { boundary: c = "clippingAncestors", rootBoundary: d = "viewport", elementContext: h = "floating", altBoundary: f = !1, padding: m = 0 } = Ln(e, n), p = Wc(m), y = a[f ? h === "floating" ? "reference" : "floating" : h], _ = ii(await o.getClippingRect({ element: (t = await (o.isElement == null ? void 0 : o.isElement(y))) == null || t ? y : y.contextElement || await (o.getDocumentElement == null ? void 0 : o.getDocumentElement(a.floating)), boundary: c, rootBoundary: d, strategy: l })), w = h === "floating" ? { ...r.floating, x: s, y: i } : r.reference, x = await (o.getOffsetParent == null ? void 0 : o.getOffsetParent(a.floating)), T = await (o.isElement == null ? void 0 : o.isElement(x)) && await (o.getScale == null ? void 0 : o.getScale(x)) || { x: 1, y: 1 }, k = ii(o.convertOffsetParentRelativeRectToViewportRelativeRect ? await o.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: w, offsetParent: x, strategy: l }) : w);
  return { top: (_.top - k.top + p.top) / T.y, bottom: (k.bottom - _.bottom + p.bottom) / T.y, left: (_.left - k.left + p.left) / T.x, right: (k.right - _.right + p.right) / T.x };
}
const dr = Math.min, Cu = Math.max;
function ur(n, e, t) {
  return Cu(n, dr(e, t));
}
const fr = (n) => ({ name: "arrow", options: n, async fn(e) {
  const { x: t, y: s, placement: i, rects: o, platform: r, elements: a } = e, { element: l, padding: c = 0 } = Ln(n, e) || {};
  if (l == null)
    return {};
  const d = Wc(c), h = { x: t, y: s }, f = Pn(i), m = oa(f), p = await r.getDimensions(l), y = f === "y", _ = y ? "top" : "left", w = y ? "bottom" : "right", x = y ? "clientHeight" : "clientWidth", T = o.reference[m] + o.reference[f] - h[f] - o.floating[m], k = h[f] - o.reference[f], E = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(l));
  let D = E ? E[x] : 0;
  D && await (r.isElement == null ? void 0 : r.isElement(E)) || (D = a.floating[x] || o.floating[m]);
  const L = T / 2 - k / 2, R = D / 2 - p[m] / 2 - 1, A = dr(d[_], R), P = dr(d[w], R), H = A, M = D - p[m] - P, S = D / 2 - p[m] / 2 + L, W = ur(H, S, M), V = An(i) != null && S != W && o.reference[m] / 2 - (S < H ? A : P) - p[m] / 2 < 0 ? S < H ? H - S : M - S : 0;
  return { [f]: h[f] - V, data: { [f]: W, centerOffset: S - W + V } };
} }), Tu = ["top", "right", "bottom", "left"];
Tu.reduce((n, e) => n.concat(e, e + "-start", e + "-end"), []);
const ku = { left: "right", right: "left", bottom: "top", top: "bottom" };
function oi(n) {
  return n.replace(/left|right|bottom|top/g, (e) => ku[e]);
}
function Su(n, e, t) {
  t === void 0 && (t = !1);
  const s = An(n), i = Pn(n), o = oa(i);
  let r = i === "x" ? s === (t ? "end" : "start") ? "right" : "left" : s === "start" ? "bottom" : "top";
  return e.reference[o] > e.floating[o] && (r = oi(r)), { main: r, cross: oi(r) };
}
const Eu = { start: "end", end: "start" };
function Zo(n) {
  return n.replace(/start|end/g, (e) => Eu[e]);
}
const Wo = function(n) {
  return n === void 0 && (n = {}), { name: "flip", options: n, async fn(e) {
    var t;
    const { placement: s, middlewareData: i, rects: o, initialPlacement: r, platform: a, elements: l } = e, { mainAxis: c = !0, crossAxis: d = !0, fallbackPlacements: h, fallbackStrategy: f = "bestFit", fallbackAxisSideDirection: m = "none", flipAlignment: p = !0, ...y } = Ln(n, e), _ = ke(s), w = ke(r) === r, x = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), T = h || (w || !p ? [oi(r)] : function(H) {
      const M = oi(H);
      return [Zo(H), M, Zo(M)];
    }(r));
    h || m === "none" || T.push(...function(H, M, S, W) {
      const V = An(H);
      let X = function(dt, gs, ad) {
        const ka = ["left", "right"], Sa = ["right", "left"], ld = ["top", "bottom"], cd = ["bottom", "top"];
        switch (dt) {
          case "top":
          case "bottom":
            return ad ? gs ? Sa : ka : gs ? ka : Sa;
          case "left":
          case "right":
            return gs ? ld : cd;
          default:
            return [];
        }
      }(ke(H), S === "start", W);
      return V && (X = X.map((dt) => dt + "-" + V), M && (X = X.concat(X.map(Zo)))), X;
    }(r, p, m, x));
    const k = [r, ...T], E = await zc(e, y), D = [];
    let L = ((t = i.flip) == null ? void 0 : t.overflows) || [];
    if (c && D.push(E[_]), d) {
      const { main: H, cross: M } = Su(s, o, x);
      D.push(E[H], E[M]);
    }
    if (L = [...L, { placement: s, overflows: D }], !D.every((H) => H <= 0)) {
      var R, A;
      const H = (((R = i.flip) == null ? void 0 : R.index) || 0) + 1, M = k[H];
      if (M)
        return { data: { index: H, overflows: L }, reset: { placement: M } };
      let S = (A = L.filter((W) => W.overflows[0] <= 0).sort((W, V) => W.overflows[1] - V.overflows[1])[0]) == null ? void 0 : A.placement;
      if (!S)
        switch (f) {
          case "bestFit": {
            var P;
            const W = (P = L.map((V) => [V.placement, V.overflows.filter((X) => X > 0).reduce((X, dt) => X + dt, 0)]).sort((V, X) => V[1] - X[1])[0]) == null ? void 0 : P[0];
            W && (S = W);
            break;
          }
          case "initialPlacement":
            S = r;
        }
      if (s !== S)
        return { reset: { placement: S } };
    }
    return {};
  } };
}, zo = function(n) {
  return n === void 0 && (n = 0), { name: "offset", options: n, async fn(e) {
    const { x: t, y: s } = e, i = await async function(o, r) {
      const { placement: a, platform: l, elements: c } = o, d = await (l.isRTL == null ? void 0 : l.isRTL(c.floating)), h = ke(a), f = An(a), m = Pn(a) === "x", p = ["left", "top"].includes(h) ? -1 : 1, y = d && m ? -1 : 1, _ = Ln(r, o);
      let { mainAxis: w, crossAxis: x, alignmentAxis: T } = typeof _ == "number" ? { mainAxis: _, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ..._ };
      return f && typeof T == "number" && (x = f === "end" ? -1 * T : T), m ? { x: x * y, y: w * p } : { x: w * p, y: x * y };
    }(e, n);
    return { x: t + i.x, y: s + i.y, data: i };
  } };
};
function Nu(n) {
  return n === "x" ? "y" : "x";
}
const ks = function(n) {
  return n === void 0 && (n = {}), { name: "shift", options: n, async fn(e) {
    const { x: t, y: s, placement: i } = e, { mainAxis: o = !0, crossAxis: r = !1, limiter: a = { fn: (_) => {
      let { x: w, y: x } = _;
      return { x: w, y: x };
    } }, ...l } = Ln(n, e), c = { x: t, y: s }, d = await zc(e, l), h = Pn(ke(i)), f = Nu(h);
    let m = c[h], p = c[f];
    if (o) {
      const _ = h === "y" ? "bottom" : "right";
      m = ur(m + d[h === "y" ? "top" : "left"], m, m - d[_]);
    }
    if (r) {
      const _ = f === "y" ? "bottom" : "right";
      p = ur(p + d[f === "y" ? "top" : "left"], p, p - d[_]);
    }
    const y = a.fn({ ...e, [h]: m, [f]: p });
    return { ...y, data: { x: y.x - t, y: y.y - s } };
  } };
};
function ct(n) {
  var e;
  return (n == null || (e = n.ownerDocument) == null ? void 0 : e.defaultView) || window;
}
function Mt(n) {
  return ct(n).getComputedStyle(n);
}
function Fc(n) {
  return n instanceof ct(n).Node;
}
function re(n) {
  return Fc(n) ? (n.nodeName || "").toLowerCase() : "#document";
}
function mt(n) {
  return n instanceof HTMLElement || n instanceof ct(n).HTMLElement;
}
function qa(n) {
  return typeof ShadowRoot < "u" && (n instanceof ct(n).ShadowRoot || n instanceof ShadowRoot);
}
function Ss(n) {
  const { overflow: e, overflowX: t, overflowY: s, display: i } = Mt(n);
  return /auto|scroll|overlay|hidden|clip/.test(e + s + t) && !["inline", "contents"].includes(i);
}
function Mu(n) {
  return ["table", "td", "th"].includes(re(n));
}
function pr(n) {
  const e = ra(), t = Mt(n);
  return t.transform !== "none" || t.perspective !== "none" || !!t.containerType && t.containerType !== "normal" || !e && !!t.backdropFilter && t.backdropFilter !== "none" || !e && !!t.filter && t.filter !== "none" || ["transform", "perspective", "filter"].some((s) => (t.willChange || "").includes(s)) || ["paint", "layout", "strict", "content"].some((s) => (t.contain || "").includes(s));
}
function ra() {
  return !(typeof CSS > "u" || !CSS.supports) && CSS.supports("-webkit-backdrop-filter", "none");
}
function Fo(n) {
  return ["html", "body", "#document"].includes(re(n));
}
const mr = Math.min, Fe = Math.max, ri = Math.round, On = Math.floor, ae = (n) => ({ x: n, y: n });
function Bc(n) {
  const e = Mt(n);
  let t = parseFloat(e.width) || 0, s = parseFloat(e.height) || 0;
  const i = mt(n), o = i ? n.offsetWidth : t, r = i ? n.offsetHeight : s, a = ri(t) !== o || ri(s) !== r;
  return a && (t = o, s = r), { width: t, height: s, $: a };
}
function jt(n) {
  return n instanceof Element || n instanceof ct(n).Element;
}
function aa(n) {
  return jt(n) ? n : n.contextElement;
}
function Be(n) {
  const e = aa(n);
  if (!mt(e))
    return ae(1);
  const t = e.getBoundingClientRect(), { width: s, height: i, $: o } = Bc(e);
  let r = (o ? ri(t.width) : t.width) / s, a = (o ? ri(t.height) : t.height) / i;
  return r && Number.isFinite(r) || (r = 1), a && Number.isFinite(a) || (a = 1), { x: r, y: a };
}
const Du = ae(0);
function Vc(n) {
  const e = ct(n);
  return ra() && e.visualViewport ? { x: e.visualViewport.offsetLeft, y: e.visualViewport.offsetTop } : Du;
}
function Re(n, e, t, s) {
  e === void 0 && (e = !1), t === void 0 && (t = !1);
  const i = n.getBoundingClientRect(), o = aa(n);
  let r = ae(1);
  e && (s ? jt(s) && (r = Be(s)) : r = Be(n));
  const a = function(f, m, p) {
    return m === void 0 && (m = !1), !(!p || m && p !== ct(f)) && m;
  }(o, t, s) ? Vc(o) : ae(0);
  let l = (i.left + a.x) / r.x, c = (i.top + a.y) / r.y, d = i.width / r.x, h = i.height / r.y;
  if (o) {
    const f = ct(o), m = s && jt(s) ? ct(s) : s;
    let p = f.frameElement;
    for (; p && s && m !== f; ) {
      const y = Be(p), _ = p.getBoundingClientRect(), w = getComputedStyle(p), x = _.left + (p.clientLeft + parseFloat(w.paddingLeft)) * y.x, T = _.top + (p.clientTop + parseFloat(w.paddingTop)) * y.y;
      l *= y.x, c *= y.y, d *= y.x, h *= y.y, l += x, c += T, p = ct(p).frameElement;
    }
  }
  return ii({ width: d, height: h, x: l, y: c });
}
function Bo(n) {
  return jt(n) ? { scrollLeft: n.scrollLeft, scrollTop: n.scrollTop } : { scrollLeft: n.pageXOffset, scrollTop: n.pageYOffset };
}
function Wt(n) {
  var e;
  return (e = (Fc(n) ? n.ownerDocument : n.document) || window.document) == null ? void 0 : e.documentElement;
}
function Uc(n) {
  return Re(Wt(n)).left + Bo(n).scrollLeft;
}
function ds(n) {
  if (re(n) === "html")
    return n;
  const e = n.assignedSlot || n.parentNode || qa(n) && n.host || Wt(n);
  return qa(e) ? e.host : e;
}
function qc(n) {
  const e = ds(n);
  return Fo(e) ? n.ownerDocument ? n.ownerDocument.body : n.body : mt(e) && Ss(e) ? e : qc(e);
}
function ai(n, e) {
  var t;
  e === void 0 && (e = []);
  const s = qc(n), i = s === ((t = n.ownerDocument) == null ? void 0 : t.body), o = ct(s);
  return i ? e.concat(o, o.visualViewport || [], Ss(s) ? s : []) : e.concat(s, ai(s));
}
function Ga(n, e, t) {
  let s;
  if (e === "viewport")
    s = function(i, o) {
      const r = ct(i), a = Wt(i), l = r.visualViewport;
      let c = a.clientWidth, d = a.clientHeight, h = 0, f = 0;
      if (l) {
        c = l.width, d = l.height;
        const m = ra();
        (!m || m && o === "fixed") && (h = l.offsetLeft, f = l.offsetTop);
      }
      return { width: c, height: d, x: h, y: f };
    }(n, t);
  else if (e === "document")
    s = function(i) {
      const o = Wt(i), r = Bo(i), a = i.ownerDocument.body, l = Fe(o.scrollWidth, o.clientWidth, a.scrollWidth, a.clientWidth), c = Fe(o.scrollHeight, o.clientHeight, a.scrollHeight, a.clientHeight);
      let d = -r.scrollLeft + Uc(i);
      const h = -r.scrollTop;
      return Mt(a).direction === "rtl" && (d += Fe(o.clientWidth, a.clientWidth) - l), { width: l, height: c, x: d, y: h };
    }(Wt(n));
  else if (jt(e))
    s = function(i, o) {
      const r = Re(i, !0, o === "fixed"), a = r.top + i.clientTop, l = r.left + i.clientLeft, c = mt(i) ? Be(i) : ae(1);
      return { width: i.clientWidth * c.x, height: i.clientHeight * c.y, x: l * c.x, y: a * c.y };
    }(e, t);
  else {
    const i = Vc(n);
    s = { ...e, x: e.x - i.x, y: e.y - i.y };
  }
  return ii(s);
}
function Gc(n, e) {
  const t = ds(n);
  return !(t === e || !jt(t) || Fo(t)) && (Mt(t).position === "fixed" || Gc(t, e));
}
function Iu(n, e, t) {
  const s = mt(e), i = Wt(e), o = t === "fixed", r = Re(n, !0, o, e);
  let a = { scrollLeft: 0, scrollTop: 0 };
  const l = ae(0);
  if (s || !s && !o)
    if ((re(e) !== "body" || Ss(i)) && (a = Bo(e)), mt(e)) {
      const c = Re(e, !0, o, e);
      l.x = c.x + e.clientLeft, l.y = c.y + e.clientTop;
    } else
      i && (l.x = Uc(i));
  return { x: r.left + a.scrollLeft - l.x, y: r.top + a.scrollTop - l.y, width: r.width, height: r.height };
}
function Ya(n, e) {
  return mt(n) && Mt(n).position !== "fixed" ? e ? e(n) : n.offsetParent : null;
}
function Xa(n, e) {
  const t = ct(n);
  if (!mt(n))
    return t;
  let s = Ya(n, e);
  for (; s && Mu(s) && Mt(s).position === "static"; )
    s = Ya(s, e);
  return s && (re(s) === "html" || re(s) === "body" && Mt(s).position === "static" && !pr(s)) ? t : s || function(i) {
    let o = ds(i);
    for (; mt(o) && !Fo(o); ) {
      if (pr(o))
        return o;
      o = ds(o);
    }
    return null;
  }(n) || t;
}
const Ru = { convertOffsetParentRelativeRectToViewportRelativeRect: function(n) {
  let { rect: e, offsetParent: t, strategy: s } = n;
  const i = mt(t), o = Wt(t);
  if (t === o)
    return e;
  let r = { scrollLeft: 0, scrollTop: 0 }, a = ae(1);
  const l = ae(0);
  if ((i || !i && s !== "fixed") && ((re(t) !== "body" || Ss(o)) && (r = Bo(t)), mt(t))) {
    const c = Re(t);
    a = Be(t), l.x = c.x + t.clientLeft, l.y = c.y + t.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - r.scrollLeft * a.x + l.x, y: e.y * a.y - r.scrollTop * a.y + l.y };
}, getDocumentElement: Wt, getClippingRect: function(n) {
  let { element: e, boundary: t, rootBoundary: s, strategy: i } = n;
  const o = [...t === "clippingAncestors" ? function(l, c) {
    const d = c.get(l);
    if (d)
      return d;
    let h = ai(l).filter((y) => jt(y) && re(y) !== "body"), f = null;
    const m = Mt(l).position === "fixed";
    let p = m ? ds(l) : l;
    for (; jt(p) && !Fo(p); ) {
      const y = Mt(p), _ = pr(p);
      _ || y.position !== "fixed" || (f = null), (m ? !_ && !f : !_ && y.position === "static" && f && ["absolute", "fixed"].includes(f.position) || Ss(p) && !_ && Gc(l, p)) ? h = h.filter((w) => w !== p) : f = y, p = ds(p);
    }
    return c.set(l, h), h;
  }(e, this._c) : [].concat(t), s], r = o[0], a = o.reduce((l, c) => {
    const d = Ga(e, c, i);
    return l.top = Fe(d.top, l.top), l.right = mr(d.right, l.right), l.bottom = mr(d.bottom, l.bottom), l.left = Fe(d.left, l.left), l;
  }, Ga(e, r, i));
  return { width: a.right - a.left, height: a.bottom - a.top, x: a.left, y: a.top };
}, getOffsetParent: Xa, getElementRects: async function(n) {
  let { reference: e, floating: t, strategy: s } = n;
  const i = this.getOffsetParent || Xa, o = this.getDimensions;
  return { reference: Iu(e, await i(t), s), floating: { x: 0, y: 0, ...await o(t) } };
}, getClientRects: function(n) {
  return Array.from(n.getClientRects());
}, getDimensions: function(n) {
  return Bc(n);
}, getScale: Be, isElement: jt, isRTL: function(n) {
  return getComputedStyle(n).direction === "rtl";
} };
function la(n, e, t, s) {
  s === void 0 && (s = {});
  const { ancestorScroll: i = !0, ancestorResize: o = !0, elementResize: r = typeof ResizeObserver == "function", layoutShift: a = typeof IntersectionObserver == "function", animationFrame: l = !1 } = s, c = aa(n), d = i || o ? [...c ? ai(c) : [], ...ai(e)] : [];
  d.forEach((_) => {
    i && _.addEventListener("scroll", t, { passive: !0 }), o && _.addEventListener("resize", t);
  });
  const h = c && a ? function(_, w) {
    let x, T = null;
    const k = Wt(_);
    function E() {
      clearTimeout(x), T && T.disconnect(), T = null;
    }
    return function D(L, R) {
      L === void 0 && (L = !1), R === void 0 && (R = 1), E();
      const { left: A, top: P, width: H, height: M } = _.getBoundingClientRect();
      if (L || w(), !H || !M)
        return;
      const S = { rootMargin: -On(P) + "px " + -On(k.clientWidth - (A + H)) + "px " + -On(k.clientHeight - (P + M)) + "px " + -On(A) + "px", threshold: Fe(0, mr(1, R)) || 1 };
      let W = !0;
      function V(X) {
        const dt = X[0].intersectionRatio;
        if (dt !== R) {
          if (!W)
            return D();
          dt ? D(!1, dt) : x = setTimeout(() => {
            D(!1, 1e-7);
          }, 100);
        }
        W = !1;
      }
      try {
        T = new IntersectionObserver(V, { ...S, root: k.ownerDocument });
      } catch {
        T = new IntersectionObserver(V, S);
      }
      T.observe(_);
    }(!0), E;
  }(c, t) : null;
  let f, m = -1, p = null;
  r && (p = new ResizeObserver((_) => {
    let [w] = _;
    w && w.target === c && p && (p.unobserve(e), cancelAnimationFrame(m), m = requestAnimationFrame(() => {
      p && p.observe(e);
    })), t();
  }), c && !l && p.observe(c), p.observe(e));
  let y = l ? Re(n) : null;
  return l && function _() {
    const w = Re(n);
    !y || w.x === y.x && w.y === y.y && w.width === y.width && w.height === y.height || t(), y = w, f = requestAnimationFrame(_);
  }(), t(), () => {
    d.forEach((_) => {
      i && _.removeEventListener("scroll", t), o && _.removeEventListener("resize", t);
    }), h && h(), p && p.disconnect(), p = null, l && cancelAnimationFrame(f);
  };
}
const Vo = (n, e, t) => {
  const s = /* @__PURE__ */ new Map(), i = { platform: Ru, ...t }, o = { ...i.platform, _c: s };
  return $u(n, e, { ...i, platform: o });
}, Au = '[data-toggle="popover"]', Ka = "show", Za = "in", je = class je extends J {
  constructor() {
    super(...arguments), this._getClickBounding = () => {
      const e = this._triggerEvent;
      return {
        x: e.clientX,
        y: e.clientY,
        left: e.clientX,
        top: e.clientY,
        width: 0,
        height: 0,
        bottom: e.clientY,
        right: e.clientX
      };
    }, this._onClickDoc = (e) => {
      const t = u(e.target);
      (!t.closest(`#${this._id}`).length && this._targetElement !== t.closest(".popover")[0] || t.closest('[data-dismiss="popover"]').length) && this.hide();
    };
  }
  get shown() {
    return this._shown;
  }
  get id() {
    return this._id;
  }
  afterInit() {
    const { trigger: e, id: t, triggerEvent: s } = this.options;
    this._triggerEvent = s, this._id = t || `popover_${this.gid}`;
    const i = this.getTriggerElement();
    if (i instanceof HTMLElement) {
      const r = u(i), { namespace: a } = this;
      e === "hover" ? r.on(`mouseenter${a}`, (l) => {
        this.show({ delay: !0, event: l });
      }).on(`mouseleave${a}`, () => {
        this.delayHide();
      }) : e && r.on(`${e}${a}`, (l) => {
        this.toggle({ event: l }), l.preventDefault();
      });
    }
    const { show: o } = this.options;
    o && this.show({ delay: typeof o == "number" ? o : !1 });
  }
  getTriggerElement() {
    if (!this._triggerElement) {
      let { element: e = this.element } = this.options;
      e === document.body && (e = {
        getBoundingClientRect: this._getClickBounding
      }), this._triggerElement = e, this._virtual = !(e instanceof HTMLElement);
    }
    return this._triggerElement;
  }
  initTarget() {
    let e = this.options.target;
    return this._dynamic = !e, e ? (typeof e == "function" && (e = e()), u(e)[0]) : this._createTarget();
  }
  show(e) {
    const { delay: t, event: s } = e || {};
    if (s && (this._triggerEvent = s), t)
      return this._resetTimer(() => {
        this.show();
      }, t === !0 ? this.options.delay : t);
    if (!this.inited) {
      this.setOptions({ show: !0 });
      return;
    }
    if (this._shown)
      return;
    const i = this.initTarget();
    if (!i)
      return;
    this._targetElement = i;
    const o = u(i), { animation: r, mask: a, onShow: l, onShown: c, trigger: d } = this.options;
    if (o.addClass(Ka), r && o.addClass(r === !0 ? "fade" : r), this._shown = !0, this.render(), l == null || l.call(this), this.emit("show"), d === "hover") {
      this._clearDelayHide();
      const { namespace: h } = this;
      o.on(`mouseenter${h}`, () => {
        this._clearDelayHide();
      }).on(`mouseleave${h}`, () => {
        this.delayHide();
      });
    }
    this._virtual || u(this._triggerElement).addClass("with-popover-show"), this._resetTimer(() => {
      o.addClass(Za), this._resetTimer(() => {
        c == null || c.call(this), this.emit("shown");
      }, 200), a && u(document).on(`click${this.namespace}`, this._onClickDoc);
    }, 50);
  }
  hide() {
    (!this._shown || !this._targetElement) && this._resetTimer();
    const { destroyOnHide: e, animation: t, onHide: s, onHidden: i, trigger: o } = this.options, r = u(this._targetElement);
    this._shown = !1, s == null || s.call(this), this.emit("hide"), r.removeClass(Za), o === "hover" && (this._clearDelayHide(), r.off(this.namespace)), this._virtual || u(this._triggerElement).removeClass("with-popover-show").removeAttr("data-popover-placement"), u(document).off(this.namespace), this._resetTimer(() => {
      i == null || i.call(this), this.emit("hidden"), r.removeClass(Ka), e && this._resetTimer(() => {
        this.destroy();
      }, typeof e == "number" ? e : 0), this._destoryTarget();
    }, t ? 200 : 0);
  }
  toggle(e) {
    this._shown ? this.hide() : this.show(e);
  }
  destroy() {
    if (super.destroy(), u(document).off(this.namespace), !this._virtual) {
      const { namespace: e } = this;
      u(this._triggerElement).off(e);
    }
    this._resetTimer(), this._destoryTarget(), this._clearDelayHide();
  }
  layout() {
    const e = this._triggerElement, t = this._targetElement, s = this._layoutWatcher;
    if (!t || !e || !this._shown) {
      s && (s(), this._layoutWatcher = void 0);
      return;
    }
    s || (this._layoutWatcher = la(e, t, () => {
      const { width: i, animation: o, name: r = "popover" } = this.options;
      i === "100%" && !this._virtual && u(t).css({ width: u(e).width() }), Vo(...this._getLayoutOptions()).then(({ x: a, y: l, middlewareData: c, placement: d, strategy: h }) => {
        const f = u(t).css({
          position: h,
          left: a,
          top: l
        }), m = d.split("-")[0], p = {
          top: "bottom",
          right: "left",
          bottom: "top",
          left: "right"
        }[m], y = c.arrow;
        y && f.find(".arrow").css({
          left: y.x,
          top: y.y
        }).attr("class", `arrow ${r}-arrow arrow-${p}`), o === !0 && f.attr("class", `${f.attr("class").split(" ").filter((_) => _ !== "fade" && !_.startsWith("fade-from")).join(" ")} fade-from-${p}`), this._virtual || u(this._triggerElement).attr("data-popover-placement", m);
      });
    }));
  }
  render(e) {
    super.render(e);
    const t = this._targetElement;
    if (!t)
      return;
    const s = this._getRenderOptions(), i = u(t);
    if (i.toggleClass("popup", s.popup).css(s.style), s.className && i.setClass(s.className), this._dynamic) {
      let o = this._panel;
      o && o.element !== t && (o.destroy(), o = void 0), o ? o.render(s) : (o = new hr(t, s), o.on("inited", () => this.layout())), this._panel = o;
    } else
      s.arrow && (i.find(".arrow").length || i.append(u('<div class="arrow"></div>').css(s.arrowStyle))), this.layout();
  }
  delayHide(e = 100) {
    this._hideTimer = window.setTimeout(() => {
      this._hideTimer = 0, this.hide();
    }, e);
  }
  _clearDelayHide() {
    this._hideTimer && (clearTimeout(this._hideTimer), this._hideTimer = 0);
  }
  _getLayoutOptions() {
    const e = this._triggerElement, t = this._targetElement, { placement: s, flip: i, shift: o, offset: r, arrow: a, strategy: l } = this.options, c = a ? t.querySelector(".arrow") : null, d = c ? typeof a == "number" ? a : 5 : 0;
    return [e, t, {
      placement: s,
      strategy: l,
      middleware: [
        i ? Wo() : null,
        o ? ks(typeof o == "object" ? o : void 0) : null,
        r || d ? zo((r || 0) + d) : null,
        a ? fr({ element: c }) : null
      ].filter(Boolean)
    }];
  }
  _getRenderOptions() {
    const { name: e = "popover" } = this.options, {
      popup: t,
      title: s,
      content: i,
      headingClass: o = `${e}-heading`,
      titleClass: r = `${e}-title`,
      contentClass: a = `${e}-content`,
      style: l,
      className: c = e,
      closeBtn: d,
      arrow: h
    } = this.options;
    return {
      popup: t,
      title: s,
      titleClass: r,
      headingClass: o,
      contentClass: a,
      content: i,
      style: { zIndex: this.constructor.Z_INDEX++, ...l },
      className: c,
      closeBtn: d,
      arrow: h ? `arrow ${e}-arrow` : !1,
      arrowStyle: { "--arrow-size": `${typeof h == "number" ? h : 5}px` },
      onlyInner: !0
    };
  }
  _destoryTarget() {
    var e, t, s;
    (e = this._layoutWatcher) == null || e.call(this), this._layoutWatcher = void 0, this._dynamic && ((t = this._panel) == null || t.destroy(), (s = this._targetElement) == null || s.remove(), this._panel = void 0, this._targetElement = void 0);
  }
  _resetTimer(e, t = 0) {
    this._timer && clearTimeout(this._timer), e && (this._timer = window.setTimeout(() => {
      this._timer = 0, e();
    }, t));
  }
  _createTarget() {
    const { container: e = "body" } = this.options, t = u(e);
    let s = t.find(`#${this._id}`);
    return s.length || (s = u("<div />").attr({ id: this._id, class: "popover" }).appendTo(t)), s[0];
  }
  static show(e) {
    const { element: t, event: s, ...i } = e, o = t || (s == null ? void 0 : s.currentTarget);
    return this.ensure(o instanceof HTMLElement ? o : document.body, { element: o, show: !0, destroyOnHide: !0, triggerEvent: s, ...i });
  }
};
je.NAME = "Popover", je.Z_INDEX = 1700, je.MULTI_INSTANCE = !0, je.DEFAULT = {
  placement: "top",
  strategy: "absolute",
  flip: !0,
  arrow: !0,
  offset: 1,
  trigger: "click",
  mask: !0,
  delay: 0,
  animation: !0,
  closeBtn: !0,
  popup: !0
};
let Et = je;
u(document).on(`click${Et.NAMESPACE} mouseenter${Et.NAMESPACE}`, Au, (n) => {
  const e = u(n.currentTarget);
  if (e.length && !e.data(Et.KEY)) {
    const t = e.data("trigger") || "click";
    if ((n.type === "mouseover" ? "hover" : "click") !== t)
      return;
    Et.ensure(e, { show: !0, triggerEvent: n }), n.preventDefault();
  }
});
const Pu = '[data-toggle="dropdown"]', _i = class _i extends Et {
  constructor() {
    super(...arguments), this._onClickDoc = (e) => {
      u(e.target).closest(".not-hide-menu").length || this.hide();
    };
  }
  _getMenuOptions() {
    let { items: e = [] } = this.options;
    return typeof e == "function" && (e = e(this)), {
      items: e,
      nestedTrigger: "hover",
      placement: this.options.placement,
      popup: !1,
      ...this.options.menu
    };
  }
  _getRenderOptions() {
    return {
      ...super._getRenderOptions(),
      contentClass: "",
      content: v(gr, this._getMenuOptions())
    };
  }
};
_i.NAME = "Dropdown", _i.DEFAULT = {
  ...Et.DEFAULT,
  name: "dropdown",
  placement: "bottom-start",
  arrow: !1,
  closeBtn: !1,
  animation: "fade"
};
let Nt = _i;
u(document).on(`click${Nt.NAMESPACE} mouseenter${Nt.NAMESPACE}`, Pu, (n) => {
  const e = u(n.currentTarget);
  if (e.length && !e.data(Nt.KEY)) {
    const t = e.data("trigger") || "click";
    if ((n.type === "mouseover" ? "hover" : "click") !== t)
      return;
    const i = {
      ...e.data(),
      show: !0,
      triggerEvent: n
    };
    if (!i.target && e.is("a")) {
      const o = e.attr("href");
      o && "#0".includes(o[0]) && (i.target = o);
    }
    !i.target && !i.items && !i.menu && (i.target = e.next(".dropdown-menu")), Nt.ensure(e, i), n.preventDefault();
  }
});
const ma = class ma extends et {
  constructor() {
    super(...arguments), this._ref = q();
  }
  get triggerElement() {
    return this._ref.current;
  }
  _updateData() {
    const { dropdown: e, items: t } = this.props, s = u(this.triggerElement), i = Nt.get(this.triggerElement), o = {
      items: t,
      ...e
    };
    i ? i.setOptions(o) : s.data(o);
  }
  componentDidMount() {
    this._updateData();
  }
  componentDidUpdate() {
    this._updateData();
  }
  componentWillUnmount() {
    var e;
    (e = Nt.get(this.triggerElement)) == null || e.destroy();
  }
  _getProps(e) {
    const { trigger: t, placement: s } = e;
    return {
      ...super._getProps(e),
      "data-toggle": "dropdown",
      "data-trigger": t,
      "data-placement": s,
      ref: this._ref
    };
  }
};
ma.defaultProps = {
  caret: !0
};
let li = ma;
const ga = class ga extends Ie {
  constructor() {
    super(...arguments), this._layoutTimer = 0;
  }
  get name() {
    return "menu";
  }
  get menuName() {
    return "dropdown-menu";
  }
  layout() {
    const e = this.ref.current, t = e == null ? void 0 : e.parentElement;
    !e || !t || Vo(t, e, {
      placement: this.props.placement,
      middleware: [Wo(), ks(), zo(1)]
    }).then(({ x: s, y: i }) => {
      u(e).css({
        left: s,
        top: i
      });
    });
  }
  getNestedMenuProps(e) {
    const t = super.getNestedMenuProps(e);
    return {
      ...t,
      className: N("show", t.className),
      popup: !0
    };
  }
  afterRender(e) {
    super.afterRender(e), this.props.controlledMenu && (this.layout(), this._layoutTimer = window.setTimeout(this.layout.bind(this), 100));
  }
  renderToggleIcon() {
    return /* @__PURE__ */ g("span", { class: "dropdown-menu-toggle-icon caret-right ml-2" });
  }
  componentWillUnmount() {
    super.componentWillUnmount(), this._layoutTimer && clearTimeout(this._layoutTimer);
  }
};
ga.defaultProps = {
  ...Ie.defaultProps,
  popup: !0,
  nestedTrigger: "hover",
  placement: "right-start"
};
let gr = ga;
function Yc({
  key: n,
  type: e,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ g(li, { type: t, ...s });
}
let Xc = class extends F {
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
  handleItemClick(e, t, s, i) {
    s && s.call(i.target, i);
    const { onClickItem: o } = this.props;
    o && o.call(this, { item: e, index: t, event: i });
  }
  beforeRender() {
    var s;
    const e = { ...this.props }, t = (s = e.beforeRender) == null ? void 0 : s.call(this, e);
    return t && Object.assign(e, t), typeof e.items == "function" && (e.items = e.items.call(this)), e;
  }
  onRenderItem(e, t) {
    const { key: s = t, ...i } = e, o = e.dropdown || e.items ? li : et;
    return /* @__PURE__ */ g(o, { ...i }, s);
  }
  renderItem(e, t, s) {
    const { itemRender: i, btnProps: o, onClickItem: r } = e, a = { key: s, ...t };
    if (o && Object.assign(a, o), r && (a.onClick = this.handleItemClick.bind(this, a, s, t.onClick)), i) {
      const l = i.call(this, a, v);
      if (st(l))
        return l;
      typeof l == "object" && Object.assign(a, l);
    }
    return this.onRenderItem(a, s);
  }
  render() {
    const e = this.beforeRender(), {
      className: t,
      items: s,
      size: i,
      type: o,
      btnProps: r,
      children: a,
      itemRender: l,
      onClickItem: c,
      beforeRender: d,
      afterRender: h,
      beforeDestroy: f,
      ...m
    } = e;
    return /* @__PURE__ */ g(
      "div",
      {
        className: N("btn-group", i ? `size-${i}` : "", t),
        ...m,
        children: [
          s && s.map(this.renderItem.bind(this, e)),
          a
        ]
      }
    );
  }
};
function Lu({
  key: n,
  type: e,
  btnType: t,
  ...s
}) {
  return /* @__PURE__ */ g(Xc, { type: t, ...s });
}
var ie;
let Dt = (ie = class extends jo {
  beforeRender() {
    const { gap: e, btnProps: t, wrap: s, ...i } = super.beforeRender();
    return i.className = N(i.className, s ? "flex-wrap" : "", typeof e == "number" ? `gap-${e}` : ""), typeof e == "string" && (i.style ? i.style.gap = e : i.style = { gap: e }), i;
  }
  isBtnItem(e) {
    return e === "item" || e === "dropdown";
  }
  renderTypedItem(e, t, s) {
    const { type: i } = s, o = this.props.btnProps, r = this.isBtnItem(i) ? { btnType: "ghost", ...o } : {};
    r.type && (r.btnType = r.type, delete r.type);
    const a = {
      ...t,
      ...r,
      ...s,
      className: N(`${this.name}-${i}`, t.className, r.className, s.className),
      style: Object.assign({}, t.style, r.style, s.style)
    };
    return i === "btn-group" && (a.btnProps = o), /* @__PURE__ */ g(e, { ...a });
  }
}, ie.ItemComponents = {
  item: _u,
  dropdown: Yc,
  "btn-group": Lu
}, ie.ROOT_TAG = "nav", ie.NAME = "toolbar", ie.defaultProps = {
  btnProps: {
    btnType: "ghost"
  }
}, ie);
function Hu({
  className: n,
  style: e,
  actions: t,
  heading: s,
  content: i,
  contentClass: o,
  children: r,
  close: a,
  onClose: l,
  icon: c,
  iconClass: d,
  ...h
}) {
  let f;
  a === !0 ? f = /* @__PURE__ */ g(et, { className: "alert-close btn ghost square text-inherit", square: !0, onClick: l, children: /* @__PURE__ */ g("span", { class: "close" }) }) : st(a) ? f = a : typeof a == "object" && (f = /* @__PURE__ */ g(et, { ...a, onClick: l }));
  const m = st(t) ? t : t ? /* @__PURE__ */ g(Dt, { ...t }) : null;
  return /* @__PURE__ */ g("div", { className: N("alert", n), style: e, ...h, children: [
    /* @__PURE__ */ g(K, { icon: c, className: N("alert-icon", d) }),
    st(i) ? i : /* @__PURE__ */ g("div", { className: N("alert-content", o), children: [
      st(s) ? s : s && /* @__PURE__ */ g("div", { className: "alert-heading", children: s }),
      /* @__PURE__ */ g("div", { className: "alert-text", children: i }),
      s ? m : null
    ] }),
    s ? null : m,
    f,
    r
  ] });
}
function Ou(n) {
  if (n === "center")
    return "fade-from-center";
  if (n) {
    if (n.includes("top"))
      return "fade-from-top";
    if (n.includes("bottom"))
      return "fade-from-bottom";
  }
  return "fade";
}
function ju({
  margin: n,
  type: e,
  placement: t,
  animation: s,
  show: i,
  className: o,
  time: r,
  ...a
}) {
  return /* @__PURE__ */ g(
    Hu,
    {
      className: N("messager", o, e, s === !0 ? Ou(t) : s, i ? "in" : ""),
      ...a
    }
  );
}
var Kt, Oe;
const xi = class xi extends B {
  constructor() {
    super(...arguments);
    $(this, Kt);
    this._show = !1, this._showTimer = 0, this._afterRender = ({ firstRender: t }) => {
      t && this.show();
      const { margin: s } = this.options;
      s && this.$element.css("margin", `${s}px`);
    };
  }
  get isShown() {
    return this._show;
  }
  afterInit() {
    this.on("click", (t) => {
      u(t.target).closest('.alert-close,[data-dismiss="messager"]').length && (t.preventDefault(), t.stopPropagation(), this.hide());
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
    this.render(), this.emit("show"), O(this, Kt, Oe).call(this, () => {
      this._show = !0, this.render(), O(this, Kt, Oe).call(this, () => {
        this.emit("shown");
        const { time: t } = this.options;
        t && O(this, Kt, Oe).call(this, () => this.hide(), t);
      });
    }, 100);
  }
  hide() {
    this._show && O(this, Kt, Oe).call(this, () => {
      this.emit("hide"), this._show = !1, this.render(), O(this, Kt, Oe).call(this, () => {
        this.emit("hidden");
      });
    }, 50);
  }
};
Kt = new WeakSet(), Oe = function(t, s = 200) {
  this._showTimer && clearTimeout(this._showTimer), this._showTimer = window.setTimeout(() => {
    t(), this._showTimer = 0;
  }, s);
}, xi.NAME = "MessagerItem", xi.Component = ju;
let yr = xi;
var pe, bt, $i, Kc, Ci, Zc;
const We = class We extends J {
  constructor() {
    super(...arguments);
    $(this, $i);
    $(this, Ci);
    $(this, pe, void 0);
    $(this, bt, void 0);
  }
  get isShown() {
    var t;
    return !!((t = b(this, bt)) != null && t.isShown);
  }
  show(t) {
    this.setOptions(t), O(this, $i, Kc).call(this).show();
  }
  hide() {
    var t;
    (t = b(this, bt)) == null || t.hide();
  }
  static show(t) {
    typeof t == "string" && (t = { content: t });
    const { container: s, ...i } = t, o = We.ensure(s || "body", { key: `messager_${u.guid++}`, ...i });
    return o.hide(), o.show(), o;
  }
};
pe = new WeakMap(), bt = new WeakMap(), $i = new WeakSet(), Kc = function() {
  if (b(this, bt))
    b(this, bt).setOptions(this.options);
  else {
    const t = O(this, Ci, Zc).call(this), s = new yr(t, this.options);
    s.on("hidden", () => {
      s.destroy(), t == null || t.remove(), C(this, pe, void 0), C(this, bt, void 0);
    }), C(this, bt, s);
  }
  return b(this, bt);
}, Ci = new WeakSet(), Zc = function() {
  if (b(this, pe))
    return b(this, pe);
  const { placement: t = "top" } = this.options;
  let s = this.$element.find(`.messagers-${t}`);
  s.length || (s = u(`<div class="messagers messagers-${t}"></div>`).appendTo(this.$element));
  let i = s.find(`#messager-${this.gid}`);
  return i.length || (i = u(`<div class="messager-holder" id="messager-${this.gid}"></div>`).appendTo(s), C(this, pe, i[0])), i[0];
}, We.NAME = "messager", We.DEFAULT = {
  placement: "top",
  animation: !0,
  close: !0,
  margin: 6,
  time: 5e3
}, We.MULTI_INSTANCE = !0;
let Se = We;
var Rs;
let Jc = (Rs = class extends F {
  render(e) {
    const { percent: t = 50, size: s = 24, circleBg: i, circleColor: o, text: r, className: a, textStyle: l, textX: c, textY: d, children: h } = e, f = s / 2;
    let { circleWidth: m = 0.2 } = e;
    m < 1 && (m = s * m);
    const p = (s - m) / 2;
    return /* @__PURE__ */ g("svg", { className: a, width: s, height: s, children: [
      /* @__PURE__ */ g("circle", { cx: f, cy: f, r: p, "stroke-width": m, stroke: i, fill: "transparent" }),
      /* @__PURE__ */ g("circle", { cx: f, cy: f, r: p, "stroke-width": m, stroke: o, fill: "transparent", "stroke-linecap": "round", "stroke-dasharray": Math.PI * p * 2, "stroke-dashoffset": Math.PI * p * 2 * (100 - t) / 100, style: { transformOrigin: "center", transform: "rotate(-90deg)" } }),
      r ? /* @__PURE__ */ g("text", { x: c ?? f, y: d ?? f + m / 2, "dominant-baseline": "middle", "text-anchor": "middle", style: l || { fontSize: `${p}px` }, children: r === !0 ? Math.round(t) : r }) : null,
      h
    ] });
  }
}, Rs.defaultProps = {
  percent: 50,
  size: 24,
  circleWidth: 0.1,
  circleBg: "var(--color-surface)",
  circleColor: "var(--color-primary-500)",
  text: !0
}, Rs);
const Ti = class Ti extends B {
};
Ti.NAME = "ProgressCircle", Ti.Component = Jc;
let Ja = Ti;
const ys = '[droppable="true"]', ki = class ki extends J {
  constructor() {
    super(...arguments), this._state = { dragging: null, dropping: null }, this._handleMouseDown = (e) => {
      const { selector: t, handle: s, beforeDrag: i } = this.options, o = u(e.target), r = o.closest(t), a = r[0];
      if (!a || s && !o.closest(s).length || i && i.call(this, e, a) === !1)
        return;
      r.attr("draggable", "true");
      const { draggingClass: l } = this.options;
      l && (this.$element.find(l).removeClass(l), r.addClass(l)), this._setState({ dragging: a });
    }, this._handleDragStart = (e) => {
      const { dragElement: t } = this;
      if (!t) {
        e.preventDefault();
        return;
      }
      const { options: s } = this, { onDragStart: i } = s;
      if (i && i.call(this, e, t) === !1) {
        this._clean();
        return;
      }
      const { $element: o } = this, { target: r, selector: a, droppableClass: l, hasDraggingClass: c } = s, d = typeof r == "function" ? u(r.call(this, t)) : o.find(r || a || ys);
      l && (o.find(l).removeClass(l), d.addClass(l)), c && o.addClass(c), o.find(ys).removeAttr("droppable"), d.attr("droppable", "true"), this._$targets = d;
    }, this._handleDrag = (e) => {
      var s;
      const { dragElement: t } = this;
      t && (this._setDragEffect(e), (s = this.options.onDrag) == null || s.call(this, e, t));
    }, this._handleDragEnd = (e) => {
      var s;
      const { dragElement: t } = this;
      this._clean(), t && ((s = this.options.onDragEnd) == null || s.call(this, e, t));
    }, this._handleDragEnter = (e) => {
      this._handleDragOver(e);
    }, this._handleDragOver = (e) => {
      var o, r;
      const { dragElement: t } = this, s = u(e.target).closest(ys)[0], i = this.state.dropping;
      if (!(!t || !s)) {
        if (e.preventDefault(), this._setDragEffect(e), i !== s) {
          const { droppingClass: a } = this.options;
          a && (i && this._leaveDropElement(e, i), u(s).addClass(a)), this._setState({ dropping: s }), (o = this.options.onDragEnter) == null || o.call(this, e, t, s);
        }
        (r = this.options.onDragOver) == null || r.call(this, e, t, s);
      }
    }, this._handleDragLeave = (e) => {
      const { dragElement: t } = this, s = u(e.target).filter(ys)[0];
      !t || !s || (e.preventDefault(), this._leaveDropElement(e, s), this._setState({ dropping: null }));
    }, this._handleDrop = (e) => {
      var s;
      const t = u(e.target).closest(ys)[0];
      t && (e.preventDefault(), (s = this.options.onDrop) == null || s.call(this, e, this.dragElement, t));
    };
  }
  get state() {
    return this._state;
  }
  get dragElement() {
    return this._state.dragging;
  }
  get dropElement() {
    return this._state.dropping;
  }
  async afterInit() {
    this.on("mousedown", this._handleMouseDown), this.on("dragstart", this._handleDragStart), this.on("dragend", this._handleDragEnd), this.options.onDrag && this.on("drag", this._handleDrag), this.on("dragover", this._handleDragOver), this.on("dragenter", this._handleDragEnter), this.on("dragleave", this._handleDragLeave), this.on("drop", this._handleDrop), u(document).on(`mouseup${this.namespace}`, this._clean.bind(this));
  }
  destroy() {
    this._clean(), u(document).off(this.namespace), super.destroy();
  }
  _setState(e) {
    var o;
    const t = this._state, { dragging: s = t.dragging, dropping: i = t.dropping } = e;
    s === t.dragging && i === t.dropping || (this._state = { dragging: s, dropping: i }, (o = this.options.onChange) == null || o.call(this, this._state, t));
  }
  _setDragEffect(e) {
    const { dropEffect: t } = this.options;
    t && (e.dataTransfer.dropEffect = t);
  }
  _leaveDropElement(e, t) {
    var i;
    const { droppingClass: s } = this.options;
    s && u(t).removeClass(s), (i = this.options.onDragLeave) == null || i.call(this, e, this.dragElement, t);
  }
  _clean() {
    const { draggingClass: e, droppableClass: t, droppingClass: s, hasDraggingClass: i } = this.options;
    i && this.$element.removeClass(i);
    const { dragElement: o } = this;
    if (o) {
      const a = u(o);
      e && a.removeClass(e);
    }
    this._setState({ dropping: null, dragging: null });
    const r = this._$targets;
    r && (t && r.removeClass(t), s && r.removeClass(s), this._$targets = void 0);
  }
};
ki.NAME = "Draggable", ki.DEFAULT = {
  selector: '[draggable="true"]',
  dropEffect: "move",
  hasDraggingClass: "has-dragging",
  draggingClass: "is-dragging",
  droppableClass: "is-droppable",
  droppingClass: "is-dropping"
};
let Qa = ki;
const Wu = '[moveable="true"]', Si = class Si extends J {
  constructor() {
    super(...arguments), this._handleMouseDown = (e) => {
      const { options: t } = this, { selector: s, handle: i, onMoveStart: o } = t, r = u(e.target), a = r.closest(s), l = a[0];
      if (!l || i && !r.closest(i).length || o && o.call(this, e, l) === !1)
        return;
      a.attr("moveable", "true");
      const { movingClass: c, hasMovingClass: d } = t;
      c && a.addClass(c), d && this.$element.addClass(d), this._setState(e, l), u(document).off("mousemove mouseup").on(`mousemove${this.namespace}`, this._handleMouseMove.bind(this)).on(`mouseup${this.namespace}`, this._handleMouseUp.bind(this));
    }, this._handleMouseMove = (e) => {
      this._state && (this._raf && cancelAnimationFrame(this._raf), this._raf = requestAnimationFrame(() => {
        var t;
        this._raf = 0, this._setState(e), (t = this.options.onMove) == null || t.call(this, e, this._state);
      }));
    }, this._handleMouseUp = (e) => {
      var t, s;
      this._state && (this._raf && (cancelAnimationFrame(this._raf), this._raf = 0), this._setState(e), (t = this.options.onMove) == null || t.call(this, e, this._state), (s = this.options.onMoveEnd) == null || s.call(this, e, this._state), this._clean());
    };
  }
  get state() {
    return this._state;
  }
  get moveElement() {
    var e;
    return (e = this._state) == null ? void 0 : e.target;
  }
  async afterInit() {
    this.on("mousedown", this._handleMouseDown);
  }
  destroy() {
    this._clean(), u(document).off(this.namespace), super.destroy();
  }
  _setState(e, t) {
    var a;
    e.preventDefault();
    let s = {
      x: e.pageX,
      y: e.pageY
    };
    const i = this._state;
    if (t) {
      const l = u(t);
      if (this.options.move === !0) {
        const d = l.css("position");
        s.strategy = d === "fixed" || d === "absolute" ? "position" : "transform";
      } else
        s.strategy = "none";
      const c = l.position();
      s = u.extend(s, {
        target: t,
        startX: s.x,
        startY: s.y,
        deltaX: 0,
        deltaY: 0,
        startLeft: c.left,
        startTop: c.top,
        left: c.left,
        top: c.top
      });
    } else if (i) {
      const l = s.x - i.startX, c = s.y - i.startY;
      s = u.extend({}, i, s, {
        deltaX: l,
        deltaY: c,
        left: i.startLeft + l,
        top: i.startTop + c
      });
    }
    this._state = s;
    const { strategy: o, target: r } = s;
    o === "position" ? u(r).css({ left: s.left, top: s.top }) : o === "transform" && u(r).css("transform", `translate(${s.deltaX}px, ${s.deltaY}px)`), (a = this.options.onChange) == null || a.call(this, s, i, e);
  }
  _clean() {
    u(document).off("mousemove mouseup");
    const { hasMovingClass: e, movingClass: t } = this.options;
    e && this.$element.removeClass(e);
    const { moveElement: s } = this;
    if (s) {
      const i = u(s);
      t && i.removeClass(t);
    }
    this._state = void 0;
  }
};
Si.NAME = "Moveable", Si.DEFAULT = {
  selector: Wu,
  hasMovingClass: "has-moving",
  movingClass: "is-moving",
  move: !0
};
let br = Si;
var Rt;
class zu {
  constructor(e = "") {
    $(this, Rt, void 0);
    typeof e == "object" ? C(this, Rt, e) : C(this, Rt, document.appendChild(document.createComment(e)));
  }
  on(e, t, s) {
    b(this, Rt).addEventListener(e, t, s);
  }
  once(e, t, s) {
    b(this, Rt).addEventListener(e, t, { once: !0, ...s });
  }
  off(e, t, s) {
    b(this, Rt).removeEventListener(e, t, s);
  }
  emit(e) {
    return b(this, Rt).dispatchEvent(e), e;
  }
}
Rt = new WeakMap();
const tl = /* @__PURE__ */ new Set([
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
class ca extends zu {
  on(e, t, s) {
    super.on(e, t, s);
  }
  off(e, t, s) {
    super.off(e, t, s);
  }
  once(e, t, s) {
    super.once(e, t, s);
  }
  emit(e, t) {
    return typeof e == "string" && (tl.has(e) ? (e = new Event(e), Object.assign(e, { detail: t })) : e = new CustomEvent(e, { detail: t })), super.emit(ca.createEvent(e, t));
  }
  static createEvent(e, t) {
    return typeof e == "string" && (tl.has(e) ? (e = new Event(e), Object.assign(e, { detail: t })) : e = new CustomEvent(e, { detail: t })), e;
  }
}
const $s = class $s extends J {
  async afterInit() {
    const e = await $s.loadModule();
    this.module = new e(this.element, this.options);
  }
  option(e, t) {
    if (t === void 0)
      return this.module.option(e);
    this.module.option(e, t);
  }
  /**
   * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
   * @param element an HTMLElement or selector string.
   * @param selector default: `options.draggable`
   */
  closest(e, t) {
    return this.module.closest(e, t);
  }
  /**
   * Sorts the elements according to the array.
   * @param order an array of strings to sort.
   * @param useAnimation default: false.
   */
  sort(e, t) {
    this.module.sort(e, t);
  }
  /**
   * Saving and restoring of the sort.
   */
  save() {
    this.module.save();
  }
  /**
   * Removes the sortable functionality completely.
   */
  destroy() {
    super.destroy(), this.module.destroy();
  }
  /**
   * Serializes the sortable's item data-id's (dataIdAttr option) into an array of string.
   */
  toArray() {
    return this.module.toArray();
  }
  static async loadModule() {
    return this.Module || (this.Module = await u.getLib("sortablejs")), this.Module;
  }
};
$s.NAME = "Sortable", $s.DEFAULT = {
  animation: 150
};
let el = $s;
u.registerLib("sortablejs", {
  src: "sortable/sortable.min.js",
  name: "Sortable"
});
let Qc = (n = 21) => crypto.getRandomValues(new Uint8Array(n)).reduce((e, t) => (t &= 63, t < 36 ? e += t.toString(36) : t < 62 ? e += (t - 26).toString(36).toUpperCase() : t > 62 ? e += "-" : e += "_", e), "");
const Jo = "```ZUI_STR\n";
var As, me, Ue, wt, qe, Ge, Un;
const ya = class ya {
  /**
   * Create new store instance
   * @param name Name of store
   * @param type Store type
   */
  constructor(e, t = "local") {
    $(this, Ge);
    $(this, As, void 0);
    $(this, me, void 0);
    $(this, Ue, void 0);
    $(this, wt, void 0);
    $(this, qe, void 0);
    C(this, As, t), C(this, Ue, e ?? Qc()), C(this, me, `ZUI_STORE:${b(this, Ue)}`), C(this, wt, t === "local" ? localStorage : sessionStorage);
  }
  /**
   * Get store type
   */
  get type() {
    return b(this, As);
  }
  /**
   * Get session type store instance
   */
  get session() {
    return this.type === "session" ? this : (b(this, qe) || C(this, qe, new ya(b(this, Ue), "session")), b(this, qe));
  }
  /**
   * Get value from store.
   *
   * @param key          Key to get.
   * @param defaultValue Default value to return if key is not found.
   * @returns Value of key or defaultValue if key is not found.
   */
  get(e, t) {
    const s = b(this, wt).getItem(O(this, Ge, Un).call(this, e));
    if (typeof s == "string") {
      if (s.startsWith(Jo))
        return s.substring(Jo.length);
      try {
        return JSON.parse(s);
      } catch {
      }
    }
    return s ?? t;
  }
  /**
   * Set key-value pair in store
   * @param key Key to set
   * @param value Value to set
   */
  set(e, t) {
    if (t == null)
      return this.remove(e);
    b(this, wt).setItem(O(this, Ge, Un).call(this, e), typeof t == "string" ? `${Jo}${t}` : JSON.stringify(t));
  }
  /**
   * Remove key-value pair from store
   * @param key Key to remove
   */
  remove(e) {
    b(this, wt).removeItem(O(this, Ge, Un).call(this, e));
  }
  /**
   * Iterate all key-value pairs in store
   * @param callback Callback function to call for each key-value pair in the store
   */
  each(e) {
    for (let t = 0; t < b(this, wt).length; t++) {
      const s = b(this, wt).key(t);
      if (s != null && s.startsWith(b(this, me))) {
        const i = b(this, wt).getItem(s);
        typeof i == "string" && e(s.substring(b(this, me).length + 1), JSON.parse(i));
      }
    }
  }
  /**
   * Get all key values in store
   * @returns All key-value pairs in the store
   */
  getAll() {
    const e = {};
    return this.each((t, s) => {
      e[t] = s;
    }), e;
  }
};
As = new WeakMap(), me = new WeakMap(), Ue = new WeakMap(), wt = new WeakMap(), qe = new WeakMap(), Ge = new WeakSet(), Un = function(e) {
  return `${b(this, me)}:${e}`;
};
let ci = ya;
const fe = new ci("DEFAULT");
function Fu(n, e = "local") {
  return new ci(n, e);
}
Object.assign(fe, { create: Fu });
const z = u, ha = window.document;
let jn, Ut;
const Bu = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, Vu = /^(?:text|application)\/javascript/i, Uu = /^(?:text|application)\/xml/i, th = "application/json", eh = "text/html", qu = /^\s*$/, wr = ha.createElement("a");
wr.href = window.location.href;
function Gu(n, e, t) {
  const s = new CustomEvent(e, { detail: t });
  return z(n).trigger(s, t), !s.defaultPrevented;
}
function Ae(n, e, t, s) {
  if (n.global)
    return Gu(e || ha, t, s);
}
z.active = 0;
function Yu(n) {
  n.global && z.active++ === 0 && Ae(n, null, "ajaxStart");
}
function Xu(n) {
  n.global && !--z.active && Ae(n, null, "ajaxStop");
}
function Ku(n, e) {
  const t = e.context;
  if (e.beforeSend.call(t, n, e) === !1 || Ae(e, t, "ajaxBeforeSend", [n, e]) === !1)
    return !1;
  Ae(e, t, "ajaxSend", [n, e]);
}
function Zu(n, e, t) {
  const s = t.context, i = "success";
  t.success.call(s, n, i, e), Ae(t, s, "ajaxSuccess", [e, t, n]), sh(i, e, t);
}
function Wn(n, e, t, s) {
  const i = s.context;
  s.error.call(i, t, e, n), Ae(s, i, "ajaxError", [t, s, n || e]), sh(e, t, s);
}
function sh(n, e, t) {
  const s = t.context;
  t.complete.call(s, e, n), Ae(t, s, "ajaxComplete", [e, t]), Xu(t);
}
function Ju(n, e, t) {
  if (t.dataFilter == Xt)
    return n;
  const s = t.context;
  return t.dataFilter.call(s, n, e);
}
function Xt() {
}
z.ajaxSettings = {
  // Default type of request
  type: "GET",
  // Callback that is executed before request
  beforeSend: Xt,
  // Callback that is executed if the request succeeds
  success: Xt,
  // Callback that is executed the the server drops error
  error: Xt,
  // Callback that is executed on request complete (both: error and success)
  complete: Xt,
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
  dataFilter: Xt
};
function Qu(n) {
  return n && (n = n.split(";", 2)[0]), n && (n == eh ? "html" : n == th ? "json" : Vu.test(n) ? "script" : Uu.test(n) && "xml") || "text";
}
function nh(n, e) {
  return e == "" ? n : (n + "&" + e).replace(/[&?]{1,2}/, "?");
}
function tf(n) {
  n.processData && n.data && typeof n.data != "string" && (n.data = z.param(n.data, n.traditional)), n.data && (!n.type || n.type.toUpperCase() == "GET" || n.dataType == "jsonp") && (n.url = nh(n.url, n.data), n.data = void 0);
}
z.ajax = function(n) {
  var y;
  const e = z.extend({}, n || {});
  let t, s;
  for (jn in z.ajaxSettings)
    e[jn] === void 0 && (e[jn] = z.ajaxSettings[jn]);
  Yu(e), e.crossDomain || (t = ha.createElement("a"), t.href = e.url, t.href = t.href, e.crossDomain = wr.protocol + "//" + wr.host != t.protocol + "//" + t.host);
  const i = e.type.toUpperCase() === "GET";
  if (e.url || (e.url = window.location.toString()), (s = e.url.indexOf("#")) > -1 && (e.url = e.url.slice(0, s)), i)
    tf(e);
  else if (e.contentType === void 0) {
    if (z.isPlainObject(e.data)) {
      const _ = new FormData();
      z.each(e.data, function(w, x) {
        _.append(w, `${x}`);
      }), e.data = _;
    }
    e.data instanceof FormData && (e.contentType = !1);
  }
  let o = e.dataType;
  /\?.+=\?/.test(e.url) && (o = "jsonp"), (e.cache === !1 || (!n || n.cache !== !0) && (o == "script" || o == "jsonp")) && (e.url = nh(e.url, "_=" + Date.now()));
  let a = e.accepts[o];
  const l = {}, c = function(_, w) {
    l[_.toLowerCase()] = [_, w];
  }, d = /^([\w-]+:)\/\//.test(e.url) ? RegExp.$1 : window.location.protocol, h = e.xhr(), f = h.setRequestHeader;
  let m;
  if (e.crossDomain || c("X-Requested-With", "XMLHttpRequest"), c("Accept", a || "*/*"), a = e.mimeType, a && (a.indexOf(",") > -1 && (a = a.split(",", 2)[0]), (y = h.overrideMimeType) == null || y.call(h, a)), (e.contentType || e.contentType !== !1 && e.data && !i) && c("Content-Type", e.contentType || "application/x-www-form-urlencoded"), e.headers)
    for (Ut in e.headers)
      c(Ut, e.headers[Ut]);
  if (h.setRequestHeader = c, h.onreadystatechange = function() {
    if (h.readyState == 4) {
      h.onreadystatechange = Xt, clearTimeout(m);
      let _, w = !1;
      if (h.status >= 200 && h.status < 300 || h.status == 304 || h.status == 0 && d == "file:") {
        if (o = o || Qu(e.mimeType || h.getResponseHeader("content-type")), h.responseType == "arraybuffer" || h.responseType == "blob")
          _ = h.response;
        else {
          _ = h.responseText;
          try {
            _ = Ju(_, o, e), o == "xml" ? _ = h.responseXML : o == "json" && (_ = qu.test(_) ? null : JSON.parse(_));
          } catch (x) {
            w = x;
          }
          if (w)
            return Wn(w, "parsererror", h, e);
        }
        Zu(_, h, e);
      } else
        Wn(h.statusText || null, h.status ? "error" : "abort", h, e);
    }
  }, Ku(h, e) === !1)
    return h.abort(), Wn(null, "abort", h, e), h;
  const p = "async" in e ? e.async : !0;
  if (h.open(e.type, e.url, p, e.username, e.password), e.xhrFields)
    for (Ut in e.xhrFields)
      h[Ut] = e.xhrFields[Ut];
  for (Ut in l)
    f.apply(h, l[Ut]);
  return e.timeout > 0 && (m = setTimeout(function() {
    h.onreadystatechange = Xt, h.abort(), Wn(null, "timeout", h, e);
  }, e.timeout)), h.send(e.data ? e.data : null), h;
};
function Uo(n, e, t, s) {
  return z.isFunction(e) && (s = t, t = e, e = void 0), z.isFunction(t) || (s = t, t = void 0), {
    url: n,
    data: e,
    success: t,
    dataType: s
  };
}
z.get = function(n, e, t, s) {
  return z.ajax(Uo(n, e, t, s));
};
z.post = function(n, e, t, s) {
  const i = Uo(n, e, t, s);
  return z.ajax(Object.assign(i, { type: "POST" }));
};
z.getJSON = function(n, e, t, s) {
  const i = Uo(n, e, t, s);
  return i.dataType = "json", z.ajax(i);
};
z.fn.load = function(n, e, t) {
  if (!this.length)
    return this;
  const s = n.split(/\s/);
  let i;
  const o = Uo(n, e, t), r = o.success;
  return s.length > 1 && (o.url = s[0], i = s[1]), o.success = (a, ...l) => {
    this.html(i ? z("<div>").html(a.replace(Bu, "")).find(i) : a), r == null || r.call(this, a, ...l);
  }, z.ajax(o), this;
};
const sl = encodeURIComponent;
function ih(n, e, t, s) {
  const i = z.isArray(e), o = z.isPlainObject(e);
  z.each(e, function(r, a) {
    const l = Array.isArray(a) ? "array" : typeof a;
    s && (r = t ? s : s + "[" + (o || l == "object" || l == "array" ? r : "") + "]"), !s && i ? n.add(a.name, a.value) : l == "array" || !t && l == "object" ? ih(n, a, t, r) : n.add(r, a);
  });
}
z.param = function(n, e) {
  const t = [];
  return t.add = function(s, i) {
    z.isFunction(i) && (i = i()), i == null && (i = ""), this.push(sl(s) + "=" + sl(i));
  }, ih(t, n, e), t.join("&").replace(/%20/g, "+");
};
const Kp = Object.assign(z.ajax, {
  get: z.get,
  post: z.post,
  getJSON: z.getJSON,
  param: z.param,
  ajaxSettings: z.ajaxSettings
}), Zp = new ca();
/*! js-cookie v3.0.1 | MIT */
function zn(n) {
  for (var e = 1; e < arguments.length; e++) {
    var t = arguments[e];
    for (var s in t)
      n[s] = t[s];
  }
  return n;
}
var ef = {
  read: function(n) {
    return n[0] === '"' && (n = n.slice(1, -1)), n.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
  },
  write: function(n) {
    return encodeURIComponent(n).replace(
      /%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,
      decodeURIComponent
    );
  }
};
function vr(n, e) {
  function t(i, o, r) {
    if (!(typeof document > "u")) {
      r = zn({}, e, r), typeof r.expires == "number" && (r.expires = new Date(Date.now() + r.expires * 864e5)), r.expires && (r.expires = r.expires.toUTCString()), i = encodeURIComponent(i).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
      var a = "";
      for (var l in r)
        r[l] && (a += "; " + l, r[l] !== !0 && (a += "=" + r[l].split(";")[0]));
      return document.cookie = i + "=" + n.write(o, i) + a;
    }
  }
  function s(i) {
    if (!(typeof document > "u" || arguments.length && !i)) {
      for (var o = document.cookie ? document.cookie.split("; ") : [], r = {}, a = 0; a < o.length; a++) {
        var l = o[a].split("="), c = l.slice(1).join("=");
        try {
          var d = decodeURIComponent(l[0]);
          if (r[d] = n.read(c, d), i === d)
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
          zn({}, o, {
            expires: -1
          })
        );
      },
      withAttributes: function(i) {
        return vr(this.converter, zn({}, this.attributes, i));
      },
      withConverter: function(i) {
        return vr(zn({}, this.converter, i), this.attributes);
      }
    },
    {
      attributes: { value: Object.freeze(e) },
      converter: { value: Object.freeze(n) }
    }
  );
}
var sf = vr(ef, { path: "/" });
window.$ && Object.assign(window.$, { cookie: sf });
function nf(n) {
  if (n.indexOf("#") === 0 && (n = n.slice(1)), n.length === 3 && (n = n[0] + n[0] + n[1] + n[1] + n[2] + n[2]), n.length !== 6)
    throw new Error(`Invalid HEX color "${n}".`);
  return [
    parseInt(n.slice(0, 2), 16),
    // r
    parseInt(n.slice(2, 4), 16),
    // g
    parseInt(n.slice(4, 6), 16)
    // b
  ];
}
function of(n) {
  const [e, t, s] = typeof n == "string" ? nf(n) : n;
  return e * 0.299 + t * 0.587 + s * 0.114 > 186;
}
function nl(n, e) {
  return of(n) ? (e == null ? void 0 : e.dark) ?? "#333333" : (e == null ? void 0 : e.light) ?? "#ffffff";
}
function il(n, e = 255) {
  return Math.min(Math.max(n, 0), e);
}
function rf(n, e, t) {
  n = n % 360 / 360, e = il(e), t = il(t);
  const s = t <= 0.5 ? t * (e + 1) : t + e - t * e, i = t * 2 - s, o = (r) => (r = r < 0 ? r + 1 : r > 1 ? r - 1 : r, r * 6 < 1 ? i + (s - i) * r * 6 : r * 2 < 1 ? s : r * 3 < 2 ? i + (s - i) * (2 / 3 - r) * 6 : i);
  return [
    o(n + 1 / 3) * 255,
    o(n) * 255,
    o(n - 1 / 3) * 255
  ];
}
function af(n) {
  let e = 0;
  if (typeof n != "string" && (n = String(n)), n && n.length)
    for (let t = 0; t < n.length; ++t)
      e += (t + 1) * n.charCodeAt(t);
  return e;
}
function lf(n, e) {
  return /^[\u4e00-\u9fa5\s]+$/.test(n) ? n.length <= e ? n : n.substring(n.length - e) : /^[A-Za-z\d\s]+$/.test(n) ? n[0].toUpperCase() : n.length <= e ? n : n.substring(0, e);
}
let oh = class extends F {
  render() {
    const {
      className: e,
      style: t,
      size: s = "",
      circle: i,
      rounded: o,
      background: r,
      foreColor: a,
      text: l,
      code: c,
      maxTextLength: d = 2,
      src: h,
      hueDistance: f = 43,
      saturation: m = 0.4,
      lightness: p = 0.6,
      children: y,
      ..._
    } = this.props, w = ["avatar", e], x = { ...t, background: r, color: a };
    let T = 32;
    s && (typeof s == "number" ? (x.width = `${s}px`, x.height = `${s}px`, x.fontSize = `${Math.max(12, Math.round(s / 2))}px`, T = s) : (w.push(`size-${s}`), T = { xs: 20, sm: 24, lg: 48, xl: 80 }[s])), i ? w.push("circle") : o && (typeof o == "number" ? x.borderRadius = `${o}px` : w.push(`rounded-${o}`));
    let k;
    if (h)
      w.push("has-img"), k = /* @__PURE__ */ g("img", { className: "avatar-img", src: h, alt: l });
    else if (l != null && l.length) {
      const E = lf(l, d);
      if (w.push("has-text", `has-text-${E.length}`), r)
        !a && r && (x.color = nl(r));
      else {
        const L = c ?? l, R = (typeof L == "number" ? L : af(L)) * f % 360;
        if (x.background = `hsl(${R},${m * 100}%,${p * 100}%)`, !a) {
          const A = rf(R, m, p);
          x.color = nl(A);
        }
      }
      let D;
      T && T < 14 * E.length && (D = { transform: `scale(${T / (14 * E.length)})`, whiteSpace: "nowrap" }), k = /* @__PURE__ */ g("div", { "data-actualSize": T, className: "avatar-text", style: D, children: E });
    }
    return /* @__PURE__ */ g(
      "div",
      {
        className: N(w),
        style: x,
        ..._,
        children: [
          k,
          y
        ]
      }
    );
  }
};
const Ei = class Ei extends B {
};
Ei.NAME = "Avatar", Ei.Component = oh;
let ol = Ei;
const Ni = class Ni extends B {
};
Ni.NAME = "BtnGroup", Ni.Component = Xc;
let rl = Ni;
const _r = Symbol("EVENT_PICK");
class qo extends F {
  constructor(e) {
    super(e), this._handleClick = this._handleClick.bind(this), this._hasInput = !!u(`#${e.id}`).length;
  }
  get hasInput() {
    return this._hasInput;
  }
  _handleClick(e) {
    const { togglePop: t, clickType: s, onClick: i } = this.props;
    let o = s === "open" ? !0 : void 0;
    const r = u(e.target), a = i == null ? void 0 : i(e);
    if (!e.defaultPrevented) {
      if (typeof a == "boolean")
        o = a;
      else {
        if (r.closest('[data-dismiss="pick"]').length) {
          t(!1);
          return;
        }
        if (r.closest("a,input").length)
          return;
      }
      requestAnimationFrame(() => t(o));
    }
  }
  _getClass(e) {
    const { state: t, className: s } = e, { open: i, disabled: o } = t;
    return N(
      "pick",
      s,
      i && "is-open focus",
      o && "disabled"
    );
  }
  _getProps(e) {
    const { id: t, style: s, attrs: i } = e;
    return {
      id: `pick-${t}`,
      className: this._getClass(e),
      style: s,
      tabIndex: -1,
      onClick: this._handleClick,
      ...i
    };
  }
  _renderTrigger(e) {
    const { children: t, state: s } = e;
    return t ?? s.value;
  }
  _renderValue(e) {
    const { name: t, state: { value: s = "" }, id: i } = e;
    if (t)
      if (this._hasInput)
        u(`#${i}`).val(s);
      else
        return /* @__PURE__ */ g("input", { id: i, type: "hidden", className: "pick-value", name: t, value: s });
    return null;
  }
  componentDidMount() {
    const { id: e, state: t } = this.props;
    u(`#${e}`).on(`change.zui.pick.${e}`, (s, i) => {
      if (i === _r)
        return;
      const o = s.target.value;
      o !== t.value && (this._skipTriggerChange = o, this.props.changeState({ value: o }));
    });
  }
  componentWillUnmount() {
    const { id: e } = this.props;
    u(`#${e}`).off(`change.zui.pick.${e}`);
  }
  componentDidUpdate(e) {
    const { id: t, state: s, name: i } = this.props;
    i && e.state.value !== s.value && (this._skipTriggerChange !== s.value && u(`#${t}`).trigger("change", _r), this._skipTriggerChange = !1);
  }
  render(e) {
    return v(
      e.tagName || "div",
      this._getProps(e),
      this._renderTrigger(e),
      this._renderValue(e)
    );
  }
}
var ge, vt, Zt;
class da extends F {
  constructor(t) {
    super(t);
    $(this, ge, void 0);
    $(this, vt, void 0);
    $(this, Zt, void 0);
    C(this, ge, q()), this._handleDocClick = (s) => {
      const { state: { open: i }, id: o, togglePop: r } = this.props, a = u(s.target);
      i !== "closing" && !a.closest(`#pick-${o},#pick-pop-${o}`).length && a.parent().length && r(!1);
    }, this._handleClick = this._handleClick.bind(this);
  }
  get trigger() {
    return u(`#pick-${this.props.id}`)[0];
  }
  get element() {
    var t;
    return (t = b(this, ge)) == null ? void 0 : t.current;
  }
  get container() {
    return b(this, Zt);
  }
  _handleClick(t) {
    const { togglePop: s } = this.props, i = u(t.target), o = i.closest("[data-pick-value]");
    if (o.length)
      return t.stopPropagation(), s(!1, { value: `${o.dataset("pickValue")}` });
    if (i.closest('[data-dismiss="pick"]').length)
      return s(!1);
  }
  _getClass(t) {
    const { className: s, state: i } = t, { open: o } = i;
    return N(
      "pick-pop",
      s,
      o === !0 && "in"
    );
  }
  _getProps(t) {
    const {
      id: s,
      style: i,
      maxHeight: o,
      maxWidth: r,
      minHeight: a,
      minWidth: l
    } = t, c = u.extend({
      maxHeight: o,
      maxWidth: r,
      minHeight: a,
      minWidth: l
    }, i);
    return {
      id: `pick-pop-${s}`,
      className: this._getClass(t),
      style: c,
      ref: b(this, ge),
      onClick: this._handleClick
    };
  }
  _getContainer(t) {
    if (!b(this, Zt)) {
      const s = u(t.container || "body");
      let i = s.find(".pick-container");
      i.length || (i = u("<div>").addClass("pick-container").appendTo(s)), C(this, Zt, i[0]);
    }
    return b(this, Zt);
  }
  _render(t) {
    return /* @__PURE__ */ g("div", { ...this._getProps(t), children: this._renderPop(t) });
  }
  _renderPop(t) {
    return t.children;
  }
  layout() {
    const { element: t, trigger: s, props: i } = this, { state: o } = i;
    if (!t || !s || !o.open) {
      b(this, vt) && (b(this, vt).call(this), C(this, vt, void 0));
      return;
    }
    b(this, vt) || C(this, vt, la(s, t, () => {
      const { placement: r, width: a } = i;
      Vo(s, t, {
        placement: !r || r === "auto" ? "bottom-start" : r,
        middleware: [r === "auto" ? Wo() : null, ks(), zo(1)].filter(Boolean)
      }).then(({ x: l, y: c }) => {
        var d, h;
        u(t).css({
          left: l,
          top: c,
          width: a === "100%" ? u(s).outerWidth() : void 0
        }), (h = (d = this.props).onLayout) == null || h.call(d, t);
      }), a === "100%" && u(t).css({ width: u(t).width() });
    }));
  }
  componentDidMount() {
    var t, s;
    this.layout(), u(document).on("click", this._handleDocClick), (s = (t = this.props).afterRender) == null || s.call(t, { firstRender: !0 });
  }
  componentDidUpdate() {
    var t, s;
    (s = (t = this.props).afterRender) == null || s.call(t, { firstRender: !1 });
  }
  componentWillUnmount() {
    var s, i;
    u(document).off("click", this._handleDocClick);
    const t = b(this, vt);
    t && (t(), C(this, vt, void 0)), C(this, Zt, void 0), C(this, ge, void 0), u(`#pick-pop-${this.props.id}`).remove(), (i = (s = this.props).beforeDestroy) == null || i.call(s);
  }
  render(t) {
    return mu(this._render(t), this._getContainer(t));
  }
}
ge = new WeakMap(), vt = new WeakMap(), Zt = new WeakMap();
var Ps, lt, ye, Ee;
let rt = (Ee = class extends F {
  constructor(t) {
    super(t);
    $(this, Ps, void 0);
    $(this, lt, void 0);
    $(this, ye, void 0);
    C(this, lt, 0), C(this, ye, q()), this.changeState = (s, i) => new Promise((o) => {
      this.setState(s, () => {
        i == null || i(), o(this.state);
      });
    }), this.toggle = async (s, i) => {
      this.props.disabled && (s = !1);
      const { state: o } = this;
      if (typeof s == "boolean" && s === (!!o.open && o.open !== "closing"))
        return i && await this.changeState(i), this.state;
      b(this, lt) && (clearTimeout(b(this, lt)), C(this, lt, 0));
      let r = await this.changeState((l) => (s = s ?? !l.open, {
        open: s ? "opening" : "closing",
        ...i
      }));
      const { open: a } = r;
      return a === "closing" ? (await Qn(200, (l) => {
        C(this, lt, l);
      }), C(this, lt, 0), r = await this.changeState({ open: !1 })) : a === "opening" && (await Qn(50, (l) => {
        C(this, lt, l);
      }), C(this, lt, 0), r = await this.changeState({ open: !0 })), r;
    }, this.state = {
      value: String(t.defaultValue ?? ""),
      open: !1,
      disabled: !1
    }, C(this, Ps, t.id ?? `_pick${u.guid++}`);
  }
  get id() {
    return b(this, Ps);
  }
  get pop() {
    return b(this, ye).current;
  }
  open(t) {
    return this.toggle(!0, t);
  }
  close(t) {
    return this.toggle(!1, t);
  }
  _getTriggerProps(t, s) {
    return {
      id: this.id,
      state: s,
      className: t.className,
      style: t.style,
      name: t.name,
      tagName: t.tagName,
      attrs: t.attrs,
      clickType: t.clickType,
      onClick: t.onClick,
      changeState: this.changeState,
      togglePop: this.toggle
    };
  }
  _getPopProps(t, s) {
    return {
      id: this.id,
      state: s,
      className: t.popClass,
      style: t.popStyle,
      changeState: this.changeState,
      togglePop: this.toggle,
      placement: t.popPlacement,
      container: t.popContainer,
      width: t.popWidth,
      height: t.popHeight,
      minHeight: t.popMinHeight,
      maxHeight: t.popMaxHeight,
      maxWidth: t.popMaxWidth,
      minWidth: t.popMinWidth
    };
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  _renderTrigger(t, s) {
    return null;
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  _renderPop(t, s) {
    return null;
  }
  _afterRender(t = !1) {
    var s;
    (s = this.props.afterRender) == null || s.call(this, { firstRender: t });
  }
  _getPop(t) {
    return t.Pop || this.constructor.Pop;
  }
  _getTrigger(t) {
    return t.Trigger || this.constructor.Trigger;
  }
  _handleChange(t, s) {
    const { onChange: i } = this.props;
    i && i(t, s);
  }
  setValue(t) {
    if (!this.props.disabled)
      return this.changeState({ value: t });
  }
  componentDidMount() {
    this._afterRender(!0);
  }
  componentWillUpdate(t, s) {
    const { open: i } = this.state, { open: o } = s;
    if (i === o)
      return;
    const { onPopShow: r, onPopHide: a } = this.props;
    o && r ? r() : !o && a && a();
  }
  componentDidUpdate(t, s) {
    const { open: i, value: o } = this.state, { open: r, value: a } = s;
    if (!!i != !!r) {
      const { onPopShown: l, onPopHidden: c } = this.props;
      i && l ? l() : !i && c && c();
    }
    o !== a && this._handleChange(o, a), this._afterRender();
  }
  componentWillUnmount() {
    var s;
    (s = this.props.beforeDestroy) == null || s.call(this), b(this, lt) && clearTimeout(b(this, lt));
    const t = b(this, ye).current;
    t && t.componentWillUnmount && t.componentWillUnmount();
  }
  render(t, s) {
    const { open: i } = s, o = this._getTrigger(t);
    let r;
    if (i) {
      const a = this._getPop(t);
      r = /* @__PURE__ */ g(a, { ref: b(this, ye), ...this._getPopProps(t, s), children: this._renderPop(t, s) }, "pop");
    }
    return /* @__PURE__ */ g(oe, { children: [
      /* @__PURE__ */ g(o, { ...this._getTriggerProps(t, s), children: this._renderTrigger(t, s) }, "pick"),
      r
    ] });
  }
}, Ps = new WeakMap(), lt = new WeakMap(), ye = new WeakMap(), Ee.Trigger = qo, Ee.Pop = da, Ee.defaultProps = {
  popContainer: "body",
  popClass: "popup",
  popWidth: "100%",
  popPlacement: "auto",
  popMinWidth: 50,
  popMinHeight: 32,
  popMaxHeight: 300,
  clickType: "open"
}, Ee);
var Ls;
let cf = (Ls = class extends rt {
  constructor(e) {
    super(e), this.state.value === void 0 && e.required && (this.state.value = this.getColors()[0]);
  }
  getColors() {
    const { colors: e } = this.props;
    return typeof e == "string" ? e.split(",") : e || [];
  }
  componentDidMount() {
    this.syncColor();
  }
  syncColor() {
    const { syncBackground: e, syncBorder: t, syncColor: s, syncValue: i } = this.props, o = this.state.value || "";
    if (e && u(e).css("backgroundColor", o), t && u(t).css("borderColor", o), s && u(s).css("color", o), i) {
      const r = u(i);
      r.is("input,textarea,select") ? r.val(o) : r.text(o);
    }
  }
  _handleChange(e, t) {
    this.props.disabled || (super._handleChange(e, t), this.syncColor());
  }
  _renderTrigger(e, t) {
    const { icon: s } = e, { value: i } = t;
    return [
      s ? /* @__PURE__ */ g(K, { icon: s }, "icon") : /* @__PURE__ */ g("span", { class: "color-picker-item bg-current ring", style: { background: i } })
    ];
  }
  _getTriggerProps(e, t) {
    const s = super._getTriggerProps(e, t);
    return s.style = u.extend({
      color: t.value
    }, s.style), s.className = N("color-picker", s.className, { disabled: e.disabled }), s;
  }
  _renderPop(e, t) {
    const { closeBtn: s, heading: i } = e, o = this.getColors(), { value: r } = t;
    let a;
    return i && (a = /* @__PURE__ */ g("div", { className: "color-picker-heading", children: [
      i,
      s ? /* @__PURE__ */ g("button", { className: "btn ghost square rounded size-sm", "data-dismiss": "pick", children: /* @__PURE__ */ g("span", { class: "close" }) }) : null
    ] }, "heading")), [
      a,
      /* @__PURE__ */ g("div", { className: "color-picker-row", children: [
        o.map((l) => /* @__PURE__ */ g("button", { className: "btn color-picker-item", style: { backgroundColor: l }, "data-pick-value": l, children: r === l ? /* @__PURE__ */ g(K, { icon: "check" }) : null }, l)),
        /* @__PURE__ */ g("button", { className: "btn color-picker-item", "data-pick-value": "", children: /* @__PURE__ */ g(K, { className: "text-fore", icon: "trash" }) })
      ] }, "row")
    ];
  }
}, Ls.defaultProps = {
  ...rt.defaultProps,
  className: "rounded btn square size-sm ghost",
  popClass: "color-picker-pop popup",
  colors: ["#ef4444", "#f97316", "#eab308", "#84cc16", "#22c55e", "#14b8a6", "#0ea5e9", "#6366f1", "#a855f7", "#d946ef", "#ec4899"],
  closeBtn: !0,
  popWidth: "auto",
  popMinWidth: 176
}, Ls);
const Mi = class Mi extends B {
};
Mi.NAME = "ColorPicker", Mi.Component = cf;
let al = Mi;
const Es = 24 * 60 * 60 * 1e3, Y = (n) => n ? (n instanceof Date || (typeof n == "string" && (n = n.trim(), /^\d+$/.test(n) && (n = Number.parseInt(n, 10))), typeof n == "number" && n < 1e10 && (n *= 1e3), n = new Date(n)), n) : /* @__PURE__ */ new Date(), hf = (n, e, t = "day") => {
  if (typeof e == "string") {
    const s = Number.parseInt(e, 10);
    t = e.replace(s.toString(), ""), e = s;
  }
  return n = new Date(Y(n).getTime()), t === "month" ? n.setMonth(n.getMonth() + e) : t === "year" ? n.setFullYear(n.getFullYear() + e) : t === "week" ? n.setDate(n.getDate() + e * 7) : t === "hour" ? n.setHours(n.getHours() + e) : t === "minute" ? n.setMinutes(n.getMinutes() + e) : t === "second" ? n.setSeconds(n.getSeconds() + e) : n.setDate(n.getDate() + e), n;
}, Hn = (n, e = /* @__PURE__ */ new Date()) => Y(n).toDateString() === Y(e).toDateString(), xr = (n, e = /* @__PURE__ */ new Date()) => Y(n).getFullYear() === Y(e).getFullYear(), rh = (n, e = /* @__PURE__ */ new Date()) => (n = Y(n), e = Y(e), n.getFullYear() === e.getFullYear() && n.getMonth() === e.getMonth()), Qp = (n, e = /* @__PURE__ */ new Date()) => {
  n = Y(n), e = Y(e);
  const t = 1e3 * 60 * 60 * 24, s = Math.floor(n.getTime() / t), i = Math.floor(e.getTime() / t);
  return Math.floor((s + 4) / 7) === Math.floor((i + 4) / 7);
}, tm = (n, e) => Hn(Y(e), n), em = (n, e) => Hn(Y(e).getTime() - Es, n), sm = (n, e) => Hn(Y(e).getTime() + Es, n), it = (n, e = "yyyy-MM-dd hh:mm", t = "") => {
  if (n = Y(n), Number.isNaN(n.getDay()))
    return t;
  const s = {
    "M+": n.getMonth() + 1,
    "d+": n.getDate(),
    "h+": n.getHours(),
    "H+": n.getHours() % 12,
    "m+": n.getMinutes(),
    "s+": n.getSeconds(),
    "S+": n.getMilliseconds()
  };
  return /(y+)/i.test(e) && (e.includes("[yyyy-]") && (e = e.replace("[yyyy-]", xr(n) ? "" : "yyyy-")), e = e.replace(RegExp.$1, `${n.getFullYear()}`.substring(4 - RegExp.$1.length))), Object.keys(s).forEach((i) => {
    if (new RegExp(`(${i})`).test(e)) {
      const o = `${s[i]}`;
      e = e.replace(RegExp.$1, RegExp.$1.length === 1 ? o : `00${o}`.substring(o.length));
    }
  }), e;
}, nm = (n, e, t) => {
  const s = {
    full: "yyyy-M-d",
    month: "M-d",
    day: "d",
    str: "{0} ~ {1}",
    ...t
  }, i = it(n, xr(n) ? s.month : s.full);
  if (Hn(n, e))
    return i;
  const o = it(e, xr(n, e) ? rh(n, e) ? s.day : s.month : s.full);
  return s.str.replace("{0}", i).replace("{1}", o);
};
var Hs, Os;
class ah extends F {
  constructor() {
    super(...arguments);
    $(this, Hs, q());
    $(this, Os, (t, s) => {
      var i, o;
      (o = (i = this.props).onChange) == null || o.call(i, t, String(s.item.key || ""));
    });
  }
  componentDidMount() {
    u(b(this, Hs).current).find(".menu-item>.active").scrollIntoView();
  }
  render(t) {
    const { minuteStep: s = 5, hour: i, minute: o } = t, r = [], a = [];
    for (let c = 0; c < 24; ++c)
      r.push({ key: c, text: c < 10 ? `0${c}` : c, active: i === c });
    for (let c = 0; c < 60; c += s)
      a.push({ key: c, text: c < 10 ? `0${c}` : c, active: o === c });
    const l = "col w-10 max-h-full overflow-y-auto scrollbar-thin scrollbar-hover";
    return /* @__PURE__ */ g("div", { className: "time-picker-menu row", ref: b(this, Hs), children: [
      /* @__PURE__ */ g(
        Ie,
        {
          className: l,
          items: r,
          onClickItem: b(this, Os).bind(this, "hour")
        }
      ),
      /* @__PURE__ */ g(
        Ie,
        {
          className: l,
          items: a,
          onClickItem: b(this, Os).bind(this, "minute")
        }
      )
    ] });
  }
}
Hs = new WeakMap(), Os = new WeakMap();
const ll = (n) => {
  if (!n)
    return;
  const e = Y(`1999-01-01 ${n}`);
  if (!Number.isNaN(e.getDay()))
    return e;
};
var Di, Ii, Ri, Ai, js;
let lh = (js = class extends rt {
  constructor(t) {
    super(t);
    $(this, Di, () => {
      this.toggle(!0);
    });
    $(this, Ii, (t) => {
      this.setTime(t.target.value);
    });
    $(this, Ri, (t, s) => {
      this.setTime({ [t]: s });
    });
    $(this, Ai, () => {
      this.setTime("");
    });
    const s = this.state;
    s.value === "now" && (s.value = it(/* @__PURE__ */ new Date(), t.format));
  }
  setTime(t) {
    if (this.props.disabled)
      return;
    let s = "";
    if (typeof t == "string")
      s = t;
    else {
      const [l, c] = (this.state.value || "00:00").split(":"), { hour: d = +l, minute: h = +c } = t;
      s = `${d}:${h}`;
    }
    const i = ll(s), { onInvalid: o, required: r, defaultValue: a } = this.props;
    this.setState({ value: i ? it(i, this.props.format) : r ? a : "" }, () => {
      !i && o && o(s);
    });
  }
  getTime() {
    const t = ll(this.state.value);
    return t ? [t.getHours(), t.getMinutes()] : null;
  }
  _renderTrigger(t, s) {
    const { placeholder: i, icon: o, required: r, disabled: a, readonly: l } = t, { value: c = "", open: d } = s, h = `time-picker-${this.id}`;
    let f;
    return d && !r && c.length ? f = /* @__PURE__ */ g("button", { type: "button", className: "btn size-sm square ghost", onClick: b(this, Ai), children: /* @__PURE__ */ g("span", { className: "close" }) }) : o && (o === !0 ? f = /* @__PURE__ */ g("i", { class: "i-time" }) : f = /* @__PURE__ */ g(K, { icon: o })), [
      /* @__PURE__ */ g("input", { id: h, type: "text", className: "form-control", placeholder: i, value: c, disabled: a, readOnly: l, onFocus: b(this, Di), onChange: b(this, Ii) }, "input"),
      f ? /* @__PURE__ */ g("label", { for: h, className: "input-control-suffix", children: f }, "icon") : null
    ];
  }
  _getTriggerProps(t, s) {
    const i = super._getTriggerProps(t, s);
    return {
      ...i,
      className: N(i.className, "time-picker input-control has-suffix-icon")
    };
  }
  _renderPop(t) {
    const [s, i] = this.getTime() || [];
    return /* @__PURE__ */ g(ah, { hour: s, minute: i, minuteStep: t.minuteStep, onChange: b(this, Ri) });
  }
}, Di = new WeakMap(), Ii = new WeakMap(), Ri = new WeakMap(), Ai = new WeakMap(), js.defaultProps = {
  ...rt.defaultProps,
  popWidth: "auto",
  popMaxHeight: 320,
  minuteStep: 5,
  format: "hh:mm",
  icon: !0
}, js);
tt.addLang({
  zh_cn: {
    today: "今天",
    yearFormat: "{0}年",
    weekNames: ["日", "一", "二", "三", "四", "五", "六"],
    monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
  },
  zh_tw: {
    today: "今天",
    yearFormat: "{0}年",
    weekNames: ["日", "一", "二", "三", "四", "五", "六"],
    monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
  },
  en: {
    today: "Today",
    yearFormat: "{0}",
    weekNames: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"],
    monthNames: ["Jan.", "Feb.", "Mar.", "Apr.", "May.", "Jun.", "Jul.", "Aug.", "Sept.", "Oct.", "Nov.", "Dec."]
  }
});
const df = (n, e, t = 0) => {
  const s = new Date(n, e - 1, 1), i = s.getDay(), o = s.getTime() - (7 + i - t) % 7 * Es;
  return {
    days: 7 * 5,
    startTime: o,
    firstDay: s.getTime()
  };
}, cl = (n, e) => new Set((Array.isArray(n) ? n : [n]).map((t) => it(t, e)));
var Pi;
class uf extends F {
  constructor() {
    super(...arguments);
    $(this, Pi, (t) => {
      const { onClickDate: s } = this.props;
      if (!s)
        return;
      const i = u(t.target).closest(".mini-calendar-day").dataset("date");
      i && s(i);
    });
  }
  render(t) {
    const s = /* @__PURE__ */ new Date(), {
      weekStart: i = 1,
      weekNames: o = tt.getLang("weekNames"),
      monthNames: r = tt.getLang("monthNames"),
      year: a = s.getFullYear(),
      month: l = s.getMonth() + 1,
      highlights: c = [],
      selections: d = []
    } = t, h = [], f = "btn ghost square rounded-full";
    for (let D = 0; D < 7; D++) {
      const L = (i + D) % 7;
      h.push(/* @__PURE__ */ g("div", { className: N("col mini-calendar-day", { "is-weekend": L === 0 || L === 6 }), children: /* @__PURE__ */ g("div", { children: o ? o[L] : L }) }, D));
    }
    const { startTime: m, days: p, firstDay: y } = df(a, l, i), _ = y + p * Es;
    let w = m;
    const x = [], T = "yyyy-MM-dd", k = cl(c, T), E = cl(d, T);
    for (; w <= _; ) {
      const D = [];
      for (let L = 0; L < 7; L++) {
        const R = new Date(w), A = R.getDate(), P = it(R, T), H = R.getDay(), M = rh(R, y), S = N("col mini-calendar-day", {
          active: k.has(P),
          selected: E.has(P),
          "is-first": A === 1,
          "is-in-month": M,
          "is-out-month": !M,
          "is-today": Hn(R, s),
          "is-weekend": H === 0 || H === 6
        });
        D.push(
          /* @__PURE__ */ g("div", { className: S, "data-date": P, children: /* @__PURE__ */ g("a", { className: f, onClick: b(this, Pi), children: A === 1 && r ? r[R.getMonth()] : R.getDate() }) }, P)
        ), w += Es;
      }
      x.push(/* @__PURE__ */ g("div", { className: "row", children: D }, w));
    }
    return /* @__PURE__ */ g("div", { className: "mini-calendar", children: [
      /* @__PURE__ */ g("div", { className: "row", children: h }),
      x
    ] });
  }
}
Pi = new WeakMap();
var Ws, Li;
class hl extends F {
  constructor() {
    super(...arguments);
    $(this, Ws, q());
    $(this, Li, (t) => {
      const { onChange: s } = this.props;
      if (!s)
        return;
      const o = u(t.target).closest("[data-value]").dataset("value");
      o && (s(+o), t.stopPropagation());
    });
  }
  componentDidMount() {
    u(b(this, Ws).current).find(".active").scrollIntoView({ block: "center" });
  }
  render(t) {
    const { className: s, max: i, min: o, value: r } = t, a = [], l = (/* @__PURE__ */ new Date()).getFullYear();
    for (let c = o; c <= i; ++c)
      a.push(/* @__PURE__ */ g(et, { type: "ghost", "data-value": c, active: c === r, className: N(l === c ? "is-current" : ""), onClick: b(this, Li), children: c }, c));
    return /* @__PURE__ */ g("div", { className: s, ref: b(this, Ws), children: a });
  }
}
Ws = new WeakMap(), Li = new WeakMap();
var Ye, zs, Fs, Bs, Vs, Us, Hi, hh, Oi, dh;
class ch extends F {
  constructor(t) {
    super(t);
    $(this, Hi);
    $(this, Oi);
    $(this, Ye, void 0);
    $(this, zs, void 0);
    $(this, Fs, void 0);
    $(this, Bs, void 0);
    $(this, Vs, void 0);
    $(this, Us, void 0);
    C(this, Ye, q()), C(this, zs, (o) => {
      const r = u(o.target).closest("[data-set-date]");
      r.length && this.changeDate(r.dataset("set-date"));
    }), C(this, Fs, () => {
      const { year: o, month: r } = this.state;
      r === 1 ? this.setState({ year: o - 1, month: 12 }) : this.setState({ month: r - 1 });
    }), C(this, Bs, () => {
      const { year: o, month: r } = this.state;
      r === 12 ? this.setState({ year: o + 1, month: 1 }) : this.setState({ month: r + 1 });
    }), C(this, Vs, (o) => {
      this.setState({ year: o, select: "day" });
    }), C(this, Us, (o) => {
      this.setState({ month: o, select: "day" });
    }), this.changeDate = (o) => {
      var r, a;
      if (o.startsWith("today")) {
        let l = /* @__PURE__ */ new Date();
        o.length > 3 && (l = hf(l, o.substring(5).replace("+", ""))), o = it(l, "yyyy-MM-dd");
      }
      (a = (r = this.props).onChange) == null || a.call(r, o);
    };
    const { date: s } = t, i = s ? new Date(s) : /* @__PURE__ */ new Date();
    this.state = {
      select: "day",
      year: i.getFullYear(),
      month: i.getMonth() + 1
    };
  }
  _showSelect(t) {
    this.setState((s) => s.select === t ? { select: "day" } : { select: t });
  }
  componentDidMount() {
    u(b(this, Ye).current).find(".active").scrollIntoView();
  }
  render(t, s) {
    const {
      date: i,
      yearText: o = tt.getLang("yearFormat") || "{0}",
      weekNames: r = tt.getLang("weekNames"),
      monthNames: a = tt.getLang("monthNames"),
      weekStart: l
    } = t, c = i ? new Date(i) : void 0, {
      year: d,
      month: h,
      select: f
    } = s, m = f === "day", p = Y(t.minDate || "1970-1-1"), y = Y(t.maxDate || "2099-12-1");
    return /* @__PURE__ */ g("div", { className: "date-picker-menu row", ref: b(this, Ye), onClick: b(this, zs), children: [
      O(this, Hi, hh).call(this, t),
      /* @__PURE__ */ g("div", { className: "cell", style: "width: 312px", children: [
        /* @__PURE__ */ g("div", { className: "row p-2", children: [
          /* @__PURE__ */ g(et, { type: f === "year" ? "primary-pale" : "ghost", size: "sm", caret: !0, onClick: this._showSelect.bind(this, "year"), children: U(o, d) }),
          /* @__PURE__ */ g(et, { type: f === "month" ? "primary-pale" : "ghost", size: "sm", caret: !0, onClick: this._showSelect.bind(this, "month"), children: a ? a[h - 1] : h }),
          /* @__PURE__ */ g("div", { className: "flex-auto" }),
          m ? /* @__PURE__ */ g("div", { children: [
            /* @__PURE__ */ g(et, { type: "ghost", size: "sm", square: !0, onClick: b(this, Fs), children: /* @__PURE__ */ g("i", { className: "chevron-left" }) }),
            /* @__PURE__ */ g(et, { type: "ghost", size: "sm", square: !0, onClick: b(this, Bs), children: /* @__PURE__ */ g("i", { className: "chevron-right" }) })
          ] }) : null
        ] }),
        m ? /* @__PURE__ */ g(
          uf,
          {
            weekStart: l,
            weekNames: r,
            monthNames: a,
            year: d,
            month: h,
            selections: c,
            onClickDate: this.changeDate
          }
        ) : null,
        f === "year" ? /* @__PURE__ */ g(
          hl,
          {
            className: "date-pick-menu-years overflow-y-auto scrollbar-hover scrollbar-thin",
            value: d,
            min: p.getFullYear(),
            max: y.getFullYear(),
            onChange: b(this, Vs)
          }
        ) : f === "month" ? /* @__PURE__ */ g(
          hl,
          {
            className: "date-pick-menu-month overflow-y-auto scrollbar-hover scrollbar-thin",
            value: h,
            min: 1,
            max: 12,
            onChange: b(this, Us)
          }
        ) : null,
        m ? O(this, Oi, dh).call(this, t) : null
      ] })
    ] });
  }
}
Ye = new WeakMap(), zs = new WeakMap(), Fs = new WeakMap(), Bs = new WeakMap(), Vs = new WeakMap(), Us = new WeakMap(), Hi = new WeakSet(), hh = function(t) {
  let { menu: s } = t;
  return s ? (Array.isArray(s) && (s = { items: s }), /* @__PURE__ */ g(Ie, { ...s })) : null;
}, Oi = new WeakSet(), dh = function(t) {
  let { actions: s } = t;
  const { todayText: i, clearText: o } = t;
  return s || (s = [{ text: i, "data-set-date": it(/* @__PURE__ */ new Date(), "yyyy-MM-dd") }]), Array.isArray(s) && (s = { items: s }), /* @__PURE__ */ g("div", { className: "date-picker-menu-footer", children: [
    /* @__PURE__ */ g(Dt, { btnProps: { className: "ghost text-primary" }, ...s }),
    o ? /* @__PURE__ */ g(et, { type: "ghost text-link", "data-set-date": "", children: o }) : null
  ] });
};
var qs, Gs, Ys, Xs;
let uh = (Xs = class extends rt {
  constructor(t) {
    super(t);
    $(this, qs, void 0);
    $(this, Gs, void 0);
    $(this, Ys, void 0);
    C(this, qs, () => {
      this.toggle(!0);
    }), C(this, Gs, (i) => {
      this.setDate(i.target.value);
    }), C(this, Ys, () => {
      this.setDate("");
    }), this.setDate = (i) => {
      const { onInvalid: o, defaultValue: r = "", required: a, disabled: l, format: c } = this.props;
      if (l)
        return;
      const d = Y(i), h = !i || Number.isNaN(d.getDay());
      this.setState({ value: h ? a ? r : "" : it(d, c) }, () => {
        !h && o && o(i), this.toggle(!1);
      });
    };
    const { value: s } = this.state;
    s && (this.state.value = it(s === "today" ? /* @__PURE__ */ new Date() : s, t.format));
  }
  _renderTrigger(t, s) {
    const { placeholder: i, icon: o, required: r, disabled: a, readonly: l } = t, { value: c = "", open: d } = s, h = `date-picker-${this.id}`;
    let f;
    return d && !r && c.length ? f = /* @__PURE__ */ g("button", { type: "button", className: "btn size-sm square ghost", onClick: b(this, Ys), children: /* @__PURE__ */ g("span", { className: "close" }) }) : o && (o === !0 ? f = /* @__PURE__ */ g("i", { class: "i-calendar" }) : f = /* @__PURE__ */ g(K, { icon: o })), [
      /* @__PURE__ */ g("input", { id: h, type: "text", className: "form-control", placeholder: i, value: c, disabled: a, readOnly: l, onFocus: b(this, qs), onChange: b(this, Gs) }, "input"),
      f ? /* @__PURE__ */ g("label", { for: h, className: "input-control-suffix", children: f }, "icon") : null
    ];
  }
  _getTriggerProps(t, s) {
    const i = super._getTriggerProps(t, s);
    return {
      ...i,
      className: N(i.className, "date-picker input-control has-suffix-icon")
    };
  }
  _getPopProps(t, s) {
    const i = super._getPopProps(t, s);
    return {
      ...i,
      className: N(i.className, "popup")
    };
  }
  _renderPop(t, s) {
    const { weekNames: i, monthNames: o, weekStart: r, yearText: a, todayText: l = tt.getLang("today"), clearText: c, menu: d, actions: h, minDate: f, maxDate: m, required: p } = t;
    return /* @__PURE__ */ g(
      ch,
      {
        onChange: this.setDate,
        date: s.value,
        weekNames: i,
        monthNames: o,
        weekStart: r,
        yearText: a,
        todayText: l,
        clearText: p ? "" : c,
        menu: d,
        actions: h,
        minDate: f,
        maxDate: m
      }
    );
  }
}, qs = new WeakMap(), Gs = new WeakMap(), Ys = new WeakMap(), Xs.defaultProps = {
  ...rt.defaultProps,
  popWidth: "auto",
  popMaxHeight: 320,
  format: "yyyy-MM-dd",
  icon: !0
}, Xs);
const ji = class ji extends B {
};
ji.NAME = "TimePicker", ji.Component = lh;
let dl = ji;
const Wi = class Wi extends B {
};
Wi.NAME = "DatePicker", Wi.Component = uh;
let ul = Wi;
class ff extends F {
  render(e) {
    const { date: t, time: s } = e;
    return /* @__PURE__ */ g("div", { className: "datetime-picker-menu row", children: [
      /* @__PURE__ */ g(ch, { ...t }),
      /* @__PURE__ */ g(ah, { ...s })
    ] });
  }
}
const fl = (n) => {
  if (!n)
    return;
  const e = Y(`1999-01-01 ${n}`);
  if (!Number.isNaN(e.getDay()))
    return e;
};
var Ks, Zs, Js, Qs, tn, en;
let pf = (en = class extends rt {
  constructor(t) {
    super(t);
    $(this, Ks, void 0);
    $(this, Zs, void 0);
    $(this, Js, void 0);
    $(this, Qs, void 0);
    $(this, tn, void 0);
    C(this, Ks, () => {
      this.toggle(!0);
    }), C(this, Zs, (a) => {
      this.setDate(a.target.value);
    }), C(this, Js, () => {
      this.setDate("");
      const { required: a, defaultValue: l } = this.props;
      this.setState({ value: a ? l : "" });
    }), C(this, Qs, (a, l) => {
      this.setTime({ [a]: l });
    }), C(this, tn, (a) => {
      this.setTime(a.target.value);
    }), this.setDate = (a) => {
      const { onInvalid: l, defaultValue: c = "", required: d, dateFormat: h, disabled: f, joiner: m } = this.props;
      if (f)
        return;
      const p = Y(a), y = !a || Number.isNaN(p.getDay()), _ = it(p, h), [, w = "00:00"] = this.state.value.split(m);
      this.setState({ value: y ? d ? c : "" : `${_}${m}${w}` }, () => {
        !y && l && l(a);
      });
    };
    const { value: s } = this.state, { dateFormat: i, timeFormat: o, joiner: r } = t;
    s && (this.state.value = it(s === "today" ? /* @__PURE__ */ new Date() : s, `${i}${r}${o}`));
  }
  setTime(t) {
    const { onInvalid: s, required: i, defaultValue: o, timeFormat: r, joiner: a, disabled: l, dateFormat: c } = this.props;
    if (l)
      return;
    let d = "";
    if (typeof t == "string")
      d = t;
    else {
      const [, m = "00:00"] = this.state.value.split(a), [p, y] = m.split(":"), { hour: _ = +p, minute: w = +y } = t;
      d = `${_}:${w}`;
    }
    const h = fl(d), f = this.state.value.split(a)[0] || it(/* @__PURE__ */ new Date(), c);
    this.setState({ value: h ? `${f}${a}${it(h, r)}` : i ? o : "" }, () => {
      !h && s && s(d);
    });
  }
  getTime() {
    const t = fl(this.state.value);
    return t ? [t.getHours(), t.getMinutes()] : null;
  }
  _renderTrigger(t, s) {
    const { placeholder: i, icon: o, required: r, disabled: a, readonly: l } = t, { value: c = "", open: d } = s, h = `datetime-picker-${this.id}`;
    let f;
    return d && !r && c.length ? f = /* @__PURE__ */ g(
      "button",
      {
        type: "button",
        className: "btn size-sm square ghost",
        onClick: b(this, Js),
        children: /* @__PURE__ */ g("span", { className: "close" })
      }
    ) : o && (o === !0 ? f = /* @__PURE__ */ g("i", { class: "i-calendar" }) : f = /* @__PURE__ */ g(K, { icon: o })), [
      /* @__PURE__ */ g(
        "input",
        {
          id: h,
          type: "text",
          className: "form-control",
          placeholder: i,
          value: c,
          disabled: a,
          readOnly: l,
          onFocus: b(this, Ks),
          onChange: (m) => {
            b(this, Zs).call(this, m), b(this, tn).call(this, m);
          }
        },
        "input"
      ),
      f ? /* @__PURE__ */ g("label", { for: h, class: "input-control-suffix", children: f }, "icon") : null
    ];
  }
  _getTriggerProps(t, s) {
    const i = super._getTriggerProps(t, s);
    return {
      ...i,
      className: N(i.className, "datetime-picker input-control has-suffix-icon")
    };
  }
  _getPopProps(t, s) {
    const i = super._getPopProps(t, s);
    return {
      ...i,
      className: N(i.className, "popup")
    };
  }
  _renderPop(t, s) {
    const { weekNames: i, monthNames: o, weekStart: r, yearText: a, todayText: l = tt.getLang("today"), clearText: c, menu: d, actions: h, minDate: f, maxDate: m, required: p, minuteStep: y } = t, [_, w] = this.getTime() || [], x = {
      date: {
        onChange: this.setDate,
        date: s.value,
        weekNames: i,
        monthNames: o,
        weekStart: r,
        yearText: a,
        todayText: l,
        clearText: p ? "" : c,
        menu: d,
        actions: h,
        minDate: f,
        maxDate: m
      },
      time: {
        hour: _,
        minute: w,
        minuteStep: y,
        onChange: b(this, Qs)
      }
    };
    return /* @__PURE__ */ g(ff, { ...x });
  }
}, Ks = new WeakMap(), Zs = new WeakMap(), Js = new WeakMap(), Qs = new WeakMap(), tn = new WeakMap(), en.defaultProps = {
  ...rt.defaultProps,
  popWidth: "auto",
  popMaxHeight: 310,
  dateFormat: "yyyy-MM-dd",
  timeFormat: "hh:mm",
  joiner: " ",
  icon: !0,
  minuteStep: 5
}, en);
const zi = class zi extends B {
};
zi.NAME = "DatetimePicker", zi.Component = pf;
let pl = zi;
const Qo = "show", ml = "in", mf = '[data-dismiss="modal"]', gt = class gt extends J {
  constructor() {
    super(...arguments), this._timer = 0, this._handleClick = (e) => {
      const t = e.target, s = t.closest(".modal");
      !s || s !== this.modalElement || (t.closest(mf) || this.options.backdrop === !0 && t === s) && (e.preventDefault(), this.hide());
    };
  }
  get modalElement() {
    return this.element;
  }
  get shown() {
    return this.modalElement.classList.contains(Qo);
  }
  get dialog() {
    return this.modalElement.querySelector(".modal-dialog");
  }
  get rob() {
    return this._rob;
  }
  _observeResize() {
    var e;
    if (this.options.responsive && typeof ResizeObserver < "u") {
      (e = this._rob) == null || e.disconnect();
      const { dialog: t } = this;
      if (t) {
        const s = new ResizeObserver(() => {
          if (!this.shown)
            return;
          const i = t.clientWidth, o = t.clientHeight, [r, a] = this._lastDialogSize || [];
          (r !== i || a !== o) && (this._lastDialogSize = [i, o], this.layout());
        });
        s.observe(t), this._rob = s;
      }
    }
  }
  afterInit() {
    this.on("click", this._handleClick), this.options.show && this.show(), this._observeResize();
  }
  destroy() {
    var e;
    super.destroy(), (e = this._rob) == null || e.disconnect(), this._rob = void 0;
  }
  show(e) {
    const { modalElement: t } = this;
    if (this.shown)
      return u(t).css("z-index", `${gt.zIndex++}`), !1;
    this.setOptions(e);
    const { animation: s, backdrop: i, className: o, style: r } = this.options;
    return u(t).setClass({
      "modal-trans": s,
      "modal-no-backdrop": !i
    }, Qo, o).css({
      zIndex: `${gt.zIndex++}`,
      ...r
    }), this.layout(), this.emit("show"), this._setTimer(() => {
      u(t).addClass(ml), this._setTimer(() => {
        this.emit("shown");
      });
    }, 50), !0;
  }
  hide() {
    return this.shown ? (u(this.modalElement).removeClass(ml), this.emit("hide"), this._setTimer(() => {
      u(this.modalElement).removeClass(Qo), this.emit("hidden");
    }), !0) : !1;
  }
  layout(e, t) {
    if (!this.shown)
      return;
    const { dialog: s } = this;
    if (!s)
      return;
    const i = u(s);
    if (t = t ?? this.options.size, t) {
      i.removeAttr("data-size");
      const l = { width: "", height: "" };
      typeof t == "object" ? (l.width = t.width, l.height = t.height) : typeof t == "string" && ["md", "sm", "lg", "full"].includes(t) ? i.attr("data-size", t) : t && (l.width = t), i.css(l);
    }
    e = e ?? this.options.position ?? "fit";
    const o = s.clientWidth, r = s.clientHeight;
    this._lastDialogSize = [o, r], typeof e == "function" && (e = e({ width: o, height: r }));
    const a = {
      top: null,
      left: null,
      bottom: null,
      right: null,
      alignSelf: "center"
    };
    typeof e == "number" ? (a.alignSelf = "flex-start", a.top = e) : typeof e == "object" && e ? (a.alignSelf = "flex-start", Object.assign(a, e)) : e === "fit" ? (a.alignSelf = "flex-start", a.top = `${Math.max(0, Math.floor((window.innerHeight - r) / 3))}px`) : e === "bottom" ? a.alignSelf = "flex-end" : e === "top" ? a.alignSelf = "flex-start" : e !== "center" && typeof e == "string" && (a.alignSelf = "flex-start", a.top = e), i.css(a), u(this.modalElement).css("justifyContent", a.left ? "flex-start" : "center");
  }
  _setTimer(e, t) {
    this._timer && (clearTimeout(this._timer), this._timer = 0), e && (this.options.animation ? this._timer = window.setTimeout(e, t ?? this.options.transTime) : e());
  }
  static hide(e) {
    var t;
    (t = gt.query(e)) == null || t.hide();
  }
  static show(e) {
    var t;
    (t = gt.query(e)) == null || t.show();
  }
};
gt.NAME = "Modal", gt.MULTI_INSTANCE = !0, gt.DEFAULT = {
  position: "fit",
  show: !0,
  keyboard: !0,
  animation: !0,
  backdrop: !0,
  responsive: !0,
  transTime: 300
}, gt.zIndex = 1500;
let Bt = gt;
u(window).on(`resize.${Bt.NAMESPACE}`, () => {
  Bt.getAll().forEach((n) => {
    const e = n;
    e.shown && e.options.responsive && e.layout();
  });
});
u(document).on(`to-hide.${Bt.NAMESPACE}`, (n, e) => {
  Bt.hide(e == null ? void 0 : e.target);
});
const ba = class ba extends F {
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
      headerClass: t,
      title: s
    } = this.props;
    return st(e) ? e : e === !1 || !s ? null : /* @__PURE__ */ g("div", { className: N("modal-header", t), children: /* @__PURE__ */ g("div", { className: "modal-title", children: s }) });
  }
  renderActions() {
    const {
      actions: e,
      closeBtn: t
    } = this.props;
    return !t && !e ? null : st(e) ? e : /* @__PURE__ */ g("div", { className: "modal-actions", children: [
      e ? /* @__PURE__ */ g(Dt, { ...e }) : null,
      t ? /* @__PURE__ */ g("button", { type: "button", class: "btn square ghost", "data-dismiss": "modal", children: /* @__PURE__ */ g("span", { class: "close" }) }) : null
    ] });
  }
  renderBody() {
    const {
      body: e,
      bodyClass: t
    } = this.props;
    return e ? st(e) ? e : /* @__PURE__ */ g("div", { className: N("modal-body", t), children: e }) : null;
  }
  renderFooter() {
    const {
      footer: e,
      footerClass: t,
      footerActions: s
    } = this.props;
    return st(e) ? e : e === !1 || !s ? null : /* @__PURE__ */ g("div", { className: N("modal-footer", t), children: s ? /* @__PURE__ */ g(Dt, { ...s }) : null });
  }
  render() {
    const {
      className: e,
      style: t,
      contentClass: s,
      children: i
    } = this.props;
    return /* @__PURE__ */ g("div", { className: N("modal-dialog", e), style: t, children: /* @__PURE__ */ g("div", { className: N("modal-content", s), children: [
      this.renderHeader(),
      this.renderActions(),
      this.renderBody(),
      i,
      this.renderFooter()
    ] }) });
  }
};
ba.defaultProps = { closeBtn: !0 };
let $r = ba;
const wa = class wa extends F {
  constructor() {
    super(...arguments), this._ref = q(), this.state = {}, this._watchIframeHeight = () => {
      var s, i;
      const e = (i = (s = this._ref.current) == null ? void 0 : s.contentWindow) == null ? void 0 : i.document;
      if (!e)
        return;
      let t = this._rob;
      t == null || t.disconnect(), t = new ResizeObserver(() => {
        const o = e.body, r = e.documentElement, a = Math.ceil(Math.max(o.scrollHeight, o.offsetHeight, r.offsetHeight)) + 1;
        this.setState({ height: a });
      }), t.observe(e.body), t.observe(e.documentElement), this._rob = t;
    };
  }
  componentDidMount() {
    this.props.watchHeight && this._watchIframeHeight();
  }
  componentWillUnmount() {
    var e;
    (e = this._rob) == null || e.disconnect();
  }
  render() {
    const { url: e, watchHeight: t } = this.props;
    return /* @__PURE__ */ g(
      "iframe",
      {
        className: "modal-iframe",
        style: this.state,
        src: e,
        ref: this._ref,
        onLoad: t ? this._watchIframeHeight : void 0
      }
    );
  }
};
wa.defaultProps = {
  watchHeight: !0
};
let Cr = wa;
function gf(n, e) {
  const { custom: t, title: s, content: i } = e;
  return {
    body: i,
    title: s,
    ...typeof t == "function" ? t() : t
  };
}
async function yf(n, e) {
  const { dataType: t = "html", url: s, request: i, custom: o, title: r, replace: a = !0, executeScript: l = !0 } = e, d = await (await fetch(s, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "X-ZUI-Modal": "true"
    },
    ...i
  })).text();
  if (t !== "html")
    try {
      const h = JSON.parse(d);
      return {
        title: r,
        ...o,
        ...h
      };
    } catch {
    }
  return a !== !1 && t === "html" ? [d] : {
    title: r,
    ...o,
    body: t === "html" ? /* @__PURE__ */ g(Rn, { className: "modal-body", html: d, executeScript: l }) : d
  };
}
async function bf(n, e) {
  const { url: t, custom: s, title: i, size: o } = e, r = typeof o == "object" && typeof o.height == "number";
  return {
    title: i,
    ...s,
    body: /* @__PURE__ */ g(Cr, { url: t, watchHeight: !r })
  };
}
const wf = {
  custom: gf,
  ajax: yf,
  iframe: bf
}, tr = "loading";
var _t, Xe, xt, sn, Tr, nn, kr;
const Gt = class Gt extends Bt {
  constructor() {
    super(...arguments);
    $(this, sn);
    $(this, nn);
    $(this, _t, void 0);
    $(this, Xe, void 0);
    $(this, xt, void 0);
  }
  get id() {
    return b(this, Xe);
  }
  get loading() {
    var t;
    return (t = b(this, _t)) == null ? void 0 : t.classList.contains(tr);
  }
  get shown() {
    var t;
    return !!((t = b(this, _t)) != null && t.classList.contains("show"));
  }
  get modalElement() {
    let t = b(this, _t);
    if (!t) {
      const { options: s } = this;
      let i = b(this, Xe);
      i || (i = s.id || `modal-${u.guid++}`, C(this, Xe, i));
      const { $element: o } = this;
      if (t = o.find(`#${i}`)[0], !t) {
        const r = this.key;
        t = u("<div>").attr({
          id: i,
          "data-key": r
        }).data(this.constructor.KEY, this).css(s.style || {}).setClass("modal modal-async load-indicator", s.className).appendTo(o)[0];
      }
      C(this, _t, t);
    }
    return t;
  }
  get $emitter() {
    const t = b(this, _t);
    return t ? u(t) : this.$element;
  }
  afterInit() {
    super.afterInit(), this.on("hidden", () => {
      this.options.destoryOnHide && this.destroy();
    });
  }
  show(t) {
    return super.show(t) ? (this.buildDialog(), !0) : !1;
  }
  destroy() {
    super.destroy();
    const t = b(this, _t);
    t && (u(t).removeData(this.constructor.KEY).remove(), C(this, _t, void 0));
  }
  render(t) {
    return super.render(t), this.buildDialog();
  }
  async buildDialog() {
    if (this.loading)
      return !1;
    b(this, xt) && clearTimeout(b(this, xt));
    const { modalElement: t, options: s } = this, i = u(t), { type: o, loadTimeout: r, loadingText: a = null } = s, l = wf[o];
    if (!l)
      return console.warn(`Modal: Cannot build modal with type "${o}"`), !1;
    i.attr("data-loading", a).addClass(tr), r && C(this, xt, window.setTimeout(() => {
      C(this, xt, 0), O(this, nn, kr).call(this, this.options.timeoutTip);
    }, r));
    const c = await l.call(this, t, s);
    return c === !1 ? await O(this, nn, kr).call(this, this.options.failedTip) : c && typeof c == "object" && await O(this, sn, Tr).call(this, c), b(this, xt) && (clearTimeout(b(this, xt)), C(this, xt, 0)), this.layout(), await Qn(100), i.removeClass(tr), !0;
  }
  static open(t) {
    return new Promise((s) => {
      const { container: i = document.body, ...o } = t, r = { show: !0, ...o };
      !r.type && r.url && (r.type = "ajax");
      const a = Gt.ensure(i, r), l = `${Gt.NAMESPACE}.open${u.guid++}`;
      a.on(`hidden${l}`, () => {
        a.off(l), s(a);
      }), a.show();
    });
  }
  static async alert(t) {
    typeof t == "string" && (t = { message: t });
    const { type: s, message: i, icon: o, iconClass: r = "icon-lg muted", actions: a = "confirm", onClickAction: l, custom: c, key: d = "__alert", ...h } = t, f = (typeof c == "function" ? c() : c) || {};
    let m = typeof i == "object" && i.html ? /* @__PURE__ */ g("div", { dangerouslySetInnerHTML: { __html: i.html } }) : /* @__PURE__ */ g("div", { children: i });
    o ? m = /* @__PURE__ */ g("div", { className: N("modal-body row gap-4 items-center", f.bodyClass), children: [
      /* @__PURE__ */ g("div", { className: `icon ${o} ${r}` }),
      m
    ] }) : m = /* @__PURE__ */ g("div", { className: N("modal-body", f.bodyClass), children: m });
    const p = [];
    (Array.isArray(a) ? a : [a]).forEach((w) => {
      w = {
        ...typeof w == "string" ? { key: w } : w
      }, typeof w.key == "string" && (w.text || (w.text = tt.getLang(w.key, w.key)), w.btnType || (w.btnType = `btn-wide ${w.key === "confirm" ? "primary" : "btn-default"}`)), w && p.push(w);
    }, []);
    let y;
    const _ = p.length ? {
      gap: 4,
      items: p,
      onClickItem: ({ item: w, event: x }) => {
        const T = Gt.query(x.target, d);
        y = w.key, (l == null ? void 0 : l(w, T)) !== !1 && T && T.hide();
      }
    } : void 0;
    return await Gt.open({
      key: d,
      type: "custom",
      size: 400,
      className: "modal-alert",
      content: m,
      backdrop: "static",
      custom: { footerActions: _, ...f },
      ...h
    }), y;
  }
  static async confirm(t) {
    typeof t == "string" && (t = { message: t });
    const { onClickAction: s, onResult: i, ...o } = t;
    return await Gt.alert({
      actions: ["confirm", "cancel"],
      onClickAction: (a, l) => {
        i == null || i(a.key === "confirm", l), s == null || s(a, l);
      },
      ...o
    }) === "confirm";
  }
};
_t = new WeakMap(), Xe = new WeakMap(), xt = new WeakMap(), sn = new WeakSet(), Tr = function(t) {
  return new Promise((s) => {
    if (Array.isArray(t))
      return u(this.modalElement).html(t[0]), this.layout(), this._observeResize(), s();
    const { afterRender: i, ...o } = t;
    t = {
      afterRender: (r) => {
        this.layout(), i == null || i(r), this._observeResize(), s();
      },
      ...o
    }, Ts(
      /* @__PURE__ */ g($r, { ...t }),
      this.modalElement
    );
  });
}, nn = new WeakSet(), kr = function(t) {
  if (t)
    return O(this, sn, Tr).call(this, {
      body: /* @__PURE__ */ g("div", { className: "modal-load-failed", children: t })
    });
}, Gt.DEFAULT = {
  ...Bt.DEFAULT,
  loadTimeout: 1e4,
  destoryOnHide: !0
};
let pt = Gt;
const vf = '[data-toggle="modal"]', va = class va extends J {
  get modal() {
    return this._modal;
  }
  get container() {
    const { container: e } = this.options;
    return typeof e == "string" ? document.querySelector(e) : e instanceof HTMLElement ? e : document.body;
  }
  show() {
    var e;
    return (e = this._initModal()) == null ? void 0 : e.show();
  }
  hide() {
    var e;
    return (e = this._modal) == null ? void 0 : e.hide();
  }
  _getBuilderOptions() {
    const {
      container: e,
      ...t
    } = this.options, s = t, i = this.$element.attr("href") || "";
    return s.type || (s.target || i[0] === "#" ? s.type = "static" : s.type = s.type || (s.url || i ? "ajax" : "custom")), !s.url && (s.type === "iframe" || s.type === "ajax") && i[0] !== "#" && (s.url = i), !s.key && s.id && (s.key = s.id), s;
  }
  _initModal() {
    const e = this._getBuilderOptions();
    let t = this._modal;
    if (t)
      return t.setOptions(e), t;
    if (e.type === "static") {
      const s = this._getStaticModalElement();
      if (!s)
        return;
      t = Bt.ensure(s, e);
    } else
      t = pt.ensure(this.container, e);
    return this._modal = t, t.on("destroyed", () => {
      this._modal = void 0;
    }), t;
  }
  _getStaticModalElement() {
    let e = this.options.target;
    if (!e) {
      const { $element: t } = this;
      if (t.is("a")) {
        const s = t.attr("href");
        s != null && s.startsWith("#") && (e = s);
      }
    }
    return this.container.querySelector(e || ".modal");
  }
};
va.NAME = "ModalTrigger";
let hi = va;
u(document).on(`click${hi.NAMESPACE}`, vf, (n) => {
  const e = u(n.currentTarget);
  if (e.length && !e.is("[disabled],.disabled")) {
    const t = hi.ensure(e);
    t && (t.show(), n.preventDefault());
  }
});
var on;
let _f = (on = class extends jo {
  beforeRender() {
    const e = super.beforeRender();
    return e.className = N(e.className, e.type ? `nav-${e.type}` : "", {
      "nav-stacked": e.stacked
    }), e;
  }
}, on.NAME = "nav", on);
const Fi = class Fi extends B {
};
Fi.NAME = "Nav", Fi.Component = _f;
let gl = Fi;
function Ns(n, e) {
  const t = n.pageTotal || Math.ceil(n.recTotal / n.recPerPage);
  return typeof e == "string" && (e === "first" ? e = 1 : e === "last" ? e = t : e === "prev" ? e = n.page - 1 : e === "next" ? e = n.page + 1 : e === "current" ? e = n.page : e = Number.parseInt(e, 10)), e = e !== void 0 ? Math.max(1, Math.min(e < 0 ? t + e : e, t)) : n.page, {
    ...n,
    pageTotal: t,
    page: e
  };
}
function xf({
  key: n,
  type: e,
  btnType: t,
  page: s,
  format: i,
  pagerInfo: o,
  linkCreator: r,
  ...a
}) {
  const l = Ns(o, s);
  return a.text === void 0 && !a.icon && i && (a.text = typeof i == "function" ? i(l) : U(i, l)), a.url === void 0 && r && (a.url = typeof r == "function" ? r(l) : U(r, l)), a.disabled === void 0 && (a.disabled = s !== void 0 && l.page === o.page), /* @__PURE__ */ g(et, { type: t, ...a });
}
function $f({
  key: n,
  type: e,
  page: t,
  text: s = "",
  pagerInfo: i,
  children: o,
  ...r
}) {
  const a = Ns(i, t);
  return s = typeof s == "function" ? s(a) : U(s, a), /* @__PURE__ */ g(jc, { ...r, children: [
    o,
    s
  ] });
}
function Cf({
  key: n,
  type: e,
  btnType: t,
  count: s = 12,
  pagerInfo: i,
  onClick: o,
  linkCreator: r,
  ...a
}) {
  if (!i.pageTotal)
    return;
  const l = { ...a, square: !0 }, c = () => (l.text = "", l.icon = "icon-ellipsis-h", l.disabled = !0, /* @__PURE__ */ g(et, { type: t, ...l })), d = (f, m) => {
    const p = [];
    for (let y = f; y <= m; y++) {
      l.text = y, delete l.icon, l.disabled = !1;
      const _ = Ns(i, y);
      r && (l.url = typeof r == "function" ? r(_) : U(r, _)), p.push(/* @__PURE__ */ g(et, { type: t, ...l, onClick: o }));
    }
    return p;
  };
  let h = [];
  return h = [...d(1, 1)], i.pageTotal <= 1 || (i.pageTotal <= s ? h = [...h, ...d(2, i.pageTotal)] : i.page < s - 2 ? h = [...h, ...d(2, s - 2), c(), ...d(i.pageTotal, i.pageTotal)] : i.page > i.pageTotal - s + 3 ? h = [...h, c(), ...d(i.pageTotal - s + 3, i.pageTotal)] : h = [...h, c(), ...d(i.page - Math.ceil((s - 4) / 2), i.page + Math.floor((s - 4) / 2)), c(), ...d(i.pageTotal, i.pageTotal)]), h;
}
function Tf({
  type: n,
  pagerInfo: e,
  linkCreator: t,
  items: s = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1e3, 2e3],
  dropdown: i = {},
  itemProps: o,
  ...r
}) {
  var l;
  i.items = i.items || s.map((c) => {
    const d = { ...e, recPerPage: c };
    return {
      ...o,
      text: `${c}`,
      active: c === e.recPerPage,
      url: typeof t == "function" ? t(d) : U(t, d)
    };
  });
  const { text: a = "" } = r;
  return r.text = typeof a == "function" ? a(e) : U(a, e), i.menu = { ...i.menu, className: N((l = i.menu) == null ? void 0 : l.className, "pager-size-menu") }, /* @__PURE__ */ g(Yc, { type: "dropdown", dropdown: i, ...r });
}
function kf({
  key: n,
  page: e,
  type: t,
  btnType: s,
  pagerInfo: i,
  size: o,
  onClick: r,
  onChange: a,
  linkCreator: l,
  ...c
}) {
  const d = { ...c };
  let h;
  const f = (y) => {
    var _;
    h = Number((_ = y.target) == null ? void 0 : _.value) || 1, h = h > i.pageTotal ? i.pageTotal : h;
  }, m = (y) => {
    if (!(y != null && y.target))
      return;
    h = h <= i.pageTotal ? h : i.pageTotal;
    const _ = Ns(i, h);
    a && !a({ info: _, event: y }) || (y.target.href = d.url = typeof l == "function" ? l(_) : U(l, _));
  }, p = Ns(i, e || 0);
  return d.url = typeof l == "function" ? l(p) : U(l, p), /* @__PURE__ */ g("div", { className: N("input-group", "pager-goto-group", o ? `size-${o}` : ""), children: [
    /* @__PURE__ */ g("input", { type: "number", class: "form-control", max: i.pageTotal, min: "1", onInput: f }),
    /* @__PURE__ */ g(et, { type: s, ...d, onClick: m })
  ] });
}
var Ne;
let fh = (Ne = class extends Dt {
  get pagerInfo() {
    const { page: e = 1, recTotal: t = 0, recPerPage: s = 10 } = this.props;
    return { page: +e, recTotal: +t, recPerPage: +s, pageTotal: s ? Math.ceil(t / s) : 0 };
  }
  isBtnItem(e) {
    return e === "link" || e === "nav" || e === "size-menu" || e === "goto" || super.isBtnItem(e);
  }
  getItemRenderProps(e, t, s) {
    const i = super.getItemRenderProps(e, t, s), o = t.type || "item", { pagerInfo: r } = this;
    return o === "info" ? Object.assign(i, { pagerInfo: r }) : (o === "link" || o === "size-menu" || o === "nav" || o === "goto") && Object.assign(i, { pagerInfo: r, linkCreator: e.linkCreator }), i;
  }
}, Ne.NAME = "pager", Ne.defaultProps = {
  btnProps: {
    btnType: "ghost",
    size: "sm"
  }
}, Ne.ItemComponents = {
  ...Dt.ItemComponents,
  link: xf,
  info: $f,
  nav: Cf,
  "size-menu": Tf,
  goto: kf
}, Ne);
const Bi = class Bi extends B {
};
Bi.NAME = "Pager", Bi.Component = fh;
let yl = Bi;
const Vi = class Vi extends B {
};
Vi.NAME = "Pick", Vi.Component = rt;
let bl = Vi;
var Ke, rn, an, Ui;
class ph extends F {
  constructor(t) {
    super(t);
    $(this, Ke, q());
    $(this, rn, q());
    $(this, an, (t) => {
      var i, o;
      const s = t.target.value;
      (o = (i = this.props).onSearch) == null || o.call(i, s), this.setState({ search: s }), t.stopPropagation();
    });
    $(this, Ui, (t) => {
      var s, i;
      t.stopPropagation(), (i = (s = this.props).onClear) == null || i.call(s), this.setState({ search: "" }, () => this.focus());
    });
    this.state = { search: t.defaultSearch ?? "" };
  }
  focus() {
    var t;
    (t = b(this, Ke).current) == null || t.focus();
  }
  componentDidMount() {
    this.focus();
  }
  componentDidUpdate() {
    const { inline: t } = this.props;
    if (t) {
      const { current: s } = b(this, rn), { current: i } = b(this, Ke);
      if (s && i) {
        const o = u(i).parent();
        o.width(Math.ceil(Math.min(s.clientWidth, o.closest(".picker").outerWidth() - 32)));
      }
    }
  }
  render(t, s) {
    const { placeholder: i, inline: o } = t, { search: r } = s, a = r.trim().length > 0;
    let l;
    return o ? l = /* @__PURE__ */ g("div", { className: "picker-search-measure", ref: b(this, rn), children: r }) : a ? l = /* @__PURE__ */ g("button", { type: "button", className: "btn picker-search-clear square size-sm ghost", onClick: b(this, Ui), children: /* @__PURE__ */ g("span", { className: "close" }) }) : l = /* @__PURE__ */ g("span", { className: "magnifier" }), /* @__PURE__ */ g("div", { className: `picker-search${o ? " is-inline" : ""}`, children: [
      /* @__PURE__ */ g(
        "input",
        {
          className: "form-control picker-search-input",
          type: "text",
          placeholder: i,
          value: r,
          onChange: b(this, an),
          onInput: b(this, an),
          ref: b(this, Ke)
        }
      ),
      l
    ] });
  }
}
Ke = new WeakMap(), rn = new WeakMap(), an = new WeakMap(), Ui = new WeakMap();
var Ze, ln, cn, hn;
class Sf extends qo {
  constructor() {
    super(...arguments);
    $(this, Ze, void 0);
    $(this, ln, void 0);
    $(this, cn, void 0);
    $(this, hn, void 0);
    C(this, Ze, q()), C(this, ln, (t) => {
      const { onDeselect: s, state: { selections: i } } = this.props, o = u(t.target).closest(".picker-deselect-btn").attr("data-value");
      s && i.length && typeof o == "string" && s(o), t.stopPropagation();
    }), C(this, cn, (t) => {
      this.props.changeState({ search: t });
    }), C(this, hn, () => {
      this.props.togglePop(!0, { search: "" });
    }), this._renderSelection = (t) => /* @__PURE__ */ g("div", { className: "picker-multi-selection", children: [
      /* @__PURE__ */ g("span", { className: "text", children: /* @__PURE__ */ g(he, { content: t.text }) }),
      this.props.disabled ? null : /* @__PURE__ */ g("div", { className: "picker-deselect-btn btn size-xs ghost", onClick: b(this, ln), "data-value": t.value, children: /* @__PURE__ */ g("span", { className: "close" }) })
    ] }, t.value);
  }
  _handleClick(t) {
    var s;
    super._handleClick(t), (s = b(this, Ze).current) == null || s.focus();
  }
  _getClass(t) {
    return N(
      super._getClass(t),
      "picker-select picker-select-multi form-control",
      t.disabled ? "disabled" : ""
    );
  }
  _renderSearch(t) {
    const { state: { search: s }, searchHint: i } = t;
    return /* @__PURE__ */ g(
      ph,
      {
        inline: !0,
        ref: b(this, Ze),
        defaultSearch: s,
        onSearch: b(this, cn),
        onClear: b(this, hn),
        placeholder: i
      }
    );
  }
  _renderTrigger(t) {
    const { state: { selections: s = [], open: i }, search: o, placeholder: r, children: a } = this.props, l = i && o;
    return !l && !s.length ? /* @__PURE__ */ g("span", { className: "picker-select-placeholder", children: r }, "selections") : [
      /* @__PURE__ */ g("div", { className: "picker-multi-selections", children: [
        s.map(this._renderSelection),
        l ? this._renderSearch(t) : null
      ] }, "selections"),
      a,
      /* @__PURE__ */ g("span", { class: "caret" }, "caret")
    ];
  }
  _renderValue(t) {
    const { name: s, state: { value: i = "" }, id: o, valueList: r, emptyValue: a } = t;
    if (!r.length)
      return super._renderValue(t);
    if (s)
      if (this.hasInput)
        u(`#${o}`).val(i);
      else {
        const l = r.length ? r : [a];
        return /* @__PURE__ */ g("select", { id: o, multiple: !0, className: "pick-value", name: s.endsWith("[]") ? s : `${s}[]`, style: { display: "none" }, children: l.map((c) => /* @__PURE__ */ g("option", { value: c, children: c }, c)) });
      }
    return null;
  }
  componentDidMount() {
    super.componentDidMount();
    const { id: t, valueList: s, emptyValue: i } = this.props;
    u(`#${t}`).val(s.length ? s : [i]);
  }
  componentDidUpdate(t) {
    const { id: s, state: i, name: o, valueList: r, emptyValue: a } = this.props;
    o && t.state.value !== i.value && u(`#${s}`).val(r.length ? r : [a]).trigger("change", _r);
  }
}
Ze = new WeakMap(), ln = new WeakMap(), cn = new WeakMap(), hn = new WeakMap();
var dn, qi, Gi, Yi, Xi, mh;
class Ef extends qo {
  constructor() {
    super(...arguments);
    $(this, Xi);
    $(this, dn, q());
    $(this, qi, (t) => {
      this.props.disabled || (this.props.onClear(), t.stopPropagation());
    });
    $(this, Gi, (t) => {
      this.props.changeState({ search: t });
    });
    $(this, Yi, () => {
      this.props.togglePop(!0, { search: "" });
    });
  }
  _handleClick(t) {
    var s;
    super._handleClick(t), (s = b(this, dn).current) == null || s.focus();
  }
  _getClass(t) {
    return N(
      super._getClass(t),
      "picker-select picker-select-single form-control",
      t.disabled ? "disabled" : ""
    );
  }
  _renderSearch(t) {
    const { state: { search: s } } = t;
    return /* @__PURE__ */ g(
      ph,
      {
        ref: b(this, dn),
        defaultSearch: s,
        onSearch: b(this, Gi),
        onClear: b(this, Yi),
        placeholder: O(this, Xi, mh).call(this)
      }
    );
  }
  _renderTrigger(t) {
    const { children: s, state: { selections: i = [], open: o }, placeholder: r, search: a, disabled: l, clearable: c } = t, [d] = i, h = o && a;
    let f;
    h ? f = this._renderSearch(t) : d ? f = /* @__PURE__ */ g("span", { className: "picker-single-selection", children: /* @__PURE__ */ g(he, { content: d.text }) }, "main") : f = /* @__PURE__ */ g("span", { className: "picker-select-placeholder", children: r }, "main");
    const m = c && !h ? /* @__PURE__ */ g("button", { type: "button", className: "btn picker-deselect-btn size-sm square ghost", disabled: l, onClick: b(this, qi), children: /* @__PURE__ */ g("span", { className: "close" }) }, "deselect") : null;
    return [
      f,
      s,
      m,
      h ? null : /* @__PURE__ */ g("span", { className: "caret" }, "caret")
    ];
  }
}
dn = new WeakMap(), qi = new WeakMap(), Gi = new WeakMap(), Yi = new WeakMap(), Xi = new WeakSet(), mh = function() {
  const { searchHint: t, state: { value: s, selections: i } } = this.props;
  let o = t;
  if (o === void 0) {
    const r = i.find((a) => a.value === s);
    r && typeof r.text == "string" && (o = r.text);
  }
  return o;
};
const gh = (n, e, t = "is-match") => n.reduce((s, i) => [...s].reduce((o, r) => {
  if (typeof r != "string")
    return o.push(r), o;
  const a = r.toLowerCase().split(i);
  if (a.length === 1)
    return o.push(r), o;
  let l = 0;
  return a.forEach((c, d) => {
    d && (o.push(/* @__PURE__ */ g("span", { class: t, children: r.substring(l, l + i.length) })), l += i.length), o.push(r.substring(l, l + c.length)), l += c.length;
  }), o;
}, []), e);
var Ki, Zi, yh, Ji, bh, Qi;
class Nf extends da {
  constructor() {
    super(...arguments);
    $(this, Zi);
    $(this, Ji);
    $(this, Ki, q());
    $(this, Qi, ({ item: t }) => {
      const s = t.key, { multiple: i, onToggleValue: o, onSelect: r, togglePop: a } = this.props;
      i ? o(s) : (r(s), a(!1, { search: "" }));
    });
  }
  componentDidMount() {
    super.componentDidMount();
    const t = this.element;
    t && u(t).on("mouseenter.picker.zui", ".menu-item", (s) => {
      const i = u(s.currentTarget);
      this.setHoverItem(i.children("a").attr("data-value") ?? "");
    });
  }
  componentWillUnmount() {
    super.componentWillUnmount();
    const t = this.element;
    t && u(t).off(".picker.zui");
  }
  setHoverItem(t) {
    this.props.changeState({ hoverItem: t }, () => {
      const s = O(this, Zi, yh).call(this);
      s != null && s.length && s.scrollIntoView({ block: "nearest", behavior: "smooth" });
    });
  }
  _getClass(t) {
    return N(
      super._getClass(t),
      "picker-menu"
    );
  }
  _renderPop(t) {
    const { menu: s } = t;
    return /* @__PURE__ */ g(
      Ie,
      {
        ref: b(this, Ki),
        className: "picker-menu-list",
        items: O(this, Ji, bh).call(this),
        onClickItem: b(this, Qi),
        ...s
      }
    );
  }
}
Ki = new WeakMap(), Zi = new WeakSet(), yh = function() {
  const t = this.element;
  if (t)
    return u(t).find(".menu-item>a.hover");
}, Ji = new WeakSet(), bh = function() {
  const { selections: t, items: s, hoverItem: i, search: o } = this.props.state, r = new Set(t.map((d) => d.value));
  let a = !1;
  const l = u.unique(o.toLowerCase().split(" ").filter((d) => d.length)), c = s.reduce((d, h) => {
    const {
      value: f = "",
      keys: m,
      text: p,
      className: y,
      content: _,
      ...w
    } = h;
    return f === i && (a = !0), p && d.push({
      key: f,
      active: r.has(f),
      text: _ ? null : typeof p == "string" ? gh(l, [p]) : /* @__PURE__ */ g(he, { content: p }),
      className: N(y, { hover: f === i }),
      "data-value": f,
      content: _,
      ...w
    }), d;
  }, []);
  return !a && c.length && (c[0].className = N(c[0].className, "hover")), c;
}, Qi = new WeakMap();
var Je, $t, At, Qe, us;
let di = (us = class extends rt {
  constructor(t) {
    super(t);
    $(this, Je, void 0);
    $(this, $t, void 0);
    $(this, At, void 0);
    $(this, Qe, void 0);
    C(this, At, 0), this.isEmptyValue = (r) => b(this, Qe).has(r), this.toggleValue = (r, a) => {
      if (!this.props.multiple)
        return a || r !== this.value ? this.setValue(r) : this.setValue();
      const { valueList: l } = this, c = l.indexOf(r);
      if (a !== c >= 0)
        return c > -1 ? l.splice(c, 1) : l.push(r), this.setValue(l);
    }, this.deselect = (r) => {
      const { valueList: a } = this, l = new Set(this.formatValueList(r)), c = a.filter((d) => !l.has(d));
      this.setValue(c);
    }, this.clear = () => {
      this.setValue();
    }, this.select = (r) => {
      const a = this.formatValueList(r), l = this.props.multiple ? [...this.valueList, ...a] : a[0];
      return this.setValue(l);
    }, this.isSelected = (r) => this.valueList.includes(r), u.extend(this.state, {
      loading: !1,
      search: "",
      items: t.items,
      selections: []
    });
    const { valueSplitter: s = ",", emptyValue: i = "" } = this.props;
    C(this, Qe, new Set(i.split(s)));
    const { items: o } = this.state;
    if (Array.isArray(o) && o.length) {
      if (o.forEach((r) => r.value = String(r.value)), t.limitValueInList) {
        const r = new Set(o.map((a) => a.value));
        this.state.value = this.valueList.filter((a) => r.has(a)).join(t.valueSplitter);
      }
      !this.valueList.length && t.required && !t.multiple && (this.state.value = o[0].value);
    }
  }
  get value() {
    return this.state.value;
  }
  get valueList() {
    return this.formatValueList(this.state.value);
  }
  get firstEmptyValue() {
    return b(this, Qe).values().next().value;
  }
  async load() {
    let t = b(this, $t);
    t && t.abort(), t = new AbortController(), C(this, $t, t);
    const { items: s, searchDelay: i } = this.props, { search: o } = this.state;
    let r = [];
    if (typeof s == "function") {
      if (await Qn(i || 500), b(this, $t) !== t || (r = await s(o, { signal: t.signal }), b(this, $t) !== t))
        return r;
    } else if (o.length) {
      const a = u.unique(o.toLowerCase().split(" ").filter((l) => l.length));
      r = s, a.length && (r = s.reduce((l, c) => {
        const {
          value: d,
          keys: h = "",
          text: f
        } = c;
        return a.every((m) => d.toLowerCase().includes(m) || h.toLowerCase().includes(m) || typeof f == "string" && f.toLowerCase().includes(m)) && l.push(c), l;
      }, []));
    } else
      r = s;
    return C(this, $t, void 0), r;
  }
  async update(t) {
    const { state: s, props: i } = this, o = b(this, Je) || {}, r = {};
    if (C(this, Je, o), t || o.search !== s.search || i.items !== o.items) {
      const l = await this.load();
      r.items = l.filter((c) => (c.value = String(c.value), !this.isEmptyValue(c.value))), r.loading = !1, o.items = i.items, o.search = s.search;
    }
    if (t || o.value !== s.value) {
      const l = r.items || s.items, c = new Map(l.map((d) => [d.value, d]));
      r.selections = this.valueList.reduce((d, h) => (this.isEmptyValue(h) || d.push(c.get(h) || { value: h }), d), []), o.value = s.value;
    }
    const a = r.items;
    i.required && !i.multiple && this.isEmptyValue(this.state.value) && Array.isArray(a) && a.length && (r.value = a[0].value), Object.keys(r).length && await this.changeState(r);
  }
  async tryUpdate() {
    b(this, At) && clearTimeout(b(this, At)), C(this, At, window.setTimeout(() => {
      C(this, At, 0), this.update();
    }, 50));
  }
  componentDidUpdate(t, s) {
    super.componentDidUpdate(t, s), this.tryUpdate();
  }
  componentDidMount() {
    super.componentDidMount(), this.tryUpdate();
  }
  componentWillUnmount() {
    var t;
    (t = b(this, $t)) == null || t.abort(), C(this, $t, void 0), C(this, Je, void 0), clearTimeout(b(this, At)), super.componentWillUnmount();
  }
  _getTriggerProps(t, s) {
    return {
      ...super._getTriggerProps(t, s),
      multiple: t.multiple,
      placeholder: t.placeholder,
      search: t.search,
      searchHint: t.searchHint,
      disabled: t.disabled,
      clearable: !!this.valueList.length && !t.required,
      valueList: this.valueList,
      emptyValue: this.firstEmptyValue,
      onDeselect: this.deselect,
      onSelect: this.select,
      onClear: this.clear,
      onToggleValue: this.toggleValue
    };
  }
  _getPopProps(t, s) {
    return {
      ...super._getPopProps(t, s),
      menu: t.menu,
      multiple: t.multiple,
      search: t.search,
      searchHint: t.searchHint,
      onDeselect: this.deselect,
      onSelect: this.select,
      onClear: this.clear,
      onToggleValue: this.toggleValue
    };
  }
  _getTrigger(t) {
    return t.Trigger || (t.multiple ? Sf : Ef);
  }
  formatValueList(t) {
    let s = [];
    return typeof t == "string" && t.length ? s = u.unique(t.split(this.props.valueSplitter ?? ",")) : Array.isArray(t) && (s = u.unique(t)), s.filter((i) => !this.isEmptyValue(i));
  }
  formatValue(t) {
    const s = this.formatValueList(t);
    return s.length ? s.join(this.props.valueSplitter ?? ",") : this.firstEmptyValue;
  }
  setValue(t = []) {
    if (this.props.disabled)
      return;
    !Array.isArray(t) && typeof t != "string" && (t = t !== null ? String(t) : this.firstEmptyValue);
    let s = this.formatValueList(t);
    if (!s.length)
      return this.changeState({ value: this.firstEmptyValue });
    const { items: i, limitValueInList: o } = this.props;
    if (o) {
      const a = new Set((Array.isArray(i) ? i : this.state.items).map((l) => String(l.value)));
      s = s.filter((l) => a.has(l));
    }
    const r = this.formatValue(s);
    return this.changeState({ value: r });
  }
}, Je = new WeakMap(), $t = new WeakMap(), At = new WeakMap(), Qe = new WeakMap(), us.defaultProps = {
  ...rt.defaultProps,
  className: "picker",
  valueSplitter: ",",
  limitValueInList: !0,
  search: !0,
  emptyValue: ""
}, us.Pop = Nf, us);
const to = class to extends B {
};
to.NAME = "Picker", to.Component = di;
let wl = to;
const eo = class eo extends J {
  init() {
    const { trigger: e } = this.options;
    this.initTarget(), this.initMask(), this.initArrow(), this.createPopper(), this.toggle = () => {
      const t = () => {
        if (this.$target.hasClass("hidden")) {
          this.show();
          return;
        }
        this.hide();
      }, { delay: s } = this.options;
      s === 0 ? t() : setTimeout(t, s);
    }, this.$element.addClass("z-50").on(e, this.toggle);
  }
  destroy() {
    this.cleanup(), this.$element.off(this.options.trigger, this.toggle), this.$target.remove();
  }
  computePositionConfig() {
    const { placement: e, strategy: t } = this.options, s = {
      placement: e,
      strategy: t,
      middleware: []
    }, { flip: i, shift: o, arrow: r, offset: a } = this.options;
    return i && s.middleware.push(Wo()), o && s.middleware.push(o === !0 ? ks() : ks(o)), r && s.middleware.push(fr({ element: this.$arrow[0] })), a && s.middleware.push(zo(a)), s;
  }
  createPopper() {
    const e = this.element, t = this.$target[0];
    this.cleanup = la(e, t, () => {
      Vo(e, t, this.computePositionConfig()).then(({ x: s, y: i, placement: o, middlewareData: r }) => {
        if (Object.assign(t.style, {
          left: `${s}px`,
          top: `${i}px`
        }), !fr || !r.arrow)
          return;
        const { x: a, y: l } = r.arrow, c = {
          top: "bottom",
          right: "left",
          bottom: "top",
          left: "right"
        }[o.split("-")[0]];
        Object.assign(this.$arrow[0].style, {
          left: a != null ? `${a}px` : "",
          top: l != null ? `${l}px` : "",
          right: "",
          bottom: "",
          [c]: "-4px"
        });
      });
    });
  }
  initTarget() {
    const e = this.$element.data("target");
    if (!e)
      throw new Error("popsvers trigger must have target.");
    const t = u(e);
    if (!t.length)
      throw new Error("popovers target must exist.");
    const { strategy: s } = this.options;
    t.addClass(s), t.addClass("hidden"), t.addClass("z-50"), t.on("click", (i) => {
      u(i.target).data("dismiss") === "popovers" && this.hide();
    }), this.$target = t;
  }
  show() {
    var e;
    this.$target.removeClass("hidden"), (e = this.$mask) == null || e.removeClass("hidden");
  }
  hide() {
    var e;
    this.$target.addClass("hidden"), (e = this.$mask) == null || e.addClass("hidden");
  }
  initMask() {
    const { mask: e } = this.options;
    if (!e)
      return;
    const t = u('<div class="fixed top-0 right-0 bottom-0 left-0 z-40 hidden"></div>');
    t.on("click", () => {
      this.hide();
    }), this.$target.parent().append(t), this.$mask = t;
  }
  initArrow() {
    const { arrow: e } = this.options;
    e && (this.$arrow = u('<div class="fl-arrow bg-inherit rotate-45 absolute w-2 h-2"></div>'), this.$target.append(this.$arrow));
  }
};
eo.NAME = "Popovers", eo.DEFAULT = {
  placement: "bottom",
  strategy: "fixed",
  flip: !0,
  shift: { padding: 5 },
  arrow: !1,
  offset: 1,
  trigger: "click",
  mask: !0,
  delay: 0
};
let Sr = eo;
var un, fn, Jt, so, pn, mn, gn, Er, yn;
let wh = (yn = class extends F {
  constructor(t) {
    super(t);
    $(this, gn);
    $(this, un, void 0);
    $(this, fn, q());
    $(this, Jt, 0);
    $(this, so, (t) => {
      const s = this.state.value;
      t.stopPropagation(), this.setState({ value: "" }, () => {
        const { onChange: i, onClear: o } = this.props;
        o == null || o(t), this.focus(), s.trim() !== "" && (i == null || i("", t));
      });
    });
    $(this, pn, (t) => {
      const s = this.state.value, i = t.target.value, { onChange: o } = this.props;
      this.setState({ value: i }, () => {
        !o || s === i || (O(this, gn, Er).call(this), C(this, Jt, window.setTimeout(() => {
          o(i, t), C(this, Jt, 0);
        }, this.props.delay || 0)));
      });
    });
    $(this, mn, (t) => {
      const s = t.type === "focus";
      this.setState({ focus: s }, () => {
        const i = s ? this.props.onFocus : this.props.onBlur;
        i == null || i(t);
      });
    });
    this.state = { focus: !1, value: t.defaultValue || "" }, C(this, un, t.id || `search-box-${u.guid++}`);
  }
  get id() {
    return b(this, un);
  }
  get input() {
    return b(this, fn).current;
  }
  focus() {
    var t;
    (t = this.input) == null || t.focus();
  }
  componentWillUnmount() {
    O(this, gn, Er).call(this);
  }
  render(t, s) {
    const { style: i, className: o, rootClass: r, rootStyle: a, readonly: l, disabled: c, circle: d, placeholder: h, mergeIcon: f, searchIcon: m, clearIcon: p } = t, { focus: y, value: _ } = s, { id: w } = this, x = typeof _ != "string" || !_.trim().length;
    let T, k, E;
    return m && (E = m === !0 ? /* @__PURE__ */ g("span", { class: "magnifier" }) : /* @__PURE__ */ g(K, { icon: m })), !f && m && (T = /* @__PURE__ */ g("label", { for: w, class: "input-control-prefix", children: E }, "prefix")), p && !x ? k = /* @__PURE__ */ g(
      "button",
      {
        type: "button",
        class: "btn ghost size-sm square rounded-full",
        onClick: b(this, so),
        children: p === !0 ? /* @__PURE__ */ g("span", { class: "close" }) : /* @__PURE__ */ g(K, { icon: p })
      }
    ) : f && m && (k = E), k && (k = /* @__PURE__ */ g("label", { for: w, class: "input-control-suffix", children: k }, "suffix")), /* @__PURE__ */ g("div", { class: N("search-box input-control", r, { focus: y, empty: x, "has-prefix-icon": T, "has-suffix-icon": k }), style: a, children: [
      T,
      /* @__PURE__ */ g(
        "input",
        {
          ref: b(this, fn),
          id: w,
          type: "text",
          class: N("form-control", o, { "rounded-full": d }),
          style: i,
          placeholder: h,
          disabled: c,
          readonly: l,
          value: _,
          onInput: b(this, pn),
          onChange: b(this, pn),
          onFocus: b(this, mn),
          onBlur: b(this, mn)
        }
      ),
      k
    ] });
  }
}, un = new WeakMap(), fn = new WeakMap(), Jt = new WeakMap(), so = new WeakMap(), pn = new WeakMap(), mn = new WeakMap(), gn = new WeakSet(), Er = function() {
  b(this, Jt) && clearTimeout(b(this, Jt)), C(this, Jt, 0);
}, yn.defaultProps = {
  clearIcon: !0,
  searchIcon: !0,
  delay: 500
}, yn);
var fs;
let im = (fs = class extends B {
}, fs.NAME = "SearchBox", fs.Component = wh, fs);
const no = class no extends B {
};
no.NAME = "Toolbar", no.Component = Dt;
let vl = no;
const Mf = '[data-toggle="tooltip"]', io = class io extends Et {
  _getRenderOptions() {
    const { type: e, className: t, title: s, content: i } = this.options;
    let o = s, r = i;
    return r === void 0 && (r = o, o = void 0), {
      ...super._getRenderOptions(),
      title: o,
      content: r,
      className: N("tooltip", e, t, o ? "tooltip-has-title" : ""),
      contentClass: o ? "tooltip-content" : ""
    };
  }
};
io.NAME = "Tooltip", io.DEFAULT = {
  ...Et.DEFAULT,
  trigger: "hover",
  delay: 500,
  closeBtn: !1,
  popup: !1,
  name: "tooltip",
  animation: "fade",
  destroyOnHide: 5e3
};
let Ht = io;
u(document).on(`click${Ht.NAMESPACE} mouseenter${Ht.NAMESPACE}`, Mf, (n) => {
  const e = u(n.currentTarget);
  if (e.length && !e.data(Ht.KEY)) {
    const t = e.data("trigger") || "hover";
    if ((n.type === "mouseover" ? "hover" : "click") !== t)
      return;
    Ht.ensure(e, { show: Ht.DEFAULT.delay || !0 }), n.preventDefault();
  }
});
function Df({
  type: n,
  component: e,
  className: t,
  children: s,
  content: i,
  style: o,
  attrs: r,
  url: a,
  disabled: l,
  active: c,
  icon: d,
  text: h,
  target: f,
  trailingIcon: m,
  hint: p,
  checked: y,
  actions: _,
  show: w,
  level: x = 0,
  items: T,
  ...k
}) {
  const E = Array.isArray(_) ? { items: _ } : _;
  return E && (E.btnProps || (E.btnProps = { size: "sm" }), E.className = N("tree-actions not-nested-toggle", E.className)), /* @__PURE__ */ g(
    "div",
    {
      className: N("tree-item-content", t, { disabled: l, active: c }),
      title: p,
      "data-target": f,
      style: { paddingLeft: `calc(${x} * var(--tree-indent, 20px))` },
      "data-level": x,
      ...k,
      children: [
        /* @__PURE__ */ g("span", { class: `tree-toggle-icon${T ? " state" : ""}`, children: T ? /* @__PURE__ */ g("span", { class: `caret-${w ? "down" : "right"}` }) : null }),
        typeof y == "boolean" ? /* @__PURE__ */ g("div", { class: `tree-checkbox checkbox-primary${y ? " checked" : ""}`, children: /* @__PURE__ */ g("label", {}) }) : null,
        /* @__PURE__ */ g(K, { icon: d, className: "tree-icon" }),
        a ? /* @__PURE__ */ g("a", { className: "text tree-link not-nested-toggle", href: a, style: o, ...r, children: h }) : /* @__PURE__ */ g("span", { class: "text", style: o, ...r, children: h }),
        /* @__PURE__ */ g(he, { content: i }),
        s,
        E ? /* @__PURE__ */ g(Dt, { ...E }) : null,
        /* @__PURE__ */ g(K, { icon: m, className: "tree-trailing-icon" })
      ]
    }
  );
}
var ps;
let vh = (ps = class extends ia {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "tree";
  }
  getNestedMenuProps(e) {
    const t = super.getNestedMenuProps(e), { collapsedIcon: s, expandedIcon: i, normalIcon: o, itemActions: r } = this.props;
    return {
      collapsedIcon: s,
      expandedIcon: i,
      normalIcon: o,
      itemActions: r,
      ...t
    };
  }
  getItemRenderProps(e, t, s) {
    const i = super.getItemRenderProps(e, t, s), { collapsedIcon: o, expandedIcon: r, normalIcon: a, itemActions: l } = e;
    return i.icon === void 0 && (i.icon = i.items ? i.show ? r : o : a), i.actions === void 0 && l && (i.actions = typeof l == "function" ? l(t) : l), i;
  }
  renderToggleIcon() {
    return null;
  }
  beforeRender() {
    const e = super.beforeRender(), { hover: t } = this.props;
    return t && (e.className = N(e.className, "tree-hover")), e;
  }
}, ps.ItemComponents = {
  item: Df
}, ps.NAME = "tree", ps);
const oo = class oo extends B {
};
oo.NAME = "Tree", oo.Component = vh;
let _l = oo;
const ro = class ro extends J {
  init() {
    const { multiple: e, defaultFileList: t, limitSize: s } = this.options;
    this.fileMap = /* @__PURE__ */ new Map(), this.renameMap = /* @__PURE__ */ new Map(), this.itemMap = /* @__PURE__ */ new Map(), this.dataTransfer = new DataTransfer(), this.limitBytes = s ? Qd(s) : Number.MAX_VALUE, this.currentBytes = 0, e || (this.options.limitCount = 1), this.$element.addClass("upload"), this.initFileInputCash(), this.initUploadCash(), t && this.addFileItem(t);
  }
  initUploadCash() {
    const { name: e, uploadText: t, uploadIcon: s, listPosition: i, btnClass: o, tip: r, draggable: a } = this.options;
    this.$list = u('<ul class="file-list py-1"></ul>');
    const l = u(`<span class="upload-tip">${r}</span>`);
    if (!a) {
      if (this.$label = u(`<label class="btn ${o}" for="${e}">${t}</label>`), s) {
        const f = u(`<i class="icon icon-${s}"></i>`);
        this.$label.prepend(f);
      }
      const h = i === "bottom" ? [this.$label, l, this.$list] : [this.$list, this.$label, l];
      this.$element.append(this.$input, ...h);
      return;
    }
    const c = u(`<span class="text-primary">${t}</span>`);
    if (s) {
      const h = u(`<i class="icon icon-${s} mr-1"></i>`);
      c.prepend(h);
    }
    this.$label = u(`<label class="draggable-area col justify-center items-center cursor-pointer block w-full h-16 border border-dashed border-gray" for="${e}"></label>`).append(c).append(l), this.bindDragEvent();
    const d = i === "bottom" ? [this.$label, this.$list] : [this.$list, this.$label];
    this.$element.append(this.$input, ...d);
  }
  bindDragEvent() {
    this.$label.on("dragover", (e) => {
      e.preventDefault(), console.log("dragover"), this.$label.hasClass("border-primary") || (this.$label.removeClass("border-gray"), this.$label.addClass("border-primary"));
    }).on("dragleave", (e) => {
      e.preventDefault(), this.$label.removeClass("border-primary"), this.$label.addClass("border-gray");
    }).on("drop", (e) => {
      var s;
      e.preventDefault(), this.$label.removeClass("border-primary"), this.$label.addClass("border-gray");
      const t = Array.from(((s = e.dataTransfer) == null ? void 0 : s.files) ?? []);
      console.log(e.dataTransfer.files), this.addFileItem(t);
    });
  }
  initFileInputCash() {
    const { name: e, multiple: t, accept: s } = this.options;
    this.$input = u("<input />").addClass("hidden").prop("type", "file").prop("name", e).prop("id", e).prop("multiple", t).on("change", (i) => {
      const o = i.target.files;
      if (!o)
        return;
      const r = [...o];
      this.addFileItem(r);
    }), s && this.$input.prop("accept", s);
  }
  addFile(e) {
    const { multiple: t, onSizeChange: s } = this.options;
    t || (this.renameMap.clear(), this.fileMap.clear(), this.dataTransfer.items.clear(), this.currentBytes = e.size), this.renameMap.set(e.name, e.name), this.fileMap.set(e.name, e), this.dataTransfer.items.add(e), this.$input.prop("files", this.dataTransfer.files), this.currentBytes += e.size, s == null || s(this.currentBytes);
  }
  renameDuplicatedFile(e) {
    if (!this.fileMap.has(e.name))
      return e;
    const t = e.name.lastIndexOf(".");
    if (t === -1)
      return this.renameDuplicatedFile(new File([e], `${e.name}(1)`));
    const s = e.name.substring(0, t), i = e.name.substring(t);
    return this.renameDuplicatedFile(new File([e], `${s}(1)${i}`));
  }
  filterFiles(e) {
    const { accept: t } = this.options;
    if (!t)
      return e;
    const s = t.replace(/\s/g, "").split(","), i = [], o = [], r = [];
    return s.forEach((a) => {
      a.endsWith("/*") ? o.push(a.substring(0, a.length - 1)) : a.includes("/") ? i.push(a) : a.startsWith(".") && r.push(a);
    }), e.filter((a) => i.includes(a.type) || o.some((l) => a.type.startsWith(l)) || r.some((l) => a.name.endsWith(l)));
  }
  addFileItem(e) {
    e = this.filterFiles(e);
    const { multiple: t, limitCount: s, exceededSizeHint: i, exceededCountHint: o, onAdd: r } = this.options;
    if (t) {
      const c = [];
      for (let d of e) {
        if (s && this.fileMap.size >= s)
          return r == null || r(c), alert(o);
        if (this.currentBytes + d.size > this.limitBytes)
          return r == null || r(c), alert(i);
        d = this.renameDuplicatedFile(d);
        const h = this.createFileItem(d);
        this.itemMap.set(d.name, h), this.$list.append(h), c.push(d);
      }
      r == null || r(c);
      return;
    }
    if (e[0].size > this.limitBytes)
      return;
    const a = this.renameDuplicatedFile(e[0]), l = this.createFileItem(a);
    this.itemMap.clear(), this.itemMap.set(a.name, l), this.$list.empty().append(l), r == null || r(a);
  }
  deleteFileItem(e) {
    var l;
    const t = this.renameMap.get(e) ?? e;
    this.renameMap.delete(e);
    const s = this.fileMap.get(t);
    if (!s)
      return;
    const { onDelete: i, onSizeChange: o } = this.options, r = this.itemMap.get(s.name);
    this.itemMap.delete(s.name), r == null || r.addClass("hidden");
    const a = (l = r == null ? void 0 : r.find(".file-delete")) == null ? void 0 : l.data("tooltip");
    a && (a.destroy(), a.tooltip.remove()), setTimeout(() => r == null ? void 0 : r.remove(), 3e3), i == null || i(s), this.fileMap.delete(s.name), this.currentBytes -= s.size, o == null || o(this.currentBytes), this.dataTransfer = new DataTransfer(), this.fileMap.forEach((c) => this.dataTransfer.items.add(c)), this.$input.prop("files", this.dataTransfer.files);
  }
  renameFileItem(e, t) {
    var o, r;
    const s = this.renameMap.get(e.name);
    this.renameMap.set(e.name, t), s && (e = this.fileMap.get(s) ?? e);
    const i = this.itemMap.get(e.name);
    i && (this.itemMap.set(t, i).delete(e.name), (r = (o = this.options).onRename) == null || r.call(o, t, e.name), this.fileMap.delete(e.name), this.dataTransfer = new DataTransfer(), e = new File([e], t), this.fileMap.set(t, e).forEach((a) => this.dataTransfer.items.add(a)), this.$input.prop("files", this.dataTransfer.files));
  }
  createFileItem(e) {
    const { showIcon: t } = this.options;
    return this.addFile(e), u('<li class="file-item my-1 flex items-center gap-2"></li>').append(t ? this.fileIcon() : null).append(this.createFileInfo(e)).append(this.createRenameContainer(e));
  }
  fileIcon() {
    const { icon: e } = this.options;
    return u(`<i class="icon icon-${e}"></i>`);
  }
  fileRenameBtn() {
    const { useIconBtn: e, renameText: t, renameIcon: s, renameClass: i } = this.options;
    if (e) {
      const o = u(`<button class="btn btn-link h-5 w-5 p-0 ${i}"><i class="icon icon-${s}"></i></button>`).prop("type", "button").addClass("file-action file-rename");
      return new Ht(o, { title: t }), o;
    }
    return u("<button />").prop("type", "button").addClass(`btn size-sm rounded-sm text-primary canvas file-action file-rename ${i}`).html(t);
  }
  fileDeleteBtn() {
    const { useIconBtn: e, deleteText: t, deleteIcon: s, deleteClass: i } = this.options;
    if (e) {
      const o = u(`<button class="btn btn-link h-5 w-5 p-0 ${i}"><i class="icon icon-${s}"></i></button>`).prop("type", "button").addClass("file-action file-delete");
      return o.data("tooltip", new Ht(o, { title: t })), o;
    }
    return u("<button />").html(t).prop("type", "button").addClass(`btn size-sm rounded-sm text-primary canvas file-action file-delete ${i}`);
  }
  fileName(e) {
    return u(`<span class="file-name">${e}</span>`);
  }
  fileSize(e) {
    return u(`<span class="file-size text-gray">${Bn(e)}</span>`);
  }
  createFileInfo(e) {
    const { renameBtn: t, deleteBtn: s, showSize: i } = this.options, o = u('<div class="file-info flex items-center gap-2"></div>');
    return o.append(this.fileName(e.name)), i && o.append(this.fileSize(e.size)), t && o.append(
      this.fileRenameBtn().on("click", (r) => {
        o.addClass("hidden").closest(".file-item").find(".input-rename-container.hidden").removeClass("hidden");
        const a = u(r.target).closest("li").find("input")[0];
        a.focus(), a.value.lastIndexOf(".") !== -1 && a.setSelectionRange(0, a.value.lastIndexOf("."));
      })
    ), s && o.append(
      this.fileDeleteBtn().on("click", () => this.deleteFileItem(e.name))
    ), o;
  }
  createRenameContainer(e) {
    const { confirmText: t, cancelText: s, duplicatedHint: i } = this.options, o = u('<div class="input-group input-rename-container hidden"></div>'), r = u("<input />").addClass("form-control").prop("type", "text").prop("autofocus", !0).prop("defaultValue", e.name).on("keydown", (d) => {
      if (d.key === "Enter") {
        const h = o.closest(".file-item"), f = h.find(".file-name");
        if (f.html() === r.val()) {
          o.addClass("hidden"), h.find(".file-info.hidden").removeClass("hidden");
          return;
        }
        if (this.fileMap.has(r.val()))
          return alert(i);
        this.renameFileItem(e, r.val()), o.addClass("hidden"), h.find(".file-info.hidden").removeClass("hidden"), f.html(r.val());
      } else
        d.key === "Escape" && (r.val(e.name), o.addClass("hidden").closest(".file-item").find(".file-info.hidden").removeClass("hidden"));
    }), a = u("<button />").addClass("btn primary rename-confirm-btn").prop("type", "button").html(t).on("click", () => {
      const d = o.closest(".file-item"), h = d.find(".file-name");
      if (h.html() === r.val()) {
        o.addClass("hidden"), d.find(".file-info.hidden").removeClass("hidden");
        return;
      }
      if (this.fileMap.has(r.val()))
        return alert(i);
      this.renameFileItem(e, r.val()), o.addClass("hidden"), d.find(".file-info.hidden").removeClass("hidden"), h.html(r.val());
    }), l = u("<button />").prop("type", "button").addClass("btn rename-cancel-btn").html(s).on("click", () => {
      r.val(e.name), o.addClass("hidden").closest(".file-item").find(".file-info.hidden").removeClass("hidden");
    }), c = u('<div class="btn-group"></div').append(a).append(l);
    return o.append(r).append(c);
  }
};
ro.NAME = "Upload", ro.DEFAULT = {
  uploadText: "上传文件",
  confirmText: "确定",
  cancelText: "取消",
  useIconBtn: !0,
  renameBtn: !0,
  renameText: "重命名",
  renameIcon: "edit",
  renameClass: "",
  deleteBtn: !0,
  deleteText: "删除",
  deleteIcon: "trash",
  deleteClass: "",
  showIcon: !0,
  multiple: !0,
  listPosition: "bottom",
  limitSize: !1,
  icon: "file-o",
  btnClass: "",
  tip: "",
  draggable: !1,
  showSize: !0
};
let Nr = ro;
const ao = class ao extends Nr {
  init() {
    this.initUploadButtonItemCash(), this.options.onSizeChange = () => {
      this.$uploadInfo.html(this.options.totalCountText.replace("%s", this.fileMap.size.toString()).replace("%s", this.fileMap.size.toString())), this.fileMap.size > 0 ? (this.$tip.remove(), this.$list.append(this.$uploadButtonItem)) : (this.$uploadButtonItem.remove(), this.$label.append(this.$tip));
    }, super.init(), this.$list.addClass("flex");
  }
  initUploadButtonItemCash() {
    this.$uploadButtonItem = u(`<label class="upload-button-item order-last" for="${this.options.name}" />`).addClass("flex justify-center items-center cursor-pointer").css({ width: 120, height: 120, background: "var(--color-slate-100)" }).append(u('<i class="icon icon-plus" />'));
  }
  initUploadCash() {
    const { name: e, tip: t, uploadText: s, uploadIcon: i, totalCountText: o } = this.options;
    this.$list = u('<ul class="file-list py-1 flex-wrap gap-x-4 gap-y-4"></ul>'), this.$label = u('<div class="draggable-area relative block w-full border border-dashed border-gray"></div>').css({ minHeight: 64 });
    const r = u(`<label for="${e}" class="text-primary cursor-pointer">${s}</label>`);
    if (i) {
      const a = u(`<i class="icon icon-${i} mr-1"></i>`);
      r.prepend(a);
    }
    this.$tip = u('<div class="absolute inset-0 col justify-center items-center"></div>').append(r), t && this.$tip.append(u(`<span class="upload-tip">${t}</span>`)), this.$label.append(this.$tip), this.$label.append(this.$input, this.$list), this.bindDragEvent(), this.$element.append(this.$label), this.$uploadInfo = u('<div class="py-1" />').css({ color: "var(--color-slate-500)" }).html(o.replace("%s", this.fileMap.size.toString()).replace("%s", this.fileMap.size.toString())), this.$element.append(this.$uploadInfo);
  }
  filterFiles(e) {
    const { accept: t } = this.options;
    if (t === "image/*")
      return e.filter((i) => i.type.includes("image"));
    const s = t.replace(/\s/g, "").replace(/\./g, "image/").split(",");
    return e.filter((i) => s.includes(i.type));
  }
  createFileItem(e) {
    const t = super.createFileItem(e).addClass("relative").removeClass("flex items-center gap-2 my-1");
    this.setImageUrl(e, t);
    const { deleteBtn: s, showSize: i } = this.options;
    return s && t.append(
      this.fileDeleteBtn().addClass("absolute right-0 top-0 text-white").css({ background: "var(--color-slate-500)" }).on("click", () => this.deleteFileItem(e.name))
    ), i && t.append(
      this.fileSize(e.size).addClass("file-size label text-white circle darker absolute px-1 hidden").removeClass("text-gray").css({ top: 96, left: 4 })
    ), t;
  }
  setImageUrl(e, t) {
    const s = new FileReader();
    s.onload = () => {
      u('<div class="img flex-none" />').addClass("rounded").css({ backgroundImage: `url(${s.result})` }).prependTo(t);
    }, s.readAsDataURL(e);
  }
  createFileInfo(e) {
    const t = this.fileRenameBtn().addClass("flex-none").on("click", (i) => {
      const o = u(i.target).closest(".file-item");
      o.find(".file-info").addClass("hidden"), o.find(".input-rename-container").removeClass("hidden");
      const r = o.find("input")[0];
      r.focus(), r.value.lastIndexOf(".") !== -1 && r.setSelectionRange(0, r.value.lastIndexOf("."));
    });
    return u('<div class="file-info flex justify-between items-center"></div>').css({ width: 120 }).append(u(`<div class="file-name py-1 ellipsis">${e.name}</div>`)).append(t);
  }
  createRenameContainer(e) {
    const { duplicatedHint: t } = this.options, s = u("<input />").addClass("input-rename-container border-primary border hidden").prop("type", "text").prop("autofocus", !0).prop("defaultValue", e.name).css({ width: 120 }).on("keydown", (i) => {
      if (i.key === "Enter") {
        const o = s.closest(".file-item").find(".file-name");
        if (o.html() === s.val()) {
          s.addClass("hidden"), o.closest(".file-info").removeClass("hidden");
          return;
        }
        if (this.fileMap.has(s.val()))
          return alert(t);
        this.renameFileItem(e, s.val()), s.addClass("hidden"), o.html(s.val()).closest(".file-info").removeClass("hidden");
      } else
        i.key === "Escape" && s.val(e.name).addClass("hidden").closest(".file-item").find(".file-name").removeClass("hidden");
    }).on("blur", () => {
      const i = s.closest(".file-item").find(".file-name");
      if (i.html() === s.val()) {
        s.addClass("hidden"), i.closest(".file-info").removeClass("hidden");
        return;
      }
      if (this.fileMap.has(s.val()))
        return alert(t);
      this.renameFileItem(e, s.val()), s.addClass("hidden"), i.html(s.val()).closest(".file-info").removeClass("hidden");
    });
    return s;
  }
};
ao.NAME = "UploadImgs", ao.DEFAULT = {
  uploadText: "添加文件",
  renameBtn: !0,
  renameText: "重命名",
  renameIcon: "edit",
  renameClass: "",
  deleteBtn: !0,
  deleteText: "删除",
  deleteIcon: "trash",
  deleteClass: "",
  showIcon: !1,
  multiple: !0,
  limitSize: !1,
  btnClass: "",
  draggable: !0,
  accept: "image/jpg, image/jpeg, image/gif, image/png",
  showSize: !0,
  useIconBtn: !0,
  totalCountText: '共 <span class="font-bold text-black">%s</span> 个文件 <span class="font-bold text-black">%s</span> 个文件等待上传。'
};
let xl = ao;
var lo, _h, be, _s, co, xh, ho, $h;
const uo = class uo extends J {
  constructor() {
    super(...arguments);
    $(this, lo);
    $(this, be);
    $(this, co);
    $(this, ho);
    this._actions = null, this._cols = [], this._idSeed = 0, this._rows = [];
  }
  afterInit() {
    var o;
    const t = u(this.element), s = t.find(".form-batch-table").addClass("borderless");
    let i = s.find("tbody");
    i.length || (i = u("<tbody></tbody>").appendTo(s)), this.$tbody = i, this._template = (o = t.find(".form-batch-template").get(0)) == null ? void 0 : o.innerHTML, this._cols = [], s.find("thead>tr>.form-batch-head").each((r, a) => {
      const c = u(a).data();
      c && this._cols.push(c);
    }), t.on("click", (r) => {
      const a = u(r.target).closest(".form-batch-btn");
      if (!a.length)
        return;
      const l = a.data("type"), d = a.closest("tr").data("index");
      l === "add" ? this.addRow(d) : l === "delete" ? this.deleteRow(d) : l === "ditto" && this.toggleDitto(a);
    }).on("change", ".form-batch-input,.pick-value", (r) => {
      const a = u(r.target);
      a.hasClass("pick-value") && !r.___td || this.syncDitto(a);
    }), this.render();
  }
  destroy() {
    u(this.element).off("click change"), this.$tbody = void 0, this._template = void 0, this._cols.length = 0, this._rows.length = 0;
  }
  render(t) {
    super.render(t), this._rows.length ? O(this, be, _s).call(this) : (this._actions = null, O(this, lo, _h).call(this));
  }
  addRow(t) {
    const s = this._idSeed++;
    typeof t == "number" && t >= 0 && t <= this._rows.length ? this._rows.splice(t + 1, 0, s) : (t = this._rows.length, this._rows.push(s)), O(this, be, _s).call(this, void 0, t);
  }
  deleteRow(t) {
    var i;
    if (this._rows.length <= 1 || typeof t != "number" || t < 0 || t >= this._rows.length)
      return !1;
    const s = this._rows[t];
    this._rows.splice(t, 1), (i = this.$tbody) == null || i.children(`[data-gid="${s}"]`).remove(), O(this, be, _s).call(this, void 0, t);
  }
  deleteRowByGid(t) {
    return this.deleteRow(this._rows.indexOf(t));
  }
  toggleDitto(t, s) {
    const i = t.closest("td");
    s = s ?? i.attr("data-ditto") !== "on", i.attr("data-ditto", s ? "on" : "off"), s && i.closest("tr").prev("tr").find(`td[data-name="${i.data("name")}"]`).find(".form-batch-input").each((a, l) => {
      const c = u(l), d = c.data("name"), h = c.val();
      this.syncDitto(i.find(`.form-batch-input[data-name="${d}"]`).val(h), !1);
    });
  }
  syncDitto(t, s = !0) {
    const i = t.closest("td");
    s && i.attr("data-ditto", "off");
    const o = i.data("name"), r = t.data("name"), a = `td[data-name="${o}"][data-ditto="on"]`, l = t.val();
    let c = t.closest("tr").next("tr"), d = c.find(a);
    for (; d.length; )
      d.find(`.form-batch-input[data-name="${r}"]`).val(l), d.find(".pick-value").val(l).trigger("change"), c = c.next("tr"), d = c.find(a);
  }
};
lo = new WeakSet(), _h = function() {
  const t = this.$tbody;
  if (!this._template || !(t != null && t.length))
    return;
  const { data: s = [], minRows: i, maxRows: o, mode: r } = this.options, l = r === "add" ? Math.min(Math.max(1, o ?? 100), Math.max(1, 10, i ?? 10, s.length)) : s.length;
  this._rows = Array(l).fill(0).map((c, d) => d), this._idSeed = this._rows.length, O(this, be, _s).call(this, s);
}, be = new WeakSet(), _s = function(t = [], s = 0) {
  var o;
  const i = this._rows.length;
  for (let r = s; r < i; r++)
    O(this, ho, $h).call(this, r, t[r]);
  (o = this.$tbody) == null || o.attr("data-count", `${i}`);
}, co = new WeakSet(), xh = function(t) {
  let s = this._actions;
  if (!s) {
    const { addRowIcon: i = "icon-plus", deleteRowIcon: o = "icon-trash" } = this.options;
    s = new DocumentFragment();
    const r = '<button type="button" data-type="{type}" class="form-batch-btn btn square ghost size-sm" title="{text}"><i class="icon {icon}"></i></button>';
    i !== !1 && s.append(u(U(r, { type: "add", icon: i, text: this.i18n("add") }))[0]), o !== !1 && s.append(u(U(r, { type: "delete", icon: o, text: this.i18n("delete") }))[0]), this._actions = s;
  }
  t.empty().append(s.cloneNode(!0));
}, ho = new WeakSet(), $h = function(t, s) {
  const i = this.$tbody, o = String(this._rows[t]), { idKey: r = "id", mode: a, onRenderRowCol: l, onRenderRow: c } = this.options, d = a === "add", h = String(d || !s ? t + 1 : s[r]);
  let f = i.children(`[data-gid="${o}"]`), m = !1;
  if (f.length) {
    if (!s && f.data("index") === t)
      return;
  } else {
    m = !0;
    let p = this._template;
    if (p.includes("<script>") && p.includes('zui.create("')) {
      const y = [];
      p = p.replace(/(zui\.create\("[a-zA-Z]+","#)(\w+)(",)/g, (_, w, x, T) => (y.push(x), `${w}${x}_${o}${T}`)), y.forEach((_) => {
        p = p.replace(`id="${_}"`, `id="${_}_${o}"`);
      });
    }
    f = u(p.trim()).attr("data-gid", o);
  }
  if (f.attr("data-index", `${t}`), f.find("textarea").autoHeight(), f.on("inited", (p, [y]) => {
    const { name: _ } = y.options;
    _ && (y.render({ name: `${_}[${h}]` }), y.$element.attr("data-name", _), s && s[_] && y.$.setValue(s[_]));
  }), t) {
    const p = this._rows[t - 1], y = i.children(`[data-gid="${p}"]`);
    y.length ? y.after(f) : f.appendTo(i);
  } else
    f.prependTo(i);
  m && f.find("script").remove(), this._cols.forEach((p) => {
    let y = f.find(`td[data-name="${p.name}"]`);
    if (y.length || (y = u(`<td data-name="${p.name}"></td>`).appendTo(f)), p.index) {
      y.find(".form-control-static").text(h).attr("id", `${p.name}_${o}`), l == null || l.call(this, y, p, s, t);
      return;
    }
    if (!y.data("init") || s) {
      if (p.name === "ACTIONS") {
        if (y.addClass("form-batch-row-actions"), !d)
          return;
        O(this, co, xh).call(this, y);
        return;
      }
      y.data("init", 1).find("[name],.form-control-static").each((_, w) => {
        const x = u(w);
        if (x.hasClass("form-control-static")) {
          const T = x.attr("data-name");
          x.attr("id", `${p.name}_${o}`), s && x.text(String(s[T] ?? ""));
        } else {
          const T = x.attr("name"), k = x.attr("id");
          x.attr({
            id: `${k}_${o}`,
            name: `${T}[${h}]`,
            "data-name": T
          }).addClass("form-batch-input"), y.find(`label[for="${k}"]`).each((E, D) => {
            u(D).attr("for", `${k}_${o}`);
          }), s && x.val(String(s[T] ?? ""));
        }
      });
    } else
      y.find("[name]").each((_, w) => {
        var k;
        const x = u(w), T = x.attr("data-name");
        (k = x.attr("name")) != null && k.startsWith(`${T}[`) && x.attr("name", `${T}[${h}]`);
      }), y.find(".pick").each((_, w) => {
        var T;
        const x = u(w).parent();
        (T = x.zui()) == null || T.render({ name: `${x.data("name")}[${h}]` });
      });
    if (p.ditto && !y.hasClass("form-batch-ditto"))
      if (y.addClass("form-batch-ditto"), t) {
        const _ = u(`<button type="button" class="btn ghost form-batch-btn form-batch-ditto-btn" data-type="ditto">${this.i18n("ditto")}</button>`).appendTo(y);
        requestAnimationFrame(() => y.css("--form-batch-ditto-width", `${_.outerWidth()}px`)), y.attr("data-ditto", p.defaultDitto ?? "on");
      } else
        y.attr("data-ditto", "").find(".form-batch-ditto-btn").remove();
    y.toggleClass("form-batch-ditto-control", !!y.attr("data-ditto")), l == null || l.call(this, y, p, s, t);
  }), c == null || c.call(this, f, t, s);
}, uo.NAME = "BatchForm", uo.DEFAULT = {
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
let $l = uo;
function If(n) {
  return typeof n == "string" ? n.split(",").map((e) => {
    const t = parseFloat(e);
    return Number.isNaN(t) ? null : t;
  }) : n;
}
var ts, we, es, bn, wn, vn;
let Ch = (vn = class extends F {
  constructor() {
    super(...arguments);
    $(this, ts, q());
    $(this, we, 0);
    $(this, es, void 0);
    $(this, bn, void 0);
    $(this, wn, !1);
  }
  componentDidMount() {
    var s;
    this.tryDraw = this.tryDraw.bind(this), this.tryDraw();
    const t = (s = b(this, ts).current) == null ? void 0 : s.parentElement;
    if (this.props.responsive !== !1) {
      if (t && typeof ResizeObserver < "u") {
        const i = new ResizeObserver(this.tryDraw);
        i.observe(t), C(this, es, i);
      }
      b(this, es) || window.addEventListener("resize", this.tryDraw);
    }
    if (t && typeof IntersectionObserver < "u") {
      const i = new IntersectionObserver((o) => {
        b(this, wn) && o.some((r) => r.isIntersecting) && this.tryDraw();
      });
      i.observe(t), C(this, bn, i);
    }
  }
  componentWillUnmount() {
    var t;
    (t = b(this, es)) == null || t.disconnect(), window.removeEventListener("resize", this.tryDraw);
  }
  tryDraw() {
    b(this, we) && cancelAnimationFrame(b(this, we)), C(this, we, requestAnimationFrame(() => {
      this.draw(), C(this, we, 0);
    }));
  }
  draw() {
    const t = b(this, ts).current;
    if (!t)
      return;
    const s = t.parentElement, { width: i, height: o, responsive: r = !0 } = this.props;
    let a = i || s.clientWidth, l = o || s.clientHeight;
    if (i && o && r && (a = s.clientWidth, l = Math.floor(o * a / i)), t.style.width = `${a}px`, t.style.height = `${l}px`, a = a * (window.devicePixelRatio || 1), l = l * (window.devicePixelRatio || 1), t.width = a, t.height = l, !u(s).isVisible() && b(this, bn)) {
      C(this, wn, !0);
      return;
    }
    const {
      lineSize: c = 1,
      scaleLine: d = !1,
      scaleLineSize: h,
      scaleLineGap: f = 1,
      scaleLineDash: m,
      referenceLine: p,
      referenceLineSize: y,
      referenceLineDash: _,
      color: w = "#2c78f1",
      fillColor: x = ["rgba(46, 127, 255, 0.3)", "rgba(46, 127, 255, 0.05)"],
      lineDash: T = [],
      bezier: k
    } = this.props, E = If(this.props.data), D = Math.floor(a / (E.length - 1)), L = Math.max(...E.filter((H) => H !== null)), R = E.map((H, M) => {
      const S = typeof H != "number";
      return {
        x: M * D,
        y: S ? l : Math.round((1 - H / L) * (l - c)),
        empty: S
      };
    });
    let A = R[0];
    const P = t.getContext("2d");
    if (d) {
      const H = typeof d == "string" ? d : "rgba(100,100,100,.1)";
      P.strokeStyle = H, P.lineWidth = h || c, m && P.setLineDash(m);
      for (let M = 0; M < R.length; ++M) {
        if (M % f !== 0)
          continue;
        const S = R[M];
        P.moveTo(S.x, 0), P.lineTo(S.x, l);
      }
      P.stroke();
    }
    if (p && R.length > 1) {
      const H = typeof p == "string" ? p : "rgba(100,100,100,.2)", M = R[R.length - 1];
      P.moveTo(M.x, M.y), P.strokeStyle = H, P.lineWidth = y || c, P.lineTo(A.x, A.y), _ && P.setLineDash(_), P.stroke();
    }
    for (P.setLineDash(T); R.length && R[R.length - 1].empty; )
      R.pop();
    if (x) {
      const H = R[R.length - 1];
      if (P.beginPath(), P.moveTo(0, l), P.lineTo(A.x, A.y), k) {
        const M = Math.round(D / 2);
        for (let S = 1; S < R.length; ++S) {
          const W = R[S], V = Math.round((W.y - A.y) / 5);
          P.bezierCurveTo(A.x + M, A.y + V, W.x - M, W.y - V, W.x, W.y), A = W;
        }
      } else
        for (let M = 1; M < R.length; ++M)
          A = R[M], P.lineTo(A.x, A.y);
      if (P.lineTo(H.x, l), Array.isArray(x)) {
        const M = P.createLinearGradient(0, 0, 0, l);
        for (let S = 0; S < x.length; ++S)
          M.addColorStop(S / (x.length - 1), x[S]);
        P.fillStyle = M;
      } else
        P.fillStyle = x;
      P.fill();
    }
    if (A = R[0], P.beginPath(), P.moveTo(A.x, A.y), k) {
      const H = Math.round(D / 2);
      for (let M = 1; M < R.length; ++M) {
        const S = R[M], W = Math.round((S.y - A.y) / 5);
        P.bezierCurveTo(A.x + H, A.y + W, S.x - H, S.y - W, S.x, S.y), A = S;
      }
    } else
      for (let H = 1; H < R.length; ++H)
        A = R[H], P.lineTo(A.x, A.y);
    P.strokeStyle = w, P.lineWidth = c, P.stroke();
  }
  render() {
    const { style: t, className: s, canvasClass: i } = this.props;
    return /* @__PURE__ */ v("div", { class: "center burn-chart", className: s, style: t }, /* @__PURE__ */ v("canvas", { className: i, ref: b(this, ts) }));
  }
}, ts = new WeakMap(), we = new WeakMap(), es = new WeakMap(), bn = new WeakMap(), wn = new WeakMap(), vn.defaultProps = {
  responsive: !0,
  lineSize: 1,
  scaleLine: !1,
  scaleLineSize: 1,
  bezier: !0
}, vn);
const fo = class fo extends B {
};
fo.NAME = "Burn", fo.Component = Ch;
let Cl = fo;
function Rf(n) {
  const { link: e, itemProps: t = {}, typeIconMap: s = {}, labelMap: i = {}, searchKeys: o, active: r, itemType: a = "", checkIcon: l, hideDirIcon: c } = this, {
    id: d,
    keys: h,
    text: f,
    className: m,
    url: p,
    type: y = a,
    items: _,
    icon: w,
    label: x,
    ...T
  } = n;
  let k = _ ? x ?? (i[y] || y) : null, E;
  typeof k == "string" && k.length && (E = k, k = /* @__PURE__ */ v("span", { class: "label rounded-full lighter size-sm ml-1" }, k));
  let D = p;
  D === void 0 && e && (typeof e == "function" ? D = e(n) : typeof e == "object" ? (D = e[y], D && (D = U(D, n))) : D = U(e, n));
  let L = w;
  L === void 0 && (!c || D) && (L = s[y]), typeof L == "string" && (L = { icon: L, "data-toggle": "tooltip", "data-title": E || y });
  let R = f ?? (i[y] || y);
  o != null && o.length && (R = gh(o, [R]));
  const A = r === d;
  return l && A && (k = [k, /* @__PURE__ */ v("div", { className: "dropmenu-item-check" }, /* @__PURE__ */ v(K, { icon: "check" }))]), {
    type: "item",
    key: d,
    children: k,
    icon: L,
    items: _,
    "data-url": D,
    text: R,
    active: A,
    title: f,
    ...t,
    ...T,
    className: N("dropmenu-item rounded", m, t.className, _ ? "is-dir" : "is-item", D ? "is-link open-url" : "is-toggle")
  };
}
function Tl(n) {
  const {
    className: e,
    items: t,
    tree: s,
    onClickItem: i
  } = n;
  return /* @__PURE__ */ v(
    vh,
    {
      items: t,
      itemRenderProps: Rf.bind(n),
      className: N(e, "dropmenu-tree"),
      defaultNestedShow: !0,
      onClickItem: i,
      ...s
    }
  );
}
const Af = (n, e) => {
  const { keys: t = "", text: s } = n;
  return !e.length || e.every((i) => t.toLowerCase().includes(i) || typeof s == "string" && s.toLowerCase().includes(i));
}, Th = (n, e, t = 0) => (n = n.reduce((s, i) => {
  const { items: o } = i;
  if (o) {
    if (o.length) {
      const [r, a] = Th(o, e);
      r.length && (s.push({ ...i, items: r }), t += a);
    }
  } else
    Af(i, e) && (s.push(i), t++);
  return s;
}, []), [n, t]);
var ss, ns, _n, xn, $n, is;
class Pf extends da {
  constructor(t) {
    super(t);
    $(this, ss, void 0);
    $(this, ns, void 0);
    $(this, _n, void 0);
    $(this, xn, void 0);
    $(this, $n, void 0);
    $(this, is, void 0);
    C(this, ss, q()), C(this, ns, q()), C(this, _n, (s) => {
      this.setState({ search: s });
    }), C(this, xn, () => {
      this.expand();
    }), C(this, $n, ({ item: s, event: i }) => {
      i.target.closest(".is-link") && (this.props.togglePop(!1, {
        text: s.text ?? `${s.type}:${s.id}`
      }), this.props.changeState({ text: s.text ?? `${s.type || ""}:${s.id}`, value: s.id }));
    }), C(this, is, () => {
      requestAnimationFrame(() => {
        const s = b(this, ns).current;
        s && u(s).find(".dropmenu-item.active").scrollIntoView();
      });
    }), this.activeTab = (s) => {
      this.setState({ active: s });
    }, this.expand = (s) => {
      this.setState({ expanded: s ?? !this.state.expanded });
    }, this.state = { search: "", data: t.data, expanded: !1, loading: !!t.fetcher && !t.data };
  }
  async load() {
    var i, o;
    let { fetcher: t } = this.props;
    if (!t)
      return b(this, is).call(this);
    if (typeof t == "string" && (t = { url: t }), typeof t == "object") {
      const { url: r, ...a } = t;
      t = async () => await (await fetch(r, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        ...a
      })).json();
    }
    this.setState({ loading: !0 });
    let s = await t();
    s.result && (s = s.data), s = { ...this.props.data, ...s }, this.setState({ data: s, loading: !1 }, b(this, is)), (o = (i = this.props).onCacheData) == null || o.call(i, s);
  }
  componentDidMount() {
    var t;
    super.componentDidMount(), this.props.data || this.load(), (t = b(this, ss).current) == null || t.focus();
  }
  _getTreeProps(t) {
    const { data: { labelMap: s, link: i, typeIconMap: o, typeLabelMap: r, itemProps: a, tree: l, itemType: c, checkIcon: d, hideDirIcon: h = !0 } = {}, search: f } = this.state;
    return {
      items: t.items,
      labelMap: s,
      typeIconMap: {
        execution: "run",
        project: "project",
        product: "product",
        program: "cards-view",
        ...o
      },
      hideDirIcon: h,
      typeLabelMap: r,
      itemProps: a,
      itemType: c,
      checkIcon: d,
      onClickItem: b(this, $n),
      tree: l,
      link: i,
      active: this.props.state.value,
      searchKeys: u.unique(f.toLowerCase().split(" ").filter((m) => m.length))
    };
  }
  _getData() {
    const { data: t = {}, search: s, active: i } = this.state, { tabs: o = [{ name: "other" }], expandName: r } = t;
    let { data: a = [] } = t;
    Array.isArray(a) && (a = { other: a });
    const l = {}, c = u.unique(s.toLowerCase().split(" ").filter((m) => m.length));
    let d, h;
    Object.keys(a).forEach((m) => {
      const p = r === m, y = o.find((E) => E.name === m) || { name: m }, _ = a[m] || [], w = i === m && !p, [x, T] = Th(_, c), k = {
        name: m,
        items: x,
        text: y.text,
        active: w,
        count: T
      };
      p ? d = k : (l[m] = k, w && (h = k));
    });
    const f = o.reduce((m, { name: p }) => (p !== r && m.push(l[p] || { name: p, items: [] }), m), []);
    return !h && f.length && (h = f[0]), { tabs: f, expandData: d, activeTab: h };
  }
  _renderList(t, s, i) {
    const o = s.name, { expanded: r, data: a = {} } = this.state;
    let { hideSingleTab: l = !0 } = a;
    return l = t.length === 1 && l, /* @__PURE__ */ v(oe, null, l ? null : /* @__PURE__ */ v("div", { className: "dropmenu-nav" }, /* @__PURE__ */ v("ul", { key: "nav", className: "nav nav-secondary" }, t.map(({ name: c, text: d, count: h }) => /* @__PURE__ */ v("li", { className: "nav-item" }, /* @__PURE__ */ v("a", { className: `${o === c ? " active" : ""}`, onClick: this.activeTab.bind(this, c) }, d || c, h ? /* @__PURE__ */ v("span", { className: "label lighter rounded-full size-sm font-normal" }, h) : null))))), /* @__PURE__ */ v("div", { key: "tab", className: "flex-auto dropmenu-list scrollbar-hover scrollbar-thin" }, /* @__PURE__ */ v(
      Tl,
      {
        ...this._getTreeProps(s)
      }
    )), i ? /* @__PURE__ */ v("div", { key: "foot", className: "dropmenu-foot flex-none toolbar justify-end border-t" }, /* @__PURE__ */ v(et, { type: "ghost text-dark rounded gap-0.5 px-1.5", trailingIcon: `angle-${this.state.expanded ? "left" : "right"} opacity-60`, onClick: b(this, xn) }, i.text || i.name, " ", i.count && r ? /* @__PURE__ */ v("span", { className: "ml-1 label lighter rounded-full size-sm font-normal" }, i.count) : null)) : null);
  }
  _renderExpand(t) {
    return /* @__PURE__ */ v("div", { className: "col w-1/2 dropmenu-list scrollbar-thin scrollbar-hover" }, /* @__PURE__ */ v(
      Tl,
      {
        ...this._getTreeProps(t)
      }
    ));
  }
  _renderPop() {
    const { expanded: t, data: s = {} } = this.state, { searchHint: i, search: o = !0, title: r } = s, { expandData: a, tabs: l, activeTab: c } = this._getData(), d = t && a;
    return /* @__PURE__ */ v(oe, null, o ? /* @__PURE__ */ v("div", { key: "search", className: "p-3 flex-none" }, /* @__PURE__ */ v(
      wh,
      {
        ref: b(this, ss),
        className: "size-md",
        placeholder: i,
        onChange: b(this, _n)
      }
    )) : null, r ? /* @__PURE__ */ v("div", { className: "dropmenu-title" }, r) : null, /* @__PURE__ */ v("div", { class: "row flex-auto min-h-0", ref: b(this, ns) }, /* @__PURE__ */ v("div", { class: `col w-${d ? "1/2" : "full"}` }, this._renderList(l, c, a)), d ? /* @__PURE__ */ v("div", { class: "w-px bg-gray opacity-10" }) : null, t && a && this._renderExpand(a)));
  }
  _getClass(t) {
    const { expanded: s, loading: i, data: o = {} } = this.state;
    return N("dropmenu load-indicator col", super._getClass(t), s && "is-expanded", i && "loading", o.search !== !1 && "has-search", o.title ? "has-title" : "");
  }
  _getProps(t) {
    const { width: s = 248 } = t, { style: i, ...o } = super._getProps(t), { expanded: r } = this.state, a = this.trigger, l = a == null ? void 0 : a.getBoundingClientRect();
    return {
      ...o,
      style: {
        ...i,
        width: s * (r ? 2 : 1),
        maxHeight: l ? Math.max(l.top - 8, window.innerHeight - l.bottom - 8) : i.maxHeight
      }
    };
  }
}
ss = new WeakMap(), ns = new WeakMap(), _n = new WeakMap(), xn = new WeakMap(), $n = new WeakMap(), is = new WeakMap();
class Lf extends qo {
  _getProps(e) {
    const t = super._getProps(e);
    return {
      type: "button",
      "data-value": e.state.value,
      ...t
    };
  }
  _renderTrigger(e) {
    const { text: t, state: s, children: i, caret: o, leadingAngle: r } = e;
    return [
      r && /* @__PURE__ */ v(K, { icon: "angle-right", className: "is-leading" }),
      /* @__PURE__ */ v("span", { key: "text", className: "text" }, s.text ?? t ?? i),
      o && /* @__PURE__ */ v("div", { key: "caret", class: "is-caret" }, /* @__PURE__ */ v("span", { className: "caret" }))
    ];
  }
}
var ve, Pt, po, Me;
let Hf = (Me = class extends rt {
  constructor() {
    super(...arguments);
    $(this, ve, void 0);
    $(this, Pt, void 0);
    $(this, po, (t) => {
      const { cache: s } = this.props;
      s && (C(this, ve, t), typeof s == "number" && (b(this, Pt) && clearTimeout(b(this, Pt)), C(this, Pt, window.setTimeout(() => {
        C(this, ve, void 0), C(this, Pt, 0);
      }, s))));
    });
  }
  componentWillUnmount() {
    super.componentWillUnmount(), b(this, Pt) && clearTimeout(b(this, Pt));
  }
  componentWillReceiveProps(t) {
    (this.props.data !== t.data || this.props.fetcher !== t.fetcher) && C(this, ve, void 0);
  }
  _getTriggerProps(t, s) {
    const { className: i, ...o } = super._getTriggerProps(t, s), { value: r = "" } = s;
    return {
      ...o,
      className: N(i, { "has-value": r.length }),
      text: t.text,
      caret: t.caret,
      maxWidth: t.maxWidth,
      leadingAngle: t.leadingAngle
    };
  }
  _getPopProps(t, s) {
    return {
      ...super._getPopProps(t, s),
      data: b(this, ve) || t.data,
      fetcher: t.fetcher,
      cache: !!t.cache,
      onCacheData: b(this, po)
    };
  }
}, ve = new WeakMap(), Pt = new WeakMap(), po = new WeakMap(), Me.defaultProps = {
  ...rt.defaultProps,
  popWidth: 248,
  maxWidth: 160,
  className: "dropmenu-btn btn ghost",
  tagName: "button",
  clickType: "toggle",
  leadingAngle: !0,
  caret: !0,
  cache: 60 * 1e3 * 4
}, Me.Pop = Pf, Me.Trigger = Lf, Me);
const mo = class mo extends B {
};
mo.NAME = "Dropmenu", mo.Component = Hf;
let kl = mo;
const _a = class _a extends J {
  init() {
    const { echarts: e } = window;
    if (!e) {
      console.warn("ZUI: ECharts is not loaded.");
      return;
    }
    const { responsive: t = !0, theme: s, ...i } = this.options, o = e.init(this.element, s);
    o.setOption(i), t && u(window).on(`resize.${this.gid}.ECharts.zt`, o.resize), this.chart = o;
  }
  destroy() {
    var s;
    const { echarts: e } = window;
    if (!e) {
      super.destroy();
      return;
    }
    const { responsive: t = !0 } = this.options;
    t && u(window).off(`resize.${this.gid}.ECharts.zt`), (s = this.chart) == null || s.dispose(), super.destroy();
  }
};
_a.NAME = "ECharts";
let Sl = _a;
class Of extends F {
  renderCommonSearch(e) {
    const { commonSearchText: t, commonSearchKey: s, searchValue: i } = this.props;
    return /* @__PURE__ */ v("li", { key: s, "data-key": s, className: N("w-full rounded flex items-center my-0.5", e) }, /* @__PURE__ */ v("a", { className: "inline-block p-1 ellipsis", style: { color: "inherit" } }, t, " ", i));
  }
  handleClick(e) {
    const t = e.target.closest("li");
    if (!t)
      return;
    const s = t.dataset.key, { searchFunc: i, searchValue: o } = this.props;
    i(s, o);
  }
  render() {
    const { searchValue: e, searchItems: t, selectedKey: s } = this.props;
    return e === "" ? null : /^\d+$/.test(e) ? /* @__PURE__ */ v("ul", { className: "global-search-panel flex flex-wrap p-1 rounded shadow bg-white w-full", onClick: this.handleClick.bind(this) }, this.renderCommonSearch(), t.map(({ key: i, text: o }) => /* @__PURE__ */ v(
      "li",
      {
        key: i,
        "data-key": i,
        className: N({
          rounded: !0,
          "my-0.5": !0,
          flex: !0,
          "justify-between": !0,
          "items-center": !0,
          "w-1/2": i !== s,
          "w-full": i === s,
          secondary: i === s,
          "order-first": i === s
        })
      },
      /* @__PURE__ */ v("a", { className: "inline-block p-1 ellipsis", style: { color: "inherit" } }, o, " #", e),
      i === s && /* @__PURE__ */ v("i", { className: "icon icon-check mr-2" })
    ))) : /* @__PURE__ */ v("ul", { className: "global-search-panel p-1 rounded shadow bg-white w-full", onClick: this.handleClick.bind(this) }, this.renderCommonSearch("secondary"));
  }
}
const xa = class xa extends J {
  init() {
    const { panelID: e = "global-search-panel", searchFunc: t, commonSearchKey: s } = this.options, i = u(`<div id="${e}" />`).css({ width: 270 });
    this.$element.after(i);
    const o = this.$element.find("input"), r = this.$element.find("button"), a = 500;
    o.data("target", `#${e}`).on("input", (l) => {
      Ts(
        /* @__PURE__ */ v(
          Of,
          {
            ...this.options,
            searchValue: l.target.value,
            selectedKey: o.data("selectedKey")
          }
        ),
        i[0]
      );
    }).on("keydown", (l) => {
      l.code === "Enter" && (t(s, l.target.value), o.trigger("blur"));
    }).on("blur", () => {
      o.data("lastValue", o.val()), setTimeout(() => {
        o.val(""), o.trigger("input");
      }, a), setTimeout(() => {
        o.data("lastValue", "");
      }, 1e3);
    }), r.on("click", () => {
      t(s, o.data("lastValue"));
    }), new Sr(this.$element.find("input"), {
      placement: "top-start",
      trigger: "focus blur",
      offset: 4,
      mask: !1,
      delay: a
    });
  }
};
xa.NAME = "GlobalSearch";
let El = xa;
const go = class go extends J {
  init() {
    this.clipWidth = this.options.defaultWidth, this.clipHeight = this.options.defaultHeight, this.initDom(), this.initSize(), this.bindEvents();
  }
  initDom() {
    this.$canvas = this.$element.find(".canvas"), this.$img = this.$canvas.find("img"), this.$btn = this.$element.find("button");
    const e = this.$img.prop("src"), { coverColor: t, coverOpacity: s } = this.options;
    this.$canvas.append([
      `<div class="cover" style="background: ${t}; opacity: ${s};"></div>`,
      `<div class="controller" style="width: ${this.clipWidth}px; height: ${this.clipHeight}px">`,
      ["top", "right", "bottom", "left", "top-left", "top-right", "bottom-left", "bottom-right"].map((i) => `<div class="control" data-type="${i}"></div>`).join(""),
      "</div>",
      '<div class="cliper">',
      `<img src="${e}"/>`,
      "</div>"
    ].join("")), this.$controller = this.$canvas.find(".controller"), this.$cliper = this.$canvas.find(".cliper"), this.$chipImg = this.$cliper.find("img");
  }
  resetImage(e) {
    this.$img.prop("src", e), this.$chipImg.prop("src", e), this.imgWidth = 0, this.left = -1, this.initSize();
  }
  initSize() {
    const { minWidth: e, minHeight: t, ready: s, onSizeError: i } = this.options;
    if (this.imgWidth)
      return;
    const o = (a) => {
      this.imgWidth = a.width, this.imgHeight = a.height, (a.width < e || a.height < t) && (i == null || i({ width: a.width, height: a.height }), this.options.minWidth = Math.min(a.width, e), this.options.minHeight = Math.min(a.height, t)), s == null || s(), this.width = Math.min(this.imgWidth, this.$element.width()), this.$canvas.css("width", this.width), this.height = this.$canvas.height(), (this.left === void 0 || this.left < 0) && (this.left = Math.floor((this.width - this.$controller.width()) / 2), this.top = Math.floor((this.height - this.$controller.height()) / 2)), this.refreshSize();
    }, r = this.$img[0];
    r.complete ? o(r) : r.onload = () => {
      o(r);
    };
  }
  refreshSize(e) {
    const { minWidth: t, minHeight: s, fixedRatio: i, defaultWidth: o, defaultHeight: r } = this.options;
    this.clipWidth = Math.max(t, Math.min(this.width, this.clipWidth)), this.clipHeight = Math.max(s, Math.min(this.height, this.clipHeight)), i && (e && e === "height" ? (this.clipWidth = Math.max(t, Math.min(this.width, this.clipHeight * o / r)), this.clipHeight = this.clipWidth * r / o) : (this.clipHeight = Math.max(s, Math.min(this.height, this.clipWidth * r / o)), this.clipWidth = this.clipHeight * o / r)), this.left = Math.min(this.width - this.clipWidth, Math.max(0, this.left)), this.top = Math.min(this.height - this.clipHeight, Math.max(0, this.top)), this.right = this.left + this.clipWidth, this.bottom = this.top + this.clipHeight, this.$controller.css({
      left: this.left,
      top: this.top,
      width: this.clipWidth,
      height: this.clipHeight
    }), this.$cliper.css("clip", `rect(${this.top}px, ${this.left + this.clipWidth}px, ${this.top + this.clipHeight}px, ${this.left}px)`);
  }
  getData() {
    return {
      originWidth: this.imgWidth,
      originHeight: this.imgHeight,
      scaleWidth: this.width,
      scaleHeight: this.height,
      width: this.right - this.left,
      height: this.bottom - this.top,
      left: this.left,
      top: this.top,
      right: this.right,
      bottom: this.bottom,
      scaled: this.imgWidth !== this.width || this.imgHeight !== this.height
    };
  }
  bindEvents() {
    this.$element.on("resize", this.initSize.bind(this)), this.$btn.on("click", (e) => {
      var t, s;
      (s = (t = this.options).handleBtnClick) == null || s.call(t, e, this.getData());
    }), this.moveable = new br(this.$controller, {
      selector: ".control,.controller",
      move: !1,
      onMoveStart: () => {
        this.startLeft = this.left, this.startTop = this.top, this.startClipWidth = this.clipWidth, this.startClipHeight = this.clipHeight;
      },
      onMove: (e, t) => {
        const s = u(t.target);
        if (s.is(".controller"))
          this.left = this.startLeft + t.deltaX, this.top = this.startTop + t.deltaY, this.refreshSize();
        else {
          const i = s.data("type");
          let o = !1;
          const { minWidth: r, minHeight: a } = this.options;
          switch (i) {
            case "left":
            case "top-left":
            case "bottom-left":
              this.left = Math.min(this.right - r, Math.max(0, this.startLeft + t.deltaX)), this.clipWidth = this.right - this.left;
              break;
            case "right":
            case "top-right":
            case "bottom-right":
              this.clipWidth = Math.min(this.width - this.left, Math.max(r, this.startClipWidth + t.deltaX));
              break;
          }
          switch (i) {
            case "top":
            case "top-left":
            case "top-right":
              this.top = Math.min(this.bottom - a, Math.max(0, this.startTop + t.deltaY)), this.clipHeight = this.bottom - this.top, o = !0;
              break;
            case "bottom":
            case "bottom-left":
            case "bottom-right":
              this.clipHeight = Math.min(this.height - this.top, Math.max(a, this.startClipHeight + t.deltaY)), o = !0;
              break;
          }
          this.refreshSize(o);
        }
      }
    });
  }
  destroy() {
    var e;
    (e = this.moveable) == null || e.destroy(), super.destroy();
  }
};
go.NAME = "ImgCutter", go.DEFAULT = {
  coverColor: "#000",
  coverOpacity: 0.6,
  defaultWidth: 128,
  defaultHeight: 128,
  minWidth: 48,
  minHeight: 48,
  fixedRatio: !0
};
let Nl = go;
Object.assign(Se, {
  success(n) {
    var e;
    return Array.isArray((e = n.actions) == null ? void 0 : e.items) && n.actions.items.length > 0 && n.actions.items.forEach((t) => {
      t.btnType = N("text-primary text-lg font-bold ghost", t.btnType);
    }), Se.show(Object.assign({
      icon: "icon-check",
      iconClass: "center w-6 h-6 rounded-full m-0 success",
      contentClass: "text-lg font-bold",
      close: !1,
      className: "p-6 bg-white text-black gap-2 messager-success"
    }, n));
  },
  fail(n) {
    var e;
    return Array.isArray((e = n.actions) == null ? void 0 : e.items) && n.actions.items.length > 0 && n.actions.items.forEach((t) => {
      t.btnType = N("text-primary text-lg font-bold ghost", t.btnType);
    }), Se.show(Object.assign({
      icon: "icon-close",
      iconClass: "center w-6 h-6 rounded-full m-0 danger",
      contentClass: "text-lg font-bold",
      close: !1,
      className: "p-6 bg-white text-black gap-2 messager-fail"
    }, n));
  }
});
Object.assign(pt, {
  reconfirm(n) {
    return pt.confirm(Object.assign({
      iconClass: "warning rounded-full center w-8 h-8 flex-none",
      type: "custom",
      custom: {
        className: "modal-reconfirm",
        closeBtn: !1
      }
    }, n));
  }
});
function kh({ pri: n = "", text: e, className: t }) {
  return /* @__PURE__ */ v("span", { className: N(`pri-${n}`, t) }, e ?? n);
}
var Cn;
let ui = (Cn = class extends rt {
  constructor(e) {
    if (super(e), this.state.value === void 0 && e.required) {
      const t = this._getItems();
      t && (this.state.value = t[0].value);
    }
  }
  _getTriggerProps(e, t) {
    const s = super._getTriggerProps(e, t);
    return e.disabled && (s.className = N(s.className, "disabled")), s;
  }
  _renderTrigger(e, t) {
    var o;
    const { value: s } = t, { placeholder: i } = e;
    return [
      s === void 0 ? /* @__PURE__ */ v("span", { class: "placeholder" }, i) : this._renderItem(t.value, (o = this._getItems().find(({ value: r }) => r === s)) == null ? void 0 : o.text, "pri"),
      /* @__PURE__ */ v("span", { key: "caret", class: "caret" })
    ];
  }
  _getItems() {
    let { items: e = ["", 1, 2, 3, 4] } = this.props;
    return Array.isArray(e) ? e = e.map((t) => typeof t == "object" ? t : { value: `${t}` }) : typeof e == "object" && (e = Object.keys(e).map((t) => ({ value: t, text: e[t] }))), e.sort((t, s) => +t.value - +s.value), e;
  }
  _renderItem(e, t, s) {
    return /* @__PURE__ */ v(kh, { key: s, pri: e, text: t });
  }
  _renderPop() {
    const { value: e } = this.state;
    return /* @__PURE__ */ v("div", { className: "pick-pri-list" }, this._getItems().map(({ value: t, text: s }) => /* @__PURE__ */ v("button", { key: t, type: "button", class: `btn w-full ${e === t ? "primary-pale" : "ghost"}`, "data-pick-value": t }, this._renderItem(t, s))));
  }
}, Cn.defaultProps = {
  ...rt.defaultProps,
  items: ["", 1, 2, 3, 4],
  className: "pick-pri form-control",
  popClass: "pick-pri-pop popup",
  popWidth: "auto",
  popMinWidth: 56
}, Cn);
function Sh({ severity: n = "", text: e, className: t }) {
  const s = e ?? n, i = `${Number.parseInt(s)}` != `${n}`;
  return /* @__PURE__ */ v("span", { className: N(`severity${i ? " severity-label" : ""}`, t), "data-severity": n }, i ? s : "");
}
var Tn;
let Eh = (Tn = class extends ui {
  _renderItem(e, t, s) {
    return /* @__PURE__ */ v(Sh, { key: s, severity: e, text: t });
  }
}, Tn.defaultProps = {
  ...ui.defaultProps,
  items: [0, 1, 2, 3, 4]
}, Tn);
const yo = class yo extends B {
};
yo.NAME = "PriPicker", yo.Component = ui;
let Ml = yo;
const bo = class bo extends B {
};
bo.NAME = "SeverityPicker", bo.Component = Eh;
let Dl = bo;
function Mr(n) {
  const { id: e, name: t, options: s, defaultValue: i } = n, o = (r) => /* @__PURE__ */ v("option", { value: r.value }, r.title);
  return /* @__PURE__ */ v("select", { id: e, name: t, className: "form-control", value: i }, s.map(o));
}
var Dr, G, er, Il, Ir = 0, Nh = [], qn = [], Rl = j.__b, Al = j.__r, Pl = j.diffed, Ll = j.__c, Hl = j.unmount;
function jf(n, e) {
  j.__h && j.__h(G, n, Ir || e), Ir = 0;
  var t = G.__H || (G.__H = { __: [], __h: [] });
  return n >= t.__.length && t.__.push({ __V: qn }), t.__[n];
}
function Rr(n) {
  return Ir = 1, Wf(Mh, n);
}
function Wf(n, e, t) {
  var s = jf(Dr++, 2);
  if (s.t = n, !s.__c && (s.__ = [t ? t(e) : Mh(void 0, e), function(a) {
    var l = s.__N ? s.__N[0] : s.__[0], c = s.t(l, a);
    l !== c && (s.__N = [c, s.__[1]], s.__c.setState({}));
  }], s.__c = G, !G.u)) {
    var i = function(a, l, c) {
      if (!s.__c.__H)
        return !0;
      var d = s.__c.__H.__.filter(function(f) {
        return f.__c;
      });
      if (d.every(function(f) {
        return !f.__N;
      }))
        return !o || o.call(this, a, l, c);
      var h = !1;
      return d.forEach(function(f) {
        if (f.__N) {
          var m = f.__[0];
          f.__ = f.__N, f.__N = void 0, m !== f.__[0] && (h = !0);
        }
      }), !(!h && s.__c.props === a) && (!o || o.call(this, a, l, c));
    };
    G.u = !0;
    var o = G.shouldComponentUpdate, r = G.componentWillUpdate;
    G.componentWillUpdate = function(a, l, c) {
      if (this.__e) {
        var d = o;
        o = void 0, i(a, l, c), o = d;
      }
      r && r.call(this, a, l, c);
    }, G.shouldComponentUpdate = i;
  }
  return s.__N || s.__;
}
function zf() {
  for (var n; n = Nh.shift(); )
    if (n.__P && n.__H)
      try {
        n.__H.__h.forEach(Gn), n.__H.__h.forEach(Ar), n.__H.__h = [];
      } catch (e) {
        n.__H.__h = [], j.__e(e, n.__v);
      }
}
j.__b = function(n) {
  G = null, Rl && Rl(n);
}, j.__r = function(n) {
  Al && Al(n), Dr = 0;
  var e = (G = n.__c).__H;
  e && (er === G ? (e.__h = [], G.__h = [], e.__.forEach(function(t) {
    t.__N && (t.__ = t.__N), t.__V = qn, t.__N = t.i = void 0;
  })) : (e.__h.forEach(Gn), e.__h.forEach(Ar), e.__h = [], Dr = 0)), er = G;
}, j.diffed = function(n) {
  Pl && Pl(n);
  var e = n.__c;
  e && e.__H && (e.__H.__h.length && (Nh.push(e) !== 1 && Il === j.requestAnimationFrame || ((Il = j.requestAnimationFrame) || Ff)(zf)), e.__H.__.forEach(function(t) {
    t.i && (t.__H = t.i), t.__V !== qn && (t.__ = t.__V), t.i = void 0, t.__V = qn;
  })), er = G = null;
}, j.__c = function(n, e) {
  e.some(function(t) {
    try {
      t.__h.forEach(Gn), t.__h = t.__h.filter(function(s) {
        return !s.__ || Ar(s);
      });
    } catch (s) {
      e.some(function(i) {
        i.__h && (i.__h = []);
      }), e = [], j.__e(s, t.__v);
    }
  }), Ll && Ll(n, e);
}, j.unmount = function(n) {
  Hl && Hl(n);
  var e, t = n.__c;
  t && t.__H && (t.__H.__.forEach(function(s) {
    try {
      Gn(s);
    } catch (i) {
      e = i;
    }
  }), t.__H = void 0, e && j.__e(e, t.__v));
};
var Ol = typeof requestAnimationFrame == "function";
function Ff(n) {
  var e, t = function() {
    clearTimeout(s), Ol && cancelAnimationFrame(e), setTimeout(n);
  }, s = setTimeout(t, 100);
  Ol && (e = requestAnimationFrame(t));
}
function Gn(n) {
  var e = G, t = n.__c;
  typeof t == "function" && (n.__c = void 0, t()), G = e;
}
function Ar(n) {
  var e = G;
  n.__c = n.__(), G = e;
}
function Mh(n, e) {
  return typeof e == "function" ? e(n) : e;
}
function He(n) {
  const { index: e, fields: t, operators: s, andOr: i, groupName: o, defaultValue: r, show: a = !1 } = n, [l, c] = Rr(r.field), d = t.find((f) => f.name === l), h = (f) => Array.from(Object.entries(f)).map(([m, p]) => ({ value: m, text: p }));
  return a ? /* @__PURE__ */ v("tr", null, /* @__PURE__ */ v("td", { className: "p-2 text-right", style: { width: 80 } }, e === 1 ? /* @__PURE__ */ v("span", null, /* @__PURE__ */ v("strong", null, o[0])) : e === 4 ? /* @__PURE__ */ v("span", null, /* @__PURE__ */ v("strong", null, o[1])) : /* @__PURE__ */ v(
    Mr,
    {
      id: `andOr${e}`,
      name: `andOr${e}`,
      options: i,
      defaultValue: r.andOr
    }
  )), /* @__PURE__ */ v("td", { className: "p-2", style: { width: 150 } }, /* @__PURE__ */ v(
    di,
    {
      className: "w-full",
      id: `field${e}`,
      name: `field${e}`,
      items: t.map((f) => ({ text: f.label, value: f.name })),
      onChange: (f) => c(f),
      defaultValue: r.field
    }
  )), /* @__PURE__ */ v("td", { className: "p-2", style: { width: 90 } }, /* @__PURE__ */ v(
    Mr,
    {
      id: `operator${e}`,
      name: `operator${e}`,
      options: s,
      defaultValue: r.operator
    }
  )), /* @__PURE__ */ v("td", { className: "p-2" }, (d == null ? void 0 : d.control) === "input" || !d ? /* @__PURE__ */ v(
    "input",
    {
      id: `value${e}`,
      name: `value${e}`,
      type: "text",
      className: "form-control",
      placeholder: d == null ? void 0 : d.placeholder,
      defaultValue: r.value
    }
  ) : /* @__PURE__ */ v(
    di,
    {
      id: `value${e}`,
      name: `value${e}`,
      items: h(d == null ? void 0 : d.values),
      defaultValue: r.value
    }
  ))) : null;
}
function Bf(n) {
  const { text: e, applyURL: t, deleteProps: s } = n, [i, o] = Rr("lighter"), [r, a] = Rr("lighter"), { className: l, ...c } = s;
  return /* @__PURE__ */ v(
    "a",
    {
      className: `search-condition label rounded-full h-6 flex p-1 gap-2 items-center cursor-pointer ${i}`,
      href: t,
      style: { width: "fit-content", maxWidth: "100%" },
      onMouseOver: () => o("gray"),
      onMouseLeave: () => o("lighter")
    },
    /* @__PURE__ */ v("span", { className: "ellipsis" }, e),
    /* @__PURE__ */ v(
      "a",
      {
        className: `rounded-full h-5 w-5 center ${r} shrink-0 grow-0 ${l}`,
        onMouseOver: () => a("danger"),
        onMouseLeave: () => a("lighter"),
        ...c
      },
      /* @__PURE__ */ v("i", { className: "icon icon-close" })
    )
  );
}
let Vf = class extends F {
  constructor(e) {
    super(e);
    const { toggleSide: t = !0, formSession: s } = e;
    this.state = {
      toggleMore: !1,
      toggleSide: t,
      formSession: s,
      formKey: Date.now().toString()
    };
  }
  handleSubmit(e) {
    e.preventDefault();
    const { formConfig: t, actionURL: s, module: i, onSubmit: o } = this.props, { action: r, method: a } = t, l = new FormData(e.target);
    l.append("module", i), l.append("actionURL", s);
    const c = fetch(r, { method: a, body: l, headers: { "X-Requested-With": "XMLHttpRequest" } });
    o == null || o(c);
  }
  handleReset() {
    this.setState({
      formSession: [{}, {}, {}, {}, {}, {}, {}],
      formKey: Date.now().toString()
    });
  }
  render(e, t) {
    const { fields: s, operators: i, andOr: o, groupName: r, saveSearch: a, savedQueryTitle: l, searchConditions: c, className: d, submitText: h, resetText: f } = e, { toggleMore: m, toggleSide: p, formKey: y, formSession: _ } = t;
    return /* @__PURE__ */ v("form", { className: N("flex", "bg-white", d), onSubmit: this.handleSubmit.bind(this) }, /* @__PURE__ */ v("table", { className: "grow", key: y, style: { tableLayout: "fixed" } }, /* @__PURE__ */ v("tr", null, /* @__PURE__ */ v("td", null, /* @__PURE__ */ v("table", { className: "w-full", style: { tableLayout: "fixed" } }, /* @__PURE__ */ v(
      He,
      {
        show: !0,
        index: 1,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[0]
      }
    ), /* @__PURE__ */ v(
      He,
      {
        show: m,
        index: 2,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[1]
      }
    ), /* @__PURE__ */ v(
      He,
      {
        show: m,
        index: 3,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[2]
      }
    ))), /* @__PURE__ */ v("td", { className: "p-2", style: { width: 140 } }, /* @__PURE__ */ v(
      Mr,
      {
        id: "groupAndOr",
        name: "groupAndOr",
        options: e.andOr,
        defaultValue: _[6].groupAndOr
      }
    )), /* @__PURE__ */ v("td", null, /* @__PURE__ */ v("table", { className: "w-full", style: { tableLayout: "fixed" } }, /* @__PURE__ */ v(
      He,
      {
        show: !0,
        index: 4,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[3]
      }
    ), /* @__PURE__ */ v(
      He,
      {
        show: m,
        index: 5,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[4]
      }
    ), /* @__PURE__ */ v(
      He,
      {
        show: m,
        index: 6,
        fields: s,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: _[5]
      }
    )))), /* @__PURE__ */ v("tr", null, /* @__PURE__ */ v("td", null), /* @__PURE__ */ v("td", { className: "text-center p-2" }, /* @__PURE__ */ v("div", { className: "w-full flex justify-center gap-2" }, /* @__PURE__ */ v("button", { className: "btn primary", type: "submit" }, h), /* @__PURE__ */ v("button", { className: "btn", type: "button", onClick: this.handleReset.bind(this) }, f))), /* @__PURE__ */ v("td", { className: "text-right" }, /* @__PURE__ */ v(
      "button",
      {
        className: "btn btn-link",
        disabled: !a.hasPriv,
        type: "button",
        "data-toggle": a.config["data-toggle"],
        "data-type": a.config["data-type"],
        "data-data-type": a.config["data-data-type"],
        "data-url": a.config["data-url"]
      },
      /* @__PURE__ */ v("i", { className: "icon icon-save" }),
      a.text
    ), /* @__PURE__ */ v("button", { className: "btn btn-link", type: "button", onClick: () => this.setState({ toggleMore: !m }) }, /* @__PURE__ */ v("i", { className: N({
      icon: !0,
      "icon-chevron-double-up": m,
      "icon-chevron-double-down": !m
    }) }))))), /* @__PURE__ */ v(
      "button",
      {
        type: "button",
        className: "secondary self-center rounded-lg",
        style: { height: "min-content" },
        onClick: () => this.setState({ toggleSide: !p })
      },
      /* @__PURE__ */ v("i", { className: N({
        icon: !0,
        "icon-angle-left": p,
        "icon-angle-right": !p
      }) })
    ), /* @__PURE__ */ v(
      "div",
      {
        style: { width: 230, height: m ? 180 : 84 },
        className: N({
          "border-l": !0,
          hidden: p,
          col: !0,
          "flex-none": !0
        })
      },
      /* @__PURE__ */ v("strong", { className: "pl-2 py-2" }, l),
      /* @__PURE__ */ v("div", { className: "grow overflow-y-auto col gap-2 p-2" }, c.map((w) => /* @__PURE__ */ v(Bf, { ...w })))
    ));
  }
};
const wo = class wo extends B {
};
wo.NAME = "SearchForm", wo.Component = Vf;
let jl = wo;
const Uf = { 1: "error", 2: "warning", 4: "parse", 8: "notice", 16: "core-error", 32: "core-warning", 64: "compile-error", 128: "compile-warning", 256: "user-error", 512: "user-warning", 1024: "user-notice", 2048: "strict", 4096: "recoverable-error", 8192: "deprecated", 16384: "user-deprecated", 32767: "all" };
function Dh(n) {
  return typeof n == "number" && (n = Uf[n]), n;
}
function Ih(n) {
  return n = Dh(n), n.includes("error") ? "error" : n.includes("warning") ? "warning" : "info";
}
function qf({ errors: n, ...e }) {
  const t = n.reduce((s, i) => (s[Ih(i.level)]++, s), { error: 0, warning: 0, info: 0 });
  return /* @__PURE__ */ v("div", { class: "row items-stretch text-sm", "data-hint": "PHP errors", ...e }, t.error ? /* @__PURE__ */ v("button", { type: "button", class: "state font-bold px-0.5 danger" }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "ERR"), t.error) : null, t.warning ? /* @__PURE__ */ v("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-90" }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "WAR"), t.warning) : null, t.info ? /* @__PURE__ */ v("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-80" }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "INF"), t.info) : null);
}
function ut(n, e, t, s) {
  console.groupCollapsed(`%c${n} %c${e}`, "color: #fff; background-color: #9333ea; padding: 0 0.1em 0 0.25em; border-radius: 0.25em 0 0 0.25em;", "color: #9333ea; background-color: #e9d5ff; padding: 0 0.5em; border-radius: 0 0.25em 0.25em 0;", s), console.table(t), console.groupEnd();
}
function qt(n, e = 400, t = 100) {
  return n < t ? "success" : n < e ? "warning" : "danger";
}
function bs(n) {
  return n < 1e3 ? `${n.toFixed(0)}ms` : `${(n / 1e3).toFixed(2)}s`;
}
function Wl({ perf: n }) {
  var r;
  const e = n.id === "page" ? "PAGE" : n.id === "#dtable" ? "TABLE" : "PART", t = [], { trace: s, xhprof: i } = n, o = s == null ? void 0 : s.request;
  if (n.requestEnd) {
    const a = n.requestEnd - n.requestBegin;
    if (t.push(/* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(a, 1e3, 400)}`, "data-hint": "Total load time (G<400<=N<1000<=B)", onClick: ut.bind(null, "Trace", "Perf", n, n.id) }, /* @__PURE__ */ v("i", { class: "icon-history" }), " ", bs(a))), o) {
      const c = o.timeUsed;
      t.push(
        /* @__PURE__ */ v("div", { class: "muted" }, "/"),
        /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(c)}`, "data-hint": "Server time (G<100<=N<400<=B)", onClick: ut.bind(null, "Trace", "Request", o, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "S"), bs(c))
      );
    }
    if (n.dataSize) {
      if (o) {
        const c = a - o.timeUsed;
        t.push(
          /* @__PURE__ */ v("div", { class: "muted" }, "/"),
          /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(c, 600, 200)}`, "data-hint": "Network time (G<200<=N<600<=B)", onClick: ut.bind(null, "Trace", "Request", o, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "N"), bs(c))
        );
      }
      if (t.push(
        /* @__PURE__ */ v("div", { class: "px-0.5 state", "data-hint": "Loaded data size", onClick: ut.bind(null, "Trace", "Perf", n, n.id) }, /* @__PURE__ */ v("span", { class: "muted" }, /* @__PURE__ */ v("i", { class: "icon icon-cube muted" }), " ", Bn(n.dataSize, 1)))
      ), o) {
        const c = a - o.timeUsed, d = 1e3 * n.dataSize / c;
        t.push(
          /* @__PURE__ */ v("div", { class: "muted" }, "/"),
          /* @__PURE__ */ v("div", { class: `px-0.5 state text-${d < 102400 ? "danger" : d < 1024e3 ? "warning" : "success"}`, "data-hint": "Download speed(B<100KB<=N<1MB<=G)", onClick: ut.bind(null, "Trace", "Request", o, n.id) }, /* @__PURE__ */ v("i", { class: "icon icon-arrow-down" }), Bn(d, 1), "/s")
        );
      }
    }
    if (n.renderEnd && n.renderBegin) {
      const c = n.renderEnd - n.renderBegin, d = qt(c, 200, 50);
      t.push(
        /* @__PURE__ */ v("div", { class: "muted" }, "/"),
        /* @__PURE__ */ v("div", { class: `px-0.5 state text-${d}`, "data-hint": "Client render time (G<50<=N<200<=B)", onClick: ut.bind(null, "Trace", "Perf", n, n.id) }, /* @__PURE__ */ v("i", { class: `icon-${d === "danger" ? "frown" : d === "warning" ? "meh" : "smile"}` }), bs(c))
      );
    }
    if (o) {
      const { memory: c, querys: d } = o;
      typeof c == "number" && t.push(
        /* @__PURE__ */ v("div", { class: "muted" }, "/"),
        /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(c, 1024e3, 102400)}`, "data-hint": "Server memory usage(G<10KB<=N<100KB<=B)", onClick: ut.bind(null, "Trace", "Request", o, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "M"), Bn(c))
      ), typeof d == "number" && t.push(
        /* @__PURE__ */ v("div", { class: "muted" }, "/"),
        /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(d, 30, 10)}`, "data-hint": "Server sql queries count (G<30<=N<10<=B)", onClick: ut.bind(null, "SQL Query", `${((r = s.sqlQuery) == null ? void 0 : r.length) ?? 0} queries`, s.sqlQuery, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "Q"), d)
      );
    }
    s != null && s.files && t.push(
      /* @__PURE__ */ v("div", { class: "muted" }, "/"),
      /* @__PURE__ */ v("div", { class: "px-0.5 state", "data-hint": "Server loaded php files count", onClick: ut.bind(null, "Trace", `${s.files.length} php files`, s.files, n.id) }, /* @__PURE__ */ v("span", { class: "muted" }, /* @__PURE__ */ v("i", { class: "icon-file icon-sm muted scale-75" }), s.files.length))
    );
    const l = s == null ? void 0 : s.profiles;
    if (l != null && l.length) {
      let c = 0, d = { Duration: 0 };
      if (l.forEach((h) => {
        h.Duration > 0.3 && c++, h.Duration > d.Duration && (d = h);
      }), t.push(
        /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(c, 3, 1)}`, "data-hint": "Server slow SQL queries count (G<3<=N<1<=B)", onClick: ut.bind(null, "SQL Query", `${l.length} SQL profiles`, l, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "LQ"), c)
      ), d.Duration) {
        const h = d.Duration * 1e3;
        t.push(
          /* @__PURE__ */ v("div", { class: `px-0.5 state text-${qt(h, 600, 300)}`, "data-hint": "Server lowest SQL query duration (G<600<=N<300<=B)", onClick: ut.bind(null, "SQL Query", `Slowest SQL query: ${h}ms`, d, n.id) }, /* @__PURE__ */ v("span", { class: "scale-95 font-bold inline-block" }, "MLQ"), bs(h))
        );
      }
    }
  } else
    t.push(/* @__PURE__ */ v("div", { class: "muted px-0.5" }, "loading..."));
  return /* @__PURE__ */ v("div", { class: "zin-perf-btn-list row items-center bg-black text-sm" }, /* @__PURE__ */ v("div", { class: "px-1 bg-canvas bg-opacity-20 self-stretch flex items-center", "data-hint": `REQUEST: ${n.id} URL: ${n.url}` }, /* @__PURE__ */ v("span", { class: "muted" }, e)), t, i ? /* @__PURE__ */ v("a", { class: "state text-secondary px-0.5", href: i, target: "_blank", "data-hint": "Visit xhprof page" }, "XHP") : null);
}
function Gf(n) {
  ut("Trace", "Error", n, n.message), navigator.clipboard.writeText(`vim +${n.line} ${n.file}`);
}
function Yf({ errors: n = [], show: e, basePath: t }) {
  n.length || (e = !1);
  const s = n.map((i) => {
    const o = Dh(i.level), r = Ih(o), a = r === "error" ? "danger" : r === "info" ? "important" : "warning";
    return /* @__PURE__ */ v("div", { class: `zin-error-item state ${a}-pale text-fore px-2 py-1 ring ring-darker`, onClick: Gf.bind(null, i) }, /* @__PURE__ */ v("div", { class: "zin-error-msg font-bold text-base" }, /* @__PURE__ */ v("strong", { class: `text-${a}`, style: "text-transform: uppercase;" }, o), " ", i.message), /* @__PURE__ */ v("div", { class: "zin-error-info text-sm opacity-60 break-all" }, /* @__PURE__ */ v("strong", null, "vim +", i.line), " ", /* @__PURE__ */ v("span", { className: "underline" }, t ? i.file.substring(t.length) : i.file)));
  });
  return /* @__PURE__ */ v("div", { class: `zin-errors-panel absolute bottom-full left-0 mono shadow-xl ring rounded fade-from-bottom ${e ? "in" : "events-none"}` }, s);
}
let Xf = class extends F {
  constructor(e) {
    var t, s;
    super(e), this.state = {
      showPanel: e.defaultShow ?? !0,
      showZinbar: !!((s = (t = e.defaultData) == null ? void 0 : t.errors) != null && s.length || !fe.get("Zinbar:hidden")),
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
    const t = e.target;
    this.state.showPanel && !t.closest(".zinbar") && this.setState({ showPanel: !1 });
  }
  update(e, t, s) {
    this.setState((i) => {
      const o = !(e != null && e.id) || e.id === "page", r = {};
      return o ? (e && (r.pagePerf = { ...i.pagePerf, ...e }), t && (r.errors = t)) : (e && (r.partPerf = { ...i.partPerf, ...e }), t && (r.errors = [...i.errors ?? [], ...t ?? []])), s && (r.basePath = s), r;
    });
  }
  togglePanel() {
    this.setState({ showPanel: !this.state.showPanel });
  }
  toggleZinbar() {
    const e = !this.state.showZinbar;
    this.setState({ showZinbar: e }, () => {
      fe.set("Zinbar:hidden", !e);
    });
  }
  render() {
    const { errors: e, pagePerf: t, partPerf: s, showZinbar: i, basePath: o } = this.state, { fixed: r } = this.props, a = e == null ? void 0 : e.length;
    return /* @__PURE__ */ v(
      "div",
      {
        className: N(
          "zinbar row h-5 items-stretch gap-px inverse bg-opacity-50",
          r ? "relative" : "fixed right-0 bottom-0",
          { collapse: !i }
        ),
        style: { zIndex: 9999 }
      },
      /* @__PURE__ */ v(
        "button",
        {
          type: "button",
          "data-hint": i ? "Collapse" : "Expand",
          className: `w-4 ${a && !i ? "danger" : "bg-dark"} flex items-center justify-center`,
          style: { marginLeft: -15 },
          onClick: this.toggleZinbar.bind(this)
        },
        /* @__PURE__ */ v("span", { class: i ? "caret-right" : "caret-left" })
      ),
      a ? /* @__PURE__ */ v(qf, { errors: e, onClick: this.togglePanel }) : null,
      t ? /* @__PURE__ */ v(Wl, { perf: t }) : null,
      s ? /* @__PURE__ */ v(Wl, { perf: s }) : null,
      /* @__PURE__ */ v(Yf, { show: this.state.showPanel, basePath: o, errors: e })
    );
  }
};
const vo = class vo extends B {
};
vo.NAME = "Zinbar", vo.Component = Xf;
let zl = vo;
const _o = class _o extends Nt {
  _getLayoutOptions() {
    const e = super._getLayoutOptions();
    return this.options.element || (e[0] = {
      getBoundingClientRect: this._getClickBounding
    }), e;
  }
};
_o.NAME = "ContextMenu", _o.DEFAULT = {
  ...Nt.DEFAULT,
  name: "contextmenu",
  trigger: "contextmenu"
};
let Pr = _o;
let Kf = class extends F {
  constructor() {
    super(...arguments), this._onDragStart = (e) => {
      var i, o, r;
      const t = e.target.closest(".dashboard-block");
      if (!t)
        return;
      const s = t.getBoundingClientRect();
      if (e.clientY - s.top > 48) {
        e.preventDefault();
        return;
      }
      this.setState({ dragging: !0 }), (i = e.dataTransfer) == null || i.setData("application/id", this.props.id), (r = (o = this.props).onDragStart) == null || r.call(o, e);
    }, this._onDragEnd = (e) => {
      var t, s;
      this.setState({ dragging: !1 }), (s = (t = this.props).onDragEnd) == null || s.call(t, e);
    };
  }
  render() {
    const { left: e, top: t, id: s, onMenuBtnClick: i, title: o, width: r, height: a, content: l, loading: c } = this.props, { dragging: d } = this.state;
    return /* @__PURE__ */ g("div", { class: "dashboard-block-cell", style: { left: e, top: t, width: r, height: a }, children: /* @__PURE__ */ g(
      "div",
      {
        class: `dashboard-block load-indicator${c && !l ? " loading" : ""}${i ? " has-more-menu" : ""}${d ? " is-dragging" : ""}`,
        draggable: !0,
        onDragStart: this._onDragStart,
        onDragEnd: this._onDragEnd,
        "data-id": s,
        children: [
          /* @__PURE__ */ g("div", { class: "dashboard-block-header", children: [
            /* @__PURE__ */ g("div", { class: "dashboard-block-title", children: o }),
            i ? /* @__PURE__ */ g("div", { class: "dashboard-block-actions toolbar", children: /* @__PURE__ */ g("button", { class: "toolbar-item dashboard-block-action btn square ghost rounded size-sm", "data-type": "more", onClick: i, children: /* @__PURE__ */ g("div", { class: "more-vert" }) }) }) : null
          ] }),
          u.isPlainObject(l) && l.html ? /* @__PURE__ */ g(Rn, { className: "dashboard-block-body", executeScript: !0, ...l }) : /* @__PURE__ */ g("div", { class: "dashboard-block-body", children: l })
        ]
      }
    ) });
  }
};
const Fl = ([n, e, t, s], [i, o, r, a]) => !(n + t <= i || i + r <= n || e + s <= o || o + a <= e), Fn = "Dashboard:Block.cache:";
var kn;
let Zf = (kn = class extends F {
  constructor(e) {
    super(e), this._ref = q(), this._loadTimer = 0, this.map = /* @__PURE__ */ new Map(), this.tryLoadNext = () => {
      clearTimeout(this._loadTimer), this._loadTimer = window.setTimeout(() => this.loadNext(), 100);
    }, this._handleDragStart = (t) => {
      var i;
      const s = (i = t.dataTransfer) == null ? void 0 : i.getData("application/id");
      s !== void 0 && (this.setState({ dragging: s }), console.log("handleBlockDragStart", t));
    }, this._handleDragEnd = (t) => {
      this.setState({ dragging: void 0 }), console.log("handleBlockDragEnd", t);
    }, this._handleMenuClick = (t) => {
      const s = t.target.closest(".dashboard-block");
      if (!s)
        return;
      const i = s.dataset.id;
      if (!i)
        return;
      const o = this.getBlock(i);
      if (!o || !o.menu)
        return;
      const { menu: r } = o, { onClickMenu: a } = this.props;
      Pr.show({
        triggerEvent: t,
        element: t.currentTarget,
        placement: "bottom-end",
        menu: {
          onClickItem: (l) => {
            var c;
            ((c = l.item.data) == null ? void 0 : c.type) === "refresh" && this.load(i), a && a.call(this, l, o);
          },
          ...r
        }
      });
    }, this.state = { blocks: this._initBlocks(e.blocks) };
  }
  getBlock(e) {
    return this.state.blocks.find((t) => t.id === e);
  }
  update(e, t) {
    const { id: s } = e, { blocks: i } = this.state, o = i.findIndex((a) => a.id === s);
    if (o < 0)
      return;
    const r = i[o];
    e.fetch && e.fetch !== r.fetch && r.needLoad && (e.needLoad = !1), i[o] = { ...r, ...e }, this.setState({ blocks: i }, t);
  }
  delete(e) {
    const { blocks: t } = this.state, s = t.findIndex((i) => i.id === e);
    s < 0 || (t.splice(s, 1), this.setState({ blocks: t }));
  }
  add(e) {
    e = Array.isArray(e) ? e : [e], this.setState({ blocks: [...this.state.blocks, ...this._initBlocks(e)] });
  }
  load(e, t) {
    const s = this.getBlock(e);
    if (!s || s.loading || (t = t || s.fetch, typeof t == "string" ? t = { url: t } : typeof t == "function" && (t = t(s.id, s)), !t || !t.url))
      return;
    const { url: i, ...o } = t;
    this.update({ id: e, loading: !0, needLoad: !1 }, async () => {
      const r = U(i, s);
      try {
        const a = await fetch(U(r, s), {
          headers: { "X-Requested-With": "XMLHttpRequest" },
          ...o
        });
        if (!a.ok)
          throw new Error(`Server response: ${a.status} ${a.statusText}}`);
        const l = await a.text();
        this.update({ id: e, loading: !1, content: { html: l } }, () => {
          this._setCache(e, l);
        });
      } catch (a) {
        const l = /* @__PURE__ */ g("div", { class: "panel center text-danger p-5", children: [
          "Error: ",
          a.message
        ] });
        this.update({ id: e, loading: !1, content: l });
      }
    });
  }
  reset(e) {
    this.setState({ blocks: this._initBlocks(e) });
  }
  loadNext() {
    const { blocks: e } = this.state;
    let t = "";
    for (const s of e) {
      if (s.loading)
        return;
      if (!s.visible && this._isVisible(s.id))
        return this.update({ id: s.id, visible: !0 });
      if (s.needLoad && s.visible) {
        t = s.id;
        break;
      }
    }
    t.length && requestAnimationFrame(() => this.load(t));
  }
  _isVisible(e) {
    return !!u(this._ref.current).find(`.dashboard-block[data-id="${e}"]`).isVisible();
  }
  _setCache(e, t) {
    const { cache: s } = this.props;
    if (s)
      try {
        typeof s == "string" ? fe.set(`${Fn}${s}:${e}`, t) : fe.session.set(`${Fn}${e}`, t);
      } catch {
        console.warn("ZUI: Failed to cache block content.", { id: e, html: t });
      }
  }
  _getCache(e) {
    const { cache: t } = this.props;
    if (!t)
      return;
    const s = typeof t == "string" ? fe.get(`${Fn}${t}:${e}`) : fe.session.get(`${Fn}${e}`);
    if (s)
      return { html: s };
  }
  _initBlocks(e) {
    const { blockFetch: t, blockMenu: s } = this.props;
    return e.map((o) => {
      const {
        id: r,
        size: a,
        left: l = -1,
        top: c = -1,
        fetch: d = t,
        menu: h = s,
        content: f,
        ...m
      } = o, [p, y] = this._getBlockSize(a);
      return {
        id: `${r}`,
        width: p,
        height: y,
        left: l,
        top: c,
        fetch: d,
        menu: h,
        content: f ?? this._getCache(`${r}`),
        loading: !1,
        needLoad: !!d,
        ...m
      };
    });
  }
  _getBlockSize(e) {
    const { blockDefaultSize: t, blockSizeMap: s } = this.props;
    return e = e ?? t, typeof e == "string" && (e = s[e]), e = e || t, Array.isArray(e) || (e = [e.width, e.height]), e;
  }
  _layout() {
    this.map.clear();
    let e = 0;
    const { blocks: t } = this.state;
    return t.forEach((s) => {
      this._layoutBlock(s);
      const [, i, , o] = this.map.get(s.id);
      e = Math.max(e, i + o);
    }), { blocks: t, height: e };
  }
  _layoutBlock(e) {
    const t = this.map, { id: s, left: i, top: o, width: r, height: a } = e;
    if (i < 0 || o < 0) {
      const [l, c] = this._appendBlock(r, a, i, o);
      t.set(s, [l, c, r, a]);
    } else
      this._insertBlock(s, [i, o, r, a]);
  }
  _canPlace(e) {
    const { dragging: t } = this.state;
    for (const [s, i] of this.map.entries())
      if (s !== t && Fl(i, e))
        return !1;
    return !0;
  }
  _insertBlock(e, t) {
    this.map.set(e, t);
    for (const [s, i] of this.map.entries())
      s !== e && Fl(i, t) && (i[1] = t[1] + t[3], this._insertBlock(s, i));
  }
  _appendBlock(e, t, s, i) {
    if (s >= 0 && i >= 0) {
      if (this._canPlace([s, i, e, t]))
        return [s, i];
      i = -1;
    }
    let o = s < 0 ? 0 : s, r = i < 0 ? 0 : i, a = !1;
    const l = this.props.grid;
    for (; !a; ) {
      if (this._canPlace([o, r, e, t])) {
        a = !0;
        break;
      }
      s < 0 ? (o += 1, o + e > l && (o = 0, r += 1)) : r += 1;
    }
    return [o, r];
  }
  componentDidMount() {
    this.loadNext(), u(window).on("scroll", this.tryLoadNext);
  }
  componentDidUpdate(e) {
    e.blocks !== this.props.blocks ? this.setState({ blocks: this._initBlocks(this.props.blocks) }) : this.loadNext();
  }
  componentWillUnmount() {
    clearTimeout(this._loadTimer), u(window).off("scroll", this.tryLoadNext);
  }
  render() {
    const { blocks: e, height: t } = this._layout(), { cellHeight: s, grid: i } = this.props, o = this.map;
    return /* @__PURE__ */ g("div", { class: "dashboard", children: /* @__PURE__ */ g(
      "div",
      {
        class: "dashboard-blocks",
        style: { height: t * s },
        ref: this._ref,
        children: e.map((r, a) => {
          const { id: l, menu: c, content: d, title: h } = r, [f, m, p, y] = o.get(l) || [0, 0, r.width, r.height];
          return /* @__PURE__ */ g(
            Kf,
            {
              id: l,
              index: a,
              left: `${100 * f / i}%`,
              top: s * m,
              width: `${100 * p / i}%`,
              height: s * y,
              content: d,
              title: h,
              onDragStart: this._handleDragStart,
              onDragEnd: this._handleDragEnd,
              onMenuBtnClick: c ? this._handleMenuClick : void 0
            },
            r.id
          );
        })
      }
    ) });
  }
}, kn.defaultProps = {
  responsive: !1,
  cache: !0,
  blocks: [],
  grid: 3,
  gap: 16,
  cellHeight: 64,
  blockDefaultSize: [1, 3],
  blockMenu: { items: [{ text: "Refresh", data: { type: "refresh" } }] },
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
}, kn);
const xo = class xo extends B {
};
xo.NAME = "Dashboard", xo.Component = Zf;
let Bl = xo;
var Qt, te;
class Vl extends F {
  constructor(t) {
    super(t);
    $(this, Qt, void 0);
    $(this, te, void 0);
    C(this, Qt, 0), C(this, te, null), this._handleWheel = (s) => {
      const { wheelContainer: i } = this.props, o = s.target;
      if (!(!o || !i) && (typeof i == "string" && o.closest(i) || typeof i == "object")) {
        const r = (this.props.type === "horz" ? s.deltaX : s.deltaY) * (this.props.wheelSpeed ?? 1);
        this.scrollOffset(r) && s.preventDefault();
      }
    }, this._handleMouseMove = (s) => {
      const { dragStart: i } = this.state;
      i && (b(this, Qt) && cancelAnimationFrame(b(this, Qt)), C(this, Qt, requestAnimationFrame(() => {
        const o = this.props.type === "horz" ? s.clientX - i.x : s.clientY - i.y;
        this.scroll(i.offset + o * this.props.scrollSize / this.props.clientSize), C(this, Qt, 0);
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
      const o = i.getBoundingClientRect(), { type: r, clientSize: a, scrollSize: l } = this.props, c = (r === "horz" ? s.clientX - o.left : s.clientY - o.top) - this.barSize / 2;
      this.scroll(c * l / a), s.preventDefault();
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
    t && (C(this, te, typeof t == "string" ? document : t.current), b(this, te).addEventListener("wheel", this._handleWheel, { passive: !1 }));
  }
  componentWillUnmount() {
    document.removeEventListener("mousemove", this._handleMouseMove), document.removeEventListener("mouseup", this._handleMouseUp), b(this, te) && b(this, te).removeEventListener("wheel", this._handleWheel);
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
      left: a,
      top: l,
      bottom: c,
      right: d
    } = this.props, { maxScrollPos: h, scrollPos: f } = this, { dragStart: m } = this.state, p = {
      left: a,
      top: l,
      bottom: c,
      right: d,
      ...r
    }, y = {};
    return s === "horz" ? (p.height = i, p.width = t, y.width = this.barSize, y.left = Math.round(Math.min(h, f) * (t - y.width) / h)) : (p.width = i, p.height = t, y.height = this.barSize, y.top = Math.round(Math.min(h, f) * (t - y.height) / h)), /* @__PURE__ */ g(
      "div",
      {
        className: N("scrollbar", o, {
          "is-vert": s === "vert",
          "is-horz": s === "horz",
          "is-dragging": m
        }),
        style: p,
        onMouseDown: this._handleClick,
        children: /* @__PURE__ */ g(
          "div",
          {
            className: "scrollbar-bar",
            style: y,
            onMouseDown: this._handleMouseDown
          }
        )
      }
    );
  }
}
Qt = new WeakMap(), te = new WeakMap();
const fi = /* @__PURE__ */ new Map(), pi = [];
function Rh(n, e) {
  const { name: t } = n;
  if (!(e != null && e.override) && fi.has(t))
    throw new Error(`DTable: Plugin with name ${t} already exists`);
  fi.set(t, n), e != null && e.buildIn && !pi.includes(t) && pi.push(t);
}
function at(n, e) {
  Rh(n, e);
  const t = (s) => {
    if (!s)
      return n;
    const { defaultOptions: i, ...o } = n;
    return {
      ...o,
      defaultOptions: { ...i, ...s }
    };
  };
  return t.plugin = n, t;
}
function Ah(n) {
  return fi.delete(n);
}
function Jf(n) {
  if (typeof n == "string") {
    const e = fi.get(n);
    return e || console.warn(`DTable: Cannot found plugin "${n}"`), e;
  }
  if (typeof n == "function" && "plugin" in n)
    return n.plugin;
  if (typeof n == "object")
    return n;
  console.warn("DTable: Invalid plugin", n);
}
function Ph(n, e, t) {
  return e.forEach((s) => {
    var o;
    if (!s)
      return;
    const i = Jf(s);
    i && (t.has(i.name) || ((o = i.plugins) != null && o.length && Ph(n, i.plugins, t), n.push(i), t.add(i.name)));
  }), n;
}
function Qf(n = [], e = !0) {
  return e && pi.length && n.unshift(...pi), n != null && n.length ? Ph([], n, /* @__PURE__ */ new Set()) : [];
}
function Ul() {
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
function tp(n, e, t) {
  return n && (e && (n = Math.max(e, n)), t && (n = Math.min(t, n))), n;
}
function ql(n, e) {
  return typeof n == "string" && (n = n.endsWith("%") ? parseFloat(n) / 100 : parseFloat(n)), typeof e == "number" && (typeof n != "number" || isNaN(n)) && (n = e), n;
}
function sr(n, e = !1) {
  if (!n.list.length)
    return;
  if (n.widthSetting && n.width !== n.widthSetting) {
    n.width = n.widthSetting;
    const s = n.width - n.totalWidth;
    if (!e && s > 0 || e && s !== 0) {
      const i = n.flexList.length ? n.flexList : n.list, o = i.reduce((r, a) => r + (a.flex || 1), 0);
      i.forEach((r) => {
        const a = Math[s < 0 ? "max" : "min"](s, Math.ceil(s * ((r.flex || 1) / o)));
        r.realWidth = r.width + a;
      });
    }
  }
  let t = 0;
  n.list.forEach((s) => {
    s.realWidth || (s.realWidth = s.width), s.left = t, t += s.realWidth;
  });
}
function ep(n, e, t, s) {
  const { defaultColWidth: i, minColWidth: o, maxColWidth: r, fixedLeftWidth: a = 0, fixedRightWidth: l = 0 } = e, c = (x) => (typeof x == "function" && (x = x.call(n)), x = ql(x, 0), x < 1 && (x = Math.round(x * s)), x), d = {
    width: 0,
    list: [],
    flexList: [],
    widthSetting: 0,
    totalWidth: 0
  }, h = {
    ...d,
    list: [],
    flexList: [],
    widthSetting: c(a)
  }, f = {
    ...d,
    list: [],
    flexList: [],
    widthSetting: c(l)
  }, m = [], p = {};
  let y = !1;
  const _ = [], w = {};
  if (t.forEach((x) => {
    const { colTypes: T, onAddCol: k } = x;
    T && Object.entries(T).forEach(([E, D]) => {
      w[E] || (w[E] = []), w[E].push(D);
    }), k && _.push(k);
  }), e.cols.forEach((x) => {
    if (x.hidden)
      return;
    const { type: T = "", name: k } = x, E = {
      fixed: !1,
      flex: !1,
      width: i,
      minWidth: o,
      maxWidth: r,
      ...x,
      type: T
    }, D = {
      name: k,
      type: T,
      setting: E,
      flex: 0,
      left: 0,
      width: 0,
      realWidth: 0,
      visible: !0,
      index: m.length
    }, L = w[T];
    L && L.forEach((W) => {
      const V = typeof W == "function" ? W.call(n, E) : W;
      V && Object.assign(E, V, x);
    });
    const { fixed: R, flex: A, minWidth: P = o, maxWidth: H = r } = E, M = ql(E.width || i, i);
    D.flex = A === !0 ? 1 : typeof A == "number" ? A : 0, D.width = tp(M < 1 ? Math.round(M * s) : M, P, H), _.forEach((W) => W.call(n, D)), m.push(D), p[D.name] = D;
    const S = R ? R === "left" ? h : f : d;
    S.list.push(D), S.totalWidth += D.width, S.width = S.totalWidth, D.flex && S.flexList.push(D), typeof E.order == "number" && (y = !0);
  }), y) {
    const x = (T, k) => (T.setting.order ?? 0) - (k.setting.order ?? 0);
    m.sort(x), h.list.sort(x), d.list.sort(x), f.list.sort(x);
  }
  return sr(h, !0), sr(f, !0), d.widthSetting = s - h.width - f.width, sr(d), {
    list: m,
    map: p,
    left: h,
    center: d,
    right: f
  };
}
function sp({ col: n, className: e, height: t, row: s, onRenderCell: i, style: o, outerStyle: r, children: a, outerClass: l, width: c, left: d, top: h, ...f }) {
  var M;
  const m = {
    left: d ?? n.left,
    top: h ?? s.top,
    width: c ?? n.realWidth,
    height: t,
    ...r
  }, { align: p, border: y } = n.setting, _ = {
    justifyContent: p ? p === "left" ? "start" : p === "right" ? "end" : p : void 0,
    ...n.setting.cellStyle,
    ...o
  }, w = ["dtable-cell", l, e, n.setting.className, {
    "has-border-left": y === !0 || y === "left",
    "has-border-right": y === !0 || y === "right"
  }], x = ["dtable-cell-content", n.setting.cellClass], T = (M = s.data) == null ? void 0 : M[n.name], k = [a ?? T ?? ""], E = i ? i(k, { row: s, col: n, value: T }, v) : k, D = [], L = [], R = {}, A = {};
  let P = "div";
  E == null || E.forEach((S) => {
    if (typeof S == "object" && S && !st(S) && ("html" in S || "className" in S || "style" in S || "attrs" in S || "children" in S || "tagName" in S)) {
      const W = S.outer ? D : L;
      S.html ? W.push(/* @__PURE__ */ g("div", { className: N("dtable-cell-html", S.className), style: S.style, dangerouslySetInnerHTML: { __html: S.html }, ...S.attrs ?? {} })) : (S.style && Object.assign(S.outer ? m : _, S.style), S.className && (S.outer ? w : x).push(S.className), S.children && W.push(S.children), S.attrs && Object.assign(S.outer ? R : A, S.attrs)), S.tagName && !S.outer && (P = S.tagName);
    } else
      L.push(S);
  });
  const H = P;
  return /* @__PURE__ */ g(
    "div",
    {
      className: N(w),
      style: m,
      "data-col": n.name,
      "data-row": s.id,
      "data-type": n.type || null,
      ...f,
      ...R,
      children: [
        L.length > 0 && /* @__PURE__ */ g(H, { className: N(x), style: _, ...A, children: L }),
        D
      ]
    }
  );
}
function nr({
  rows: n = [],
  cols: e,
  rowHeight: t,
  scrollLeft: s = 0,
  scrollTop: i = 0,
  left: o = 0,
  top: r = 0,
  width: a,
  height: l = "100%",
  className: c,
  CellComponent: d = sp,
  onRenderCell: h
}) {
  var y;
  const f = Array.isArray(n) ? n : [n], m = ((y = f[0]) == null ? void 0 : y.top) ?? 0, p = f.length;
  return /* @__PURE__ */ g(
    "div",
    {
      className: N("dtable-cells", c),
      style: { top: r, left: o, width: a, height: l },
      children: /* @__PURE__ */ g("div", { className: "dtable-cells-container", style: { left: -s, top: -i + m }, children: f.reduce((_, w, x) => {
        const T = e.length;
        return e.forEach((k, E) => {
          _.push(
            /* @__PURE__ */ g(
              d,
              {
                className: N(
                  `is-${w.index % 2 ? "odd" : "even"}-row`,
                  E ? "" : "is-first-in-row",
                  E === T - 1 ? "is-last-in-row" : "",
                  x ? "" : "is-first-row",
                  x === p - 1 ? "is-last-row" : ""
                ),
                col: k,
                row: w,
                top: w.top - m,
                height: t,
                onRenderCell: h
              },
              `${w.index}:${k.name}`
            )
          );
        }), _;
      }, []) })
    }
  );
}
function Gl({
  top: n,
  height: e,
  rowHeight: t,
  rows: s,
  cols: { left: i, center: o, right: r },
  scrollLeft: a,
  scrollTop: l,
  className: c,
  style: d,
  onRenderCell: h
}) {
  let f = null;
  i.list.length && (f = /* @__PURE__ */ g(
    nr,
    {
      className: "dtable-fixed-left",
      rows: s,
      scrollTop: l,
      cols: i.list,
      width: i.width,
      rowHeight: t,
      onRenderCell: h
    },
    "left"
  ));
  let m = null;
  o.list.length && (m = /* @__PURE__ */ g(
    nr,
    {
      rows: s,
      className: "dtable-scroll-center",
      scrollLeft: a,
      scrollTop: l,
      cols: o.list,
      left: i.width,
      width: o.width,
      rowHeight: t,
      onRenderCell: h
    },
    "center"
  ));
  let p = null;
  return r.list.length && (p = /* @__PURE__ */ g(
    nr,
    {
      className: "dtable-fixed-right",
      rows: s,
      scrollTop: l,
      cols: r.list,
      left: i.width + o.width,
      width: r.width,
      rowHeight: t,
      onRenderCell: h
    },
    "right"
  )), /* @__PURE__ */ g(
    "div",
    {
      className: N("dtable-block", c),
      style: { ...d, top: n, height: e },
      children: [
        f,
        m,
        p
      ]
    }
  );
}
var ee, os, Lt, Ct, se, nt, Tt, ft, _e, Sn, rs, xe, kt, $e, Ce, $o, Lh, Co, Hh, To, Oh, ko, jh, En, Lr, as, ls, Nn, Mn, Dn, In, cs, Yn, So, Wh, Eo, zh, No, Fh, ms;
let np = (ms = class extends F {
  constructor(t) {
    super(t);
    $(this, $o);
    $(this, Co);
    $(this, To);
    $(this, ko);
    $(this, En);
    $(this, cs);
    $(this, So);
    $(this, Eo);
    $(this, No);
    $(this, ee, void 0);
    $(this, os, void 0);
    $(this, Lt, void 0);
    $(this, Ct, void 0);
    $(this, se, void 0);
    $(this, nt, void 0);
    $(this, Tt, void 0);
    $(this, ft, void 0);
    $(this, _e, void 0);
    $(this, Sn, void 0);
    $(this, rs, void 0);
    $(this, xe, void 0);
    $(this, kt, void 0);
    $(this, $e, void 0);
    $(this, Ce, void 0);
    $(this, as, void 0);
    $(this, ls, void 0);
    $(this, Nn, void 0);
    $(this, Mn, void 0);
    $(this, Dn, void 0);
    $(this, In, void 0);
    this.ref = q(), C(this, ee, 0), C(this, Lt, !1), C(this, nt, []), C(this, ft, /* @__PURE__ */ new Map()), C(this, _e, {}), C(this, rs, []), C(this, xe, { in: !1 }), this.updateLayout = () => {
      b(this, ee) && cancelAnimationFrame(b(this, ee)), C(this, ee, requestAnimationFrame(() => {
        this.update({ dirtyType: "layout" }), C(this, ee, 0);
      }));
    }, C(this, kt, (s, i) => {
      i = i || s.type;
      const o = b(this, ft).get(i);
      if (o != null && o.length) {
        for (const r of o)
          if (r.call(this, s) === !1) {
            s.stopPropagation(), s.preventDefault();
            break;
          }
      }
    }), C(this, $e, (s) => {
      b(this, kt).call(this, s, `window_${s.type}`);
    }), C(this, Ce, (s) => {
      b(this, kt).call(this, s, `document_${s.type}`);
    }), C(this, as, (s, i, o) => {
      const { row: r, col: a } = i;
      i.value = this.getCellValue(r, a), s[0] = i.value;
      const l = r.id === "HEADER" ? "onRenderHeaderCell" : "onRenderCell";
      return b(this, nt).forEach((c) => {
        c[l] && (s = c[l].call(this, s, i, o));
      }), this.options[l] && (s = this.options[l].call(this, s, i, o)), a.setting[l] && (s = a.setting[l].call(this, s, i, o)), s;
    }), C(this, ls, (s, i) => {
      i === "horz" ? this.scroll({ scrollLeft: s }) : this.scroll({ scrollTop: s });
    }), C(this, Nn, (s) => {
      var l, c, d;
      const i = this.getPointerInfo(s);
      if (!i)
        return;
      const { rowID: o, colName: r, cellElement: a } = i;
      if (o === "HEADER")
        a && ((l = this.options.onHeaderCellClick) == null || l.call(this, s, { colName: r, element: a }), b(this, nt).forEach((h) => {
          var f;
          (f = h.onHeaderCellClick) == null || f.call(this, s, { colName: r, element: a });
        }));
      else {
        const h = this.layout.visibleRows.find((f) => f.id === o);
        if (a) {
          if (((c = this.options.onCellClick) == null ? void 0 : c.call(this, s, { colName: r, rowID: o, rowInfo: h, element: a })) === !0)
            return;
          for (const f of b(this, nt))
            if (((d = f.onCellClick) == null ? void 0 : d.call(this, s, { colName: r, rowID: o, rowInfo: h, element: a })) === !0)
              return;
        }
      }
    }), C(this, Mn, (s) => {
      const i = s.key.toLowerCase();
      if (["pageup", "pagedown", "home", "end"].includes(i))
        return !this.scroll({ to: i.replace("page", "") });
    }), C(this, Dn, (s) => {
      const i = u(s.target).closest(".dtable-cell");
      if (!i.length)
        return O(this, cs, Yn).call(this, !1);
      O(this, cs, Yn).call(this, [i.attr("data-row"), i.attr("data-col")]);
    }), C(this, In, () => {
      O(this, cs, Yn).call(this, !1);
    }), C(this, os, t.id ?? `dtable-${Qc(10)}`), this.state = { scrollTop: 0, scrollLeft: 0, renderCount: 0 }, C(this, se, Object.freeze(Qf(t.plugins))), b(this, se).forEach((s) => {
      var a;
      const { methods: i, data: o, state: r } = s;
      i && Object.entries(i).forEach(([l, c]) => {
        typeof c == "function" && Object.assign(this, { [l]: c.bind(this) });
      }), o && Object.assign(b(this, _e), o.call(this)), r && Object.assign(this.state, r.call(this)), (a = s.onCreate) == null || a.call(this, s);
    });
  }
  get options() {
    var t;
    return ((t = b(this, Tt)) == null ? void 0 : t.options) || b(this, Ct) || Ul();
  }
  get plugins() {
    return b(this, nt);
  }
  get layout() {
    return b(this, Tt);
  }
  get id() {
    return b(this, os);
  }
  get data() {
    return b(this, _e);
  }
  get element() {
    return this.ref.current;
  }
  get parent() {
    var t;
    return this.props.parent ?? ((t = this.element) == null ? void 0 : t.parentElement);
  }
  get hoverInfo() {
    return b(this, xe);
  }
  componentWillReceiveProps() {
    C(this, Ct, void 0);
  }
  componentDidMount() {
    b(this, Lt) ? this.forceUpdate() : O(this, En, Lr).call(this), b(this, nt).forEach((s) => {
      let { events: i } = s;
      i && (typeof i == "function" && (i = i.call(this)), Object.entries(i).forEach(([o, r]) => {
        r && this.on(o, r);
      }));
    }), this.on("click", b(this, Nn)), this.on("keydown", b(this, Mn));
    const { options: t } = this;
    if ((t.rowHover || t.colHover) && (this.on("mouseover", b(this, Dn)), this.on("mouseleave", b(this, In))), t.responsive)
      if (typeof ResizeObserver < "u") {
        const { parent: s } = this;
        if (s) {
          const i = new ResizeObserver(this.updateLayout);
          i.observe(s), C(this, Sn, i);
        }
      } else
        this.on("window_resize", this.updateLayout);
    b(this, nt).forEach((s) => {
      var i;
      (i = s.onMounted) == null || i.call(this);
    });
  }
  componentDidUpdate() {
    b(this, Lt) ? O(this, En, Lr).call(this) : b(this, nt).forEach((t) => {
      var s;
      (s = t.onUpdated) == null || s.call(this);
    });
  }
  componentWillUnmount() {
    var s;
    (s = b(this, Sn)) == null || s.disconnect();
    const { element: t } = this;
    if (t)
      for (const i of b(this, ft).keys())
        i.startsWith("window_") ? window.removeEventListener(i.replace("window_", ""), b(this, $e)) : i.startsWith("document_") ? document.removeEventListener(i.replace("document_", ""), b(this, Ce)) : t.removeEventListener(i, b(this, kt));
    b(this, nt).forEach((i) => {
      var o;
      (o = i.onUnmounted) == null || o.call(this);
    }), b(this, se).forEach((i) => {
      var o;
      (o = i.onDestory) == null || o.call(this);
    }), C(this, _e, {}), b(this, ft).clear();
  }
  on(t, s, i) {
    var r;
    i && (t = `${i}_${t}`);
    const o = b(this, ft).get(t);
    o ? o.push(s) : (b(this, ft).set(t, [s]), t.startsWith("window_") ? window.addEventListener(t.replace("window_", ""), b(this, $e)) : t.startsWith("document_") ? document.addEventListener(t.replace("document_", ""), b(this, Ce)) : (r = this.element) == null || r.addEventListener(t, b(this, kt)));
  }
  off(t, s, i) {
    var a;
    i && (t = `${i}_${t}`);
    const o = b(this, ft).get(t);
    if (!o)
      return;
    const r = o.indexOf(s);
    r >= 0 && o.splice(r, 1), o.length || (b(this, ft).delete(t), t.startsWith("window_") ? window.removeEventListener(t.replace("window_", ""), b(this, $e)) : t.startsWith("document_") ? document.removeEventListener(t.replace("document_", ""), b(this, Ce)) : (a = this.element) == null || a.removeEventListener(t, b(this, kt)));
  }
  emitCustomEvent(t, s) {
    b(this, kt).call(this, s instanceof Event ? s : new CustomEvent(t, { detail: s }), t);
  }
  scroll(t, s) {
    const { scrollLeft: i, scrollTop: o, rowsHeightTotal: r, rowsHeight: a, rowHeight: l, cols: { center: { totalWidth: c, width: d } } } = this.layout, { to: h } = t;
    let { scrollLeft: f, scrollTop: m } = t;
    if (h === "up" || h === "down")
      m = o + (h === "down" ? 1 : -1) * Math.floor(a / l) * l;
    else if (h === "left" || h === "right")
      f = i + (h === "right" ? 1 : -1) * d;
    else if (h === "top")
      m = 0;
    else if (h === "bottom")
      m = r - a;
    else if (h === "begin")
      f = 0;
    else if (h === "end")
      f = c - d;
    else {
      const { offsetLeft: y, offsetTop: _ } = t;
      typeof y == "number" && (f = i + y), typeof _ == "number" && (f = o + _);
    }
    const p = {};
    return typeof f == "number" && (f = Math.max(0, Math.min(f, c - d)), f !== i && (p.scrollLeft = f)), typeof m == "number" && (m = Math.max(0, Math.min(m, r - a)), m !== o && (p.scrollTop = m)), Object.keys(p).length ? (this.setState(p, () => {
      var y;
      (y = this.options.onScroll) == null || y.call(this, p), s == null || s.call(this, !0);
    }), !0) : (s == null || s.call(this, !1), !1);
  }
  getColInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    const { cols: s } = this.layout;
    return typeof t == "number" ? s.list[t] : s.map[t];
  }
  getRowInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    if (t === -1 || t === "HEADER")
      return { id: "HEADER", index: -1, top: 0 };
    const { rows: s, rowsMap: i, allRows: o } = this.layout;
    return typeof t == "number" ? s[t] : i[t] || o.find((r) => r.id === t);
  }
  getCellValue(t, s) {
    var l;
    const i = typeof t == "object" ? t : this.getRowInfo(t);
    if (!i)
      return;
    const o = typeof s == "object" ? s : this.getColInfo(s);
    if (!o)
      return;
    let r = i.id === "HEADER" ? o.setting.title : (l = i.data) == null ? void 0 : l[o.name];
    const { cellValueGetter: a } = this.options;
    return a && (r = a.call(this, i, o, r)), r;
  }
  getRowInfoByIndex(t) {
    return this.layout.rows[t];
  }
  update(t = {}, s) {
    if (!b(this, Ct))
      return;
    typeof t == "function" && (s = t, t = {});
    const { dirtyType: i, state: o } = t;
    if (i === "layout")
      C(this, Tt, void 0);
    else if (i === "options") {
      if (C(this, Ct, void 0), !b(this, Tt))
        return;
      C(this, Tt, void 0);
    }
    this.setState(o ?? ((r) => ({ renderCount: r.renderCount + 1 })), s);
  }
  getPointerInfo(t) {
    const s = t.target;
    if (!s || s.closest(".no-cell-event"))
      return;
    const i = u(s).closest(".dtable-cell");
    if (!i.length)
      return;
    const o = i.attr("data-row"), r = i.attr("data-col");
    if (!(typeof r != "string" || typeof o != "string"))
      return {
        cellElement: i[0],
        colName: r,
        rowID: o,
        target: s
      };
  }
  i18n(t, s, i) {
    return tt(b(this, rs), t, s, i, this.options.lang) ?? `{i18n:${t}}`;
  }
  getPlugin(t) {
    return this.plugins.find((s) => s.name === t);
  }
  render() {
    const t = O(this, No, Fh).call(this), { className: s, rowHover: i, colHover: o, cellHover: r, bordered: a, striped: l, scrollbarHover: c } = this.options, d = {}, h = ["dtable", s, {
      "dtable-hover-row": i,
      "dtable-hover-col": o,
      "dtable-hover-cell": r,
      "dtable-bordered": a,
      "dtable-striped": l,
      "scrollbar-hover": c
    }], f = [];
    return t && (d.width = t.width, d.height = t.height, h.push({
      "dtable-scrolled-down": t.scrollTop > 0,
      "dtable-scrolled-bottom": t.scrollTop >= t.rowsHeightTotal - t.rowsHeight,
      "dtable-scrolled-right": t.scrollLeft > 0,
      "dtable-scrolled-end": t.scrollLeft >= t.cols.center.totalWidth - t.cols.center.width
    }), f.push(
      O(this, $o, Lh).call(this, t),
      O(this, Co, Hh).call(this, t),
      O(this, To, Oh).call(this, t),
      O(this, ko, jh).call(this, t)
    ), b(this, nt).forEach((m) => {
      var y;
      const p = (y = m.onRender) == null ? void 0 : y.call(this, t);
      p && (p.style && Object.assign(d, p.style), p.className && h.push(p.className), p.children && f.push(p.children));
    })), /* @__PURE__ */ g(
      "div",
      {
        id: b(this, os),
        className: N(h),
        style: d,
        ref: this.ref,
        tabIndex: -1,
        children: f
      }
    );
  }
}, ee = new WeakMap(), os = new WeakMap(), Lt = new WeakMap(), Ct = new WeakMap(), se = new WeakMap(), nt = new WeakMap(), Tt = new WeakMap(), ft = new WeakMap(), _e = new WeakMap(), Sn = new WeakMap(), rs = new WeakMap(), xe = new WeakMap(), kt = new WeakMap(), $e = new WeakMap(), Ce = new WeakMap(), $o = new WeakSet(), Lh = function(t) {
  const { header: s, cols: i, headerHeight: o, scrollLeft: r } = t;
  if (!s)
    return null;
  if (s === !0)
    return /* @__PURE__ */ g(
      Gl,
      {
        className: "dtable-header",
        cols: i,
        height: o,
        scrollLeft: r,
        rowHeight: o,
        scrollTop: 0,
        rows: { id: "HEADER", index: -1, top: 0 },
        top: 0,
        onRenderCell: b(this, as)
      },
      "header"
    );
  const a = Array.isArray(s) ? s : [s];
  return /* @__PURE__ */ g(
    cr,
    {
      className: "dtable-header",
      style: { height: o },
      renders: a,
      generateArgs: [t],
      generatorThis: this
    },
    "header"
  );
}, Co = new WeakSet(), Hh = function(t) {
  const { headerHeight: s, rowsHeight: i, visibleRows: o, rowHeight: r, cols: a, scrollLeft: l, scrollTop: c } = t;
  return /* @__PURE__ */ g(
    Gl,
    {
      className: "dtable-body",
      top: s,
      height: i,
      rows: o,
      rowHeight: r,
      scrollLeft: l,
      scrollTop: c,
      cols: a,
      onRenderCell: b(this, as)
    },
    "body"
  );
}, To = new WeakSet(), Oh = function(t) {
  let { footer: s } = t;
  if (typeof s == "function" && (s = s.call(this, t)), !s)
    return null;
  const i = Array.isArray(s) ? s : [s];
  return /* @__PURE__ */ g(
    cr,
    {
      className: "dtable-footer",
      style: { height: t.footerHeight, top: t.rowsHeight + t.headerHeight },
      renders: i,
      generateArgs: [t],
      generatorThis: this,
      generators: t.footerGenerators
    },
    "footer"
  );
}, ko = new WeakSet(), jh = function(t) {
  const s = [], { scrollLeft: i, cols: { left: { width: o }, center: { width: r, totalWidth: a } }, scrollTop: l, rowsHeight: c, rowsHeightTotal: d, footerHeight: h, headerHeight: f } = t, { scrollbarSize: m = 12, horzScrollbarPos: p } = this.options;
  return a > r && s.push(
    /* @__PURE__ */ g(
      Vl,
      {
        type: "horz",
        scrollPos: i,
        scrollSize: a,
        clientSize: r,
        onScroll: b(this, ls),
        left: o,
        bottom: (p === "inside" ? 0 : -m) + h,
        size: m,
        wheelContainer: this.ref
      },
      "horz"
    ),
    /* @__PURE__ */ g("div", { className: "dtable-scroll-shadow is-left", style: { left: o, height: f + c } }),
    /* @__PURE__ */ g("div", { className: "dtable-scroll-shadow is-right", style: { left: o + r, height: f + c } })
  ), d > c && s.push(
    /* @__PURE__ */ g(
      Vl,
      {
        type: "vert",
        scrollPos: l,
        scrollSize: d,
        clientSize: c,
        onScroll: b(this, ls),
        right: 0,
        size: m,
        top: f,
        wheelContainer: this.ref
      },
      "vert"
    )
  ), s.length ? s : null;
}, En = new WeakSet(), Lr = function() {
  var t;
  C(this, Lt, !1), (t = this.options.afterRender) == null || t.call(this), b(this, nt).forEach((s) => {
    var i;
    return (i = s.afterRender) == null ? void 0 : i.call(this);
  });
}, as = new WeakMap(), ls = new WeakMap(), Nn = new WeakMap(), Mn = new WeakMap(), Dn = new WeakMap(), In = new WeakMap(), cs = new WeakSet(), Yn = function(t) {
  const { element: s, options: i } = this;
  if (!s)
    return;
  const o = u(s), r = t ? { in: !0, row: t[0], col: t[1] } : { in: !1 };
  i.colHover === "header" && r.row !== "HEADER" && (r.col = void 0);
  const a = b(this, xe);
  r.in !== a.in && o.toggleClass("dtable-hover", r.in), r.row !== a.row && (o.find(".is-hover-row").removeClass("is-hover-row"), r.row && o.find(`.dtable-cell[data-row="${r.row}"]`).addClass("is-hover-row")), r.col !== a.col && (o.find(".is-hover-col").removeClass("is-hover-col"), r.col && o.find(`.dtable-cell[data-col="${r.col}"]`).addClass("is-hover-col")), C(this, xe, r);
}, So = new WeakSet(), Wh = function() {
  if (b(this, Ct))
    return !1;
  const s = { ...Ul(), ...b(this, se).reduce((i, o) => {
    const { defaultOptions: r } = o;
    return r && Object.assign(i, r), i;
  }, {}), ...this.props };
  return C(this, Ct, s), C(this, nt, b(this, se).reduce((i, o) => {
    const { when: r, options: a } = o;
    let l = s;
    return a && (l = Object.assign({ ...l }, typeof a == "function" ? a.call(this, s) : a)), (!r || r(l)) && (l !== s && Object.assign(s, l), i.push(o)), i;
  }, [])), C(this, rs, [this.options.i18n, ...this.plugins.map((i) => i.i18n)].filter(Boolean)), !0;
}, Eo = new WeakSet(), zh = function() {
  var P, H;
  const { plugins: t } = this;
  let s = b(this, Ct);
  const i = {
    flex: /* @__PURE__ */ g("div", { style: "flex:auto" }),
    divider: /* @__PURE__ */ g("div", { style: "width:1px;margin:var(--space);background:var(--color-border);height:50%" })
  };
  t.forEach((M) => {
    var W;
    const S = (W = M.beforeLayout) == null ? void 0 : W.call(this, s);
    S && (s = { ...s, ...S }), Object.assign(i, M.footer);
  });
  let o = s.width, r = 0;
  if (typeof o == "function" && (o = o.call(this)), o === "100%") {
    const { parent: M } = this;
    if (M)
      r = M.clientWidth;
    else {
      C(this, Lt, !0);
      return;
    }
  }
  const a = ep(this, s, t, r), { data: l, rowKey: c = "id", rowHeight: d } = s, h = [], f = (M, S, W) => {
    var X, dt;
    const V = { data: W ?? { [c]: M }, id: M, index: h.length, top: 0 };
    if (W || (V.lazy = !0), h.push(V), ((X = s.onAddRow) == null ? void 0 : X.call(this, V, S)) !== !1) {
      for (const gs of t)
        if (((dt = gs.onAddRow) == null ? void 0 : dt.call(this, V, S)) === !1)
          return;
    }
  };
  if (typeof l == "number")
    for (let M = 0; M < l; M++)
      f(`${M}`, M);
  else
    Array.isArray(l) && l.forEach((M, S) => {
      typeof M == "object" ? f(`${M[c] ?? ""}`, S, M) : f(`${M ?? ""}`, S);
    });
  let m = h;
  const p = {};
  if (s.onAddRows) {
    const M = s.onAddRows.call(this, m);
    M && (m = M);
  }
  for (const M of t) {
    const S = (P = M.onAddRows) == null ? void 0 : P.call(this, m);
    S && (m = S);
  }
  m.forEach((M, S) => {
    p[M.id] = M, M.index = S, M.top = M.index * d;
  });
  const { header: y, footer: _ } = s, w = y ? s.headerHeight || d : 0, x = _ ? s.footerHeight || d : 0;
  let T = s.height, k = 0;
  const E = m.length * d, D = w + x + E;
  if (typeof T == "function" && (T = T.call(this, D)), T === "auto")
    k = D;
  else if (typeof T == "object")
    k = Math.min(T.max, Math.max(T.min, D));
  else if (T === "100%") {
    const { parent: M } = this;
    if (M)
      k = M.clientHeight;
    else {
      k = 0, C(this, Lt, !0);
      return;
    }
  } else
    k = T;
  const L = k - w - x, R = {
    options: s,
    allRows: h,
    width: r,
    height: k,
    rows: m,
    rowsMap: p,
    rowHeight: d,
    rowsHeight: L,
    rowsHeightTotal: E,
    header: y,
    footer: _,
    footerGenerators: i,
    headerHeight: w,
    footerHeight: x,
    cols: a
  }, A = (H = s.onLayout) == null ? void 0 : H.call(this, R);
  A && Object.assign(R, A), t.forEach((M) => {
    if (M.onLayout) {
      const S = M.onLayout.call(this, R);
      S && Object.assign(R, S);
    }
  }), C(this, Tt, R);
}, No = new WeakSet(), Fh = function() {
  (O(this, So, Wh).call(this) || !b(this, Tt)) && O(this, Eo, zh).call(this);
  const { layout: t } = this;
  if (!t)
    return;
  const { cols: { center: s } } = t;
  let { scrollLeft: i } = this.state;
  i = Math.min(Math.max(0, s.totalWidth - s.width), i);
  let o = 0;
  s.list.forEach((_) => {
    _.left = o, o += _.realWidth, _.visible = _.left + _.realWidth >= i && _.left <= i + s.width;
  });
  const { rowsHeightTotal: r, rowsHeight: a, rows: l, rowHeight: c } = t, d = Math.min(Math.max(0, r - a), this.state.scrollTop), h = Math.floor(d / c), f = d + a, m = Math.min(l.length, Math.ceil(f / c)), p = [], { rowDataGetter: y } = this.options;
  for (let _ = h; _ < m; _++) {
    const w = l[_];
    w.lazy && y && (w.data = y([w.id])[0], w.lazy = !1), p.push(w);
  }
  return t.visibleRows = p, t.scrollTop = d, t.scrollLeft = i, t;
}, ms.addPlugin = Rh, ms.removePlugin = Ah, ms);
const ip = {
  html: { component: Rn }
}, op = {
  name: "custom",
  onRenderCell(n, e) {
    const { col: t } = e;
    let { custom: s } = t.setting;
    if (typeof s == "function" && (s = s.call(this, e)), !s)
      return n;
    const i = Array.isArray(s) ? s : [s], { customMap: o } = this.options;
    return i.forEach((r) => {
      let a;
      typeof r == "string" ? a = r.startsWith("<") ? {
        component: Rn,
        props: { html: U(r, { value: e.value, ...e.row.data, $value: e.value }) }
      } : {
        component: r
      } : a = r;
      let { component: l } = a;
      const c = [a];
      typeof l == "string" && c.unshift(ip[l], o == null ? void 0 : o[l]);
      const d = {};
      c.forEach((f) => {
        if (f) {
          const { props: m } = f;
          m && u.extend(d, typeof m == "function" ? m.call(this, e) : m), l = f.component || l;
        }
      }, { props: {} });
      const h = l;
      n[0] = { outer: !0, className: "dtable-custom-cell", children: /* @__PURE__ */ g(h, { ...d }) };
    }), n;
  }
}, Bh = at(op), $a = class $a extends F {
  render(e) {
    const { percent: t = 50, color: s, background: i, height: o, width: r, children: a, className: l, style: c } = e;
    return /* @__PURE__ */ g("div", { class: N("progress", l), style: {
      width: r,
      height: o,
      "--progress-bg": i,
      "--progress-bar-color": s,
      ...c
    }, children: [
      /* @__PURE__ */ g("div", { class: "progress-bar", style: { width: `${t}%` } }),
      a
    ] });
  }
};
$a.defaultProps = {
  percent: 50,
  height: 20,
  width: "auto"
};
let Hr = $a;
function ua(n, e, t, s) {
  if (typeof n == "function" && (n = n(e)), typeof n == "string" && n.length && (n = { url: n }), !n)
    return t;
  const { url: i, ...o } = n, { setting: r } = e.col, a = {};
  return r && Object.keys(r).forEach((l) => {
    l.startsWith("data-") && (a[l] = r[l]);
  }), /* @__PURE__ */ g("a", { href: U(i, e.row.data), ...s, ...o, ...a, children: t });
}
function fa(n, e, t) {
  var s;
  if (n != null)
    return t = t ?? ((s = e.row.data) == null ? void 0 : s[e.col.name]), typeof n == "function" ? n(t, e) : U(n, t);
}
function Vh(n, e, t, s) {
  var i;
  return t ? (t = t ?? ((i = e.row.data) == null ? void 0 : i[e.col.name]), n === !1 ? t : (n === !0 && (n = "[yyyy-]MM-dd hh:mm"), typeof n == "function" && (n = n(t, e)), it(t, n, s ?? t))) : s ?? t;
}
function Uh(n, e) {
  const { link: t } = e.col.setting, s = ua(t, e, n[0]);
  return s && (n[0] = s), n;
}
function qh(n, e) {
  const { format: t } = e.col.setting;
  return t && (n[0] = fa(t, e, n[0])), n;
}
function Gh(n, e) {
  const { map: t } = e.col.setting;
  return typeof t == "function" ? n[0] = t(n[0], e) : typeof t == "object" && t && (n[0] = t[n[0]] ?? n[0]), n;
}
function Yh(n, e, t = "[yyyy-]MM-dd hh:mm") {
  const { formatDate: s = t, invalidDate: i } = e.col.setting;
  return n[0] = Vh(s, e, n[0], i), n;
}
function Or(n, e, t = !1) {
  const { html: s = t } = e.col.setting;
  if (s === !1)
    return n;
  const i = n[0], o = s === !0 ? i : fa(s, e, i);
  return n[0] = {
    html: o
  }, n;
}
const rp = {
  name: "rich",
  colTypes: {
    html: {
      onRenderCell(n, e) {
        return Or(n, e, !0);
      }
    },
    progress: {
      align: "center",
      onRenderCell(n, { col: e }) {
        const { progressType: t, barColor: s, barHeight: i = 6, barWidth: o = 64, circleSize: r = 24, circleBorderSize: a = 1, circleBgColor: l = "var(--color-border)", circleColor: c = "var(--color-success-500)" } = e.setting, d = n[0];
        return n[0] = t === "bar" ? /* @__PURE__ */ g(
          Hr,
          {
            className: "rounded-full",
            width: o,
            height: i,
            color: s || c,
            percent: d
          }
        ) : /* @__PURE__ */ g(
          Jc,
          {
            percent: d,
            size: r,
            circleWidth: a,
            circleBg: l,
            circleColor: c,
            text: !0
          }
        ), n;
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
  onRenderCell(n, e) {
    const { formatDate: t, html: s, hint: i } = e.col.setting;
    if (t && (n = Yh(n, e, t)), n = Gh(n, e), n = qh(n, e), s ? n = Or(n, e) : n = Uh(n, e), i) {
      let o = n[0];
      typeof i == "function" ? o = i.call(this, e) : typeof i == "string" && (o = U(i, e.row.data)), n.push({ attrs: { title: o } });
    }
    return n;
  }
}, ap = at(rp, { buildIn: !0 });
function lp(n, e, t = !1) {
  var a, l;
  typeof n == "boolean" && (e = n, n = void 0);
  const s = this.state.checkedRows, i = {}, { canRowCheckable: o } = this.options, r = (c, d) => {
    const h = o ? o.call(this, c) : !0;
    !h || t && h === "disabled" || !!s[c] === d || (d ? s[c] = !0 : delete s[c], i[c] = d);
  };
  if (n === void 0 ? (e === void 0 && (e = !Xh.call(this)), (a = this.layout) == null || a.allRows.forEach(({ id: c }) => {
    r(c, !!e);
  })) : (Array.isArray(n) || (n = [n]), n.forEach((c) => {
    r(c, e ?? !s[c]);
  })), Object.keys(i).length) {
    const c = (l = this.options.beforeCheckRows) == null ? void 0 : l.call(this, n, i, s);
    c && Object.keys(c).forEach((d) => {
      c[d] ? s[d] = !0 : delete s[d];
    }), this.setState({ checkedRows: { ...s } }, () => {
      var d;
      (d = this.options.onCheckChange) == null || d.call(this, i);
    });
  }
  return i;
}
function cp(n) {
  return this.state.checkedRows[n] ?? !1;
}
function Xh() {
  var s, i;
  const n = (s = this.layout) == null ? void 0 : s.allRows.length;
  if (!n)
    return !1;
  const e = this.getChecks().length, { canRowCheckable: t } = this.options;
  return t ? e === ((i = this.layout) == null ? void 0 : i.allRows.reduce((o, r) => o + (t.call(this, r.id) ? 1 : 0), 0)) : e === n;
}
function hp() {
  return Object.keys(this.state.checkedRows);
}
function dp(n) {
  const { checkable: e } = this.options;
  n === void 0 && (n = !e), e !== n && this.setState({ forceCheckable: n });
}
function Yl(n, e, t = !1) {
  return /* @__PURE__ */ g("div", { class: `checkbox-primary dtable-checkbox${n ? " checked" : ""}${t ? " disabled" : ""}`, children: /* @__PURE__ */ g("label", {}) });
}
const Xl = 'input[type="checkbox"],.dtable-checkbox', up = {
  name: "checkable",
  defaultOptions: {
    checkable: "auto",
    checkboxRender: Yl
  },
  when: (n) => !!n.checkable,
  options(n) {
    const { forceCheckable: e } = this.state;
    return e !== void 0 ? n.checkable = e : n.checkable === "auto" && (n.checkable = !!n.cols.some((t) => t.checkbox)), n;
  },
  state() {
    return { checkedRows: {} };
  },
  methods: {
    toggleCheckRows: lp,
    isRowChecked: cp,
    isAllRowChecked: Xh,
    getChecks: hp,
    toggleCheckable: dp
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
      const n = this.isAllRowChecked();
      return [
        /* @__PURE__ */ g("div", { style: { paddingRight: "calc(3*var(--space))", display: "flex", alignItems: "center" }, onClick: () => this.toggleCheckRows(), children: Yl(n) })
      ];
    },
    checkedInfo(n, e) {
      const t = this.getChecks(), { checkInfo: s } = this.options;
      if (s)
        return [s.call(this, t)];
      const i = t.length, o = [];
      return i && o.push(this.i18n("checkedCountInfo", { selected: i })), o.push(this.i18n("totalCountInfo", { total: e.allRows.length })), [
        /* @__PURE__ */ g("div", { children: o.join(", ") })
      ];
    }
  },
  onRenderCell(n, { row: e, col: t }) {
    var c;
    const { id: s } = e, { canRowCheckable: i } = this.options, o = i ? i.call(this, s) : !0;
    if (!o)
      return n;
    const { checkbox: r } = t.setting, a = typeof r == "function" ? r.call(this, s) : r, l = this.isRowChecked(s);
    if (a) {
      const d = (c = this.options.checkboxRender) == null ? void 0 : c.call(this, l, s, o === "disabled");
      n.unshift(d), n.push({ outer: !0, className: "has-checkbox" });
    }
    return l && n.push({ outer: !0, className: "is-checked" }), n;
  },
  onRenderHeaderCell(n, { row: e, col: t }) {
    var r;
    const { id: s } = e, { checkbox: i } = t.setting;
    if (typeof i == "function" ? i.call(this, s) : i) {
      const a = this.isAllRowChecked(), l = (r = this.options.checkboxRender) == null ? void 0 : r.call(this, a, s);
      n.unshift(l), n.push({ outer: !0, className: "has-checkbox" });
    }
    return n;
  },
  onHeaderCellClick(n) {
    const e = n.target;
    if (!e)
      return;
    const t = e.closest(Xl);
    t && (this.toggleCheckRows(t.checked), n.stopPropagation());
  },
  onCellClick(n, { rowID: e }) {
    const t = u(n.target);
    if (!t.length || t.closest("btn,a,button.not-checkable,.form-control,.btn").length)
      return;
    (t.closest(Xl).not(".disabled").length || this.options.checkOnClickRow) && this.toggleCheckRows(e, void 0, !0);
  }
}, fp = at(up);
var Kh = /* @__PURE__ */ ((n) => (n.unknown = "", n.collapsed = "collapsed", n.expanded = "expanded", n.hidden = "hidden", n.normal = "normal", n))(Kh || {});
function mi(n) {
  const e = this.data.nestedMap.get(n);
  if (!e || e.state !== "")
    return e ?? { state: "normal", level: -1 };
  if (!e.parent && !e.children)
    return e.state = "normal", e;
  const t = this.state.collapsedRows, s = e.children && t && t[n];
  let i = !1, { parent: o } = e;
  for (; o; ) {
    const r = mi.call(this, o);
    if (r.state !== "expanded") {
      i = !0;
      break;
    }
    o = r.parent;
  }
  return e.state = i ? "hidden" : s ? "collapsed" : e.children ? "expanded" : "normal", e.level = e.parent ? mi.call(this, e.parent).level + 1 : 0, e;
}
function pp(n) {
  return n !== void 0 ? mi.call(this, n) : this.data.nestedMap;
}
function mp(n, e) {
  let t = this.state.collapsedRows ?? {};
  const { nestedMap: s } = this.data;
  if (n === "HEADER")
    if (e === void 0 && (e = !Zh.call(this)), e) {
      const i = s.entries();
      for (const [o, r] of i)
        r.state === "expanded" && (t[o] = !0);
    } else
      t = {};
  else {
    const i = Array.isArray(n) ? n : [n];
    e === void 0 && (e = !t[i[0]]), i.forEach((o) => {
      const r = s.get(o);
      e && (r != null && r.children) ? t[o] = !0 : delete t[o];
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
function Zh() {
  const n = this.data.nestedMap.values();
  for (const e of n)
    if (e.state === "expanded")
      return !1;
  return !0;
}
function Jh(n, e = 0, t, s = 0) {
  var i;
  t || (t = [...n.keys()]);
  for (const o of t) {
    const r = n.get(o);
    r && (r.level === s && (r.order = e++), (i = r.children) != null && i.length && (e = Jh(n, e, r.children, s + 1)));
  }
  return e;
}
function Qh(n, e, t, s) {
  const i = n.getNestedRowInfo(e);
  return !i || i.state === "" || !i.children || i.children.forEach((o) => {
    s[o] = t, Qh(n, o, t, s);
  }), i;
}
function td(n, e, t, s, i) {
  var a;
  const o = n.getNestedRowInfo(e);
  if (!o || o.state === "")
    return;
  ((a = o.children) == null ? void 0 : a.every((l) => {
    const c = !!(s[l] !== void 0 ? s[l] : i[l]);
    return t === c;
  })) && (s[e] = t), o.parent && td(n, o.parent, t, s, i);
}
const gp = {
  name: "nested",
  defaultOptions: {
    nested: "auto",
    nestedParentKey: "parent",
    asParentKey: "asParent",
    nestedIndent: 20,
    canSortTo(n, e) {
      const { nestedMap: t } = this.data, s = t.get(n.id), i = t.get(e.id);
      return (s == null ? void 0 : s.parent) === (i == null ? void 0 : i.parent);
    },
    beforeCheckRows(n, e, t) {
      if (!this.options.checkable || !(n != null && n.length))
        return;
      const s = {};
      return Object.entries(e).forEach(([i, o]) => {
        const r = Qh(this, i, o, s);
        r != null && r.parent && td(this, r.parent, o, s, t);
      }), s;
    }
  },
  options(n) {
    return n.nested === "auto" && (n.nested = !!n.cols.some((e) => e.nestedToggle)), n;
  },
  when: (n) => !!n.nested,
  data() {
    return { nestedMap: /* @__PURE__ */ new Map() };
  },
  methods: {
    getNestedInfo: pp,
    toggleRow: mp,
    isAllCollapsed: Zh,
    getNestedRowInfo: mi
  },
  beforeLayout() {
    var n;
    (n = this.data.nestedMap) == null || n.clear();
  },
  onAddRow(n) {
    var i, o;
    const { nestedMap: e } = this.data, t = String((i = n.data) == null ? void 0 : i[this.options.nestedParentKey ?? "parent"]), s = e.get(n.id) ?? {
      state: "",
      level: 0
    };
    if (s.parent = t === "0" ? void 0 : t, (o = n.data) != null && o[this.options.asParentKey ?? "asParent"] && (s.children = []), e.set(n.id, s), t) {
      let r = e.get(t);
      r || (r = {
        state: "",
        level: 0
      }, e.set(t, r)), r.children || (r.children = []), r.children.push(n.id);
    }
  },
  onAddRows(n) {
    return n = n.filter(
      (e) => this.getNestedRowInfo(e.id).state !== "hidden"
      /* hidden */
    ), Jh(this.data.nestedMap), n.sort((e, t) => {
      const s = this.getNestedRowInfo(e.id), i = this.getNestedRowInfo(t.id), o = (s.order ?? 0) - (i.order ?? 0);
      return o === 0 ? e.index - t.index : o;
    }), n;
  },
  onRenderCell(n, { col: e, row: t }) {
    var a;
    const { id: s, data: i } = t, { nestedToggle: o } = e.setting, r = this.getNestedRowInfo(s);
    if (o && (r.children || r.parent) && n.unshift(
      ((a = this.options.onRenderNestedToggle) == null ? void 0 : a.call(this, r, s, e, i)) ?? /* @__PURE__ */ g("a", { className: `dtable-nested-toggle state${r.children ? "" : " is-no-child"}`, children: /* @__PURE__ */ g("span", { className: "toggle-icon" }) }),
      { outer: !0, className: `is-${r.state}` }
    ), r.level) {
      let { nestedIndent: l = o } = e.setting;
      l && (l === !0 && (l = this.options.nestedIndent ?? 12), n.unshift(/* @__PURE__ */ g("div", { className: "dtable-nested-indent", style: { width: l * r.level + "px" } })));
    }
    return n;
  },
  onRenderHeaderCell(n, { row: e, col: t }) {
    var i;
    const { id: s } = e;
    return t.setting.nestedToggle && n.unshift(
      ((i = this.options.onRenderNestedToggle) == null ? void 0 : i.call(this, void 0, s, t, void 0)) ?? /* @__PURE__ */ g("a", { className: "dtable-nested-toggle state", children: /* @__PURE__ */ g("span", { className: "toggle-icon" }) }),
      { outer: !0, className: `is-${this.isAllCollapsed() ? "collapsed" : "expanded"}` }
    ), n;
  },
  onHeaderCellClick(n) {
    const e = n.target;
    if (!(!e || !e.closest(".dtable-nested-toggle")))
      return this.toggleRow("HEADER"), !0;
  },
  onCellClick(n, { rowID: e }) {
    const t = n.target;
    if (!(!t || !this.getNestedRowInfo(e).children || !t.closest(".dtable-nested-toggle")))
      return this.toggleRow(e), !0;
  }
}, yp = at(gp);
function ir(n, { row: e, col: t }) {
  const { data: s } = e, i = s ? s[t.name] : void 0;
  if (!(i != null && i.length))
    return n;
  const { avatarClass: o = "rounded-full", avatarKey: r = `${t.name}Avatar`, avatarProps: a, avatarCodeKey: l, avatarNameKey: c = `${t.name}Name` } = t.setting, d = (s ? s[c] : i) || n[0], h = {
    size: "xs",
    className: N(o, a == null ? void 0 : a.className, "flex-none"),
    src: s ? s[r] : void 0,
    text: d,
    code: l ? s ? s[l] : void 0 : i,
    ...a
  };
  if (n[0] = /* @__PURE__ */ g(oh, { ...h }), t.type === "avatarBtn") {
    const { avatarBtnProps: f } = t.setting, m = typeof f == "function" ? f(t, e) : f;
    n[0] = /* @__PURE__ */ g("button", { type: "button", className: "btn btn-avatar", ...m, children: [
      n[0],
      /* @__PURE__ */ g("div", { children: d })
    ] });
  } else
    t.type === "avatarName" && (n[0] = /* @__PURE__ */ g("div", { className: "flex items-center gap-1", children: [
      n[0],
      /* @__PURE__ */ g("span", { children: d })
    ] }));
  return n;
}
const bp = {
  name: "avatar",
  colTypes: {
    avatar: {
      onRenderCell: ir
    },
    avatarBtn: {
      onRenderCell: ir
    },
    avatarName: {
      onRenderCell: ir
    }
  }
}, wp = at(bp, { buildIn: !0 }), vp = {
  name: "sort-type",
  onRenderHeaderCell(n, e) {
    const { col: t } = e, { sortType: s } = t.setting;
    if (s) {
      const i = s === !0 ? "none" : s;
      n.push(
        /* @__PURE__ */ g("div", { className: `dtable-sort dtable-sort-${i}` }),
        { outer: !0, attrs: { "data-sort": i } }
      );
      let { sortLink: o = this.options.sortLink } = t.setting;
      if (o) {
        const r = i === "asc" ? "desc" : "asc";
        typeof o == "function" && (o = o.call(this, t, r, i)), typeof o == "string" && (o = { url: o });
        const { url: a, ...l } = o;
        n[0] = /* @__PURE__ */ g("a", { href: U(a, { ...t.setting, sortType: r }), ...l, children: n[0] });
      }
    }
    return n;
  }
}, _p = at(vp, { buildIn: !0 }), or = (n) => {
  n.length !== 1 && n.forEach((e, t) => {
    !t || e.setting.border || e.setting.group === n[t - 1].setting.group || (e.setting.border = "left");
  });
}, xp = {
  name: "group",
  defaultOptions: {
    groupDivider: !0
  },
  when: (n) => !!n.groupDivider,
  onLayout(n) {
    if (!this.options.groupDivider)
      return;
    const { cols: e } = n;
    or(e.left.list), or(e.center.list), or(e.right.list);
  }
}, $p = at(xp), Cp = {
  name: "cellspan",
  when: (n) => !!n.getCellSpan,
  data() {
    return { cellSpanMap: /* @__PURE__ */ new Map(), overlayedCellSet: /* @__PURE__ */ new Set() };
  },
  onLayout(n) {
    const { getCellSpan: e } = this.options;
    if (!e)
      return;
    const { cellSpanMap: t, overlayedCellSet: s } = this.data, { rows: i, cols: o, rowHeight: r } = n;
    t.clear(), s.clear();
    const a = (l, c, d) => {
      const { index: h } = c;
      l.forEach((f, m) => {
        const { index: p } = f, y = `C${p}R${h}`;
        if (s.has(y))
          return;
        const _ = e.call(this, { row: c, col: f });
        if (!_)
          return;
        const w = Math.min(_.colSpan || 1, l.length - m), x = Math.min(_.rowSpan || 1, i.length - d);
        if (w <= 1 && x <= 1)
          return;
        let T = 0;
        for (let k = 0; k < w; k++) {
          T += l[m + k].realWidth;
          for (let E = 0; E < x; E++) {
            const D = `C${p + k}R${h + E}`;
            D !== y && s.add(D);
          }
        }
        t.set(y, {
          colSpan: w,
          rowSpan: x,
          width: T,
          height: r * x
        });
      });
    };
    i.forEach((l, c) => {
      ["left", "center", "right"].forEach((d) => {
        a(o[d].list, l, c);
      });
    });
  },
  onRenderCell(n, { row: e, col: t }) {
    const s = `C${t.index}R${e.index}`;
    if (this.data.overlayedCellSet.has(s))
      n.push({ outer: !0, style: { display: "none", className: "cellspan-overlayed-cell" } });
    else {
      const i = this.data.cellSpanMap.get(s);
      i && n.push({
        outer: !0,
        style: {
          width: i.width,
          height: i.height
        }
      });
    }
    return n;
  }
}, Tp = at(Cp), kp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  NestedRowState: Kh,
  avatar: wp,
  cellspan: Tp,
  checkable: fp,
  custom: Bh,
  group: $p,
  nested: yp,
  renderDatetime: Vh,
  renderDatetimeCell: Yh,
  renderFormat: fa,
  renderFormatCell: qh,
  renderHtmlCell: Or,
  renderLink: ua,
  renderLinkCell: Uh,
  renderMapCell: Gh,
  rich: ap,
  sortType: _p
}, Symbol.toStringTag, { value: "Module" })), ue = class ue extends B {
};
ue.NAME = "DTable", ue.Component = np, ue.definePlugin = at, ue.removePlugin = Ah, ue.plugins = kp;
let Kl = ue;
const Sp = "nav", Xn = '[data-toggle="tab"]', Ep = "active";
var Te;
const Ca = class Ca extends J {
  constructor() {
    super(...arguments);
    $(this, Te, 0);
  }
  active(t) {
    const s = this.$element, i = s.find(Xn);
    let o = t ? u(t).closest(Xn) : i.filter(`.${Ep}`);
    if (!o.length && (o = s.find(Xn).first(), !o.length))
      return;
    i.removeClass("active"), o.addClass("active");
    const r = o.attr("href") || o.data("target"), a = o.data("name") || r, l = u(r);
    l.length && (l.parent().children(".tab-pane").removeClass("active in"), l.addClass("active").trigger("show", [a]), this.emit("show", a), b(this, Te) && clearTimeout(b(this, Te)), C(this, Te, setTimeout(() => {
      l.addClass("in").trigger("show", [a]), this.emit("shown", a), C(this, Te, 0);
    }, 10)));
  }
};
Te = new WeakMap(), Ca.NAME = "Tabs";
let jr = Ca;
u(document).on("click.tabs.zui", Xn, (n) => {
  n.preventDefault();
  const e = u(n.target), t = e.closest(`.${Sp}`);
  t.length && jr.ensure(t).active(e);
});
const Zl = (n) => n.replace("[", "\\[").replace("]", "\\]");
var Mo, ed, Do, sd, Io, nd, Ro, id;
const Ta = class Ta extends J {
  constructor() {
    super(...arguments);
    $(this, Mo);
    $(this, Do);
    $(this, Io);
    $(this, Ro);
  }
  init() {
    u(this.element).on("submit", this.onSubmit.bind(this)).on("input mousedown change", this.onInput.bind(this));
  }
  enable(t = !0) {
    u(this.element).toggleClass("loading", !t);
  }
  disable() {
    this.enable(!1);
  }
  onInput(t) {
    const s = u(t.target).closest(".has-error");
    !s.length || !s.attr("name") || (s.removeClass("has-error"), s.closest(".form-group,.form-batch-control").find(`#${Zl(s.attr("name"))}Tip`).remove());
  }
  onSubmit(t) {
    var a;
    t.preventDefault();
    const { element: s } = this, i = u.extend({}, this.options);
    this.emit("before", t, s, i);
    const o = () => {
      this.disable(), O(this, Do, sd).call(this, O(this, Mo, ed).call(this)).finally(() => {
        this.enable();
      });
    }, r = (a = i.beforeSubmit) == null ? void 0 : a.call(i, t, s, i);
    if (r !== !1) {
      if (r instanceof Promise) {
        r.then((l) => l && o());
        return;
      }
      o();
    }
  }
  submit() {
    this.element.submit();
  }
  reset() {
    this.element.reset();
  }
};
Mo = new WeakSet(), ed = function() {
  const { element: t, options: s } = this;
  let i = new FormData(t), { submitEmptySelectValue: o = "" } = s;
  o !== !1 && (typeof o != "boolean" && (o = ""), u(t).find("select").each((a, l) => {
    const d = u(l).attr("name");
    i.has(d) || i.append(d, typeof o == "object" ? o[d] : o);
  }));
  const { beforeSend: r } = s;
  if (r) {
    const a = r(i);
    a instanceof FormData && (i = a);
  }
  return this.emit("send", i), i;
}, Do = new WeakSet(), sd = async function(t) {
  var c, d;
  const { element: s, options: i } = this;
  let o, r, a;
  const l = {
    method: u(s).attr("method") || "POST",
    body: t,
    credentials: "same-origin",
    headers: {
      "X-Requested-With": "XMLHttpRequest"
    }
  };
  this.$element.closest(".modal").length && (l.headers["X-ZUI-Modal"] = "true");
  try {
    const h = await fetch(i.url || u(s).attr("action"), l);
    r = await h.text(), h.ok ? (a = JSON.parse(r), (!a || typeof a != "object") && (o = new Error("Invalid json format"))) : o = new Error(h.statusText);
  } catch (h) {
    o = h, console.warn("ZUI: cannot send ajax form", h);
  }
  o ? (this.emit("error", o, r), (c = i.onError) == null || c.call(i, o, r)) : O(this, Ro, id).call(this, a), this.emit("complete", a, o), (d = i.onComplete) == null || d.call(i, a, o);
}, Io = new WeakSet(), nd = function(t) {
  var i;
  let s;
  Object.entries(t).forEach(([o, r]) => {
    Array.isArray(r) && (r = r.join(""));
    const a = Zl(o);
    let l = this.$element.find(`#${a}`);
    if (l.length || (l = u(this.element).find(`[name="${a}"]`)), !l.length) {
      pt.alert({ message: r });
      return;
    }
    l.addClass("has-error");
    const c = l.closest(".form-group,.form-batch-control");
    if (c.length) {
      let d = c.find(`#${a}Tip`);
      d.length || (d = u(`<div class="form-tip ajax-form-tip text-danger pre-line" id="${o}Tip"></div>`).appendTo(c)), d.empty().text(r);
    }
    s || (s = l);
  }), s && ((i = s[0]) == null || i.focus());
}, Ro = new WeakSet(), id = function(t) {
  var l, c;
  const { options: s } = this, { message: i } = t;
  if (t.result === "success") {
    if (this.emit("success", t), ((l = s.onSuccess) == null ? void 0 : l.call(s, t)) === !1)
      return;
    typeof i == "string" && i.length && Se.show({ content: i, type: "success" });
  } else {
    if (this.emit("fail", t), ((c = s.onFail) == null ? void 0 : c.call(s, t)) === !1)
      return;
    i && (typeof i == "string" && i.length ? pt.alert({ message: i }) : typeof i == "object" && O(this, Io, nd).call(this, i));
  }
  const o = t.closeModal || s.closeModal;
  o && pt.hide(typeof o == "string" ? o : void 0);
  const r = t.callback || s.callback;
  if (r) {
    const d = [["options", s], ["result", t]];
    if (typeof r == "string") {
      const h = u.runJS(r, ...d);
      typeof h == "function" && !r.endsWith(";") && h(t);
    } else if (typeof r == "object") {
      const h = u.runJS(r.name, ...d);
      typeof h == "function" && h.apply(this, Array.isArray(r.params) ? r.params : [r.params]);
    }
  }
  const a = t.load || s.load || t.locate;
  a && u(this.element).trigger("locate.zt", a);
}, Ta.NAME = "AjaxForm";
let Jl = Ta;
function Np(n, e) {
  var l, c;
  const { message: t } = n, s = typeof t == "string" && t.length, i = n.result === "success";
  if (i) {
    if (((l = e.onSuccess) == null ? void 0 : l.call(e, n)) === !1)
      return;
  } else if (((c = e.onFail) == null ? void 0 : c.call(e, n)) === !1)
    return;
  s && (e.onMessage ? e.onMessage(t, n) : i ? Se.show({ content: t, type: "success" }) : pt.alert({ message: t }));
  const o = n.closeModal || e.closeModal;
  o && pt.hide(typeof o == "string" ? o : void 0);
  const r = n.callback || e.callback;
  if (r) {
    const d = [["options", e], ["result", n]];
    if (typeof r == "string") {
      const h = u.runJS(r, ...d);
      typeof h == "function" && !r.endsWith(";") && h(n);
    } else if (typeof r == "object") {
      const h = u.runJS(r.name, ...d);
      typeof h == "function" && h.apply(null, Array.isArray(r.params) ? r.params : [r.params]);
    }
  }
  const a = n.load || e.load || n.locate;
  a && u(e.element || document).trigger("locate.zt", a);
}
async function pa(n) {
  var c, d;
  if (n.confirm)
    return await pt.confirm(n.confirm) ? pa({ ...n, confirm: void 0 }) : [void 0, new Error("canceled")];
  if (n.beforeSubmit && await n.beforeSubmit(n) === !1)
    return [void 0, new Error("canceled")];
  const { loadingClass: e, element: t } = n;
  t && e && u(t).addClass(e);
  const { data: s } = n;
  let i;
  if (s instanceof FormData)
    i = s;
  else if (s) {
    i = new FormData();
    for (const [h, f] of Object.entries(s))
      if (Array.isArray(f)) {
        for (const m of f)
          i.append(h, m);
        continue;
      } else
        i.append(h, f);
  }
  const { beforeSend: o } = n;
  if (o) {
    const h = o(i);
    h instanceof FormData && (i = h);
  }
  let r, a, l;
  try {
    const h = await fetch(n.url, {
      method: n.method || "POST",
      body: i,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        ...n.headers
      }
    });
    a = await h.text(), h.ok ? (l = JSON.parse(a), (!l || typeof l != "object") && (r = new Error("Invalid json format"))) : r = new Error(h.statusText);
  } catch (h) {
    r = h;
  }
  return r ? (c = n.onError) == null || c.call(n, r, a) : Np(l, n), (d = n.onComplete) == null || d.call(n, l, r), t && e && u(t).removeClass(e), [l, r];
}
u.extend(u, { ajaxSubmit: pa });
u(document).on("click.ajaxSubmit.zui", ".ajax-submit", function(n) {
  n.preventDefault();
  const e = u(this), t = e.data();
  !t.url && e.is("a") && (t.url = e.attr("href") || ""), t.url && (t.element = this[0], pa(t));
});
function Mp(n) {
  const [e, t] = n.split(":"), s = e[0] === "-" ? { name: e.substring(1), disabled: !0 } : { name: e };
  return t != null && t.length && (s.type = "dropdown", s.items = t.split(",").reduce((i, o) => (o = o.trim(), o.length && i.push(o[0] === "-" ? { name: o.substring(1), disabled: !0 } : { name: o }), i), [])), s;
}
const Dp = (n, e) => {
  var t;
  return n.url && (n.url = U(n.url, e.row.data)), (t = n.dropdown) != null && t.items && (n.dropdown.items = n.dropdown.items.map((s) => (s.url && (s.url = U(s.url, e.row.data)), s))), n;
}, Ql = (n) => n ? (typeof n == "string" && (n = n.split("|")), n.map((e) => typeof e == "string" ? Mp(e) : e).filter(Boolean)) : [], Ip = {
  name: "actions",
  colTypes: {
    actions: {
      onRenderCell(n, e) {
        var d;
        const { row: t, col: s } = e, i = Ql(((d = t.data) == null ? void 0 : d[s.name]) || s.setting.actions);
        if (!i.length)
          return n;
        const { actionsSetting: o, actionsMap: r, actionsCreator: a = this.options.actionsCreator, actionItemCreator: l = this.options.actionItemCreator || Dp } = s.setting, c = {
          items: (a == null ? void 0 : a(e)) ?? i.map((h) => {
            const { name: f, items: m, ...p } = h;
            if (r && f) {
              Object.assign(p, r[f], { ...p });
              const { buildProps: y } = p;
              typeof y == "function" && (delete p.buildProps, Object.assign(p, y(n, e)));
            }
            if (p.disabled && (delete p.url, delete p["data-toggle"]), m && p.type === "dropdown") {
              const { dropdown: y = { placement: "bottom-end" } } = p;
              y.menu = {
                className: "menu-dtable-actions",
                items: m.reduce((_, w) => {
                  const x = typeof w == "string" ? { name: w } : { ...w };
                  return x != null && x.name && (r && "name" in x && Object.assign(x, r[x.name], { ...x }), _.push(x)), x.disabled ? (delete x.url, delete x["data-toggle"]) : x.url && (x.url = U(x.url, t.data)), _;
                }, [])
              }, p.dropdown = y;
            }
            return l ? l(p, e) : p;
          }),
          btnProps: { size: "sm", className: "text-primary" },
          ...o
        };
        return n[0] = /* @__PURE__ */ g(Dt, { ...c }), n;
      }
    }
  },
  beforeLayout(n) {
    !Array.isArray(n.data) || !n.data.length || n.cols.forEach((e, t) => {
      if (e.type !== "actions" || e.width)
        return;
      const { actionsMap: s = {} } = e, o = Ql(n.data[0][e.name]).reduce((r, a) => {
        const l = a.name ? s[a.name] : null;
        return l && l.type === "dropdown" && l.caret && !l.text ? r + 16 : r + 24;
      }, 24);
      n.cols[t] = {
        ...e,
        width: o
      };
    });
  }
}, Rp = at(Ip), Ap = {
  name: "toolbar",
  footer: {
    toolbar() {
      const { footToolbar: n, showToolbarOnChecked: e } = this.options;
      return e && !this.getChecks().length ? [] : [n ? /* @__PURE__ */ g(Dt, { gap: 2, ...n }) : null];
    }
  }
}, Pp = at(Ap), Lp = {
  name: "pager",
  footer: {
    pager() {
      const { footPager: n } = this.options;
      return [n ? /* @__PURE__ */ g(fh, { ...n }) : null];
    }
  }
}, Hp = at(Lp);
function tc(n, e, t) {
  if (t) {
    if (typeof t == "object") {
      const s = t[n];
      return typeof s == "string" ? s : typeof s == "object" && s ? s.realname : "";
    }
    if (typeof t == "function")
      return t(n, e);
  }
}
const Op = {
  name: "zentao",
  plugins: ["group", "checkable", "nested", Rp, Pp, Hp],
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
    height(n) {
      return Math.min(n, window.innerHeight - 1 - (u("#header").outerHeight() || 0) - (u("#mainMenu").outerHeight() || 0) - (u("#mainNavbar").outerHeight() || 0));
    }
  },
  options(n) {
    const { checkable: e, footToolbar: t, footPager: s, footer: i, sortLink: o } = n;
    if (i === void 0) {
      const r = [];
      e && r.push("checkbox"), t && (r.push("toolbar"), t.btnProps = Object.assign({
        type: "primary",
        size: "sm"
      }, t.btnProps)), e && r.push("checkedInfo"), s && r.push("flex", "pager"), r.length && (n.footer = r);
    }
    return typeof o == "string" && (n.sortLink = { url: o, "data-load": "table" }), n;
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
      onRenderCell(n, { col: e, row: t }) {
        var r, a;
        const s = (r = t.data) == null ? void 0 : r[e.name];
        let i, o;
        return typeof s == "string" ? (i = s, o = (a = e.setting.statusMap) == null ? void 0 : a[s]) : typeof s == "object" && s && ({ name: i, label: o } = s), n[0] = /* @__PURE__ */ v("span", { class: `${e.setting.statusClassPrefix ?? "status-"}${i}` }, o ?? i), n;
      }
    },
    user: {
      width: 80,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0,
      // 启用排序
      onRenderCell(n, { col: e, row: t, value: s }) {
        const { userMap: i = this.options.userMap } = e.setting, o = tc(s, t, i);
        return o !== void 0 && (n[0] = o), n;
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
      onRenderCell(n, e) {
        const { col: t, row: s, value: i } = e, { userMap: o = this.options.userMap, currentUser: r, assignLink: a, unassignedText: l = this.i18n("unassigned") } = t.setting, c = !i, d = c ? l : tc(i, s, o) ?? i;
        return n[0] = ua(a, e, [
          /* @__PURE__ */ v("i", { className: "icon icon-hand-right" }),
          /* @__PURE__ */ v("span", null, d)
        ], {
          "data-toggle": "modal",
          className: `dtable-assign-btn${r === i ? " is-me" : ""}${c ? " is-unassigned" : ""}`
        }), n;
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
      onRenderCell(n, { row: e, col: t, value: s }) {
        const { iconRender: i } = t.setting;
        let o = {};
        if (typeof i == "function") {
          const r = i(s, e);
          typeof r == "string" ? s = r : typeof r == "object" && r && ({ icon: s, ...o } = r);
        }
        return typeof s == "string" ? o.className = N(s, o.className) : typeof s == "object" && s && Object.assign(o, s), n[0] = /* @__PURE__ */ v("i", { ...o }), n;
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
      onRenderCell(n, { value: e }) {
        return n[0] = /* @__PURE__ */ v(kh, { pri: e }), n;
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
      onRenderCell(n) {
        const e = n[0];
        return n[0] = /* @__PURE__ */ v(Sh, { severity: e }), n;
      }
    },
    burn: {
      width: 88,
      // 默认宽度
      align: "center",
      // 居中对齐
      onRenderCell(n, { col: e }) {
        const t = n[0];
        if (!t)
          return n;
        const { burn: s } = e.setting, i = {
          data: t,
          className: "border-b",
          width: 64,
          height: 24,
          responsive: !1,
          ...s
        };
        return n[0] = /* @__PURE__ */ v(Ch, { ...i }), n;
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
      format(n) {
        return typeof n == "string" && n.endsWith("%") ? n : `${n}%`;
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
  onRenderCell(n, { row: e, col: t, value: s }) {
    const { iconRender: i } = t.setting;
    if (typeof i == "function" && t.type !== "icon") {
      const o = i(s, e);
      o && n.unshift(typeof o == "object" ? /* @__PURE__ */ v("i", { ...o }) : /* @__PURE__ */ v("i", { className: o }));
    }
    return n;
  },
  onRender() {
    const { customCols: n } = this.options;
    if (!n)
      return;
    const { custom: e, setGlobal: t, reset: s, resetGlobal: i } = n;
    return {
      children: /* @__PURE__ */ v("div", { className: "absolute gap-3 m-1.5 top-0 right-0 z-20 row" }, /* @__PURE__ */ v("div", { className: "w-px border-l my-1" }), /* @__PURE__ */ v(
        "a",
        {
          className: "btn ghost square size-sm rounded",
          "data-toggle": "dropdown",
          "data-placement": "bottom-end"
        },
        /* @__PURE__ */ v("i", { class: "icon icon-cog-outline" })
      ), /* @__PURE__ */ v("menu", { class: "dropdown-menu menu" }, e && /* @__PURE__ */ v("li", { class: "menu-item" }, /* @__PURE__ */ v("a", { href: e.url, "data-toggle": "modal" }, e.text)), t && /* @__PURE__ */ v("li", { class: "menu-item" }, /* @__PURE__ */ v("a", { href: t.url }, t.text)), s && /* @__PURE__ */ v("li", { class: "menu-item" }, /* @__PURE__ */ v("a", { href: s.url }, s.text)), i && /* @__PURE__ */ v("li", { class: "menu-item" }, /* @__PURE__ */ v("a", { href: i.url }, i.text))))
    };
  }
}, lm = at(Op, { buildIn: !0 });
const xs = (n, e, t, s = "value") => {
  const i = `${e.col.name}[${e.row.id}]`, o = n.getFormData(i) ?? e.value;
  return {
    [s]: o,
    className: "form-control",
    onChange: (r) => n.setFormData(i, r instanceof Event ? r.currentTarget.value : r),
    ...t
  };
};
function rr(n) {
  return xs(this, n, null, "defaultValue");
}
const jp = {
  input: {
    component: "input",
    props(n) {
      return xs(this, n, { type: "text" });
    }
  },
  select: {
    component: function(n) {
      const { items: e, defaultValue: t, ...s } = n;
      return /* @__PURE__ */ v("select", { ...s }, e.map((i) => /* @__PURE__ */ v("option", { key: i.value, value: i.value }, i.text)));
    },
    props(n) {
      return xs(this, n);
    }
  },
  picker: {
    component: di,
    props: rr
  },
  datePicker: {
    component: uh,
    props(n) {
      return xs(this, n, { className: "flex-auto", icon: "calendar" }, "defaultValue");
    }
  },
  timePicker: {
    component: lh,
    props(n) {
      return xs(this, n, { className: "flex-auto", icon: "time" }, "defaultValue");
    }
  },
  priPicker: {
    component: ui,
    props: rr
  },
  severityPicker: {
    component: Eh,
    props: rr
  }
}, Wp = {
  name: "form",
  plugins: [Bh],
  data() {
    return { formData: {} };
  },
  colTypes: {
    control: {
      custom(n) {
        let { control: e } = n.col.setting;
        if (typeof e == "function" && (e = e.call(this, n)), !e)
          return;
        typeof e == "string" && (e = { type: e });
        const { controlMap: t } = this.options, { type: s } = e, i = { name: `${n.col.name}[${n.row.id}]` };
        let o;
        if ([jp[s], t == null ? void 0 : t[s], e].forEach((r) => {
          if (r) {
            const { props: a } = r;
            a && u.extend(i, typeof a == "function" ? a.call(this, n) : a), o = r.component || o;
          }
        }), !!o)
          return {
            component: o,
            props: i
          };
      }
    }
  },
  methods: {
    setFormData(n, e) {
      var s;
      const { formData: t } = this.data;
      t[n] = e, (s = this.options.onFormChange) == null || s.call(this, n, e, t);
    },
    getFormData(n) {
      return n ? this.data.formData[n] : this.data.formData;
    }
  }
}, cm = at(Wp);
u(() => {
  u(".disabled, [disabled]").on("click", (n) => {
    n.preventDefault(), n.stopImmediatePropagation();
  });
});
function od(n) {
  n = n || location.search, n[0] === "?" && (n = n.substring(1));
  try {
    const e = new URLSearchParams(n), t = {};
    for (const s of e.keys())
      t[s] = e.getAll(s).join(",");
    return t;
  } catch {
    return {};
  }
}
function zp(n) {
  if (!n)
    return { url: n };
  const { config: e } = window;
  if (/^https?:\/\//.test(n)) {
    const l = window.location.origin;
    if (!n.includes(l))
      return { external: !0, url: n };
    n = n.substring((l + e.webRoot).length);
  }
  const t = n.split("#"), s = t[0].split("?"), i = s[1], o = i ? od(i) : {};
  let r = s[0];
  const a = {
    url: n,
    isOnlyBody: o.onlybody === "yes",
    vars: [],
    hash: t[1] || "",
    params: o,
    tid: o.tid || ""
  };
  if (e.requestType === "GET") {
    a.moduleName = o[e.moduleVar] || "index", a.methodName = o[e.methodVar] || "index", a.viewType = o[e.viewVar] || e.defaultView;
    for (const l in o)
      l !== e.moduleVar && l !== e.methodVar && l !== e.viewVar && l !== "onlybody" && l !== "tid" && a.vars.push([l, o[l]]);
  } else {
    let l = r.lastIndexOf("/");
    l === r.length - 1 && (r = r.substring(0, l), l = r.lastIndexOf("/")), l >= 0 && (r = r.substring(l + 1));
    const c = r.lastIndexOf(".");
    c >= 0 ? (a.viewType = r.substring(c + 1), r = r.substring(0, c)) : a.viewType = e.defaultView;
    const d = r.split(e.requestFix);
    if (a.moduleName = d[0] || "index", a.methodName = d[1] || "index", d.length > 2)
      for (let h = 2; h < d.length; h++)
        a.vars.push(["", d[h]]), o["$" + (h - 1)] = d[h];
  }
  return a;
}
function rd(n, e, t, s, i, o) {
  if (typeof n == "object")
    return rd(n.moduleName, n.methodName, n.vars, n.viewType, n.hash, n.params);
  const r = window.config;
  if (s || (s = r.defaultView), t) {
    typeof t == "string" && (t = t.split("&"));
    for (let c = 0; c < t.length; c++) {
      const d = t[c];
      if (typeof d == "string") {
        const h = d.split("=");
        t[c] = [h.shift(), h.join("=")];
      }
    }
  }
  const a = [], l = r.requestType === "GET";
  if (l) {
    if (a.push(r.router, "?", r.moduleVar, "=", n, "&", r.methodVar, "=", e), t)
      for (let c = 0; c < t.length; c++)
        a.push("&", t[c][0], "=", t[c][1]);
    a.push("&", r.viewVar, "=", s);
  } else {
    if (r.requestType == "PATH_INFO" && a.push(r.webRoot, n, r.requestFix, e), r.requestType == "PATH_INFO2" && a.push(r.webRoot, "index.php/", n, r.requestFix, e), t)
      for (let c = 0; c < t.length; c++)
        a.push(r.requestFix + t[c][1]);
    a.push(".", s);
  }
  return o && Object.keys(o).forEach((c) => {
    const d = o[c];
    c[0] !== "$" && a.push(!l && !a.includes("?") ? "?" : "&", c, "=", d);
  }), typeof i == "string" && a.push(i.startsWith("#") ? "" : "#", i), a.join("");
}
function ec(n) {
  const e = u(n);
  e.css({ minHeight: 0 }).css({ minHeight: Math.max(32, e[0].scrollHeight) });
}
u.fn.autoHeight = function() {
  return this.each(function() {
    const n = u(this);
    n.data("auto-height") || n.on("input paste change", function() {
      ec(this);
    }).data("auto-height", !0), ec(n);
  });
};
const Fp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  createLink: rd,
  parseLink: zp,
  parseUrlParams: od
}, Symbol.toStringTag, { value: "Module" }));
function Bp(n) {
  const e = u(this), t = e.dataset();
  if (!(t.on || "click").split(" ").includes(n.type) || t.selector && !u(n.target).closest(t.selector).length)
    return;
  const s = (a) => a === "" ? !0 : a, i = (a) => {
    if (typeof a == "string")
      try {
        a = JSON.parse(a);
      } catch {
      }
    return a;
  };
  if (s(t.once)) {
    if (t.onceCalled)
      return;
    e.dataset("once-called", !0);
  }
  if (s(t.prevent) && n.preventDefault(), s(t.stop) && n.stopPropagation(), s(t.self) && n.currentTarget !== n.target)
    return;
  const o = [["$element", e], ["event", n], ["options", t]];
  if (t.if && !u.runJS(t.if, ...o))
    return;
  const r = t.call;
  if (r) {
    let a = window[r];
    const l = /^[$A-Z_][0-9A-Z_$.]*$/i.test(r);
    if (a || (a = u.runJS(r, ...o)), !l || !u.isFunction(a))
      return;
    const c = [], d = t.params;
    t.params = c, typeof d == "string" && d.length && (d[0] === "[" ? c.push(...i(d)) : c.push(...d.split(", ").map((h) => (h = h.trim(), h === "$element" ? e : h === "event" ? n : h === "options" ? t : h.startsWith("$element.") || h.startsWith("$event.") || h.startsWith("$options.") ? u.runJS(h, ...o) : i(h))))), a(...c);
  }
  t.do && u.runJS(t.do, ...o);
}
u(document).on("click.helpers.zt change.helpers.zt", "[data-on]", Bp);
window.$ && Object.assign(window.$, Fp);
export {
  u as $,
  Fa as ActionMenu,
  Ba as ActionMenuNested,
  Jl as AjaxForm,
  ol as Avatar,
  $l as BatchForm,
  rl as BtnGroup,
  Cl as Burn,
  al as ColorPicker,
  J as Component,
  B as ComponentFromReact,
  Pr as ContextMenu,
  he as CustomContent,
  cr as CustomRender,
  Kl as DTable,
  Bl as Dashboard,
  ul as DatePicker,
  pl as DatetimePicker,
  Qa as Draggable,
  Nt as Dropdown,
  kl as Dropmenu,
  Sl as ECharts,
  ca as EventBus,
  El as GlobalSearch,
  na as HElement,
  Rn as HtmlContent,
  K as Icon,
  Nl as ImgCutter,
  Va as Menu,
  Se as Messager,
  pt as Modal,
  Bt as ModalBase,
  hi as ModalTrigger,
  br as Moveable,
  gl as Nav,
  yl as Pager,
  bl as Pick,
  wl as Picker,
  Et as Popover,
  hr as PopoverPanel,
  Sr as Popovers,
  Ml as PriPicker,
  Ja as ProgressCircle,
  F as ReactComponent,
  im as SearchBox,
  jl as SearchForm,
  Dl as SeverityPicker,
  el as Sortable,
  Es as TIME_DAY,
  jr as Tabs,
  dl as TimePicker,
  vl as Toolbar,
  Ht as Tooltip,
  _l as Tree,
  Nr as Upload,
  xl as UploadImgs,
  zl as Zinbar,
  hf as addDate,
  Kp as ajax,
  pa as ajaxSubmit,
  Zp as bus,
  u as cash,
  N as classes,
  vs as componentsMap,
  Qd as convertBytes,
  sf as cookie,
  iu as create,
  Y as createDate,
  mu as createPortal,
  q as createRef,
  Jd as deepGet,
  Zd as deepGetPath,
  Up as defineFn,
  Qn as delay,
  qp as dom,
  cm as form,
  Bn as formatBytes,
  it as formatDate,
  nm as formatDateSpan,
  U as formatString,
  _c as getClassList,
  ti as getComponent,
  v as h,
  Gp as hh,
  hu as htm,
  tt as i18n,
  Hn as isSameDay,
  rh as isSameMonth,
  Qp as isSameWeek,
  xr as isSameYear,
  tm as isToday,
  sm as isTomorrow,
  st as isValidElement,
  em as isYesterday,
  tl as nativeEvents,
  Ts as render,
  Lc as renderCustomContent,
  uu as renderCustomResult,
  nu as shareData,
  fe as store,
  xc as storeData,
  $c as takeData,
  lm as zentao,
  Op as zentaoPlugin
};
//# sourceMappingURL=zui.zentao.js.map
