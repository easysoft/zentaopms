var Uo = (s, e, t) => {
  if (!e.has(s))
    throw TypeError("Cannot " + t);
};
var y = (s, e, t) => (Uo(s, e, "read from private field"), t ? t.call(s) : e.get(s)), C = (s, e, t) => {
  if (e.has(s))
    throw TypeError("Cannot add the same private member more than once");
  e instanceof WeakSet ? e.add(s) : e.set(s, t);
}, $ = (s, e, t, n) => (Uo(s, e, "write to private field"), n ? n.call(s, t) : e.set(s, t), t);
var _a = (s, e, t, n) => ({
  set _(i) {
    $(s, e, i, t);
  },
  get _() {
    return y(s, e, n);
  }
}), j = (s, e, t) => (Uo(s, e, "access private method"), t);
const Ft = document, Js = window, Vl = Ft.documentElement, Le = Ft.createElement.bind(Ft), Ul = Le("div"), qo = Le("table"), Kh = Le("tbody"), xa = Le("tr"), { isArray: Ro, prototype: ql } = Array, { concat: Yh, filter: Ar, indexOf: Gl, map: Kl, push: Xh, slice: Yl, some: Lr, splice: Jh } = ql, Zh = /^#(?:[\w-]|\\.|[^\x00-\xa0])*$/, Qh = /^\.(?:[\w-]|\\.|[^\x00-\xa0])*$/, td = /<.+>/, ed = /^\w+$/;
function Or(s, e) {
  const t = nd(e);
  return !s || !t && !Re(e) && !X(e) ? [] : !t && Qh.test(s) ? e.getElementsByClassName(s.slice(1).replace(/\\/g, "")) : !t && ed.test(s) ? e.getElementsByTagName(s) : e.querySelectorAll(s);
}
class Io {
  constructor(e, t) {
    if (!e)
      return;
    if (or(e))
      return e;
    let n = e;
    if (it(e)) {
      const i = t || Ft;
      if (n = Zh.test(e) && Re(i) ? i.getElementById(e.slice(1).replace(/\\/g, "")) : td.test(e) ? Zl(e) : or(i) ? i.find(e) : it(i) ? u(i).find(e) : Or(e, i), !n)
        return;
    } else if (Oe(e))
      return this.ready(e);
    (n.nodeType || n === Js) && (n = [n]), this.length = n.length;
    for (let i = 0, o = this.length; i < o; i++)
      this[i] = n[i];
  }
  init(e, t) {
    return new Io(e, t);
  }
}
const P = Io.prototype, u = P.init;
u.fn = u.prototype = P;
P.length = 0;
P.splice = Jh;
typeof Symbol == "function" && (P[Symbol.iterator] = ql[Symbol.iterator]);
function or(s) {
  return s instanceof Io;
}
function yn(s) {
  return !!s && s === s.window;
}
function Re(s) {
  return !!s && s.nodeType === 9;
}
function nd(s) {
  return !!s && s.nodeType === 11;
}
function X(s) {
  return !!s && s.nodeType === 1;
}
function sd(s) {
  return !!s && s.nodeType === 3;
}
function id(s) {
  return typeof s == "boolean";
}
function Oe(s) {
  return typeof s == "function";
}
function it(s) {
  return typeof s == "string";
}
function ct(s) {
  return s === void 0;
}
function Pn(s) {
  return s === null;
}
function Xl(s) {
  return !isNaN(parseFloat(s)) && isFinite(s);
}
function jr(s) {
  if (typeof s != "object" || s === null)
    return !1;
  const e = Object.getPrototypeOf(s);
  return e === null || e === Object.prototype;
}
u.isWindow = yn;
u.isFunction = Oe;
u.isArray = Ro;
u.isNumeric = Xl;
u.isPlainObject = jr;
function J(s, e, t) {
  if (t) {
    let n = s.length;
    for (; n--; )
      if (e.call(s[n], n, s[n]) === !1)
        return s;
  } else if (jr(s)) {
    const n = Object.keys(s);
    for (let i = 0, o = n.length; i < o; i++) {
      const r = n[i];
      if (e.call(s[r], r, s[r]) === !1)
        return s;
    }
  } else
    for (let n = 0, i = s.length; n < i; n++)
      if (e.call(s[n], n, s[n]) === !1)
        return s;
  return s;
}
u.each = J;
P.each = function(s) {
  return J(this, s);
};
P.empty = function() {
  return this.each((s, e) => {
    for (; e.firstChild; )
      e.removeChild(e.firstChild);
  });
};
function Zs(...s) {
  const e = id(s[0]) ? s.shift() : !1, t = s.shift(), n = s.length;
  if (!t)
    return {};
  if (!n)
    return Zs(e, u, t);
  for (let i = 0; i < n; i++) {
    const o = s[i];
    for (const r in o)
      e && (Ro(o[r]) || jr(o[r])) ? ((!t[r] || t[r].constructor !== o[r].constructor) && (t[r] = new o[r].constructor()), Zs(e, t[r], o[r])) : t[r] = o[r];
  }
  return t;
}
u.extend = Zs;
P.extend = function(s) {
  return Zs(P, s);
};
const od = /\S+/g;
function Do(s) {
  return it(s) ? s.match(od) || [] : [];
}
P.toggleClass = function(s, e) {
  const t = Do(s), n = !ct(e);
  return this.each((i, o) => {
    X(o) && J(t, (r, a) => {
      n ? e ? o.classList.add(a) : o.classList.remove(a) : o.classList.toggle(a);
    });
  });
};
P.addClass = function(s) {
  return this.toggleClass(s, !0);
};
P.removeAttr = function(s) {
  const e = Do(s);
  return this.each((t, n) => {
    X(n) && J(e, (i, o) => {
      n.removeAttribute(o);
    });
  });
};
function rd(s, e) {
  if (s) {
    if (it(s)) {
      if (arguments.length < 2) {
        if (!this[0] || !X(this[0]))
          return;
        const t = this[0].getAttribute(s);
        return Pn(t) ? void 0 : t;
      }
      return ct(e) ? this : Pn(e) ? this.removeAttr(s) : this.each((t, n) => {
        X(n) && n.setAttribute(s, e);
      });
    }
    for (const t in s)
      this.attr(t, s[t]);
    return this;
  }
}
P.attr = rd;
P.removeClass = function(s) {
  return arguments.length ? this.toggleClass(s, !1) : this.attr("class", "");
};
P.hasClass = function(s) {
  return !!s && Lr.call(this, (e) => X(e) && e.classList.contains(s));
};
P.get = function(s) {
  return ct(s) ? Yl.call(this) : (s = Number(s), this[s < 0 ? s + this.length : s]);
};
P.eq = function(s) {
  return u(this.get(s));
};
P.first = function() {
  return this.eq(0);
};
P.last = function() {
  return this.eq(-1);
};
function ad(s) {
  return ct(s) ? this.get().map((e) => X(e) || sd(e) ? e.textContent : "").join("") : this.each((e, t) => {
    X(t) && (t.textContent = s);
  });
}
P.text = ad;
function Wt(s, e, t) {
  if (!X(s))
    return;
  const n = Js.getComputedStyle(s, null);
  return t ? n.getPropertyValue(e) || void 0 : n[e] || s.style[e];
}
function St(s, e) {
  return parseInt(Wt(s, e), 10) || 0;
}
function Ca(s, e) {
  return St(s, `border${e ? "Left" : "Top"}Width`) + St(s, `padding${e ? "Left" : "Top"}`) + St(s, `padding${e ? "Right" : "Bottom"}`) + St(s, `border${e ? "Right" : "Bottom"}Width`);
}
const Go = {};
function ld(s) {
  if (Go[s])
    return Go[s];
  const e = Le(s);
  Ft.body.insertBefore(e, null);
  const t = Wt(e, "display");
  return Ft.body.removeChild(e), Go[s] = t !== "none" ? t : "block";
}
function $a(s) {
  return Wt(s, "display") === "none";
}
function Jl(s, e) {
  const t = s && (s.matches || s.webkitMatchesSelector || s.msMatchesSelector);
  return !!t && !!e && t.call(s, e);
}
function Ao(s) {
  return it(s) ? (e, t) => Jl(t, s) : Oe(s) ? s : or(s) ? (e, t) => s.is(t) : s ? (e, t) => t === s : () => !1;
}
P.filter = function(s) {
  const e = Ao(s);
  return u(Ar.call(this, (t, n) => e.call(t, n, t)));
};
function he(s, e) {
  return e ? s.filter(e) : s;
}
P.detach = function(s) {
  return he(this, s).each((e, t) => {
    t.parentNode && t.parentNode.removeChild(t);
  }), this;
};
const cd = /^\s*<(\w+)[^>]*>/, hd = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, ka = {
  "*": Ul,
  tr: Kh,
  td: xa,
  th: xa,
  thead: qo,
  tbody: qo,
  tfoot: qo
};
function Zl(s) {
  if (!it(s))
    return [];
  if (hd.test(s))
    return [Le(RegExp.$1)];
  const e = cd.test(s) && RegExp.$1, t = ka[e] || ka["*"];
  return t.innerHTML = s, u(t.childNodes).detach().get();
}
u.parseHTML = Zl;
P.has = function(s) {
  const e = it(s) ? (t, n) => Or(s, n).length : (t, n) => n.contains(s);
  return this.filter(e);
};
P.not = function(s) {
  const e = Ao(s);
  return this.filter((t, n) => (!it(s) || X(n)) && !e.call(n, t, n));
};
function Ut(s, e, t, n) {
  const i = [], o = Oe(e), r = n && Ao(n);
  for (let a = 0, l = s.length; a < l; a++)
    if (o) {
      const h = e(s[a]);
      h.length && Xh.apply(i, h);
    } else {
      let h = s[a][e];
      for (; h != null && !(n && r(-1, h)); )
        i.push(h), h = t ? h[e] : null;
    }
  return i;
}
function Ql(s) {
  return s.multiple && s.options ? Ut(Ar.call(s.options, (e) => e.selected && !e.disabled && !e.parentNode.disabled), "value") : s.value || "";
}
function dd(s) {
  return arguments.length ? this.each((e, t) => {
    const n = t.multiple && t.options;
    if (n || ac.test(t.type)) {
      const i = Ro(s) ? Kl.call(s, String) : Pn(s) ? [] : [String(s)];
      n ? J(t.options, (o, r) => {
        r.selected = i.indexOf(r.value) >= 0;
      }, !0) : t.checked = i.indexOf(t.value) >= 0;
    } else
      t.value = ct(s) || Pn(s) ? "" : s;
  }) : this[0] && Ql(this[0]);
}
P.val = dd;
P.is = function(s) {
  const e = Ao(s);
  return Lr.call(this, (t, n) => e.call(t, n, t));
};
u.guid = 1;
function Pt(s) {
  return s.length > 1 ? Ar.call(s, (e, t, n) => Gl.call(n, e) === t) : s;
}
u.unique = Pt;
P.add = function(s, e) {
  return u(Pt(this.get().concat(u(s, e).get())));
};
P.children = function(s) {
  return he(u(Pt(Ut(this, (e) => e.children))), s);
};
P.parent = function(s) {
  return he(u(Pt(Ut(this, "parentNode"))), s);
};
P.index = function(s) {
  const e = s ? u(s)[0] : this[0], t = s ? this : u(e).parent().children();
  return Gl.call(t, e);
};
P.closest = function(s) {
  const e = this.filter(s);
  if (e.length)
    return e;
  const t = this.parent();
  return t.length ? t.closest(s) : e;
};
P.siblings = function(s) {
  return he(u(Pt(Ut(this, (e) => u(e).parent().children().not(e)))), s);
};
P.find = function(s) {
  return u(Pt(Ut(this, (e) => Or(s, e))));
};
const ud = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, fd = /^$|^module$|\/(java|ecma)script/i, pd = ["type", "src", "nonce", "noModule"];
function md(s, e) {
  const t = u(s);
  t.filter("script").add(t.find("script")).each((n, i) => {
    if (fd.test(i.type) && Vl.contains(i)) {
      const o = Le("script");
      o.text = i.textContent.replace(ud, ""), J(pd, (r, a) => {
        i[a] && (o[a] = i[a]);
      }), e.head.insertBefore(o, null), e.head.removeChild(o);
    }
  });
}
function gd(s, e, t, n, i) {
  n ? s.insertBefore(e, t ? s.firstChild : null) : s.nodeName === "HTML" ? s.parentNode.replaceChild(e, s) : s.parentNode.insertBefore(e, t ? s : s.nextSibling), i && md(e, s.ownerDocument);
}
function de(s, e, t, n, i, o, r, a) {
  return J(s, (l, h) => {
    J(u(h), (d, c) => {
      J(u(e), (f, p) => {
        const m = t ? c : p, b = t ? p : c, _ = t ? d : f;
        gd(m, _ ? b.cloneNode(!0) : b, n, i, !_);
      }, a);
    }, r);
  }, o), e;
}
P.after = function() {
  return de(arguments, this, !1, !1, !1, !0, !0);
};
P.append = function() {
  return de(arguments, this, !1, !1, !0);
};
function yd(s) {
  if (!arguments.length)
    return this[0] && this[0].innerHTML;
  if (ct(s))
    return this;
  const e = /<script[\s>]/.test(s);
  return this.each((t, n) => {
    X(n) && (e ? u(n).empty().append(s) : n.innerHTML = s);
  });
}
P.html = yd;
P.appendTo = function(s) {
  return de(arguments, this, !0, !1, !0);
};
P.wrapInner = function(s) {
  return this.each((e, t) => {
    const n = u(t), i = n.contents();
    i.length ? i.wrapAll(s) : n.append(s);
  });
};
P.before = function() {
  return de(arguments, this, !1, !0);
};
P.wrapAll = function(s) {
  let e = u(s), t = e[0];
  for (; t.children.length; )
    t = t.firstElementChild;
  return this.first().before(e), this.appendTo(t);
};
P.wrap = function(s) {
  return this.each((e, t) => {
    const n = u(s)[0];
    u(t).wrapAll(e ? n.cloneNode(!0) : n);
  });
};
P.insertAfter = function(s) {
  return de(arguments, this, !0, !1, !1, !1, !1, !0);
};
P.insertBefore = function(s) {
  return de(arguments, this, !0, !0);
};
P.prepend = function() {
  return de(arguments, this, !1, !0, !0, !0, !0);
};
P.prependTo = function(s) {
  return de(arguments, this, !0, !0, !0, !1, !1, !0);
};
P.contents = function() {
  return u(Pt(Ut(this, (s) => s.tagName === "IFRAME" ? [s.contentDocument] : s.tagName === "TEMPLATE" ? s.content.childNodes : s.childNodes)));
};
P.next = function(s, e, t) {
  return he(u(Pt(Ut(this, "nextElementSibling", e, t))), s);
};
P.nextAll = function(s) {
  return this.next(s, !0);
};
P.nextUntil = function(s, e) {
  return this.next(e, !0, s);
};
P.parents = function(s, e) {
  return he(u(Pt(Ut(this, "parentElement", !0, e))), s);
};
P.parentsUntil = function(s, e) {
  return this.parents(e, s);
};
P.prev = function(s, e, t) {
  return he(u(Pt(Ut(this, "previousElementSibling", e, t))), s);
};
P.prevAll = function(s) {
  return this.prev(s, !0);
};
P.prevUntil = function(s, e) {
  return this.prev(e, !0, s);
};
P.map = function(s) {
  return u(Yh.apply([], Kl.call(this, (e, t) => s.call(e, t, e))));
};
P.clone = function() {
  return this.map((s, e) => e.cloneNode(!0));
};
P.offsetParent = function() {
  return this.map((s, e) => {
    let t = e.offsetParent;
    for (; t && Wt(t, "position") === "static"; )
      t = t.offsetParent;
    return t || Vl;
  });
};
P.slice = function(s, e) {
  return u(Yl.call(this, s, e));
};
const bd = /-([a-z])/g;
function Hr(s) {
  return s.replace(bd, (e, t) => t.toUpperCase());
}
P.ready = function(s) {
  const e = () => setTimeout(s, 0, u);
  return Ft.readyState !== "loading" ? e() : Ft.addEventListener("DOMContentLoaded", e), this;
};
P.unwrap = function() {
  return this.parent().each((s, e) => {
    if (e.tagName === "BODY")
      return;
    const t = u(e);
    t.replaceWith(t.children());
  }), this;
};
P.offset = function() {
  const s = this[0];
  if (!s)
    return;
  const e = s.getBoundingClientRect();
  return {
    top: e.top + Js.pageYOffset,
    left: e.left + Js.pageXOffset
  };
};
P.position = function() {
  const s = this[0];
  if (!s)
    return;
  const e = Wt(s, "position") === "fixed", t = e ? s.getBoundingClientRect() : this.offset();
  if (!e) {
    const n = s.ownerDocument;
    let i = s.offsetParent || n.documentElement;
    for (; (i === n.body || i === n.documentElement) && Wt(i, "position") === "static"; )
      i = i.parentNode;
    if (i !== s && X(i)) {
      const o = u(i).offset();
      t.top -= o.top + St(i, "borderTopWidth"), t.left -= o.left + St(i, "borderLeftWidth");
    }
  }
  return {
    top: t.top - St(s, "marginTop"),
    left: t.left - St(s, "marginLeft")
  };
};
const tc = {
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
P.prop = function(s, e) {
  if (s) {
    if (it(s))
      return s = tc[s] || s, arguments.length < 2 ? this[0] && this[0][s] : this.each((t, n) => {
        n[s] = e;
      });
    for (const t in s)
      this.prop(t, s[t]);
    return this;
  }
};
P.removeProp = function(s) {
  return this.each((e, t) => {
    delete t[tc[s] || s];
  });
};
const wd = /^--/;
function zr(s) {
  return wd.test(s);
}
const Ko = {}, { style: vd } = Ul, _d = ["webkit", "moz", "ms"];
function xd(s, e = zr(s)) {
  if (e)
    return s;
  if (!Ko[s]) {
    const t = Hr(s), n = `${t[0].toUpperCase()}${t.slice(1)}`, i = `${t} ${_d.join(`${n} `)}${n}`.split(" ");
    J(i, (o, r) => {
      if (r in vd)
        return Ko[s] = r, !1;
    });
  }
  return Ko[s];
}
const Cd = {
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
function ec(s, e, t = zr(s)) {
  return !t && !Cd[s] && Xl(e) ? `${e}px` : e;
}
function $d(s, e) {
  if (it(s)) {
    const t = zr(s);
    return s = xd(s, t), arguments.length < 2 ? this[0] && Wt(this[0], s, t) : s ? (e = ec(s, e, t), this.each((n, i) => {
      X(i) && (t ? i.style.setProperty(s, e) : i.style[s] = e);
    })) : this;
  }
  for (const t in s)
    this.css(t, s[t]);
  return this;
}
P.css = $d;
function nc(s, e) {
  try {
    return s(e);
  } catch {
    return e;
  }
}
const kd = /^\s+|\s+$/;
function Ta(s, e) {
  const t = s.dataset[e] || s.dataset[Hr(e)];
  return kd.test(t) ? t : nc(JSON.parse, t);
}
function Td(s, e, t) {
  t = nc(JSON.stringify, t), s.dataset[Hr(e)] = t;
}
function Sd(s, e) {
  if (!s) {
    if (!this[0])
      return;
    const t = {};
    for (const n in this[0].dataset)
      t[n] = Ta(this[0], n);
    return t;
  }
  if (it(s))
    return arguments.length < 2 ? this[0] && Ta(this[0], s) : ct(e) ? this : this.each((t, n) => {
      Td(n, s, e);
    });
  for (const t in s)
    this.data(t, s[t]);
  return this;
}
P.data = Sd;
function sc(s, e) {
  const t = s.documentElement;
  return Math.max(s.body[`scroll${e}`], t[`scroll${e}`], s.body[`offset${e}`], t[`offset${e}`], t[`client${e}`]);
}
J([!0, !1], (s, e) => {
  J(["Width", "Height"], (t, n) => {
    const i = `${e ? "outer" : "inner"}${n}`;
    P[i] = function(o) {
      if (this[0])
        return yn(this[0]) ? e ? this[0][`inner${n}`] : this[0].document.documentElement[`client${n}`] : Re(this[0]) ? sc(this[0], n) : this[0][`${e ? "offset" : "client"}${n}`] + (o && e ? St(this[0], `margin${t ? "Top" : "Left"}`) + St(this[0], `margin${t ? "Bottom" : "Right"}`) : 0);
    };
  });
});
J(["Width", "Height"], (s, e) => {
  const t = e.toLowerCase();
  P[t] = function(n) {
    if (!this[0])
      return ct(n) ? void 0 : this;
    if (!arguments.length)
      return yn(this[0]) ? this[0].document.documentElement[`client${e}`] : Re(this[0]) ? sc(this[0], e) : this[0].getBoundingClientRect()[t] - Ca(this[0], !s);
    const i = parseInt(n, 10);
    return this.each((o, r) => {
      if (!X(r))
        return;
      const a = Wt(r, "boxSizing");
      r.style[t] = ec(t, i + (a === "border-box" ? Ca(r, !s) : 0));
    });
  };
});
const Sa = "___cd";
P.toggle = function(s) {
  return this.each((e, t) => {
    if (!X(t))
      return;
    const n = $a(t);
    (ct(s) ? n : s) ? (t.style.display = t[Sa] || "", $a(t) && (t.style.display = ld(t.tagName))) : n || (t[Sa] = Wt(t, "display"), t.style.display = "none");
  });
};
P.hide = function() {
  return this.toggle(!1);
};
P.show = function() {
  return this.toggle(!0);
};
const Na = "___ce", Br = ".", Fr = { focus: "focusin", blur: "focusout" }, ic = { mouseenter: "mouseover", mouseleave: "mouseout" }, Nd = /^(mouse|pointer|contextmenu|drag|drop|click|dblclick)/i;
function Wr(s) {
  return ic[s] || Fr[s] || s;
}
function Vr(s) {
  const e = s.split(Br);
  return [e[0], e.slice(1).sort()];
}
P.trigger = function(s, e) {
  if (it(s)) {
    const [n, i] = Vr(s), o = Wr(n);
    if (!o)
      return this;
    const r = Nd.test(o) ? "MouseEvents" : "HTMLEvents";
    s = Ft.createEvent(r), s.initEvent(o, !0, !0), s.namespace = i.join(Br), s.___ot = n;
  }
  s.___td = e;
  const t = s.___ot in Fr;
  return this.each((n, i) => {
    t && Oe(i[s.___ot]) && (i[`___i${s.type}`] = !0, i[s.___ot](), i[`___i${s.type}`] = !1), i.dispatchEvent(s);
  });
};
function oc(s) {
  return s[Na] = s[Na] || {};
}
function Ed(s, e, t, n, i) {
  const o = oc(s);
  o[e] = o[e] || [], o[e].push([t, n, i]), s.addEventListener(e, i);
}
function rc(s, e) {
  return !e || !Lr.call(e, (t) => s.indexOf(t) < 0);
}
function Qs(s, e, t, n, i) {
  const o = oc(s);
  if (e)
    o[e] && (o[e] = o[e].filter(([r, a, l]) => {
      if (i && l.guid !== i.guid || !rc(r, t) || n && n !== a)
        return !0;
      s.removeEventListener(e, l);
    }));
  else
    for (e in o)
      Qs(s, e, t, n, i);
}
P.off = function(s, e, t) {
  if (ct(s))
    this.each((n, i) => {
      !X(i) && !Re(i) && !yn(i) || Qs(i);
    });
  else if (it(s))
    Oe(e) && (t = e, e = ""), J(Do(s), (n, i) => {
      const [o, r] = Vr(i), a = Wr(o);
      this.each((l, h) => {
        !X(h) && !Re(h) && !yn(h) || Qs(h, a, r, e, t);
      });
    });
  else
    for (const n in s)
      this.off(n, s[n]);
  return this;
};
P.remove = function(s) {
  return he(this, s).detach().off(), this;
};
P.replaceWith = function(s) {
  return this.before(s).remove();
};
P.replaceAll = function(s) {
  return u(s).replaceWith(this), this;
};
function Md(s, e, t, n, i) {
  if (!it(s)) {
    for (const o in s)
      this.on(o, e, t, s[o], i);
    return this;
  }
  return it(e) || (ct(e) || Pn(e) ? e = "" : ct(t) ? (t = e, e = "") : (n = t, t = e, e = "")), Oe(n) || (n = t, t = void 0), n ? (J(Do(s), (o, r) => {
    const [a, l] = Vr(r), h = Wr(a), d = a in ic, c = a in Fr;
    h && this.each((f, p) => {
      if (!X(p) && !Re(p) && !yn(p))
        return;
      const m = function(b) {
        if (b.target[`___i${b.type}`])
          return b.stopImmediatePropagation();
        if (b.namespace && !rc(l, b.namespace.split(Br)) || !e && (c && (b.target !== p || b.___ot === h) || d && b.relatedTarget && p.contains(b.relatedTarget)))
          return;
        let _ = p;
        if (e) {
          let x = b.target;
          for (; !Jl(x, e); )
            if (x === p || (x = x.parentNode, !x))
              return;
          _ = x;
        }
        Object.defineProperty(b, "currentTarget", {
          configurable: !0,
          get() {
            return _;
          }
        }), Object.defineProperty(b, "delegateTarget", {
          configurable: !0,
          get() {
            return p;
          }
        }), Object.defineProperty(b, "data", {
          configurable: !0,
          get() {
            return t;
          }
        });
        const v = n.call(_, b, b.___td);
        i && Qs(p, h, l, e, m), v === !1 && (b.preventDefault(), b.stopPropagation());
      };
      m.guid = n.guid = n.guid || u.guid++, Ed(p, h, l, e, m);
    });
  }), this) : this;
}
P.on = Md;
function Pd(s, e, t, n) {
  return this.on(s, e, t, n, !0);
}
P.one = Pd;
const Rd = /\r?\n/g;
function Id(s, e) {
  return `&${encodeURIComponent(s)}=${encodeURIComponent(e.replace(Rd, `\r
`))}`;
}
const Dd = /file|reset|submit|button|image/i, ac = /radio|checkbox/i;
P.serialize = function() {
  let s = "";
  return this.each((e, t) => {
    J(t.elements || [t], (n, i) => {
      if (i.disabled || !i.name || i.tagName === "FIELDSET" || Dd.test(i.type) || ac.test(i.type) && !i.checked)
        return;
      const o = Ql(i);
      if (!ct(o)) {
        const r = Ro(o) ? o : [o];
        J(r, (a, l) => {
          s += Id(i.name, l);
        });
      }
    });
  }), s.slice(1);
};
window.$ = u;
function Ad(s, e) {
  if (s == null)
    return [s, void 0];
  typeof e == "string" && (e = e.split("."));
  const t = e.join(".");
  let n = s;
  const i = [n];
  for (; typeof n == "object" && n !== null && e.length; ) {
    let o = e.shift(), r;
    const a = o.indexOf("[");
    if (a > 0 && a < o.length - 1 && o.endsWith("]") && (r = o.substring(a + 1, o.length - 1), o = o.substring(0, a)), n = n[o], i.push(n), r !== void 0)
      if (typeof n == "object" && n !== null)
        n instanceof Map ? n = n.get(r) : n = n[r], i.push(n);
      else
        throw new Error(`Cannot access property "${o}[${r}]", the full path is "${t}".`);
  }
  if (e.length)
    throw new Error(`Cannot access property with rest path "${e.join(".")}", the full path is "${t}".`);
  return i;
}
function Ld(s, e, t) {
  try {
    const n = Ad(s, e), i = n[n.length - 1];
    return i === void 0 ? t : i;
  } catch {
    return t;
  }
}
function U(s, ...e) {
  if (e.length === 0)
    return s;
  if (e.length === 1 && typeof e[0] == "object" && e[0]) {
    const t = e[0];
    return Object.keys(t).forEach((n) => {
      const i = t[n] ?? "";
      s = s.replace(new RegExp(`\\{${n}\\}`, "g"), `${i}`);
    }), s;
  }
  for (let t = 0; t < e.length; t++) {
    const n = e[t] ?? "";
    s = s.replace(new RegExp(`\\{${t}\\}`, "g"), `${n}`);
  }
  return s;
}
var Ur = /* @__PURE__ */ ((s) => (s[s.B = 1] = "B", s[s.KB = 1024] = "KB", s[s.MB = 1048576] = "MB", s[s.GB = 1073741824] = "GB", s[s.TB = 1099511627776] = "TB", s))(Ur || {});
function Vs(s, e = 2, t) {
  return Number.isNaN(s) ? "?KB" : (t || (s < 1024 ? t = "B" : s < 1048576 ? t = "KB" : s < 1073741824 ? t = "MB" : s < 1099511627776 ? t = "GB" : t = "TB"), (s / Ur[t]).toFixed(e) + t);
}
const Od = (s) => {
  const e = /^[0-9]*(B|KB|MB|GB|TB)$/;
  s = s.toUpperCase();
  const t = s.match(e);
  if (!t)
    return 0;
  const n = t[1];
  return s = s.replace(n, ""), Number.parseInt(s, 10) * Ur[n];
};
let qr = (document.documentElement.getAttribute("lang") || "zh_cn").toLowerCase().replace("-", "_"), Yt;
function jd() {
  return qr;
}
function Hd(s) {
  qr = s.toLowerCase();
}
function lc(s, e) {
  Yt || (Yt = {}), typeof s == "string" && (s = { [s]: e ?? {} }), u.extend(!0, Yt, s);
}
function nt(s, e, t, n, i, o) {
  Array.isArray(s) ? Yt && s.unshift(Yt) : s = Yt ? [Yt, s] : [s], typeof t == "string" && (o = i, i = n, n = t, t = void 0);
  const r = i || qr;
  let a;
  for (const l of s) {
    if (!l)
      continue;
    const h = l[r];
    if (!h)
      continue;
    const d = o && l === Yt ? `${o}.${e}` : e;
    if (a = Ld(h, d), a !== void 0)
      break;
  }
  return a === void 0 ? n : t ? U(a, ...Array.isArray(t) ? t : [t]) : a;
}
function zd(s, e, t, n) {
  return nt(void 0, s, e, t, n);
}
nt.addLang = lc;
nt.getLang = zd;
nt.getCode = jd;
nt.setCode = Hd;
lc({
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
function cc(...s) {
  const e = [], t = /* @__PURE__ */ new Map(), n = (i, o) => {
    if (Array.isArray(i) && (o = i[1], i = i[0]), !i.length)
      return;
    const r = t.get(i);
    typeof r == "number" ? e[r][1] = !!o : (t.set(i, e.length), e.push([i, !!o]));
  };
  return s.forEach((i) => {
    typeof i == "function" && (i = i()), Array.isArray(i) ? cc(...i).forEach(n) : i && typeof i == "object" ? Object.entries(i).forEach(n) : typeof i == "string" && i.split(" ").forEach((o) => n(o, !0));
  }), e.sort((i, o) => (t.get(i[0]) || 0) - (t.get(o[0]) || 0));
}
const M = (...s) => cc(...s).reduce((e, [t, n]) => (n && e.push(t), e), []).join(" ");
u.classes = M;
u.fn.setClass = function(s, ...e) {
  return this.each((t, n) => {
    const i = u(n);
    s === !0 ? i.attr("class", M(i.attr("class"), ...e)) : i.addClass(M(s, ...e));
  });
};
const Tn = /* @__PURE__ */ new WeakMap();
function hc(s, e, t) {
  const n = Tn.has(s), i = n ? Tn.get(s) : {};
  typeof e == "string" ? i[e] = t : e === null ? Object.keys(i).forEach((o) => {
    delete i[o];
  }) : Object.assign(i, e), Object.keys(i).forEach((o) => {
    i[o] === void 0 && delete i[o];
  }), Object.keys(i).length ? (!n && s instanceof Element && Object.assign(i, u(s).dataset(), i), Tn.set(s, i)) : Tn.delete(s);
}
function dc(s, e, t) {
  let n = Tn.get(s) || {};
  return !t && s instanceof Element && (n = Object.assign({}, u(s).dataset(), n)), e === void 0 ? n : n[e];
}
u.fn.dataset = u.fn.data;
u.fn.data = function(...s) {
  if (!this.length)
    return;
  const [e, t] = s;
  return !s.length || s.length === 1 && typeof e == "string" ? dc(this[0], e) : this.each((n, i) => hc(i, e, t));
};
u.fn.removeData = function(s = null) {
  return this.each((e, t) => hc(t, s));
};
u.fn._attr = u.fn.attr;
u.fn.extend({
  attr(...s) {
    const [e, t] = s;
    return !s.length || s.length === 1 && typeof e == "string" ? this._attr.apply(this, s) : typeof e == "object" ? (e && Object.keys(e).forEach((n) => {
      const i = e[n];
      i === null ? this.removeAttr(n) : this._attr(n, i);
    }), this) : t === null ? this.removeAttr(e) : this._attr(e, t);
  }
});
u.Event = (s, e) => {
  const [t, ...n] = s.split("."), i = new Event(t, {
    bubbles: !0,
    cancelable: !0
  });
  return i.namespace = n.join("."), i.___ot = t, i.___td = e, i;
};
const ti = (s, e) => new Promise((t) => {
  const n = window.setTimeout(t, s);
  e && e(n);
}), Sn = /* @__PURE__ */ new Map();
function ei(s) {
  const { zui: e } = window;
  return (!Sn.size || s && !Sn.has(s.toUpperCase())) && Object.keys(e).forEach((t) => {
    const n = e[t];
    !n.NAME || !n.ZUI || Sn.set(t.toLowerCase(), n);
  }), s ? Sn.get(s.toLowerCase()) : void 0;
}
function Bd(s, e, t) {
  const n = ei(s);
  return n ? new n(e, t) : null;
}
function Ep(s) {
  if (s) {
    const e = ei(s);
    e && e.defineFn();
  } else
    ei(), Sn.forEach((e) => {
      e.defineFn();
    });
}
u.fn.zuiInit = function() {
  return this.find("[data-zui]").each(function() {
    const e = u(this).dataset(), t = e.zui;
    delete e.zui, Bd(t, this, e);
  }), this;
};
u.fn.zui = function(s, e) {
  const t = this[0];
  if (!t)
    return;
  if (typeof s != "string") {
    const i = dc(t, void 0, !0), o = {};
    let r;
    return Object.keys(i).forEach((a) => {
      if (a.startsWith("zui.")) {
        const l = i[a];
        o[a] = l, (!r || r.gid < l.gid) && (r = o[a]);
      }
    }), s === !0 ? o : r;
  }
  const n = ei(s);
  if (n)
    return e === !0 ? n.getAll(t) : n.query(t, e);
};
u(() => {
  u("body").zuiInit();
});
function Gr(s, e) {
  const t = u(s)[0];
  if (!t)
    return !1;
  let { viewport: n } = e || {};
  const { left: i, top: o, width: r, height: a } = t.getBoundingClientRect();
  if (!n) {
    const { innerHeight: m, innerWidth: b } = window, { clientHeight: _, clientWidth: v } = document.documentElement;
    n = { left: 0, top: 0, width: b || v, height: m || _ };
  }
  const { left: l, top: h, width: d, height: c } = n;
  if (e != null && e.fullyCheck)
    return i >= l && o >= h && i + r <= d && o + a <= c;
  const f = i <= d && i + r >= l;
  return o <= c && o + a >= h && f;
}
u.fn.isVisible = function(s) {
  return this.each((e, t) => {
    Gr(t, s);
  });
};
function Kr(s, e, t = !1) {
  const n = u(s);
  if (e !== void 0) {
    if (e.length) {
      const i = `zui-runjs-${u.guid++}`;
      n.append(`<script id="${i}">${e}<\/script>`), t && n.find(`#${i}`).remove();
    }
    return;
  }
  n.find("script").each((i, o) => {
    Kr(n, o.innerHTML), o.remove();
  });
}
u.runJS = (s, ...e) => (s = s.trim(), !s.startsWith("return ") && !s.endsWith(";") && (s = `return ${s}`), new Function(...e.map(([n]) => n), s)(...e.map(([, n]) => n)));
u.fn.runJS = function(s) {
  return this.each((e, t) => {
    Kr(t, s);
  });
};
function uc(s, e) {
  const t = u(s), { ifNeeded: n = !0, ...i } = e || {};
  return t.each((o, r) => {
    n && Gr(r, { viewport: r.getBoundingClientRect() }) || r.scrollIntoView(i);
  }), t;
}
u.fn.scrollIntoView = function(s) {
  return this.each((e, t) => {
    uc(t, s);
  });
};
u.getScript = function(s, e, t) {
  return new Promise((n) => {
    const i = u(`script[src="${s}"]`), o = () => {
      n(), t == null || t();
    };
    if (i.length) {
      if (i.dataset("loaded"))
        o();
      else {
        const a = i.data("loadCalls") || [];
        a.push(o), i.data("loadCalls", a);
      }
      return;
    }
    const r = document.createElement("script");
    r.async = !0, e && Object.assign(r, e), r.onload = () => {
      o(), (u(r).dataset("loaded", !0).data("loadCalls") || []).forEach((l) => l()), u(r).removeData("loadCalls");
    }, r.src = s, u("head").append(r);
  });
};
const Mp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  isVisible: Gr,
  runJS: Kr,
  scrollIntoView: uc
}, Symbol.toStringTag, { value: "Module" }));
var Lo, H, fc, et, ue, Ea, pc, rr, Be = {}, mc = [], Fd = /acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i, Yr = Array.isArray;
function ie(s, e) {
  for (var t in e)
    s[t] = e[t];
  return s;
}
function gc(s) {
  var e = s.parentNode;
  e && e.removeChild(s);
}
function w(s, e, t) {
  var n, i, o, r = {};
  for (o in e)
    o == "key" ? n = e[o] : o == "ref" ? i = e[o] : r[o] = e[o];
  if (arguments.length > 2 && (r.children = arguments.length > 3 ? Lo.call(arguments, 2) : t), typeof s == "function" && s.defaultProps != null)
    for (o in s.defaultProps)
      r[o] === void 0 && (r[o] = s.defaultProps[o]);
  return Us(s, r, n, i, null);
}
function Us(s, e, t, n, i) {
  var o = { type: s, props: e, key: t, ref: n, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: i ?? ++fc };
  return i == null && H.vnode != null && H.vnode(o), o;
}
function G() {
  return { current: null };
}
function ae(s) {
  return s.children;
}
function F(s, e) {
  this.props = s, this.context = e;
}
function ni(s, e) {
  if (e == null)
    return s.__ ? ni(s.__, s.__.__k.indexOf(s) + 1) : null;
  for (var t; e < s.__k.length; e++)
    if ((t = s.__k[e]) != null && t.__e != null)
      return t.__e;
  return typeof s.type == "function" ? ni(s) : null;
}
function yc(s) {
  var e, t;
  if ((s = s.__) != null && s.__c != null) {
    for (s.__e = s.__c.base = null, e = 0; e < s.__k.length; e++)
      if ((t = s.__k[e]) != null && t.__e != null) {
        s.__e = s.__c.base = t.__e;
        break;
      }
    return yc(s);
  }
}
function Ma(s) {
  (!s.__d && (s.__d = !0) && ue.push(s) && !si.__r++ || Ea !== H.debounceRendering) && ((Ea = H.debounceRendering) || pc)(si);
}
function si() {
  var s, e, t, n, i, o, r, a, l;
  for (ue.sort(rr); s = ue.shift(); )
    s.__d && (e = ue.length, n = void 0, i = void 0, o = void 0, a = (r = (t = s).__v).__e, (l = t.__P) && (n = [], i = [], (o = ie({}, r)).__v = r.__v + 1, Xr(l, r, o, t.__n, l.ownerSVGElement !== void 0, r.__h != null ? [a] : null, n, a ?? ni(r), r.__h, i), _c(n, r, i), r.__e != a && yc(r)), ue.length > e && ue.sort(rr));
  si.__r = 0;
}
function bc(s, e, t, n, i, o, r, a, l, h, d) {
  var c, f, p, m, b, _, v, x, k, N, S = 0, R = n && n.__k || mc, L = R.length, I = L, D = e.length;
  for (t.__k = [], c = 0; c < D; c++)
    (m = t.__k[c] = (m = e[c]) == null || typeof m == "boolean" || typeof m == "function" ? null : typeof m == "string" || typeof m == "number" || typeof m == "bigint" ? Us(null, m, null, null, m) : Yr(m) ? Us(ae, { children: m }, null, null, null) : m.__b > 0 ? Us(m.type, m.props, m.key, m.ref ? m.ref : null, m.__v) : m) != null && (m.__ = t, m.__b = t.__b + 1, (x = Wd(m, R, v = c + S, I)) === -1 ? p = Be : (p = R[x] || Be, R[x] = void 0, I--), Xr(s, m, p, i, o, r, a, l, h, d), b = m.__e, (f = m.ref) && p.ref != f && (p.ref && Jr(p.ref, null, m), d.push(f, m.__c || b, m)), b != null && (_ == null && (_ = b), N = !(k = p === Be || p.__v === null) && x === v, k ? x == -1 && S-- : x !== v && (x === v + 1 ? (S++, N = !0) : x > v ? I > D - v ? (S += x - v, N = !0) : S-- : S = x < v && x == v - 1 ? x - v : 0), v = c + S, N = N || x == c && !k, typeof m.type != "function" || x === v && p.__k !== m.__k ? typeof m.type == "function" || N ? m.__d !== void 0 ? (l = m.__d, m.__d = void 0) : l = b.nextSibling : l = vc(s, b, l) : l = wc(m, l, s), typeof t.type == "function" && (t.__d = l)));
  for (t.__e = _, c = L; c--; )
    R[c] != null && (typeof t.type == "function" && R[c].__e != null && R[c].__e == t.__d && (t.__d = R[c].__e.nextSibling), xc(R[c], R[c]));
}
function wc(s, e, t) {
  for (var n, i = s.__k, o = 0; i && o < i.length; o++)
    (n = i[o]) && (n.__ = s, e = typeof n.type == "function" ? wc(n, e, t) : vc(t, n.__e, e));
  return e;
}
function vc(s, e, t) {
  return t == null || t.parentNode !== s ? s.insertBefore(e, null) : e == t && e.parentNode != null || s.insertBefore(e, t), e.nextSibling;
}
function Wd(s, e, t, n) {
  var i = s.key, o = s.type, r = t - 1, a = t + 1, l = e[t];
  if (l === null || l && i == l.key && o === l.type)
    return t;
  if (n > (l != null ? 1 : 0))
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
function Vd(s, e, t, n, i) {
  var o;
  for (o in t)
    o === "children" || o === "key" || o in e || ii(s, o, null, t[o], n);
  for (o in e)
    i && typeof e[o] != "function" || o === "children" || o === "key" || o === "value" || o === "checked" || t[o] === e[o] || ii(s, o, e[o], t[o], n);
}
function Pa(s, e, t) {
  e[0] === "-" ? s.setProperty(e, t ?? "") : s[e] = t == null ? "" : typeof t != "number" || Fd.test(e) ? t : t + "px";
}
function ii(s, e, t, n, i) {
  var o;
  t:
    if (e === "style")
      if (typeof t == "string")
        s.style.cssText = t;
      else {
        if (typeof n == "string" && (s.style.cssText = n = ""), n)
          for (e in n)
            t && e in t || Pa(s.style, e, "");
        if (t)
          for (e in t)
            n && t[e] === n[e] || Pa(s.style, e, t[e]);
      }
    else if (e[0] === "o" && e[1] === "n")
      o = e !== (e = e.replace(/Capture$/, "")), e = e.toLowerCase() in s ? e.toLowerCase().slice(2) : e.slice(2), s.l || (s.l = {}), s.l[e + o] = t, t ? n || s.addEventListener(e, o ? Ia : Ra, o) : s.removeEventListener(e, o ? Ia : Ra, o);
    else if (e !== "dangerouslySetInnerHTML") {
      if (i)
        e = e.replace(/xlink(H|:h)/, "h").replace(/sName$/, "s");
      else if (e !== "width" && e !== "height" && e !== "href" && e !== "list" && e !== "form" && e !== "tabIndex" && e !== "download" && e !== "rowSpan" && e !== "colSpan" && e in s)
        try {
          s[e] = t ?? "";
          break t;
        } catch {
        }
      typeof t == "function" || (t == null || t === !1 && e[4] !== "-" ? s.removeAttribute(e) : s.setAttribute(e, t));
    }
}
function Ra(s) {
  return this.l[s.type + !1](H.event ? H.event(s) : s);
}
function Ia(s) {
  return this.l[s.type + !0](H.event ? H.event(s) : s);
}
function Xr(s, e, t, n, i, o, r, a, l, h) {
  var d, c, f, p, m, b, _, v, x, k, N, S, R, L, I, D = e.type;
  if (e.constructor !== void 0)
    return null;
  t.__h != null && (l = t.__h, a = e.__e = t.__e, e.__h = null, o = [a]), (d = H.__b) && d(e);
  try {
    t:
      if (typeof D == "function") {
        if (v = e.props, x = (d = D.contextType) && n[d.__c], k = d ? x ? x.props.value : d.__ : n, t.__c ? _ = (c = e.__c = t.__c).__ = c.__E : ("prototype" in D && D.prototype.render ? e.__c = c = new D(v, k) : (e.__c = c = new F(v, k), c.constructor = D, c.render = qd), x && x.sub(c), c.props = v, c.state || (c.state = {}), c.context = k, c.__n = n, f = c.__d = !0, c.__h = [], c._sb = []), c.__s == null && (c.__s = c.state), D.getDerivedStateFromProps != null && (c.__s == c.state && (c.__s = ie({}, c.__s)), ie(c.__s, D.getDerivedStateFromProps(v, c.__s))), p = c.props, m = c.state, c.__v = e, f)
          D.getDerivedStateFromProps == null && c.componentWillMount != null && c.componentWillMount(), c.componentDidMount != null && c.__h.push(c.componentDidMount);
        else {
          if (D.getDerivedStateFromProps == null && v !== p && c.componentWillReceiveProps != null && c.componentWillReceiveProps(v, k), !c.__e && (c.shouldComponentUpdate != null && c.shouldComponentUpdate(v, c.__s, k) === !1 || e.__v === t.__v)) {
            for (e.__v !== t.__v && (c.props = v, c.state = c.__s, c.__d = !1), e.__e = t.__e, e.__k = t.__k, e.__k.forEach(function(A) {
              A && (A.__ = e);
            }), N = 0; N < c._sb.length; N++)
              c.__h.push(c._sb[N]);
            c._sb = [], c.__h.length && r.push(c);
            break t;
          }
          c.componentWillUpdate != null && c.componentWillUpdate(v, c.__s, k), c.componentDidUpdate != null && c.__h.push(function() {
            c.componentDidUpdate(p, m, b);
          });
        }
        if (c.context = k, c.props = v, c.__P = s, c.__e = !1, S = H.__r, R = 0, "prototype" in D && D.prototype.render) {
          for (c.state = c.__s, c.__d = !1, S && S(e), d = c.render(c.props, c.state, c.context), L = 0; L < c._sb.length; L++)
            c.__h.push(c._sb[L]);
          c._sb = [];
        } else
          do
            c.__d = !1, S && S(e), d = c.render(c.props, c.state, c.context), c.state = c.__s;
          while (c.__d && ++R < 25);
        c.state = c.__s, c.getChildContext != null && (n = ie(ie({}, n), c.getChildContext())), f || c.getSnapshotBeforeUpdate == null || (b = c.getSnapshotBeforeUpdate(p, m)), bc(s, Yr(I = d != null && d.type === ae && d.key == null ? d.props.children : d) ? I : [I], e, t, n, i, o, r, a, l, h), c.base = e.__e, e.__h = null, c.__h.length && r.push(c), _ && (c.__E = c.__ = null);
      } else
        o == null && e.__v === t.__v ? (e.__k = t.__k, e.__e = t.__e) : e.__e = Ud(t.__e, e, t, n, i, o, r, l, h);
    (d = H.diffed) && d(e);
  } catch (A) {
    e.__v = null, (l || o != null) && (e.__e = a, e.__h = !!l, o[o.indexOf(a)] = null), H.__e(A, e, t);
  }
}
function _c(s, e, t) {
  for (var n = 0; n < t.length; n++)
    Jr(t[n], t[++n], t[++n]);
  H.__c && H.__c(e, s), s.some(function(i) {
    try {
      s = i.__h, i.__h = [], s.some(function(o) {
        o.call(i);
      });
    } catch (o) {
      H.__e(o, i.__v);
    }
  });
}
function Ud(s, e, t, n, i, o, r, a, l) {
  var h, d, c, f = t.props, p = e.props, m = e.type, b = 0;
  if (m === "svg" && (i = !0), o != null) {
    for (; b < o.length; b++)
      if ((h = o[b]) && "setAttribute" in h == !!m && (m ? h.localName === m : h.nodeType === 3)) {
        s = h, o[b] = null;
        break;
      }
  }
  if (s == null) {
    if (m === null)
      return document.createTextNode(p);
    s = i ? document.createElementNS("http://www.w3.org/2000/svg", m) : document.createElement(m, p.is && p), o = null, a = !1;
  }
  if (m === null)
    f === p || a && s.data === p || (s.data = p);
  else {
    if (o = o && Lo.call(s.childNodes), d = (f = t.props || Be).dangerouslySetInnerHTML, c = p.dangerouslySetInnerHTML, !a) {
      if (o != null)
        for (f = {}, b = 0; b < s.attributes.length; b++)
          f[s.attributes[b].name] = s.attributes[b].value;
      (c || d) && (c && (d && c.__html == d.__html || c.__html === s.innerHTML) || (s.innerHTML = c && c.__html || ""));
    }
    if (Vd(s, p, f, i, a), c)
      e.__k = [];
    else if (bc(s, Yr(b = e.props.children) ? b : [b], e, t, n, i && m !== "foreignObject", o, r, o ? o[0] : t.__k && ni(t, 0), a, l), o != null)
      for (b = o.length; b--; )
        o[b] != null && gc(o[b]);
    a || ("value" in p && (b = p.value) !== void 0 && (b !== s.value || m === "progress" && !b || m === "option" && b !== f.value) && ii(s, "value", b, f.value, !1), "checked" in p && (b = p.checked) !== void 0 && b !== s.checked && ii(s, "checked", b, f.checked, !1));
  }
  return s;
}
function Jr(s, e, t) {
  try {
    typeof s == "function" ? s(e) : s.current = e;
  } catch (n) {
    H.__e(n, t);
  }
}
function xc(s, e, t) {
  var n, i;
  if (H.unmount && H.unmount(s), (n = s.ref) && (n.current && n.current !== s.__e || Jr(n, null, e)), (n = s.__c) != null) {
    if (n.componentWillUnmount)
      try {
        n.componentWillUnmount();
      } catch (o) {
        H.__e(o, e);
      }
    n.base = n.__P = null, s.__c = void 0;
  }
  if (n = s.__k)
    for (i = 0; i < n.length; i++)
      n[i] && xc(n[i], e, t || typeof s.type != "function");
  t || s.__e == null || gc(s.__e), s.__ = s.__e = s.__d = void 0;
}
function qd(s, e, t) {
  return this.constructor(s, t);
}
function Rn(s, e, t) {
  var n, i, o, r;
  H.__ && H.__(s, e), i = (n = typeof t == "function") ? null : t && t.__k || e.__k, o = [], r = [], Xr(e, s = (!n && t || e).__k = w(ae, null, [s]), i || Be, Be, e.ownerSVGElement !== void 0, !n && t ? [t] : i ? null : e.firstChild ? Lo.call(e.childNodes) : null, o, !n && t ? t : i ? i.__e : e.firstChild, n, r), _c(o, s, r);
}
Lo = mc.slice, H = { __e: function(s, e, t, n) {
  for (var i, o, r; e = e.__; )
    if ((i = e.__c) && !i.__)
      try {
        if ((o = i.constructor) && o.getDerivedStateFromError != null && (i.setState(o.getDerivedStateFromError(s)), r = i.__d), i.componentDidCatch != null && (i.componentDidCatch(s, n || {}), r = i.__d), r)
          return i.__E = i;
      } catch (a) {
        s = a;
      }
  throw s;
} }, fc = 0, et = function(s) {
  return s != null && s.constructor === void 0;
}, F.prototype.setState = function(s, e) {
  var t;
  t = this.__s != null && this.__s !== this.state ? this.__s : this.__s = ie({}, this.state), typeof s == "function" && (s = s(ie({}, t), this.props)), s && ie(t, s), s != null && this.__v && (e && this._sb.push(e), Ma(this));
}, F.prototype.forceUpdate = function(s) {
  this.__v && (this.__e = !0, s && this.__h.push(s), Ma(this));
}, F.prototype.render = ae, ue = [], pc = typeof Promise == "function" ? Promise.prototype.then.bind(Promise.resolve()) : setTimeout, rr = function(s, e) {
  return s.__v.__b - e.__v.__b;
}, si.__r = 0;
var Cc = function(s, e, t, n) {
  var i;
  e[0] = 0;
  for (var o = 1; o < e.length; o++) {
    var r = e[o++], a = e[o] ? (e[0] |= r ? 1 : 2, t[e[o++]]) : e[++o];
    r === 3 ? n[0] = a : r === 4 ? n[1] = Object.assign(n[1] || {}, a) : r === 5 ? (n[1] = n[1] || {})[e[++o]] = a : r === 6 ? n[1][e[++o]] += a + "" : r ? (i = s.apply(a, Cc(s, a, t, ["", null])), n.push(i), a[0] ? e[0] |= 2 : (e[o - 2] = 0, e[o] = i)) : n.push(a);
  }
  return n;
}, Da = /* @__PURE__ */ new Map();
function Gd(s) {
  var e = Da.get(this);
  return e || (e = /* @__PURE__ */ new Map(), Da.set(this, e)), (e = Cc(this, e.get(s) || (e.set(s, e = function(t) {
    for (var n, i, o = 1, r = "", a = "", l = [0], h = function(f) {
      o === 1 && (f || (r = r.replace(/^\s*\n\s*|\s*\n\s*$/g, ""))) ? l.push(0, f, r) : o === 3 && (f || r) ? (l.push(3, f, r), o = 2) : o === 2 && r === "..." && f ? l.push(4, f, 0) : o === 2 && r && !f ? l.push(5, 0, !0, r) : o >= 5 && ((r || !f && o === 5) && (l.push(o, 0, r, i), o = 6), f && (l.push(o, f, 0, i), o = 6)), r = "";
    }, d = 0; d < t.length; d++) {
      d && (o === 1 && h(), h(d));
      for (var c = 0; c < t[d].length; c++)
        n = t[d][c], o === 1 ? n === "<" ? (h(), l = [l], o = 3) : r += n : o === 4 ? r === "--" && n === ">" ? (o = 1, r = "") : r = n + r[0] : a ? n === a ? a = "" : r += n : n === '"' || n === "'" ? a = n : n === ">" ? (h(), o = 1) : o && (n === "=" ? (o = 5, i = r, r = "") : n === "/" && (o < 5 || t[d][c + 1] === ">") ? (h(), o === 3 && (l = l[0]), o = l, (l = l[0]).push(2, 0, o), o = 0) : n === " " || n === "	" || n === `
` || n === "\r" ? (h(), o = 2) : r += n), o === 3 && r === "!--" && (o = 4, l = l[0]);
    }
    return h(), l;
  }(s)), e), arguments, [])).length > 1 ? e : e[0];
}
const Pp = Gd.bind(w);
class Zr extends F {
  _getClassName(e) {
    return [e.className, e.class];
  }
  _getProps(e) {
    const { className: t, class: n, attrs: i, data: o, forwardRef: r, children: a, style: l, ...h } = e, d = Object.keys(h).reduce((c, f) => ((f === "dangerouslySetInnerHTML" || f.startsWith("data-")) && (c[f] = h[f]), c), {});
    return { ref: r, class: M(this._getClassName(e)), style: l, ...d, ...i };
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
    return e = this._beforeRender(e) || e, w(this._getComponent(e), this._getProps(e), this._getChildren(e));
  }
}
var Kd = 0;
function g(s, e, t, n, i, o) {
  var r, a, l = {};
  for (a in e)
    a == "ref" ? r = e[a] : l[a] = e[a];
  var h = { type: s, props: l, key: t, ref: r, __k: null, __: null, __b: 0, __e: null, __d: void 0, __c: null, __h: null, constructor: void 0, __v: --Kd, __source: i, __self: o };
  if (typeof s == "function" && (r = s.defaultProps))
    for (a in r)
      l[a] === void 0 && (l[a] = r[a]);
  return H.vnode && H.vnode(h), h;
}
class Ds extends F {
  constructor() {
    super(...arguments), this._ref = G();
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
    const { executeScript: t, html: n, ...i } = e;
    return /* @__PURE__ */ g(Zr, { forwardRef: this._ref, dangerouslySetInnerHTML: { __html: n }, ...i });
  }
}
function Yd(s) {
  const {
    tag: e,
    className: t,
    style: n,
    renders: i,
    generateArgs: o = [],
    generatorThis: r,
    generators: a,
    onGenerate: l,
    onRenderItem: h,
    ...d
  } = s, c = [t], f = { ...n }, p = [], m = [];
  return i.forEach((b) => {
    const _ = [];
    if (typeof b == "string" && a && a[b] && (b = a[b]), typeof b == "function")
      if (l)
        _.push(...l.call(r, b, p, ...o));
      else {
        const v = b.call(r, p, ...o);
        v && (Array.isArray(v) ? _.push(...v) : _.push(v));
      }
    else
      _.push(b);
    _.forEach((v) => {
      v != null && (typeof v == "object" && !et(v) && ("html" in v || "__html" in v || "className" in v || "style" in v || "attrs" in v || "children" in v) ? v.html ? p.push(
        /* @__PURE__ */ g("div", { className: M(v.className), style: v.style, dangerouslySetInnerHTML: { __html: v.html }, ...v.attrs ?? {} })
      ) : v.__html ? m.push(v.__html) : (v.style && Object.assign(f, v.style), v.className && c.push(v.className), v.children && p.push(v.children), v.attrs && Object.assign(d, v.attrs)) : p.push(v));
    });
  }), m.length && Object.assign(d, { dangerouslySetInnerHTML: { __html: m } }), [{
    className: M(c),
    style: f,
    ...d
  }, p];
}
function ar({
  tag: s = "div",
  ...e
}) {
  const [t, n] = Yd(e);
  return w(s, t, ...n);
}
function $c(s, e, t) {
  return typeof s == "function" ? s.call(e, ...t) : Array.isArray(s) ? s.map((n) => $c(n, e, t)) : et(s) || s === null ? s : typeof s == "object" ? s.html ? /* @__PURE__ */ g(Ds, { ...s }) : /* @__PURE__ */ g(Zr, { ...s }) : s;
}
function Cn(s) {
  const { content: e, generatorThis: t, generatorArgs: n } = s, i = $c(e, t, n);
  return i == null || typeof i == "boolean" ? null : et(i) ? i : /* @__PURE__ */ g(ae, { children: i });
}
const Aa = (s) => s.startsWith("icon-") ? s : `icon-${s}`;
function Z(s) {
  const { icon: e, className: t, ...n } = s;
  if (!e)
    return null;
  if (et(e))
    return e;
  const i = ["icon", t];
  if (typeof e == "string")
    i.push(Aa(e));
  else if (typeof e == "object") {
    const { className: o, icon: r, ...a } = e;
    i.push(o, r ? Aa(r) : ""), Object.assign(n, a);
  }
  return /* @__PURE__ */ g("i", { className: M(i), ...n });
}
function Xd(s) {
  return this.getChildContext = () => s.context, s.children;
}
function Jd(s) {
  const e = this, t = s._container;
  e.componentWillUnmount = function() {
    Rn(null, e._temp), e._temp = null, e._container = null;
  }, e._container && e._container !== t && e.componentWillUnmount(), s._vnode ? (e._temp || (e._container = t, e._temp = {
    nodeType: 1,
    parentNode: t,
    childNodes: [],
    appendChild(n) {
      this.childNodes.push(n), e._container.appendChild(n);
    },
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    insertBefore(n, i) {
      this.childNodes.push(n), e._container.appendChild(n);
    },
    removeChild(n) {
      this.childNodes.splice(this.childNodes.indexOf(n) >>> 1, 1), e._container.removeChild(n);
    }
  }), Rn(
    w(Xd, { context: e.context }, s._vnode),
    e._temp
  )) : e._temp && e.componentWillUnmount();
}
function Zd(s, e) {
  const t = w(Jd, { _vnode: s, _container: e });
  return t.containerInfo = e, t;
}
const bi = class bi {
  /**
   * The component constructor.
   *
   * @param options The component initial options.
   */
  constructor(e, t) {
    this._inited = !1;
    const { KEY: n, DATA_KEY: i, DEFAULT: o, MULTI_INSTANCE: r, NAME: a } = this.constructor;
    if (!a)
      throw new Error('[ZUI] The component must have a "NAME" static property.');
    const l = u(e);
    if (l.data(n) && !r)
      throw new Error("[ZUI] The component has been initialized on element.");
    const h = u.guid++;
    if (this._gid = h, this._element = l[0], l.on("DOMNodeRemovedFromDocument", () => {
      this.destroy();
    }), this._options = { ...o, ...l.dataset() }, this.setOptions(t), this._key = this.options.key ?? `__${h}`, l.data(n, this).attr(i, `${h}`), r) {
      const d = `${n}:ALL`;
      let c = l.data(d);
      c || (c = /* @__PURE__ */ new Map(), l.data(d, c)), c.set(this._key, this);
    }
    this.init(), requestAnimationFrame(() => {
      this._inited = !0, this.afterInit(), this.emit("inited", this.options);
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
    const { KEY: e, DATA_KEY: t, MULTI_INSTANCE: n } = this.constructor, { $element: i } = this;
    if (this.emit("destroyed"), i.off(this.namespace).removeData(e).attr(t, null), n) {
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
    const n = u.Event(e);
    return n.__src = this, this.$emitter.trigger(n, [this, ...t]), n;
  }
  /**
   * Listen to a component event.
   *
   * @param event     The event name.
   * @param callback  The event callback.
   */
  on(e, t, n) {
    const i = this;
    this.$element[n != null && n.once ? "one" : "on"](this._wrapEvent(e), function(o, r) {
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
  i18n(e, t, n) {
    return nt(this.options.i18n, e, t, n, this.options.lang, this.constructor.NAME) ?? nt(this.options.i18n, e, t, n, this.options.lang) ?? `{i18n:${e}}`;
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
    const n = u(e);
    if (this.MULTI_INSTANCE && t !== void 0) {
      const i = n.data(`${this.KEY}:ALL`);
      return i ? i.get(t) : void 0;
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
  static ensure(e, t) {
    const n = this.get(e, t == null ? void 0 : t.key);
    return n ? (t && n.setOptions(t), n) : new this(e, t);
  }
  /**
   * Get all component instances.
   *
   * @param this     Current component constructor.
   * @param selector The component element selector.
   * @returns        All component instances.
   */
  static getAll(e) {
    const { MULTI_INSTANCE: t, DATA_KEY: n } = this, i = [];
    return u(e || document).find(`[${n}]`).each((o, r) => {
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
    return e === void 0 ? this.getAll().sort((n, i) => n.gid - i.gid)[0] : this.get(u(e).closest(`[${this.DATA_KEY}]`), t);
  }
  /**
   * Create cash fn.method for current component.
   *
   * @param name The method name.
   */
  static defineFn(e) {
    let t = e || this.ZUI;
    u.fn[t] && (t = `zui${this.NAME}`);
    const n = this;
    u.fn.extend({
      [t](i, ...o) {
        const r = typeof i == "object" ? i : void 0, a = typeof i == "string" ? i : void 0;
        let l;
        return this.each((h, d) => {
          let c = n.get(d);
          if (c ? r && c.render(r) : c = new n(d, r), a) {
            let f = c[a], p = c;
            f === void 0 && (p = c.$, f = p[a]), typeof f == "function" ? l = f.call(p, ...o) : l = f;
          }
        }), l !== void 0 ? l : this;
      }
    });
  }
};
bi.DEFAULT = {}, bi.MULTI_INSTANCE = !1;
let ot = bi;
class V extends ot {
  constructor() {
    super(...arguments), this.ref = G();
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
    Rn(
      w(this.constructor.Component, {
        ref: this.ref,
        ...this.setOptions(e)
      }),
      this.element
    );
  }
}
function Qd({
  component: s = "div",
  className: e,
  children: t,
  style: n,
  attrs: i
}) {
  return w(s, {
    className: M(e),
    style: n,
    ...i
  }, t);
}
function kc({
  type: s,
  component: e = "a",
  className: t,
  children: n,
  content: i,
  attrs: o,
  url: r,
  disabled: a,
  active: l,
  icon: h,
  text: d,
  target: c,
  trailingIcon: f,
  hint: p,
  checked: m,
  onClick: b,
  data: _,
  ...v
}) {
  const x = [
    typeof m == "boolean" ? /* @__PURE__ */ g("div", { class: `checkbox-primary${m ? " checked" : ""}`, children: /* @__PURE__ */ g("label", {}) }) : null,
    /* @__PURE__ */ g(Z, { icon: h }),
    /* @__PURE__ */ g("span", { className: "text", children: d }),
    /* @__PURE__ */ g(Cn, { content: i }),
    n,
    /* @__PURE__ */ g(Z, { icon: f })
  ];
  return w(e, {
    className: M(t, { disabled: a, active: l }),
    title: p,
    [e === "a" ? "href" : "data-url"]: r,
    [e === "a" ? "target" : "data-target"]: c,
    onClick: b,
    ...v,
    ...o
  }, ...x);
}
function tu({
  component: s = "div",
  className: e,
  text: t,
  attrs: n,
  children: i,
  content: o,
  style: r,
  onClick: a
}) {
  return w(s, {
    className: M(e),
    style: r,
    onClick: a,
    ...n
  }, t, /* @__PURE__ */ g(Cn, { content: o }), i);
}
function eu({
  component: s = "div",
  className: e,
  style: t,
  space: n,
  flex: i,
  attrs: o,
  onClick: r,
  children: a
}) {
  return w(s, {
    className: M(e),
    style: { width: n, height: n, flex: i, ...t },
    onClick: r,
    ...o
  }, a);
}
function nu({ type: s, ...e }) {
  return /* @__PURE__ */ g(ar, { ...e });
}
function Tc({
  component: s = "div",
  className: e,
  children: t,
  content: n,
  style: i,
  attrs: o
}) {
  return w(s, {
    className: M(e),
    style: i,
    ...o
  }, /* @__PURE__ */ g(Cn, { content: n }), t);
}
var jt;
let Oo = (jt = class extends F {
  constructor() {
    super(...arguments), this.ref = G();
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
    var t, n;
    (n = (t = this.props).afterRender) == null || n.call(t, { menu: this, firstRender: e });
  }
  handleItemClick(e, t, n, i) {
    n && n.call(i.target, i, e, t);
    const { onClickItem: o } = this.props;
    o && o({ menu: this, item: e, index: t, event: i });
  }
  beforeRender() {
    var n;
    const e = { ...this.props };
    typeof e.items == "function" && (e.items = e.items(this)), e.items || (e.items = []);
    const t = (n = e.beforeRender) == null ? void 0 : n.call(e, { menu: this, options: e });
    return t && Object.assign(e, t), e;
  }
  getItemRenderProps(e, t, n) {
    const { commonItemProps: i, onClickItem: o, itemRenderProps: r } = e;
    let a = { ...t };
    return i && Object.assign(a, i[t.type || "item"]), (o || t.onClick) && (a.onClick = this.handleItemClick.bind(this, a, n, t.onClick)), a.className = M(a.className), r && (a = r(a)), a;
  }
  renderItem(e, t, n) {
    if (!t)
      return null;
    const i = this.getItemRenderProps(e, t, n), { itemRender: o } = e;
    if (o) {
      if (typeof o == "object") {
        const b = o[t.type || "item"];
        if (b)
          return /* @__PURE__ */ g(b, { ...i });
      } else if (typeof o == "function") {
        const b = o.call(this, i, w);
        if (et(b))
          return b;
        typeof b == "object" && Object.assign(i, b);
      }
    }
    const { type: r = "item", component: a, key: l = n, rootAttrs: h, rootClass: d, rootStyle: c, rootChildren: f, ...p } = i;
    if (r === "html")
      return /* @__PURE__ */ g(
        "li",
        {
          className: M("action-menu-item", `${this.name}-html`, d, p.className),
          ...h,
          style: c || p.style,
          dangerouslySetInnerHTML: { __html: p.html }
        },
        l
      );
    const m = !a || typeof a == "string" ? this.constructor.ItemComponents && this.constructor.ItemComponents[r] || jt.ItemComponents[r] : a;
    return Object.assign(p, {
      type: r,
      component: typeof a == "string" ? a : void 0
    }), e.checkbox && r === "item" && p.checked === void 0 && (p.checked = !!p.active), this.renderTypedItem(m, {
      className: M(d),
      children: f,
      style: c,
      key: l,
      ...h
    }, {
      ...p,
      type: r,
      component: typeof a == "string" ? a : void 0
    });
  }
  renderTypedItem(e, t, n) {
    const { children: i, className: o, key: r, ...a } = t;
    return /* @__PURE__ */ g(
      "li",
      {
        className: M(`${this.constructor.NAME}-item`, `${this.name}-${n.type}`, o),
        ...a,
        children: [
          /* @__PURE__ */ g(e, { ...n }),
          typeof i == "function" ? i() : i
        ]
      },
      r
    );
  }
  render() {
    const e = this.beforeRender(), {
      name: t,
      style: n,
      commonItemProps: i,
      className: o,
      items: r,
      children: a,
      itemRender: l,
      onClickItem: h,
      beforeRender: d,
      afterRender: c,
      beforeDestroy: f,
      ...p
    } = e, m = this.constructor.ROOT_TAG;
    return /* @__PURE__ */ g(m, { class: M(this.name, o), style: n, ...p, ref: this.ref, children: [
      r && r.map(this.renderItem.bind(this, e)),
      a
    ] });
  }
}, jt.ItemComponents = {
  divider: Qd,
  item: kc,
  heading: tu,
  space: eu,
  custom: nu,
  basic: Tc
}, jt.ROOT_TAG = "menu", jt.NAME = "action-menu", jt);
const wi = class wi extends V {
};
wi.NAME = "ActionMenu", wi.Component = Oo;
let La = wi;
function su({
  items: s,
  show: e,
  level: t,
  ...n
}) {
  return /* @__PURE__ */ g(kc, { ...n });
}
var On, yt, Ve, jn;
let Qr = (jn = class extends Oo {
  constructor(t) {
    super(t);
    C(this, On, /* @__PURE__ */ new Set());
    C(this, yt, void 0);
    C(this, Ve, (t, n, i) => {
      u(i.target).closest(".not-nested-toggle").length || (this.toggle(t, n), i.preventDefault());
    });
    $(this, yt, t.nestedShow === void 0), y(this, yt) && (this.state = { nestedShow: t.defaultNestedShow ?? {} });
  }
  get nestedTrigger() {
    return this.props.nestedTrigger;
  }
  beforeRender() {
    const t = super.beforeRender(), { nestedShow: n, nestedTrigger: i, defaultNestedShow: o, controlledMenu: r, indent: a, ...l } = t;
    return typeof l.items == "function" && (l.items = l.items(this)), l.items || (l.items = []), l.items.some((h) => h.items) || (l.className = M(l.className, "no-nested-items")), !r && a && (l.style = Object.assign({
      [`--${this.name}-indent`]: `${a}px`
    }, l.style)), l;
  }
  getNestedMenuProps(t) {
    const { name: n, controlledMenu: i, nestedShow: o, beforeDestroy: r, beforeRender: a, itemRender: l, onClickItem: h, afterRender: d, commonItemProps: c, level: f, itemRenderProps: p } = this.props;
    return {
      items: t,
      name: n,
      nestedShow: y(this, yt) ? this.state.nestedShow : o,
      nestedTrigger: this.nestedTrigger,
      controlledMenu: i || this,
      commonItemProps: c,
      onClickItem: h,
      afterRender: d,
      beforeRender: a,
      beforeDestroy: r,
      itemRender: l,
      itemRenderProps: p,
      level: (f || 0) + 1
    };
  }
  renderNestedMenu(t) {
    let { items: n } = t;
    if (!n || (typeof n == "function" && (n = n(t, this)), !n.length))
      return;
    const i = this.constructor, o = this.getNestedMenuProps(n);
    return /* @__PURE__ */ g(i, { ...o, "data-level": o.level });
  }
  isNestedItem(t) {
    return (!t.type || t.type === "item") && !!t.items;
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  renderToggleIcon(t, n) {
  }
  getItemRenderProps(t, n, i) {
    const o = super.getItemRenderProps(t, n, i);
    if (o.level = t.level || 0, !this.isNestedItem(o))
      return o;
    const r = o.key ?? o.id ?? `${t.level || 0}:${i}`;
    y(this, On).add(r);
    const a = this.isExpanded(r);
    if (a && (o.rootChildren = [
      o.rootChildren,
      this.renderNestedMenu(n)
    ]), this.nestedTrigger === "hover")
      o.rootAttrs = {
        ...o.rootAttrs,
        onMouseEnter: y(this, Ve).bind(this, r, !0),
        onMouseLeave: y(this, Ve).bind(this, r, !1)
      };
    else if (this.nestedTrigger === "click") {
      const { onClick: h } = o;
      o.onClick = (d) => {
        y(this, Ve).call(this, r, void 0, d), h == null || h(d);
      };
    }
    const l = this.renderToggleIcon(a, o);
    return l && (o.children = [o.children, l]), o.show = a, o.rootClass = [o.rootClass, "has-nested-menu", a ? "show" : ""], o;
  }
  isExpanded(t) {
    const n = y(this, yt) ? this.state.nestedShow : this.props.nestedShow;
    return n && typeof n == "object" ? n[t] : !!n;
  }
  toggle(t, n) {
    const { controlledMenu: i } = this.props;
    if (i)
      return i.toggle(t, n);
    if (!y(this, yt))
      return !1;
    let { nestedShow: o = {} } = this.state;
    if (typeof o == "boolean" && (o === !0 ? o = [...y(this, On).values()].reduce((r, a) => (r[a] = !0, r), {}) : o = {}), n === void 0)
      n = !o[t];
    else if (!!o[t] == !!n)
      return !1;
    return n ? o[t] = n : delete o[t], this.setState({ nestedShow: { ...o } }), !0;
  }
  expand(t) {
    return this.toggle(t, !0);
  }
  collapse(t) {
    return this.toggle(t, !1);
  }
  expandAll() {
    y(this, yt) && this.setState({ nestedShow: !0 });
  }
  collapseAll() {
    y(this, yt) && this.setState({ nestedShow: !1 });
  }
}, On = new WeakMap(), yt = new WeakMap(), Ve = new WeakMap(), jn.ItemComponents = {
  item: su
}, jn);
const vi = class vi extends V {
};
vi.NAME = "ActionMenuNested", vi.Component = Qr;
let Oa = vi;
var Hn;
let Ie = (Hn = class extends Qr {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "menu-nested";
  }
  beforeRender() {
    const e = super.beforeRender();
    let { hasIcons: t } = e;
    return t === void 0 && (t = e.items.some((n) => n.icon)), e.className = M(e.className, this.menuName, {
      "has-icons": t,
      "has-nested-items": e.items.some((n) => this.isNestedItem(n)),
      popup: e.popup
    }), e;
  }
  renderToggleIcon(e) {
    return /* @__PURE__ */ g("span", { class: `${this.name}-toggle-icon caret-${e ? "down" : "right"}` });
  }
}, Hn.NAME = "menu", Hn);
const _i = class _i extends V {
};
_i.NAME = "Menu", _i.Component = Ie;
let ja = _i;
class tt extends Zr {
  _beforeRender(e) {
    const { text: t, loading: n, loadingText: i, caret: o, icon: r, trailingIcon: a, children: l } = e;
    this._isEmptyText = t == null || typeof t == "string" && !t.length || n && !i, this._onlyCaret = o && this._isEmptyText && !r && !a && !l && !n;
  }
  _getChildren(e) {
    const { loading: t, loadingIcon: n, loadingText: i, icon: o, text: r, children: a, trailingIcon: l, caret: h } = e;
    return [
      t ? /* @__PURE__ */ g(Z, { icon: n || "icon-spinner-snake", className: "spin" }) : /* @__PURE__ */ g(Z, { icon: o }),
      this._isEmptyText ? null : /* @__PURE__ */ g("span", { className: "text", children: t ? i : r }),
      t ? null : a,
      t ? null : /* @__PURE__ */ g(Z, { icon: l }),
      t ? null : h ? /* @__PURE__ */ g("span", { className: typeof h == "string" ? `caret-${h}` : "caret" }) : null
    ];
  }
  _getClassName(e) {
    const { type: t, className: n, disabled: i, loading: o, active: r, children: a, square: l, size: h, rounded: d } = e;
    return M("btn", t, n, {
      "btn-caret": this._onlyCaret,
      disabled: i || o,
      active: r,
      loading: o,
      square: l === void 0 ? !this._onlyCaret && !a && this._isEmptyText : l
    }, h ? `size-${h}` : "", typeof d == "string" ? d : { rounded: d });
  }
  _getComponent(e) {
    return e.component || (e.url ? "a" : "button");
  }
  _getProps(e) {
    const t = this._getComponent(e), { url: n, target: i, btnType: o = "button", hint: r } = e, a = {
      ...super._getProps(e),
      title: r,
      type: t === "button" ? o : void 0
    };
    return n !== void 0 && (a[t === "a" ? "href" : "data-url"] = n), i !== void 0 && (a[t === "a" ? "target" : "data-target"] = i), a;
  }
}
function iu({
  key: s,
  type: e,
  btnType: t,
  ...n
}) {
  return /* @__PURE__ */ g(tt, { type: t, ...n });
}
const ha = class ha extends tt {
  constructor() {
    super(...arguments), this._ref = G();
  }
  get triggerElement() {
    return this._ref.current;
  }
  _updateData() {
    const { dropdown: e, items: t } = this.props, n = u(this.triggerElement), i = n.zui("dropdown"), o = {
      items: t,
      ...e
    };
    i ? i.setOptions(o) : n.data(o);
  }
  componentDidMount() {
    this._updateData();
  }
  componentDidUpdate() {
    this._updateData();
  }
  componentWillUnmount() {
    const e = u(this.triggerElement).zui("dropdown");
    e && e.destroy();
  }
  _getProps(e) {
    const { trigger: t, placement: n } = e;
    return {
      ...super._getProps(e),
      "data-toggle": "dropdown",
      "data-trigger": t,
      "data-placement": n,
      ref: this._ref
    };
  }
};
ha.defaultProps = {
  caret: !0
};
let oi = ha;
function As(s) {
  return s.split("-")[1];
}
function ta(s) {
  return s === "y" ? "height" : "width";
}
function Ne(s) {
  return s.split("-")[0];
}
function Ls(s) {
  return ["top", "bottom"].includes(Ne(s)) ? "x" : "y";
}
function Ha(s, e, t) {
  let { reference: n, floating: i } = s;
  const o = n.x + n.width / 2 - i.width / 2, r = n.y + n.height / 2 - i.height / 2, a = Ls(e), l = ta(a), h = n[l] / 2 - i[l] / 2, d = a === "x";
  let c;
  switch (Ne(e)) {
    case "top":
      c = { x: o, y: n.y - i.height };
      break;
    case "bottom":
      c = { x: o, y: n.y + n.height };
      break;
    case "right":
      c = { x: n.x + n.width, y: r };
      break;
    case "left":
      c = { x: n.x - i.width, y: r };
      break;
    default:
      c = { x: n.x, y: n.y };
  }
  switch (As(e)) {
    case "start":
      c[a] -= h * (t && d ? -1 : 1);
      break;
    case "end":
      c[a] += h * (t && d ? -1 : 1);
  }
  return c;
}
const ou = async (s, e, t) => {
  const { placement: n = "bottom", strategy: i = "absolute", middleware: o = [], platform: r } = t, a = o.filter(Boolean), l = await (r.isRTL == null ? void 0 : r.isRTL(e));
  let h = await r.getElementRects({ reference: s, floating: e, strategy: i }), { x: d, y: c } = Ha(h, n, l), f = n, p = {}, m = 0;
  for (let b = 0; b < a.length; b++) {
    const { name: _, fn: v } = a[b], { x, y: k, data: N, reset: S } = await v({ x: d, y: c, initialPlacement: n, placement: f, strategy: i, middlewareData: p, rects: h, platform: r, elements: { reference: s, floating: e } });
    d = x ?? d, c = k ?? c, p = { ...p, [_]: { ...p[_], ...N } }, S && m <= 50 && (m++, typeof S == "object" && (S.placement && (f = S.placement), S.rects && (h = S.rects === !0 ? await r.getElementRects({ reference: s, floating: e, strategy: i }) : S.rects), { x: d, y: c } = Ha(h, f, l)), b = -1);
  }
  return { x: d, y: c, placement: f, strategy: i, middlewareData: p };
};
function Os(s, e) {
  return typeof s == "function" ? s(e) : s;
}
function Sc(s) {
  return typeof s != "number" ? function(e) {
    return { top: 0, right: 0, bottom: 0, left: 0, ...e };
  }(s) : { top: s, right: s, bottom: s, left: s };
}
function ri(s) {
  return { ...s, top: s.y, left: s.x, right: s.x + s.width, bottom: s.y + s.height };
}
async function Nc(s, e) {
  var t;
  e === void 0 && (e = {});
  const { x: n, y: i, platform: o, rects: r, elements: a, strategy: l } = s, { boundary: h = "clippingAncestors", rootBoundary: d = "viewport", elementContext: c = "floating", altBoundary: f = !1, padding: p = 0 } = Os(e, s), m = Sc(p), b = a[f ? c === "floating" ? "reference" : "floating" : c], _ = ri(await o.getClippingRect({ element: (t = await (o.isElement == null ? void 0 : o.isElement(b))) == null || t ? b : b.contextElement || await (o.getDocumentElement == null ? void 0 : o.getDocumentElement(a.floating)), boundary: h, rootBoundary: d, strategy: l })), v = c === "floating" ? { ...r.floating, x: n, y: i } : r.reference, x = await (o.getOffsetParent == null ? void 0 : o.getOffsetParent(a.floating)), k = await (o.isElement == null ? void 0 : o.isElement(x)) && await (o.getScale == null ? void 0 : o.getScale(x)) || { x: 1, y: 1 }, N = ri(o.convertOffsetParentRelativeRectToViewportRelativeRect ? await o.convertOffsetParentRelativeRectToViewportRelativeRect({ rect: v, offsetParent: x, strategy: l }) : v);
  return { top: (_.top - N.top + m.top) / k.y, bottom: (N.bottom - _.bottom + m.bottom) / k.y, left: (_.left - N.left + m.left) / k.x, right: (N.right - _.right + m.right) / k.x };
}
const lr = Math.min, ru = Math.max;
function cr(s, e, t) {
  return ru(s, lr(e, t));
}
const hr = (s) => ({ name: "arrow", options: s, async fn(e) {
  const { x: t, y: n, placement: i, rects: o, platform: r, elements: a } = e, { element: l, padding: h = 0 } = Os(s, e) || {};
  if (l == null)
    return {};
  const d = Sc(h), c = { x: t, y: n }, f = Ls(i), p = ta(f), m = await r.getDimensions(l), b = f === "y", _ = b ? "top" : "left", v = b ? "bottom" : "right", x = b ? "clientHeight" : "clientWidth", k = o.reference[p] + o.reference[f] - c[f] - o.floating[p], N = c[f] - o.reference[f], S = await (r.getOffsetParent == null ? void 0 : r.getOffsetParent(l));
  let R = S ? S[x] : 0;
  R && await (r.isElement == null ? void 0 : r.isElement(S)) || (R = a.floating[x] || o.floating[p]);
  const L = k / 2 - N / 2, I = R / 2 - m[p] / 2 - 1, D = lr(d[_], I), A = lr(d[v], I), O = D, E = R - m[p] - A, T = R / 2 - m[p] / 2 + L, z = cr(O, T, E), W = As(i) != null && T != z && o.reference[p] / 2 - (T < O ? D : A) - m[p] / 2 < 0 ? T < O ? O - T : E - T : 0;
  return { [f]: c[f] - W, data: { [f]: z, centerOffset: T - z + W } };
} }), au = ["top", "right", "bottom", "left"];
au.reduce((s, e) => s.concat(e, e + "-start", e + "-end"), []);
const lu = { left: "right", right: "left", bottom: "top", top: "bottom" };
function ai(s) {
  return s.replace(/left|right|bottom|top/g, (e) => lu[e]);
}
function cu(s, e, t) {
  t === void 0 && (t = !1);
  const n = As(s), i = Ls(s), o = ta(i);
  let r = i === "x" ? n === (t ? "end" : "start") ? "right" : "left" : n === "start" ? "bottom" : "top";
  return e.reference[o] > e.floating[o] && (r = ai(r)), { main: r, cross: ai(r) };
}
const hu = { start: "end", end: "start" };
function Yo(s) {
  return s.replace(/start|end/g, (e) => hu[e]);
}
const jo = function(s) {
  return s === void 0 && (s = {}), { name: "flip", options: s, async fn(e) {
    var t;
    const { placement: n, middlewareData: i, rects: o, initialPlacement: r, platform: a, elements: l } = e, { mainAxis: h = !0, crossAxis: d = !0, fallbackPlacements: c, fallbackStrategy: f = "bestFit", fallbackAxisSideDirection: p = "none", flipAlignment: m = !0, ...b } = Os(s, e), _ = Ne(n), v = Ne(r) === r, x = await (a.isRTL == null ? void 0 : a.isRTL(l.floating)), k = c || (v || !m ? [ai(r)] : function(O) {
      const E = ai(O);
      return [Yo(O), E, Yo(E)];
    }(r));
    c || p === "none" || k.push(...function(O, E, T, z) {
      const W = As(O);
      let Y = function(dt, $n, Uh) {
        const wa = ["left", "right"], va = ["right", "left"], qh = ["top", "bottom"], Gh = ["bottom", "top"];
        switch (dt) {
          case "top":
          case "bottom":
            return Uh ? $n ? va : wa : $n ? wa : va;
          case "left":
          case "right":
            return $n ? qh : Gh;
          default:
            return [];
        }
      }(Ne(O), T === "start", z);
      return W && (Y = Y.map((dt) => dt + "-" + W), E && (Y = Y.concat(Y.map(Yo)))), Y;
    }(r, m, p, x));
    const N = [r, ...k], S = await Nc(e, b), R = [];
    let L = ((t = i.flip) == null ? void 0 : t.overflows) || [];
    if (h && R.push(S[_]), d) {
      const { main: O, cross: E } = cu(n, o, x);
      R.push(S[O], S[E]);
    }
    if (L = [...L, { placement: n, overflows: R }], !R.every((O) => O <= 0)) {
      var I, D;
      const O = (((I = i.flip) == null ? void 0 : I.index) || 0) + 1, E = N[O];
      if (E)
        return { data: { index: O, overflows: L }, reset: { placement: E } };
      let T = (D = L.filter((z) => z.overflows[0] <= 0).sort((z, W) => z.overflows[1] - W.overflows[1])[0]) == null ? void 0 : D.placement;
      if (!T)
        switch (f) {
          case "bestFit": {
            var A;
            const z = (A = L.map((W) => [W.placement, W.overflows.filter((Y) => Y > 0).reduce((Y, dt) => Y + dt, 0)]).sort((W, Y) => W[1] - Y[1])[0]) == null ? void 0 : A[0];
            z && (T = z);
            break;
          }
          case "initialPlacement":
            T = r;
        }
      if (n !== T)
        return { reset: { placement: T } };
    }
    return {};
  } };
}, Ho = function(s) {
  return s === void 0 && (s = 0), { name: "offset", options: s, async fn(e) {
    const { x: t, y: n } = e, i = await async function(o, r) {
      const { placement: a, platform: l, elements: h } = o, d = await (l.isRTL == null ? void 0 : l.isRTL(h.floating)), c = Ne(a), f = As(a), p = Ls(a) === "x", m = ["left", "top"].includes(c) ? -1 : 1, b = d && p ? -1 : 1, _ = Os(r, o);
      let { mainAxis: v, crossAxis: x, alignmentAxis: k } = typeof _ == "number" ? { mainAxis: _, crossAxis: 0, alignmentAxis: null } : { mainAxis: 0, crossAxis: 0, alignmentAxis: null, ..._ };
      return f && typeof k == "number" && (x = f === "end" ? -1 * k : k), p ? { x: x * b, y: v * m } : { x: v * m, y: x * b };
    }(e, s);
    return { x: t + i.x, y: n + i.y, data: i };
  } };
};
function du(s) {
  return s === "x" ? "y" : "x";
}
const In = function(s) {
  return s === void 0 && (s = {}), { name: "shift", options: s, async fn(e) {
    const { x: t, y: n, placement: i } = e, { mainAxis: o = !0, crossAxis: r = !1, limiter: a = { fn: (_) => {
      let { x: v, y: x } = _;
      return { x: v, y: x };
    } }, ...l } = Os(s, e), h = { x: t, y: n }, d = await Nc(e, l), c = Ls(Ne(i)), f = du(c);
    let p = h[c], m = h[f];
    if (o) {
      const _ = c === "y" ? "bottom" : "right";
      p = cr(p + d[c === "y" ? "top" : "left"], p, p - d[_]);
    }
    if (r) {
      const _ = f === "y" ? "bottom" : "right";
      m = cr(m + d[f === "y" ? "top" : "left"], m, m - d[_]);
    }
    const b = a.fn({ ...e, [c]: p, [f]: m });
    return { ...b, data: { x: b.x - t, y: b.y - n } };
  } };
};
function lt(s) {
  var e;
  return (s == null || (e = s.ownerDocument) == null ? void 0 : e.defaultView) || window;
}
function Et(s) {
  return lt(s).getComputedStyle(s);
}
function Ec(s) {
  return s instanceof lt(s).Node;
}
function le(s) {
  return Ec(s) ? (s.nodeName || "").toLowerCase() : "#document";
}
function pt(s) {
  return s instanceof HTMLElement || s instanceof lt(s).HTMLElement;
}
function za(s) {
  return typeof ShadowRoot < "u" && (s instanceof lt(s).ShadowRoot || s instanceof ShadowRoot);
}
function Dn(s) {
  const { overflow: e, overflowX: t, overflowY: n, display: i } = Et(s);
  return /auto|scroll|overlay|hidden|clip/.test(e + n + t) && !["inline", "contents"].includes(i);
}
function uu(s) {
  return ["table", "td", "th"].includes(le(s));
}
function dr(s) {
  const e = ea(), t = Et(s);
  return t.transform !== "none" || t.perspective !== "none" || !!t.containerType && t.containerType !== "normal" || !e && !!t.backdropFilter && t.backdropFilter !== "none" || !e && !!t.filter && t.filter !== "none" || ["transform", "perspective", "filter"].some((n) => (t.willChange || "").includes(n)) || ["paint", "layout", "strict", "content"].some((n) => (t.contain || "").includes(n));
}
function ea() {
  return !(typeof CSS > "u" || !CSS.supports) && CSS.supports("-webkit-backdrop-filter", "none");
}
function zo(s) {
  return ["html", "body", "#document"].includes(le(s));
}
const ur = Math.min, Fe = Math.max, li = Math.round, Hs = Math.floor, ce = (s) => ({ x: s, y: s });
function Mc(s) {
  const e = Et(s);
  let t = parseFloat(e.width) || 0, n = parseFloat(e.height) || 0;
  const i = pt(s), o = i ? s.offsetWidth : t, r = i ? s.offsetHeight : n, a = li(t) !== o || li(n) !== r;
  return a && (t = o, n = r), { width: t, height: n, $: a };
}
function Ht(s) {
  return s instanceof Element || s instanceof lt(s).Element;
}
function na(s) {
  return Ht(s) ? s : s.contextElement;
}
function We(s) {
  const e = na(s);
  if (!pt(e))
    return ce(1);
  const t = e.getBoundingClientRect(), { width: n, height: i, $: o } = Mc(e);
  let r = (o ? li(t.width) : t.width) / n, a = (o ? li(t.height) : t.height) / i;
  return r && Number.isFinite(r) || (r = 1), a && Number.isFinite(a) || (a = 1), { x: r, y: a };
}
const fu = ce(0);
function Pc(s) {
  const e = lt(s);
  return ea() && e.visualViewport ? { x: e.visualViewport.offsetLeft, y: e.visualViewport.offsetTop } : fu;
}
function De(s, e, t, n) {
  e === void 0 && (e = !1), t === void 0 && (t = !1);
  const i = s.getBoundingClientRect(), o = na(s);
  let r = ce(1);
  e && (n ? Ht(n) && (r = We(n)) : r = We(s));
  const a = function(f, p, m) {
    return p === void 0 && (p = !1), !(!m || p && m !== lt(f)) && p;
  }(o, t, n) ? Pc(o) : ce(0);
  let l = (i.left + a.x) / r.x, h = (i.top + a.y) / r.y, d = i.width / r.x, c = i.height / r.y;
  if (o) {
    const f = lt(o), p = n && Ht(n) ? lt(n) : n;
    let m = f.frameElement;
    for (; m && n && p !== f; ) {
      const b = We(m), _ = m.getBoundingClientRect(), v = getComputedStyle(m), x = _.left + (m.clientLeft + parseFloat(v.paddingLeft)) * b.x, k = _.top + (m.clientTop + parseFloat(v.paddingTop)) * b.y;
      l *= b.x, h *= b.y, d *= b.x, c *= b.y, l += x, h += k, m = lt(m).frameElement;
    }
  }
  return ri({ width: d, height: c, x: l, y: h });
}
function Bo(s) {
  return Ht(s) ? { scrollLeft: s.scrollLeft, scrollTop: s.scrollTop } : { scrollLeft: s.pageXOffset, scrollTop: s.pageYOffset };
}
function zt(s) {
  var e;
  return (e = (Ec(s) ? s.ownerDocument : s.document) || window.document) == null ? void 0 : e.documentElement;
}
function Rc(s) {
  return De(zt(s)).left + Bo(s).scrollLeft;
}
function bn(s) {
  if (le(s) === "html")
    return s;
  const e = s.assignedSlot || s.parentNode || za(s) && s.host || zt(s);
  return za(e) ? e.host : e;
}
function Ic(s) {
  const e = bn(s);
  return zo(e) ? s.ownerDocument ? s.ownerDocument.body : s.body : pt(e) && Dn(e) ? e : Ic(e);
}
function ci(s, e) {
  var t;
  e === void 0 && (e = []);
  const n = Ic(s), i = n === ((t = s.ownerDocument) == null ? void 0 : t.body), o = lt(n);
  return i ? e.concat(o, o.visualViewport || [], Dn(n) ? n : []) : e.concat(n, ci(n));
}
function Ba(s, e, t) {
  let n;
  if (e === "viewport")
    n = function(i, o) {
      const r = lt(i), a = zt(i), l = r.visualViewport;
      let h = a.clientWidth, d = a.clientHeight, c = 0, f = 0;
      if (l) {
        h = l.width, d = l.height;
        const p = ea();
        (!p || p && o === "fixed") && (c = l.offsetLeft, f = l.offsetTop);
      }
      return { width: h, height: d, x: c, y: f };
    }(s, t);
  else if (e === "document")
    n = function(i) {
      const o = zt(i), r = Bo(i), a = i.ownerDocument.body, l = Fe(o.scrollWidth, o.clientWidth, a.scrollWidth, a.clientWidth), h = Fe(o.scrollHeight, o.clientHeight, a.scrollHeight, a.clientHeight);
      let d = -r.scrollLeft + Rc(i);
      const c = -r.scrollTop;
      return Et(a).direction === "rtl" && (d += Fe(o.clientWidth, a.clientWidth) - l), { width: l, height: h, x: d, y: c };
    }(zt(s));
  else if (Ht(e))
    n = function(i, o) {
      const r = De(i, !0, o === "fixed"), a = r.top + i.clientTop, l = r.left + i.clientLeft, h = pt(i) ? We(i) : ce(1);
      return { width: i.clientWidth * h.x, height: i.clientHeight * h.y, x: l * h.x, y: a * h.y };
    }(e, t);
  else {
    const i = Pc(s);
    n = { ...e, x: e.x - i.x, y: e.y - i.y };
  }
  return ri(n);
}
function Dc(s, e) {
  const t = bn(s);
  return !(t === e || !Ht(t) || zo(t)) && (Et(t).position === "fixed" || Dc(t, e));
}
function pu(s, e, t) {
  const n = pt(e), i = zt(e), o = t === "fixed", r = De(s, !0, o, e);
  let a = { scrollLeft: 0, scrollTop: 0 };
  const l = ce(0);
  if (n || !n && !o)
    if ((le(e) !== "body" || Dn(i)) && (a = Bo(e)), pt(e)) {
      const h = De(e, !0, o, e);
      l.x = h.x + e.clientLeft, l.y = h.y + e.clientTop;
    } else
      i && (l.x = Rc(i));
  return { x: r.left + a.scrollLeft - l.x, y: r.top + a.scrollTop - l.y, width: r.width, height: r.height };
}
function Fa(s, e) {
  return pt(s) && Et(s).position !== "fixed" ? e ? e(s) : s.offsetParent : null;
}
function Wa(s, e) {
  const t = lt(s);
  if (!pt(s))
    return t;
  let n = Fa(s, e);
  for (; n && uu(n) && Et(n).position === "static"; )
    n = Fa(n, e);
  return n && (le(n) === "html" || le(n) === "body" && Et(n).position === "static" && !dr(n)) ? t : n || function(i) {
    let o = bn(i);
    for (; pt(o) && !zo(o); ) {
      if (dr(o))
        return o;
      o = bn(o);
    }
    return null;
  }(s) || t;
}
const mu = { convertOffsetParentRelativeRectToViewportRelativeRect: function(s) {
  let { rect: e, offsetParent: t, strategy: n } = s;
  const i = pt(t), o = zt(t);
  if (t === o)
    return e;
  let r = { scrollLeft: 0, scrollTop: 0 }, a = ce(1);
  const l = ce(0);
  if ((i || !i && n !== "fixed") && ((le(t) !== "body" || Dn(o)) && (r = Bo(t)), pt(t))) {
    const h = De(t);
    a = We(t), l.x = h.x + t.clientLeft, l.y = h.y + t.clientTop;
  }
  return { width: e.width * a.x, height: e.height * a.y, x: e.x * a.x - r.scrollLeft * a.x + l.x, y: e.y * a.y - r.scrollTop * a.y + l.y };
}, getDocumentElement: zt, getClippingRect: function(s) {
  let { element: e, boundary: t, rootBoundary: n, strategy: i } = s;
  const o = [...t === "clippingAncestors" ? function(l, h) {
    const d = h.get(l);
    if (d)
      return d;
    let c = ci(l).filter((b) => Ht(b) && le(b) !== "body"), f = null;
    const p = Et(l).position === "fixed";
    let m = p ? bn(l) : l;
    for (; Ht(m) && !zo(m); ) {
      const b = Et(m), _ = dr(m);
      _ || b.position !== "fixed" || (f = null), (p ? !_ && !f : !_ && b.position === "static" && f && ["absolute", "fixed"].includes(f.position) || Dn(m) && !_ && Dc(l, m)) ? c = c.filter((v) => v !== m) : f = b, m = bn(m);
    }
    return h.set(l, c), c;
  }(e, this._c) : [].concat(t), n], r = o[0], a = o.reduce((l, h) => {
    const d = Ba(e, h, i);
    return l.top = Fe(d.top, l.top), l.right = ur(d.right, l.right), l.bottom = ur(d.bottom, l.bottom), l.left = Fe(d.left, l.left), l;
  }, Ba(e, r, i));
  return { width: a.right - a.left, height: a.bottom - a.top, x: a.left, y: a.top };
}, getOffsetParent: Wa, getElementRects: async function(s) {
  let { reference: e, floating: t, strategy: n } = s;
  const i = this.getOffsetParent || Wa, o = this.getDimensions;
  return { reference: pu(e, await i(t), n), floating: { x: 0, y: 0, ...await o(t) } };
}, getClientRects: function(s) {
  return Array.from(s.getClientRects());
}, getDimensions: function(s) {
  return Mc(s);
}, getScale: We, isElement: Ht, isRTL: function(s) {
  return getComputedStyle(s).direction === "rtl";
} };
function sa(s, e, t, n) {
  n === void 0 && (n = {});
  const { ancestorScroll: i = !0, ancestorResize: o = !0, elementResize: r = typeof ResizeObserver == "function", layoutShift: a = typeof IntersectionObserver == "function", animationFrame: l = !1 } = n, h = na(s), d = i || o ? [...h ? ci(h) : [], ...ci(e)] : [];
  d.forEach((_) => {
    i && _.addEventListener("scroll", t, { passive: !0 }), o && _.addEventListener("resize", t);
  });
  const c = h && a ? function(_, v) {
    let x, k = null;
    const N = zt(_);
    function S() {
      clearTimeout(x), k && k.disconnect(), k = null;
    }
    return function R(L, I) {
      L === void 0 && (L = !1), I === void 0 && (I = 1), S();
      const { left: D, top: A, width: O, height: E } = _.getBoundingClientRect();
      if (L || v(), !O || !E)
        return;
      const T = { rootMargin: -Hs(A) + "px " + -Hs(N.clientWidth - (D + O)) + "px " + -Hs(N.clientHeight - (A + E)) + "px " + -Hs(D) + "px", threshold: Fe(0, ur(1, I)) || 1 };
      let z = !0;
      function W(Y) {
        const dt = Y[0].intersectionRatio;
        if (dt !== I) {
          if (!z)
            return R();
          dt ? R(!1, dt) : x = setTimeout(() => {
            R(!1, 1e-7);
          }, 100);
        }
        z = !1;
      }
      try {
        k = new IntersectionObserver(W, { ...T, root: N.ownerDocument });
      } catch {
        k = new IntersectionObserver(W, T);
      }
      k.observe(_);
    }(!0), S;
  }(h, t) : null;
  let f, p = -1, m = null;
  r && (m = new ResizeObserver((_) => {
    let [v] = _;
    v && v.target === h && m && (m.unobserve(e), cancelAnimationFrame(p), p = requestAnimationFrame(() => {
      m && m.observe(e);
    })), t();
  }), h && !l && m.observe(h), m.observe(e));
  let b = l ? De(s) : null;
  return l && function _() {
    const v = De(s);
    !b || v.x === b.x && v.y === b.y && v.width === b.width && v.height === b.height || t(), b = v, f = requestAnimationFrame(_);
  }(), t(), () => {
    d.forEach((_) => {
      i && _.removeEventListener("scroll", t), o && _.removeEventListener("resize", t);
    }), c && c(), m && m.disconnect(), m = null, l && cancelAnimationFrame(f);
  };
}
const Fo = (s, e, t) => {
  const n = /* @__PURE__ */ new Map(), i = { platform: mu, ...t }, o = { ...i.platform, _c: n };
  return ou(s, e, { ...i, platform: o });
}, da = class da extends Ie {
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
    !e || !t || Fo(t, e, {
      placement: this.props.placement,
      middleware: [jo(), In(), Ho(1)]
    }).then(({ x: n, y: i }) => {
      u(e).css({
        left: n,
        top: i
      });
    });
  }
  getNestedMenuProps(e) {
    const t = super.getNestedMenuProps(e);
    return {
      ...t,
      className: M("show", t.className),
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
da.defaultProps = {
  ...Ie.defaultProps,
  popup: !0,
  nestedTrigger: "hover",
  placement: "right-start"
};
let fr = da;
function Ac({
  key: s,
  type: e,
  btnType: t,
  ...n
}) {
  return /* @__PURE__ */ g(oi, { type: t, ...n });
}
let Lc = class extends F {
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
  handleItemClick(e, t, n, i) {
    n && n.call(i.target, i);
    const { onClickItem: o } = this.props;
    o && o.call(this, { item: e, index: t, event: i });
  }
  beforeRender() {
    var n;
    const e = { ...this.props }, t = (n = e.beforeRender) == null ? void 0 : n.call(this, e);
    return t && Object.assign(e, t), typeof e.items == "function" && (e.items = e.items.call(this)), e;
  }
  onRenderItem(e, t) {
    const { key: n = t, ...i } = e, o = e.dropdown || e.items ? oi : tt;
    return /* @__PURE__ */ g(o, { ...i }, n);
  }
  renderItem(e, t, n) {
    const { itemRender: i, btnProps: o, onClickItem: r } = e, a = { key: n, ...t };
    if (o && Object.assign(a, o), r && (a.onClick = this.handleItemClick.bind(this, a, n, t.onClick)), i) {
      const l = i.call(this, a, w);
      if (et(l))
        return l;
      typeof l == "object" && Object.assign(a, l);
    }
    return this.onRenderItem(a, n);
  }
  render() {
    const e = this.beforeRender(), {
      className: t,
      items: n,
      size: i,
      type: o,
      btnProps: r,
      children: a,
      itemRender: l,
      onClickItem: h,
      beforeRender: d,
      afterRender: c,
      beforeDestroy: f,
      ...p
    } = e;
    return /* @__PURE__ */ g(
      "div",
      {
        className: M("btn-group", i ? `size-${i}` : "", t),
        ...p,
        children: [
          n && n.map(this.renderItem.bind(this, e)),
          a
        ]
      }
    );
  }
};
function gu({
  key: s,
  type: e,
  btnType: t,
  ...n
}) {
  return /* @__PURE__ */ g(Lc, { type: t, ...n });
}
var re;
let Mt = (re = class extends Oo {
  beforeRender() {
    const { gap: e, btnProps: t, wrap: n, ...i } = super.beforeRender();
    return i.className = M(i.className, n ? "flex-wrap" : "", typeof e == "number" ? `gap-${e}` : ""), typeof e == "string" && (i.style ? i.style.gap = e : i.style = { gap: e }), i;
  }
  isBtnItem(e) {
    return e === "item" || e === "dropdown";
  }
  renderTypedItem(e, t, n) {
    const { type: i } = n, o = this.props.btnProps, r = this.isBtnItem(i) ? { btnType: "ghost", ...o } : {}, a = {
      ...t,
      ...r,
      ...n,
      className: M(`${this.name}-${i}`, t.className, r.className, n.className),
      style: Object.assign({}, t.style, r.style, n.style)
    };
    return i === "btn-group" && (a.btnProps = o), /* @__PURE__ */ g(e, { ...a });
  }
}, re.ItemComponents = {
  item: iu,
  dropdown: Ac,
  "btn-group": gu
}, re.ROOT_TAG = "nav", re.NAME = "toolbar", re.defaultProps = {
  btnProps: {
    btnType: "ghost"
  }
}, re);
function yu({
  className: s,
  style: e,
  actions: t,
  heading: n,
  content: i,
  contentClass: o,
  children: r,
  close: a,
  onClose: l,
  icon: h,
  ...d
}) {
  let c;
  a === !0 ? c = /* @__PURE__ */ g(tt, { className: "alert-close btn ghost square text-inherit", square: !0, onClick: l, children: /* @__PURE__ */ g("span", { class: "close" }) }) : et(a) ? c = a : typeof a == "object" && (c = /* @__PURE__ */ g(tt, { ...a, onClick: l }));
  const f = et(t) ? t : t ? /* @__PURE__ */ g(Mt, { ...t }) : null;
  return /* @__PURE__ */ g("div", { className: M("alert", s), style: e, ...d, children: [
    /* @__PURE__ */ g(Z, { icon: h, className: "alert-icon" }),
    et(i) ? i : /* @__PURE__ */ g("div", { className: M("alert-content", o), children: [
      et(n) ? n : n && /* @__PURE__ */ g("div", { className: "alert-heading", children: n }),
      /* @__PURE__ */ g("div", { className: "alert-text", children: i }),
      n ? f : null
    ] }),
    n ? null : f,
    c,
    r
  ] });
}
function bu(s) {
  if (s === "center")
    return "fade-from-center";
  if (s) {
    if (s.includes("top"))
      return "fade-from-top";
    if (s.includes("bottom"))
      return "fade-from-bottom";
  }
  return "fade";
}
function wu({
  margin: s,
  type: e,
  placement: t,
  animation: n,
  show: i,
  className: o,
  time: r,
  ...a
}) {
  return /* @__PURE__ */ g(
    yu,
    {
      className: M("messager", o, e, n === !0 ? bu(t) : n, i ? "in" : ""),
      ...a
    }
  );
}
var Jt, He;
const xi = class xi extends V {
  constructor() {
    super(...arguments);
    C(this, Jt);
    this._show = !1, this._showTimer = 0, this._afterRender = ({ firstRender: t }) => {
      t && this.show();
      const { margin: n } = this.options;
      n && this.$element.css("margin", `${n}px`);
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
    this.render(), this.emit("show"), j(this, Jt, He).call(this, () => {
      this._show = !0, this.render(), j(this, Jt, He).call(this, () => {
        this.emit("shown");
        const { time: t } = this.options;
        t && j(this, Jt, He).call(this, () => this.hide(), t);
      });
    }, 100);
  }
  hide() {
    this._show && j(this, Jt, He).call(this, () => {
      this.emit("hide"), this._show = !1, this.render(), j(this, Jt, He).call(this, () => {
        this.emit("hidden");
      });
    }, 50);
  }
};
Jt = new WeakSet(), He = function(t, n = 200) {
  this._showTimer && clearTimeout(this._showTimer), this._showTimer = window.setTimeout(() => {
    t(), this._showTimer = 0;
  }, n);
}, xi.NAME = "MessagerItem", xi.Component = wu;
let pr = xi;
var me, bt, Ci, Oc, $i, jc;
const Mn = class Mn extends ot {
  constructor() {
    super(...arguments);
    C(this, Ci);
    C(this, $i);
    C(this, me, void 0);
    C(this, bt, void 0);
  }
  get isShown() {
    var t;
    return !!((t = y(this, bt)) != null && t.isShown);
  }
  show(t) {
    this.setOptions(t), j(this, Ci, Oc).call(this).show();
  }
  hide() {
    var t;
    (t = y(this, bt)) == null || t.hide();
  }
  static show(t) {
    typeof t == "string" && (t = { content: t });
    const { container: n, ...i } = t, o = Mn.ensure(n || "body");
    return o.setOptions(i), o.hide(), o.show(), o;
  }
};
me = new WeakMap(), bt = new WeakMap(), Ci = new WeakSet(), Oc = function() {
  if (y(this, bt))
    y(this, bt).setOptions(this.options);
  else {
    const t = j(this, $i, jc).call(this), n = new pr(t, this.options);
    n.on("hidden", () => {
      n.destroy(), t == null || t.remove(), $(this, me, void 0), $(this, bt, void 0);
    }), $(this, bt, n);
  }
  return y(this, bt);
}, $i = new WeakSet(), jc = function() {
  if (y(this, me))
    return y(this, me);
  const { placement: t = "top" } = this.options;
  let n = this.$element.find(`.messagers-${t}`);
  n.length || (n = u(`<div class="messagers messagers-${t}"></div>`).appendTo(this.$element));
  let i = n.find(`#messager-${this.gid}`);
  return i.length || (i = u(`<div class="messager-holder" id="messager-${this.gid}"></div>`).appendTo(n), $(this, me, i[0])), i[0];
}, Mn.NAME = "messager", Mn.DEFAULT = {
  placement: "top",
  animation: !0,
  close: !0,
  margin: 6,
  time: 5e3
};
let hi = Mn;
var zn;
let vu = (zn = class extends F {
  render(e) {
    const { percent: t = 50, size: n = 24, circleBg: i, circleColor: o, text: r, className: a, textStyle: l, textX: h, textY: d } = e, c = n / 2;
    let { circleWidth: f = 0.2 } = e;
    f < 1 && (f = n * f);
    const p = (n - f) / 2;
    return /* @__PURE__ */ g("svg", { className: a, width: n, height: n, children: [
      /* @__PURE__ */ g("circle", { cx: c, cy: c, r: p, "stroke-width": f, stroke: i, fill: "transparent" }),
      /* @__PURE__ */ g("circle", { cx: c, cy: c, r: p, "stroke-width": f, stroke: o, fill: "transparent", "stroke-linecap": "round", "stroke-dasharray": Math.PI * p * 2, "stroke-dashoffset": Math.PI * p * 2 * (100 - t) / 100, style: { transformOrigin: "center", transform: "rotate(-90deg)" } }),
      r ? /* @__PURE__ */ g("text", { x: h ?? c, y: d ?? c + f / 2, "dominant-baseline": "middle", "text-anchor": "middle", style: l || { fontSize: `${p}px` }, children: r === !0 ? Math.round(t) : r }) : null
    ] });
  }
}, zn.defaultProps = {
  percent: 50,
  size: 24,
  circleWidth: 0.1,
  circleBg: "var(--color-surface)",
  circleColor: "var(--color-primary-500)",
  text: !0
}, zn);
const ki = class ki extends V {
};
ki.NAME = "ProgressCircle", ki.Component = vu;
let Va = ki;
var Rt;
class _u {
  constructor(e = "") {
    C(this, Rt, void 0);
    typeof e == "object" ? $(this, Rt, e) : $(this, Rt, document.appendChild(document.createComment(e)));
  }
  on(e, t, n) {
    y(this, Rt).addEventListener(e, t, n);
  }
  once(e, t, n) {
    y(this, Rt).addEventListener(e, t, { once: !0, ...n });
  }
  off(e, t, n) {
    y(this, Rt).removeEventListener(e, t, n);
  }
  emit(e) {
    return y(this, Rt).dispatchEvent(e), e;
  }
}
Rt = new WeakMap();
const Ua = /* @__PURE__ */ new Set([
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
class ia extends _u {
  on(e, t, n) {
    super.on(e, t, n);
  }
  off(e, t, n) {
    super.off(e, t, n);
  }
  once(e, t, n) {
    super.once(e, t, n);
  }
  emit(e, t) {
    return typeof e == "string" && (Ua.has(e) ? (e = new Event(e), Object.assign(e, { detail: t })) : e = new CustomEvent(e, { detail: t })), super.emit(ia.createEvent(e, t));
  }
  static createEvent(e, t) {
    return typeof e == "string" && (Ua.has(e) ? (e = new Event(e), Object.assign(e, { detail: t })) : e = new CustomEvent(e, { detail: t })), e;
  }
}
let Hc = (s = 21) => crypto.getRandomValues(new Uint8Array(s)).reduce((e, t) => (t &= 63, t < 36 ? e += t.toString(36) : t < 62 ? e += (t - 26).toString(36).toUpperCase() : t > 62 ? e += "-" : e += "_", e), "");
const Xo = "```ZUI_STR\n";
var Bn, ge, Ue, wt, qe, Ge, qs;
const ua = class ua {
  /**
   * Create new store instance
   * @param name Name of store
   * @param type Store type
   */
  constructor(e, t = "local") {
    C(this, Ge);
    C(this, Bn, void 0);
    C(this, ge, void 0);
    C(this, Ue, void 0);
    C(this, wt, void 0);
    C(this, qe, void 0);
    $(this, Bn, t), $(this, Ue, e ?? Hc()), $(this, ge, `ZUI_STORE:${y(this, Ue)}`), $(this, wt, t === "local" ? localStorage : sessionStorage);
  }
  /**
   * Get store type
   */
  get type() {
    return y(this, Bn);
  }
  /**
   * Get session type store instance
   */
  get session() {
    return this.type === "session" ? this : (y(this, qe) || $(this, qe, new ua(y(this, Ue), "session")), y(this, qe));
  }
  /**
   * Get value from store.
   *
   * @param key          Key to get.
   * @param defaultValue Default value to return if key is not found.
   * @returns Value of key or defaultValue if key is not found.
   */
  get(e, t) {
    const n = y(this, wt).getItem(j(this, Ge, qs).call(this, e));
    if (typeof n == "string") {
      if (n.startsWith(Xo))
        return n.substring(Xo.length);
      try {
        return JSON.parse(n);
      } catch {
      }
    }
    return n ?? t;
  }
  /**
   * Set key-value pair in store
   * @param key Key to set
   * @param value Value to set
   */
  set(e, t) {
    if (t == null)
      return this.remove(e);
    y(this, wt).setItem(j(this, Ge, qs).call(this, e), typeof t == "string" ? `${Xo}${t}` : JSON.stringify(t));
  }
  /**
   * Remove key-value pair from store
   * @param key Key to remove
   */
  remove(e) {
    y(this, wt).removeItem(j(this, Ge, qs).call(this, e));
  }
  /**
   * Iterate all key-value pairs in store
   * @param callback Callback function to call for each key-value pair in the store
   */
  each(e) {
    for (let t = 0; t < y(this, wt).length; t++) {
      const n = y(this, wt).key(t);
      if (n != null && n.startsWith(y(this, ge))) {
        const i = y(this, wt).getItem(n);
        typeof i == "string" && e(n.substring(y(this, ge).length + 1), JSON.parse(i));
      }
    }
  }
  /**
   * Get all key values in store
   * @returns All key-value pairs in the store
   */
  getAll() {
    const e = {};
    return this.each((t, n) => {
      e[t] = n;
    }), e;
  }
};
Bn = new WeakMap(), ge = new WeakMap(), Ue = new WeakMap(), wt = new WeakMap(), qe = new WeakMap(), Ge = new WeakSet(), qs = function(e) {
  return `${y(this, ge)}:${e}`;
};
let di = ua;
const pe = new di("DEFAULT");
function xu(s, e = "local") {
  return new di(s, e);
}
Object.assign(pe, { create: xu });
const B = u, oa = window.document;
let zs, qt;
const Cu = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, $u = /^(?:text|application)\/javascript/i, ku = /^(?:text|application)\/xml/i, zc = "application/json", Bc = "text/html", Tu = /^\s*$/, mr = oa.createElement("a");
mr.href = window.location.href;
function Su(s, e, t) {
  const n = new CustomEvent(e, { detail: t });
  return B(s).trigger(n, t), !n.defaultPrevented;
}
function Ae(s, e, t, n) {
  if (s.global)
    return Su(e || oa, t, n);
}
B.active = 0;
function Nu(s) {
  s.global && B.active++ === 0 && Ae(s, null, "ajaxStart");
}
function Eu(s) {
  s.global && !--B.active && Ae(s, null, "ajaxStop");
}
function Mu(s, e) {
  const t = e.context;
  if (e.beforeSend.call(t, s, e) === !1 || Ae(e, t, "ajaxBeforeSend", [s, e]) === !1)
    return !1;
  Ae(e, t, "ajaxSend", [s, e]);
}
function Pu(s, e, t) {
  const n = t.context, i = "success";
  t.success.call(n, s, i, e), Ae(t, n, "ajaxSuccess", [e, t, s]), Fc(i, e, t);
}
function Bs(s, e, t, n) {
  const i = n.context;
  n.error.call(i, t, e, s), Ae(n, i, "ajaxError", [t, n, s || e]), Fc(e, t, n);
}
function Fc(s, e, t) {
  const n = t.context;
  t.complete.call(n, e, s), Ae(t, n, "ajaxComplete", [e, t]), Eu(t);
}
function Ru(s, e, t) {
  if (t.dataFilter == Xt)
    return s;
  const n = t.context;
  return t.dataFilter.call(n, s, e);
}
function Xt() {
}
B.ajaxSettings = {
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
    json: zc,
    xml: "application/xml, text/xml",
    html: Bc,
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
function Iu(s) {
  return s && (s = s.split(";", 2)[0]), s && (s == Bc ? "html" : s == zc ? "json" : $u.test(s) ? "script" : ku.test(s) && "xml") || "text";
}
function Wc(s, e) {
  return e == "" ? s : (s + "&" + e).replace(/[&?]{1,2}/, "?");
}
function Du(s) {
  s.processData && s.data && typeof s.data != "string" && (s.data = B.param(s.data, s.traditional)), s.data && (!s.type || s.type.toUpperCase() == "GET" || s.dataType == "jsonp") && (s.url = Wc(s.url, s.data), s.data = void 0);
}
B.ajax = function(s) {
  var b;
  const e = B.extend({}, s || {});
  let t, n;
  for (zs in B.ajaxSettings)
    e[zs] === void 0 && (e[zs] = B.ajaxSettings[zs]);
  Nu(e), e.crossDomain || (t = oa.createElement("a"), t.href = e.url, t.href = t.href, e.crossDomain = mr.protocol + "//" + mr.host != t.protocol + "//" + t.host);
  const i = e.type.toUpperCase() === "GET";
  if (e.url || (e.url = window.location.toString()), (n = e.url.indexOf("#")) > -1 && (e.url = e.url.slice(0, n)), i)
    Du(e);
  else if (e.contentType === void 0) {
    if (B.isPlainObject(e.data)) {
      const _ = new FormData();
      B.each(e.data, function(v, x) {
        _.append(v, `${x}`);
      }), e.data = _;
    }
    e.data instanceof FormData && (e.contentType = !1);
  }
  let o = e.dataType;
  /\?.+=\?/.test(e.url) && (o = "jsonp"), (e.cache === !1 || (!s || s.cache !== !0) && (o == "script" || o == "jsonp")) && (e.url = Wc(e.url, "_=" + Date.now()));
  let a = e.accepts[o];
  const l = {}, h = function(_, v) {
    l[_.toLowerCase()] = [_, v];
  }, d = /^([\w-]+:)\/\//.test(e.url) ? RegExp.$1 : window.location.protocol, c = e.xhr(), f = c.setRequestHeader;
  let p;
  if (e.crossDomain || h("X-Requested-With", "XMLHttpRequest"), h("Accept", a || "*/*"), a = e.mimeType, a && (a.indexOf(",") > -1 && (a = a.split(",", 2)[0]), (b = c.overrideMimeType) == null || b.call(c, a)), (e.contentType || e.contentType !== !1 && e.data && !i) && h("Content-Type", e.contentType || "application/x-www-form-urlencoded"), e.headers)
    for (qt in e.headers)
      h(qt, e.headers[qt]);
  if (c.setRequestHeader = h, c.onreadystatechange = function() {
    if (c.readyState == 4) {
      c.onreadystatechange = Xt, clearTimeout(p);
      let _, v = !1;
      if (c.status >= 200 && c.status < 300 || c.status == 304 || c.status == 0 && d == "file:") {
        if (o = o || Iu(e.mimeType || c.getResponseHeader("content-type")), c.responseType == "arraybuffer" || c.responseType == "blob")
          _ = c.response;
        else {
          _ = c.responseText;
          try {
            _ = Ru(_, o, e), o == "xml" ? _ = c.responseXML : o == "json" && (_ = Tu.test(_) ? null : JSON.parse(_));
          } catch (x) {
            v = x;
          }
          if (v)
            return Bs(v, "parsererror", c, e);
        }
        Pu(_, c, e);
      } else
        Bs(c.statusText || null, c.status ? "error" : "abort", c, e);
    }
  }, Mu(c, e) === !1)
    return c.abort(), Bs(null, "abort", c, e), c;
  const m = "async" in e ? e.async : !0;
  if (c.open(e.type, e.url, m, e.username, e.password), e.xhrFields)
    for (qt in e.xhrFields)
      c[qt] = e.xhrFields[qt];
  for (qt in l)
    f.apply(c, l[qt]);
  return e.timeout > 0 && (p = setTimeout(function() {
    c.onreadystatechange = Xt, c.abort(), Bs(null, "timeout", c, e);
  }, e.timeout)), c.send(e.data ? e.data : null), c;
};
function Wo(s, e, t, n) {
  return B.isFunction(e) && (n = t, t = e, e = void 0), B.isFunction(t) || (n = t, t = void 0), {
    url: s,
    data: e,
    success: t,
    dataType: n
  };
}
B.get = function(s, e, t, n) {
  return B.ajax(Wo(s, e, t, n));
};
B.post = function(s, e, t, n) {
  const i = Wo(s, e, t, n);
  return B.ajax(Object.assign(i, { type: "POST" }));
};
B.getJSON = function(s, e, t, n) {
  const i = Wo(s, e, t, n);
  return i.dataType = "json", B.ajax(i);
};
B.fn.load = function(s, e, t) {
  if (!this.length)
    return this;
  const n = s.split(/\s/);
  let i;
  const o = Wo(s, e, t), r = o.success;
  return n.length > 1 && (o.url = n[0], i = n[1]), o.success = (a, ...l) => {
    this.html(i ? B("<div>").html(a.replace(Cu, "")).find(i) : a), r == null || r.call(this, a, ...l);
  }, B.ajax(o), this;
};
const qa = encodeURIComponent;
function Vc(s, e, t, n) {
  const i = B.isArray(e), o = B.isPlainObject(e);
  B.each(e, function(r, a) {
    const l = Array.isArray(a) ? "array" : typeof a;
    n && (r = t ? n : n + "[" + (o || l == "object" || l == "array" ? r : "") + "]"), !n && i ? s.add(a.name, a.value) : l == "array" || !t && l == "object" ? Vc(s, a, t, r) : s.add(r, a);
  });
}
B.param = function(s, e) {
  const t = [];
  return t.add = function(n, i) {
    B.isFunction(i) && (i = i()), i == null && (i = ""), this.push(qa(n) + "=" + qa(i));
  }, Vc(t, s, e), t.join("&").replace(/%20/g, "+");
};
const Ip = Object.assign(B.ajax, {
  get: B.get,
  post: B.post,
  getJSON: B.getJSON,
  param: B.param,
  ajaxSettings: B.ajaxSettings
}), Dp = new ia();
/*! js-cookie v3.0.1 | MIT */
function Fs(s) {
  for (var e = 1; e < arguments.length; e++) {
    var t = arguments[e];
    for (var n in t)
      s[n] = t[n];
  }
  return s;
}
var Au = {
  read: function(s) {
    return s[0] === '"' && (s = s.slice(1, -1)), s.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
  },
  write: function(s) {
    return encodeURIComponent(s).replace(
      /%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,
      decodeURIComponent
    );
  }
};
function gr(s, e) {
  function t(i, o, r) {
    if (!(typeof document > "u")) {
      r = Fs({}, e, r), typeof r.expires == "number" && (r.expires = new Date(Date.now() + r.expires * 864e5)), r.expires && (r.expires = r.expires.toUTCString()), i = encodeURIComponent(i).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
      var a = "";
      for (var l in r)
        r[l] && (a += "; " + l, r[l] !== !0 && (a += "=" + r[l].split(";")[0]));
      return document.cookie = i + "=" + s.write(o, i) + a;
    }
  }
  function n(i) {
    if (!(typeof document > "u" || arguments.length && !i)) {
      for (var o = document.cookie ? document.cookie.split("; ") : [], r = {}, a = 0; a < o.length; a++) {
        var l = o[a].split("="), h = l.slice(1).join("=");
        try {
          var d = decodeURIComponent(l[0]);
          if (r[d] = s.read(h, d), i === d)
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
      get: n,
      remove: function(i, o) {
        t(
          i,
          "",
          Fs({}, o, {
            expires: -1
          })
        );
      },
      withAttributes: function(i) {
        return gr(this.converter, Fs({}, this.attributes, i));
      },
      withConverter: function(i) {
        return gr(Fs({}, this.converter, i), this.attributes);
      }
    },
    {
      attributes: { value: Object.freeze(e) },
      converter: { value: Object.freeze(s) }
    }
  );
}
var Lu = gr(Au, { path: "/" });
window.$ && Object.assign(window.$, { cookie: Lu });
function Ou(s) {
  if (s.indexOf("#") === 0 && (s = s.slice(1)), s.length === 3 && (s = s[0] + s[0] + s[1] + s[1] + s[2] + s[2]), s.length !== 6)
    throw new Error(`Invalid HEX color "${s}".`);
  return [
    parseInt(s.slice(0, 2), 16),
    // r
    parseInt(s.slice(2, 4), 16),
    // g
    parseInt(s.slice(4, 6), 16)
    // b
  ];
}
function ju(s) {
  const [e, t, n] = typeof s == "string" ? Ou(s) : s;
  return e * 0.299 + t * 0.587 + n * 0.114 > 186;
}
function Ga(s, e) {
  return ju(s) ? (e == null ? void 0 : e.dark) ?? "#333333" : (e == null ? void 0 : e.light) ?? "#ffffff";
}
function Ka(s, e = 255) {
  return Math.min(Math.max(s, 0), e);
}
function Hu(s, e, t) {
  s = s % 360 / 360, e = Ka(e), t = Ka(t);
  const n = t <= 0.5 ? t * (e + 1) : t + e - t * e, i = t * 2 - n, o = (r) => (r = r < 0 ? r + 1 : r > 1 ? r - 1 : r, r * 6 < 1 ? i + (n - i) * r * 6 : r * 2 < 1 ? n : r * 3 < 2 ? i + (n - i) * (2 / 3 - r) * 6 : i);
  return [
    o(s + 1 / 3) * 255,
    o(s) * 255,
    o(s - 1 / 3) * 255
  ];
}
function zu(s) {
  let e = 0;
  if (typeof s != "string" && (s = String(s)), s && s.length)
    for (let t = 0; t < s.length; ++t)
      e += (t + 1) * s.charCodeAt(t);
  return e;
}
function Bu(s, e) {
  return /^[\u4e00-\u9fa5\s]+$/.test(s) ? s.length <= e ? s : s.substring(s.length - e) : /^[A-Za-z\d\s]+$/.test(s) ? s[0].toUpperCase() : s.length <= e ? s : s.substring(0, e);
}
let Uc = class extends F {
  render() {
    const {
      className: e,
      style: t,
      size: n = "",
      circle: i,
      rounded: o,
      background: r,
      foreColor: a,
      text: l,
      code: h,
      maxTextLength: d = 2,
      src: c,
      hueDistance: f = 43,
      saturation: p = 0.4,
      lightness: m = 0.6,
      children: b,
      ..._
    } = this.props, v = ["avatar", e], x = { ...t, background: r, color: a };
    let k = 32;
    n && (typeof n == "number" ? (x.width = `${n}px`, x.height = `${n}px`, x.fontSize = `${Math.max(12, Math.round(n / 2))}px`, k = n) : (v.push(`size-${n}`), k = { xs: 20, sm: 24, lg: 48, xl: 80 }[n])), i ? v.push("circle") : o && (typeof o == "number" ? x.borderRadius = `${o}px` : v.push(`rounded-${o}`));
    let N;
    if (c)
      v.push("has-img"), N = /* @__PURE__ */ g("img", { className: "avatar-img", src: c, alt: l });
    else if (l != null && l.length) {
      const S = Bu(l, d);
      if (v.push("has-text", `has-text-${S.length}`), r)
        !a && r && (x.color = Ga(r));
      else {
        const L = h ?? l, I = (typeof L == "number" ? L : zu(L)) * f % 360;
        if (x.background = `hsl(${I},${p * 100}%,${m * 100}%)`, !a) {
          const D = Hu(I, p, m);
          x.color = Ga(D);
        }
      }
      let R;
      k && k < 14 * S.length && (R = { transform: `scale(${k / (14 * S.length)})`, whiteSpace: "nowrap" }), N = /* @__PURE__ */ g("div", { "data-actualSize": k, className: "avatar-text", style: R, children: S });
    }
    return /* @__PURE__ */ g(
      "div",
      {
        className: M(v),
        style: x,
        ..._,
        children: [
          N,
          b
        ]
      }
    );
  }
};
const Ti = class Ti extends V {
};
Ti.NAME = "Avatar", Ti.Component = Uc;
let Ya = Ti;
const Si = class Si extends V {
};
Si.NAME = "BtnGroup", Si.Component = Lc;
let Xa = Si;
const yr = Symbol("EVENT_PICK");
var Ke;
class Vo extends F {
  constructor(t) {
    super(t);
    C(this, Ke, void 0);
    this._handleClick = this._handleClick.bind(this), $(this, Ke, !!u(`#${t.id}`).length);
  }
  get hasInput() {
    return y(this, Ke);
  }
  _handleClick(t) {
    const { togglePop: n, clickType: i, onClick: o } = this.props;
    let r = i === "open" ? !0 : void 0;
    const a = u(t.target), l = o == null ? void 0 : o(t);
    if (!t.defaultPrevented) {
      if (typeof l == "boolean")
        r = l;
      else {
        if (a.closest('[data-dismiss="pick"]').length) {
          n(!1);
          return;
        }
        if (a.closest("a,input").length)
          return;
      }
      requestAnimationFrame(() => n(r));
    }
  }
  _getClass(t) {
    const { state: n, className: i } = t, { open: o, disabled: r } = n;
    return M(
      "pick",
      i,
      o && "is-open focus",
      r && "disabled"
    );
  }
  _getProps(t) {
    const { id: n, style: i, attrs: o } = t;
    return {
      id: `pick-${n}`,
      className: this._getClass(t),
      style: i,
      tabIndex: -1,
      onClick: this._handleClick,
      ...o
    };
  }
  _renderTrigger(t) {
    const { children: n, state: i } = t;
    return n ?? i.value;
  }
  _renderValue(t) {
    const { name: n, state: { value: i = "" }, id: o } = t;
    if (n)
      if (y(this, Ke))
        u(`#${o}`).val(i);
      else
        return /* @__PURE__ */ g("input", { id: o, type: "hidden", className: "pick-value", name: n, value: i });
    return null;
  }
  componentDidMount() {
    const { id: t, state: n } = this.props;
    u(`#${t}`).on(`change.pick.zui.${t}`, (i, o) => {
      if (o === yr)
        return;
      const r = i.target.value;
      r !== n.value && this.props.changeState({ value: r });
    });
  }
  componentWillUnmount() {
    const { id: t } = this.props;
    u(`#${t}`).off(`change.pick.zui.${t}`);
  }
  componentDidUpdate(t) {
    const { id: n, state: i, name: o } = this.props;
    o && t.state.value !== i.value && u(`#${n}`).trigger("change", yr);
  }
  render(t) {
    return w(
      t.tagName || "div",
      this._getProps(t),
      this._renderTrigger(t),
      this._renderValue(t)
    );
  }
}
Ke = new WeakMap();
var ye, vt, Zt;
class ra extends F {
  constructor(t) {
    super(t);
    C(this, ye, void 0);
    C(this, vt, void 0);
    C(this, Zt, void 0);
    $(this, ye, G()), this._handleDocClick = (n) => {
      const { state: { open: i }, id: o, togglePop: r } = this.props, a = u(n.target);
      i !== "closing" && !a.closest(`#pick-${o},#pick-pop-${o}`).length && a.parent().length && r(!1);
    }, this._handleClick = this._handleClick.bind(this);
  }
  get trigger() {
    return u(`#pick-${this.props.id}`)[0];
  }
  get element() {
    var t;
    return (t = y(this, ye)) == null ? void 0 : t.current;
  }
  get container() {
    return y(this, Zt);
  }
  _handleClick(t) {
    const { togglePop: n } = this.props, i = u(t.target), o = i.closest("[data-pick-value]");
    if (o.length)
      return t.stopPropagation(), n(!1, { value: `${o.dataset("pickValue")}` });
    if (i.closest('[data-dismiss="pick"]').length)
      return n(!1);
  }
  _getClass(t) {
    const { className: n, state: i } = t, { open: o } = i;
    return M(
      "pick-pop",
      n,
      o === !0 && "in"
    );
  }
  _getProps(t) {
    const {
      id: n,
      style: i,
      maxHeight: o,
      maxWidth: r,
      minHeight: a,
      minWidth: l
    } = t, h = u.extend({
      maxHeight: o,
      maxWidth: r,
      minHeight: a,
      minWidth: l
    }, i);
    return {
      id: `pick-pop-${n}`,
      className: this._getClass(t),
      style: h,
      ref: y(this, ye),
      onClick: this._handleClick
    };
  }
  _getContainer(t) {
    if (!y(this, Zt)) {
      const n = u(t.container || "body");
      let i = n.find(".pick-container");
      i.length || (i = u("<div>").addClass("pick-container").appendTo(n)), $(this, Zt, i[0]);
    }
    return y(this, Zt);
  }
  _render(t) {
    return /* @__PURE__ */ g("div", { ...this._getProps(t), children: this._renderPop(t) });
  }
  _renderPop(t) {
    return t.children;
  }
  layout() {
    const { element: t, trigger: n, props: i } = this, { state: o } = i;
    if (!t || !n || !o.open) {
      y(this, vt) && (y(this, vt).call(this), $(this, vt, void 0));
      return;
    }
    y(this, vt) || $(this, vt, sa(n, t, () => {
      const { placement: r, width: a } = i;
      Fo(n, t, {
        placement: !r || r === "auto" ? "bottom-start" : r,
        middleware: [r === "auto" ? jo() : null, In(), Ho(1)].filter(Boolean)
      }).then(({ x: l, y: h }) => {
        var d, c;
        u(t).css({
          left: l,
          top: h,
          width: a === "100%" ? u(n).outerWidth() : void 0
        }), (c = (d = this.props).onLayout) == null || c.call(d, t);
      }), a === "100%" && u(t).css({ width: u(t).width() });
    }));
  }
  componentDidMount() {
    var t, n;
    this.layout(), u(document).on("click", this._handleDocClick), (n = (t = this.props).afterRender) == null || n.call(t, { firstRender: !0 });
  }
  componentDidUpdate() {
    var t, n;
    (n = (t = this.props).afterRender) == null || n.call(t, { firstRender: !1 });
  }
  componentWillUnmount() {
    var n, i;
    u(document).off("click", this._handleDocClick);
    const t = y(this, vt);
    t && (t(), $(this, vt, void 0)), $(this, Zt, void 0), $(this, ye, void 0), u(`pick-pop-${this.props.id}`).remove(), (i = (n = this.props).beforeDestroy) == null || i.call(n);
  }
  render(t) {
    return Zd(this._render(t), this._getContainer(t));
  }
}
ye = new WeakMap(), vt = new WeakMap(), Zt = new WeakMap();
var Fn, at, be, Ee;
let ht = (Ee = class extends F {
  constructor(t) {
    super(t);
    C(this, Fn, void 0);
    C(this, at, void 0);
    C(this, be, void 0);
    $(this, at, 0), $(this, be, G()), this.changeState = (n, i) => new Promise((o) => {
      this.setState(n, () => {
        i == null || i(), o(this.state);
      });
    }), this.toggle = async (n, i) => {
      this.props.disabled && (n = !1);
      const { state: o } = this;
      if (typeof n == "boolean" && n === (!!o.open && o.open !== "closing"))
        return i && await this.changeState(i), this.state;
      y(this, at) && (clearTimeout(y(this, at)), $(this, at, 0));
      let r = await this.changeState((l) => (n = n ?? !l.open, {
        open: n ? "opening" : "closing",
        ...i
      }));
      const { open: a } = r;
      return a === "closing" ? (await ti(200, (l) => {
        $(this, at, l);
      }), $(this, at, 0), r = await this.changeState({ open: !1 })) : a === "opening" && (await ti(50, (l) => {
        $(this, at, l);
      }), $(this, at, 0), r = await this.changeState({ open: !0 })), r;
    }, this.state = {
      value: String(t.defaultValue ?? ""),
      open: !1,
      disabled: !1
    }, $(this, Fn, t.id ?? `_pick${u.guid++}`);
  }
  get id() {
    return y(this, Fn);
  }
  get pop() {
    return y(this, be).current;
  }
  open(t) {
    return this.toggle(!0, t);
  }
  close(t) {
    return this.toggle(!1, t);
  }
  _getTriggerProps(t, n) {
    return {
      id: this.id,
      state: n,
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
  _getPopProps(t, n) {
    return {
      id: this.id,
      state: n,
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
  _renderTrigger(t, n) {
    return null;
  }
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  _renderPop(t, n) {
    return null;
  }
  _afterRender(t = !1) {
    var n;
    (n = this.props.afterRender) == null || n.call(this, { firstRender: t });
  }
  _getPop(t) {
    return t.Pop || this.constructor.Pop;
  }
  _getTrigger(t) {
    return t.Trigger || this.constructor.Trigger;
  }
  _handleChange(t, n) {
    const { onChange: i } = this.props;
    i && i(t, n);
  }
  componentDidMount() {
    this._afterRender(!0);
  }
  componentWillUpdate(t, n) {
    const { open: i } = this.state, { open: o } = n;
    if (i === o)
      return;
    const { onPopShow: r, onPopHide: a } = this.props;
    o && r ? r() : !o && a && a();
  }
  componentDidUpdate(t, n) {
    const { open: i, value: o } = this.state, { open: r, value: a } = n;
    if (!!i != !!r) {
      const { onPopShown: l, onPopHidden: h } = this.props;
      i && l ? l() : !i && h && h();
    }
    o !== a && this._handleChange(o, a), this._afterRender();
  }
  componentWillUnmount() {
    var n;
    (n = this.props.beforeDestroy) == null || n.call(this), y(this, at) && clearTimeout(y(this, at));
    const t = y(this, be).current;
    t && t.componentWillUnmount && t.componentWillUnmount();
  }
  render(t, n) {
    const { open: i } = n, o = this._getTrigger(t);
    let r;
    if (i) {
      const a = this._getPop(t);
      r = /* @__PURE__ */ g(a, { ref: y(this, be), ...this._getPopProps(t, n), children: this._renderPop(t, n) }, "pop");
    }
    return /* @__PURE__ */ g(ae, { children: [
      /* @__PURE__ */ g(o, { ...this._getTriggerProps(t, n), children: this._renderTrigger(t, n) }, "pick"),
      r
    ] });
  }
}, Fn = new WeakMap(), at = new WeakMap(), be = new WeakMap(), Ee.Trigger = Vo, Ee.Pop = ra, Ee.defaultProps = {
  popContainer: "body",
  popClass: "popup",
  popWidth: "100%",
  popPlacement: "auto",
  popMinWidth: 50,
  popMinHeight: 32,
  popMaxHeight: 300,
  clickType: "open"
}, Ee);
var Wn;
let Fu = (Wn = class extends ht {
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
    const { syncBackground: e, syncBorder: t, syncColor: n, syncValue: i } = this.props, o = this.state.value || "";
    if (e && u(e).css("backgroundColor", o), t && u(t).css("borderColor", o), n && u(n).css("color", o), i) {
      const r = u(i);
      r.is("input,textarea,select") ? r.val(o) : r.text(o);
    }
  }
  _handleChange(e, t) {
    this.props.disabled || (super._handleChange(e, t), this.syncColor());
  }
  _renderTrigger(e, t) {
    const { icon: n } = e, { value: i } = t;
    return [
      n ? /* @__PURE__ */ g(Z, { icon: n }, "icon") : /* @__PURE__ */ g("span", { class: "color-picker-item bg-current ring", style: { background: i } })
    ];
  }
  _getTriggerProps(e, t) {
    const n = super._getTriggerProps(e, t);
    return n.style = u.extend({
      color: t.value
    }, n.style), n.className = M("color-picker", n.className, { disabled: e.disabled }), n;
  }
  _renderPop(e, t) {
    const { closeBtn: n, heading: i } = e, o = this.getColors(), { value: r } = t;
    let a;
    return i && (a = /* @__PURE__ */ g("div", { className: "color-picker-heading", children: [
      i,
      n ? /* @__PURE__ */ g("button", { className: "btn ghost square rounded size-sm", "data-dismiss": "pick", children: /* @__PURE__ */ g("span", { class: "close" }) }) : null
    ] }, "heading")), [
      a,
      /* @__PURE__ */ g("div", { className: "color-picker-row", children: [
        o.map((l) => /* @__PURE__ */ g("button", { className: "btn color-picker-item", style: { backgroundColor: l }, "data-pick-value": l, children: r === l ? /* @__PURE__ */ g(Z, { icon: "check" }) : null }, l)),
        /* @__PURE__ */ g("button", { className: "btn color-picker-item", "data-pick-value": "", children: /* @__PURE__ */ g(Z, { className: "text-fore", icon: "trash" }) })
      ] }, "row")
    ];
  }
}, Wn.defaultProps = {
  ...ht.defaultProps,
  className: "rounded btn square size-sm ghost",
  popClass: "color-picker-pop popup",
  colors: ["#ef4444", "#f97316", "#eab308", "#84cc16", "#22c55e", "#14b8a6", "#0ea5e9", "#6366f1", "#a855f7", "#d946ef", "#ec4899"],
  closeBtn: !0,
  popWidth: "auto",
  popMinWidth: 176
}, Wn);
const Ni = class Ni extends V {
};
Ni.NAME = "ColorPicker", Ni.Component = Fu;
let Ja = Ni;
const An = 24 * 60 * 60 * 1e3, Q = (s) => s ? (s instanceof Date || (typeof s == "string" && (s = s.trim(), /^\d+$/.test(s) && (s = Number.parseInt(s, 10))), typeof s == "number" && s < 1e10 && (s *= 1e3), s = new Date(s)), s) : /* @__PURE__ */ new Date(), Wu = (s, e, t = "day") => {
  if (typeof e == "string") {
    const n = Number.parseInt(e, 10);
    t = e.replace(n.toString(), ""), e = n;
  }
  return s = new Date(Q(s).getTime()), t === "month" ? s.setMonth(s.getMonth() + e) : t === "year" ? s.setFullYear(s.getFullYear() + e) : t === "week" ? s.setDate(s.getDate() + e * 7) : t === "hour" ? s.setHours(s.getHours() + e) : t === "minute" ? s.setMinutes(s.getMinutes() + e) : t === "second" ? s.setSeconds(s.getSeconds() + e) : s.setDate(s.getDate() + e), s;
}, js = (s, e = /* @__PURE__ */ new Date()) => Q(s).toDateString() === Q(e).toDateString(), br = (s, e = /* @__PURE__ */ new Date()) => Q(s).getFullYear() === Q(e).getFullYear(), qc = (s, e = /* @__PURE__ */ new Date()) => (s = Q(s), e = Q(e), s.getFullYear() === e.getFullYear() && s.getMonth() === e.getMonth()), Lp = (s, e = /* @__PURE__ */ new Date()) => {
  s = Q(s), e = Q(e);
  const t = 1e3 * 60 * 60 * 24, n = Math.floor(s.getTime() / t), i = Math.floor(e.getTime() / t);
  return Math.floor((n + 4) / 7) === Math.floor((i + 4) / 7);
}, Op = (s, e) => js(Q(e), s), jp = (s, e) => js(Q(e).getTime() - An, s), Hp = (s, e) => js(Q(e).getTime() + An, s), mt = (s, e = "yyyy-MM-dd hh:mm", t = "") => {
  if (s = Q(s), Number.isNaN(s.getDay()))
    return t;
  const n = {
    "M+": s.getMonth() + 1,
    "d+": s.getDate(),
    "h+": s.getHours(),
    "H+": s.getHours() % 12,
    "m+": s.getMinutes(),
    "s+": s.getSeconds(),
    "S+": s.getMilliseconds()
  };
  return /(y+)/i.test(e) && (e.includes("[yyyy-]") && (e = e.replace("[yyyy-]", br(s) ? "" : "yyyy-")), e = e.replace(RegExp.$1, `${s.getFullYear()}`.substring(4 - RegExp.$1.length))), Object.keys(n).forEach((i) => {
    if (new RegExp(`(${i})`).test(e)) {
      const o = `${n[i]}`;
      e = e.replace(RegExp.$1, RegExp.$1.length === 1 ? o : `00${o}`.substring(o.length));
    }
  }), e;
}, zp = (s, e, t) => {
  const n = {
    full: "yyyy-M-d",
    month: "M-d",
    day: "d",
    str: "{0} ~ {1}",
    ...t
  }, i = mt(s, br(s) ? n.month : n.full);
  if (js(s, e))
    return i;
  const o = mt(e, br(s, e) ? qc(s, e) ? n.day : n.month : n.full);
  return n.str.replace("{0}", i).replace("{1}", o);
};
var Vn, Un;
class Vu extends F {
  constructor() {
    super(...arguments);
    C(this, Vn, G());
    C(this, Un, (t, n) => {
      var i, o;
      (o = (i = this.props).onChange) == null || o.call(i, t, String(n.item.key || ""));
    });
  }
  componentDidMount() {
    u(y(this, Vn).current).find(".menu-item>.active").scrollIntoView();
  }
  render(t) {
    const { minuteStep: n = 5, hour: i, minute: o } = t, r = [], a = [];
    for (let h = 0; h < 24; ++h)
      r.push({ key: h, text: h < 10 ? `0${h}` : h, active: i === h });
    for (let h = 0; h < 60; h += n)
      a.push({ key: h, text: h < 10 ? `0${h}` : h, active: o === h });
    const l = "col w-10 max-h-full overflow-y-auto scrollbar-thin scrollbar-hover";
    return /* @__PURE__ */ g("div", { className: "time-picker-menu row", ref: y(this, Vn), children: [
      /* @__PURE__ */ g(
        Ie,
        {
          className: l,
          items: r,
          onClickItem: y(this, Un).bind(this, "hour")
        }
      ),
      /* @__PURE__ */ g(
        Ie,
        {
          className: l,
          items: a,
          onClickItem: y(this, Un).bind(this, "minute")
        }
      )
    ] });
  }
}
Vn = new WeakMap(), Un = new WeakMap();
const Za = (s) => {
  if (!s)
    return;
  const e = Q(`1999-01-01 ${s}`);
  if (!Number.isNaN(e.getDay()))
    return e;
};
var Ei, Mi, Pi, Ri, qn;
let Gc = (qn = class extends ht {
  constructor(t) {
    super(t);
    C(this, Ei, () => {
      this.toggle(!0);
    });
    C(this, Mi, (t) => {
      this.setTime(t.target.value);
    });
    C(this, Pi, (t, n) => {
      this.setTime({ [t]: n });
    });
    C(this, Ri, () => {
      this.setTime("");
    });
    const n = this.state;
    n.value === "now" && (n.value = mt(/* @__PURE__ */ new Date(), t.format));
  }
  setTime(t) {
    if (this.props.disabled)
      return;
    let n = "";
    if (typeof t == "string")
      n = t;
    else {
      const [l, h] = (this.state.value || "00:00").split(":"), { hour: d = +l, minute: c = +h } = t;
      n = `${d}:${c}`;
    }
    const i = Za(n), { onInvalid: o, required: r, defaultValue: a } = this.props;
    this.setState({ value: i ? mt(i, this.props.format) : r ? a : "" }, () => {
      !i && o && o(n);
    });
  }
  getTime() {
    const t = Za(this.state.value);
    return t ? [t.getHours(), t.getMinutes()] : null;
  }
  _renderTrigger(t, n) {
    const { placeholder: i, name: o, icon: r, required: a, disabled: l, readonly: h } = t, { value: d = "", open: c } = n, f = `time-picker-${this.id}`;
    let p;
    return c && !a && d.length ? p = /* @__PURE__ */ g("button", { type: "button", className: "btn size-sm square ghost", onClick: y(this, Ri), children: /* @__PURE__ */ g("span", { className: "close" }) }) : r && (r === !0 ? p = /* @__PURE__ */ g("i", { class: "i-time" }) : p = /* @__PURE__ */ g(Z, { icon: r })), [
      /* @__PURE__ */ g("input", { name: o, id: f, type: "text", class: "form-control", placeholder: i, value: d, disabled: l, readOnly: h, onFocus: y(this, Ei), onChange: y(this, Mi) }, "input"),
      p ? /* @__PURE__ */ g("label", { for: f, class: "input-control-suffix", children: p }, "icon") : null
    ];
  }
  _getTriggerProps(t, n) {
    const i = super._getTriggerProps(t, n);
    return {
      ...i,
      className: M(i.className, "time-picker input-control has-suffix-icon"),
      name: ""
    };
  }
  _renderPop(t) {
    const [n, i] = this.getTime() || [];
    return /* @__PURE__ */ g(Vu, { hour: n, minute: i, minuteStep: t.minuteStep, onChange: y(this, Pi) });
  }
}, Ei = new WeakMap(), Mi = new WeakMap(), Pi = new WeakMap(), Ri = new WeakMap(), qn.defaultProps = {
  ...ht.defaultProps,
  popWidth: "auto",
  popMaxHeight: 320,
  minuteStep: 5,
  format: "hh:mm",
  icon: !0
}, qn);
nt.addLang({
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
const Uu = (s, e, t = 0) => {
  const n = new Date(s, e - 1, 1), i = new Date(s, e, 0), o = n.getDay(), r = n.getTime() - (7 + o - t) % 7 * An;
  return {
    days: i.getDate(),
    startTime: r,
    firstDay: n.getTime()
  };
}, Qa = (s, e) => new Set((Array.isArray(s) ? s : [s]).map((t) => mt(t, e)));
var Ii;
class qu extends F {
  constructor() {
    super(...arguments);
    C(this, Ii, (t) => {
      const { onClickDate: n } = this.props;
      if (!n)
        return;
      const i = u(t.target).closest(".mini-calendar-day").dataset("date");
      i && n(i);
    });
  }
  render(t) {
    const n = /* @__PURE__ */ new Date(), {
      weekStart: i = 1,
      weekNames: o = nt.getLang("weekNames"),
      monthNames: r = nt.getLang("monthNames"),
      year: a = n.getFullYear(),
      month: l = n.getMonth() + 1,
      highlights: h = [],
      selections: d = []
    } = t, c = [], f = "btn ghost square rounded-full";
    for (let R = 0; R < 7; R++) {
      const L = (i + R) % 7;
      c.push(/* @__PURE__ */ g("div", { className: M("col mini-calendar-day", { "is-weekend": L === 0 || L === 6 }), children: /* @__PURE__ */ g("div", { children: o ? o[L] : L }) }, R));
    }
    const { startTime: p, days: m, firstDay: b } = Uu(a, l, i), _ = b + m * An;
    let v = p;
    const x = [], k = "yyyy-MM-dd", N = Qa(h, k), S = Qa(d, k);
    for (; v <= _; ) {
      const R = [];
      for (let L = 0; L < 7; L++) {
        const I = new Date(v), D = I.getDate(), A = mt(I, k), O = I.getDay(), E = qc(I, b), T = M("col mini-calendar-day", {
          active: N.has(A),
          selected: S.has(A),
          "is-first": D === 1,
          "is-in-month": E,
          "is-out-month": !E,
          "is-today": js(I, n),
          "is-weekend": O === 0 || O === 6
        });
        R.push(
          /* @__PURE__ */ g("div", { className: T, "data-date": A, children: /* @__PURE__ */ g("a", { className: f, onClick: y(this, Ii), children: D === 1 && r ? r[I.getMonth()] : I.getDate() }) }, A)
        ), v += An;
      }
      x.push(/* @__PURE__ */ g("div", { className: "row", children: R }, v));
    }
    return /* @__PURE__ */ g("div", { className: "mini-calendar", children: [
      /* @__PURE__ */ g("div", { className: "row", children: c }),
      x
    ] });
  }
}
Ii = new WeakMap();
var Gn, Di;
class tl extends F {
  constructor() {
    super(...arguments);
    C(this, Gn, G());
    C(this, Di, (t) => {
      const { onChange: n } = this.props;
      if (!n)
        return;
      const o = u(t.target).closest("[data-value]").dataset("value");
      o && (n(+o), t.stopPropagation());
    });
  }
  componentDidMount() {
    u(y(this, Gn).current).find(".active").scrollIntoView({ block: "center" });
  }
  render(t) {
    const { className: n, max: i, min: o, value: r } = t, a = [], l = (/* @__PURE__ */ new Date()).getFullYear();
    for (let h = o; h <= i; ++h)
      a.push(/* @__PURE__ */ g(tt, { type: "ghost", "data-value": h, active: h === r, className: M(l === h ? "is-current" : ""), onClick: y(this, Di), children: h }, h));
    return /* @__PURE__ */ g("div", { className: n, ref: y(this, Gn), children: a });
  }
}
Gn = new WeakMap(), Di = new WeakMap();
var Ye, Kn, Yn, Xn, Jn, Zn, Ai, Kc, Li, Yc;
class Gu extends F {
  constructor(t) {
    super(t);
    C(this, Ai);
    C(this, Li);
    C(this, Ye, void 0);
    C(this, Kn, void 0);
    C(this, Yn, void 0);
    C(this, Xn, void 0);
    C(this, Jn, void 0);
    C(this, Zn, void 0);
    $(this, Ye, G()), $(this, Kn, (r) => {
      const a = u(r.target).closest("[data-set-date]");
      a.length && this.changeDate(a.dataset("set-date"));
    }), $(this, Yn, () => {
      const { year: r, month: a } = this.state;
      a === 1 ? this.setState({ year: r - 1, month: 12 }) : this.setState({ month: a - 1 });
    }), $(this, Xn, () => {
      const { year: r, month: a } = this.state;
      a === 12 ? this.setState({ year: r + 1, month: 1 }) : this.setState({ month: a + 1 });
    }), $(this, Jn, (r) => {
      this.setState({ year: r, select: "day" });
    }), $(this, Zn, (r) => {
      this.setState({ month: r, select: "day" });
    }), this.changeDate = (r) => {
      var a, l;
      if (r.startsWith("today")) {
        let h = /* @__PURE__ */ new Date();
        r.length > 3 && (h = Wu(h, r.substring(5).replace("+", ""))), r = mt(h, "yyyy-MM-dd");
      }
      (l = (a = this.props).onChange) == null || l.call(a, r);
    };
    const { date: n } = t, i = /* @__PURE__ */ new Date(), o = n ? new Date(n) : void 0;
    this.state = { select: "day", year: o ? o.getFullYear() : i.getFullYear(), month: o ? o.getMonth() + 1 : i.getMonth() + 1 };
  }
  _showSelect(t) {
    this.setState((n) => n.select === t ? { select: "day" } : { select: t });
  }
  componentDidMount() {
    u(y(this, Ye).current).find(".active").scrollIntoView();
  }
  render(t, n) {
    const {
      date: i,
      yearText: o = nt.getLang("yearFormat") || "{0}",
      weekNames: r = nt.getLang("weekNames"),
      monthNames: a = nt.getLang("monthNames"),
      weekStart: l
    } = t, h = i ? new Date(i) : void 0, {
      year: d,
      month: c,
      select: f
    } = n, p = f === "day", m = Q(t.minDate || "1970-1-1"), b = Q(t.maxDate || "2099-12-1");
    return /* @__PURE__ */ g("div", { className: "date-picker-menu row", ref: y(this, Ye), onClick: y(this, Kn), children: [
      j(this, Ai, Kc).call(this, t),
      /* @__PURE__ */ g("div", { className: "cell", style: "width: 312px", children: [
        /* @__PURE__ */ g("div", { className: "row p-2", children: [
          /* @__PURE__ */ g(tt, { type: f === "year" ? "primary-pale" : "ghost", size: "sm", caret: !0, onClick: this._showSelect.bind(this, "year"), children: U(o, d) }),
          /* @__PURE__ */ g(tt, { type: f === "month" ? "primary-pale" : "ghost", size: "sm", caret: !0, onClick: this._showSelect.bind(this, "month"), children: a ? a[c - 1] : c }),
          /* @__PURE__ */ g("div", { className: "flex-auto" }),
          p ? /* @__PURE__ */ g("div", { children: [
            /* @__PURE__ */ g(tt, { type: "ghost", size: "sm", square: !0, onClick: y(this, Yn), children: /* @__PURE__ */ g("i", { className: "chevron-left" }) }),
            /* @__PURE__ */ g(tt, { type: "ghost", size: "sm", square: !0, onClick: y(this, Xn), children: /* @__PURE__ */ g("i", { className: "chevron-right" }) })
          ] }) : null
        ] }),
        p ? /* @__PURE__ */ g(
          qu,
          {
            weekStart: l,
            weekNames: r,
            monthNames: a,
            year: d,
            month: c,
            selections: h,
            onClickDate: this.changeDate
          }
        ) : null,
        f === "year" ? /* @__PURE__ */ g(
          tl,
          {
            className: "date-pick-menu-years overflow-y-auto scrollbar-hover scrollbar-thin",
            value: d,
            min: m.getFullYear(),
            max: b.getFullYear(),
            onChange: y(this, Jn)
          }
        ) : f === "month" ? /* @__PURE__ */ g(
          tl,
          {
            className: "date-pick-menu-month overflow-y-auto scrollbar-hover scrollbar-thin",
            value: c,
            min: 1,
            max: 12,
            onChange: y(this, Zn)
          }
        ) : null,
        p ? j(this, Li, Yc).call(this, t) : null
      ] })
    ] });
  }
}
Ye = new WeakMap(), Kn = new WeakMap(), Yn = new WeakMap(), Xn = new WeakMap(), Jn = new WeakMap(), Zn = new WeakMap(), Ai = new WeakSet(), Kc = function(t) {
  let { menu: n } = t;
  return n ? (Array.isArray(n) && (n = { items: n }), /* @__PURE__ */ g(Ie, { ...n })) : null;
}, Li = new WeakSet(), Yc = function(t) {
  let { actions: n } = t;
  const { todayText: i, clearText: o } = t;
  return n || (n = [{ text: i, "data-set-date": mt(/* @__PURE__ */ new Date(), "yyyy-MM-dd") }]), Array.isArray(n) && (n = { items: n }), /* @__PURE__ */ g("div", { className: "date-picker-menu-footer", children: [
    /* @__PURE__ */ g(Mt, { btnProps: { className: "ghost text-primary" }, ...n }),
    o ? /* @__PURE__ */ g(tt, { type: "ghost text-link", "data-set-date": "", children: o }) : null
  ] });
};
var Qn, ts, es, ns;
let Xc = (ns = class extends ht {
  constructor(t) {
    super(t);
    C(this, Qn, void 0);
    C(this, ts, void 0);
    C(this, es, void 0);
    $(this, Qn, () => {
      this.toggle(!0);
    }), $(this, ts, (i) => {
      this.setDate(i.target.value);
    }), $(this, es, () => {
      this.setDate("");
    }), this.setDate = (i) => {
      if (this.props.disabled)
        return;
      const o = Q(i), r = !i || Number.isNaN(o.getDay()), { onInvalid: a, defaultValue: l = "", required: h } = this.props;
      this.setState({ value: r ? h ? l : "" : mt(o, this.props.format) }, () => {
        !r && a && a(i), this.toggle(!1);
      });
    };
    const { value: n } = this.state;
    n && (this.state.value = mt(n === "today" ? /* @__PURE__ */ new Date() : n, t.format));
  }
  _renderTrigger(t, n) {
    const { placeholder: i, icon: o, required: r, disabled: a, readonly: l } = t, { value: h = "", open: d } = n, c = `date-picker-${this.id}`;
    let f;
    return d && !r && h.length ? f = /* @__PURE__ */ g("button", { type: "button", className: "btn size-sm square ghost", onClick: y(this, es), children: /* @__PURE__ */ g("span", { className: "close" }) }) : o && (o === !0 ? f = /* @__PURE__ */ g("i", { class: "i-calendar" }) : f = /* @__PURE__ */ g(Z, { icon: o })), [
      /* @__PURE__ */ g("input", { id: c, type: "text", class: "form-control", placeholder: i, value: h, disabled: a, readOnly: l, onFocus: y(this, Qn), onChange: y(this, ts) }, "input"),
      f ? /* @__PURE__ */ g("label", { for: c, class: "input-control-suffix", children: f }, "icon") : null
    ];
  }
  _getTriggerProps(t, n) {
    const i = super._getTriggerProps(t, n);
    return {
      ...i,
      className: M(i.className, "date-picker input-control has-suffix-icon")
    };
  }
  _getPopProps(t, n) {
    const i = super._getPopProps(t, n);
    return {
      ...i,
      className: M(i.className, "popup")
    };
  }
  _renderPop(t, n) {
    const { weekNames: i, monthNames: o, weekStart: r, yearText: a, todayText: l = nt.getLang("today"), clearText: h, menu: d, actions: c, minDate: f, maxDate: p, required: m } = t;
    return /* @__PURE__ */ g(
      Gu,
      {
        onChange: this.setDate,
        date: n.value,
        weekNames: i,
        monthNames: o,
        weekStart: r,
        yearText: a,
        todayText: l,
        clearText: m ? "" : h,
        menu: d,
        actions: c,
        minDate: f,
        maxDate: p
      }
    );
  }
}, Qn = new WeakMap(), ts = new WeakMap(), es = new WeakMap(), ns.defaultProps = {
  ...ht.defaultProps,
  popWidth: "auto",
  popMaxHeight: 320,
  format: "yyyy-MM-dd",
  icon: !0
}, ns);
const Oi = class Oi extends V {
};
Oi.NAME = "TimePicker", Oi.Component = Gc;
let el = Oi;
const ji = class ji extends V {
};
ji.NAME = "DatePicker", ji.Component = Xc;
let nl = ji;
const Jo = "show", sl = "in", Ku = '[data-dismiss="modal"]', gt = class gt extends ot {
  constructor() {
    super(...arguments), this._timer = 0, this._handleClick = (e) => {
      const t = e.target, n = t.closest(".modal");
      !n || n !== this.modalElement || (t.closest(Ku) || this.options.backdrop === !0 && t === n) && (e.stopPropagation(), this.hide());
    };
  }
  get modalElement() {
    return this.element;
  }
  get shown() {
    return this.modalElement.classList.contains(Jo);
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
        const n = new ResizeObserver(() => {
          if (!this.shown)
            return;
          const i = t.clientWidth, o = t.clientHeight, [r, a] = this._lastDialogSize || [];
          (r !== i || a !== o) && (this._lastDialogSize = [i, o], this.layout());
        });
        n.observe(t), this._rob = n;
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
    const { animation: n, backdrop: i, className: o, style: r } = this.options;
    return u(t).setClass({
      "modal-trans": n,
      "modal-no-backdrop": !i
    }, Jo, o).css({
      zIndex: `${gt.zIndex++}`,
      ...r
    }), this.layout(), this.emit("show"), this._setTimer(() => {
      u(t).addClass(sl), this._setTimer(() => {
        this.emit("shown");
      });
    }, 50), !0;
  }
  hide() {
    return this.shown ? (u(this.modalElement).removeClass(sl), this.emit("hide"), this._setTimer(() => {
      u(this.modalElement).removeClass(Jo), this.emit("hidden");
    }), !0) : !1;
  }
  layout(e, t) {
    if (!this.shown)
      return;
    const { dialog: n } = this;
    if (!n)
      return;
    const i = u(n);
    if (t = t ?? this.options.size, t) {
      i.removeAttr("data-size");
      const l = { width: "", height: "" };
      typeof t == "object" ? (l.width = t.width, l.height = t.height) : typeof t == "string" && ["md", "sm", "lg", "full"].includes(t) ? i.attr("data-size", t) : t && (l.width = t), i.css(l);
    }
    e = e ?? this.options.position ?? "fit";
    const o = n.clientWidth, r = n.clientHeight;
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
let Vt = gt;
u(window).on(`resize.${Vt.NAMESPACE}`, () => {
  Vt.getAll().forEach((s) => {
    const e = s;
    e.shown && e.options.responsive && e.layout();
  });
});
u(document).on(`to-hide.${Vt.NAMESPACE}`, (s, e) => {
  Vt.hide(e == null ? void 0 : e.target);
});
const fa = class fa extends F {
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
      title: n
    } = this.props;
    return et(e) ? e : e === !1 || !n ? null : /* @__PURE__ */ g("div", { className: M("modal-header", t), children: /* @__PURE__ */ g("div", { className: "modal-title", children: n }) });
  }
  renderActions() {
    const {
      actions: e,
      closeBtn: t
    } = this.props;
    return !t && !e ? null : et(e) ? e : /* @__PURE__ */ g("div", { className: "modal-actions", children: [
      e ? /* @__PURE__ */ g(Mt, { ...e }) : null,
      t ? /* @__PURE__ */ g("button", { type: "button", class: "btn square ghost", "data-dismiss": "modal", children: /* @__PURE__ */ g("span", { class: "close" }) }) : null
    ] });
  }
  renderBody() {
    const {
      body: e,
      bodyClass: t
    } = this.props;
    return e ? et(e) ? e : /* @__PURE__ */ g("div", { className: M("modal-body", t), children: e }) : null;
  }
  renderFooter() {
    const {
      footer: e,
      footerClass: t,
      footerActions: n
    } = this.props;
    return et(e) ? e : e === !1 || !n ? null : /* @__PURE__ */ g("div", { className: M("modal-footer", t), children: n ? /* @__PURE__ */ g(Mt, { ...n }) : null });
  }
  render() {
    const {
      className: e,
      style: t,
      contentClass: n,
      children: i
    } = this.props;
    return /* @__PURE__ */ g("div", { className: M("modal-dialog", e), style: t, children: /* @__PURE__ */ g("div", { className: M("modal-content", n), children: [
      this.renderHeader(),
      this.renderActions(),
      this.renderBody(),
      i,
      this.renderFooter()
    ] }) });
  }
};
fa.defaultProps = { closeBtn: !0 };
let wr = fa;
var Xe, Je, Ze;
class Yu extends F {
  constructor() {
    super(...arguments);
    C(this, Xe, void 0);
    C(this, Je, void 0);
    C(this, Ze, void 0);
    $(this, Xe, G()), this.state = {}, $(this, Ze, () => {
      var i, o;
      const t = (o = (i = y(this, Xe).current) == null ? void 0 : i.contentWindow) == null ? void 0 : o.document;
      if (!t)
        return;
      let n = y(this, Je);
      n == null || n.disconnect(), n = new ResizeObserver(() => {
        const r = t.body, a = t.documentElement, l = Math.ceil(Math.max(r.scrollHeight, r.offsetHeight, a.offsetHeight));
        this.setState({ height: l });
      }), n.observe(t.body), n.observe(t.documentElement), $(this, Je, n);
    });
  }
  componentDidMount() {
    y(this, Ze).call(this);
  }
  componentWillUnmount() {
    var t;
    (t = y(this, Je)) == null || t.disconnect();
  }
  render() {
    const { url: t } = this.props;
    return /* @__PURE__ */ g(
      "iframe",
      {
        className: "modal-iframe",
        style: this.state,
        src: t,
        ref: y(this, Xe),
        onLoad: y(this, Ze)
      }
    );
  }
}
Xe = new WeakMap(), Je = new WeakMap(), Ze = new WeakMap();
function Xu(s, e) {
  const { custom: t, title: n, content: i } = e;
  return {
    body: i,
    title: n,
    ...typeof t == "function" ? t() : t
  };
}
async function Ju(s, e) {
  const { dataType: t = "html", url: n, request: i, custom: o, title: r, replace: a = !0, executeScript: l = !0 } = e, d = await (await fetch(n, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "X-ZUI-Modal": "true"
    },
    ...i
  })).text();
  if (t !== "html")
    try {
      const c = JSON.parse(d);
      return {
        title: r,
        ...o,
        ...c
      };
    } catch {
    }
  return a !== !1 && t === "html" ? [d] : {
    title: r,
    ...o,
    body: t === "html" ? /* @__PURE__ */ g(Ds, { className: "modal-body", html: d, executeScript: l }) : d
  };
}
async function Zu(s, e) {
  const { url: t, custom: n, title: i } = e;
  return {
    title: i,
    ...n,
    body: /* @__PURE__ */ g(Yu, { url: t })
  };
}
const Qu = {
  custom: Xu,
  ajax: Ju,
  iframe: Zu
}, Zo = "loading";
var _t, Qe, xt, ss, vr, is, _r;
const Kt = class Kt extends Vt {
  constructor() {
    super(...arguments);
    C(this, ss);
    C(this, is);
    C(this, _t, void 0);
    C(this, Qe, void 0);
    C(this, xt, void 0);
  }
  get id() {
    return y(this, Qe);
  }
  get loading() {
    var t;
    return (t = y(this, _t)) == null ? void 0 : t.classList.contains(Zo);
  }
  get shown() {
    var t;
    return !!((t = y(this, _t)) != null && t.classList.contains("show"));
  }
  get modalElement() {
    let t = y(this, _t);
    if (!t) {
      const { options: n } = this;
      let i = y(this, Qe);
      i || (i = n.id || `modal-${u.guid++}`, $(this, Qe, i));
      const { $element: o } = this;
      if (t = o.find(`#${i}`)[0], !t) {
        const r = this.key;
        t = u("<div>").attr({
          id: i,
          "data-key": r
        }).data(this.constructor.KEY, this).css(n.style || {}).setClass("modal modal-async load-indicator", n.className).appendTo(o)[0];
      }
      $(this, _t, t);
    }
    return t;
  }
  get $emitter() {
    const t = y(this, _t);
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
    const t = y(this, _t);
    t && (u(t).removeData(this.constructor.KEY).remove(), $(this, _t, void 0));
  }
  render(t) {
    super.render(t), this.buildDialog();
  }
  async buildDialog() {
    if (this.loading)
      return !1;
    y(this, xt) && clearTimeout(y(this, xt));
    const { modalElement: t, options: n } = this, i = u(t), { type: o, loadTimeout: r, loadingText: a = null } = n, l = Qu[o];
    if (!l)
      return console.warn(`Modal: Cannot build modal with type "${o}"`), !1;
    i.attr("data-loading", a).addClass(Zo), r && $(this, xt, window.setTimeout(() => {
      $(this, xt, 0), j(this, is, _r).call(this, this.options.timeoutTip);
    }, r));
    const h = await l.call(this, t, n);
    return h === !1 ? await j(this, is, _r).call(this, this.options.failedTip) : h && typeof h == "object" && await j(this, ss, vr).call(this, h), y(this, xt) && (clearTimeout(y(this, xt)), $(this, xt, 0)), this.layout(), await ti(100), i.removeClass(Zo), !0;
  }
  static open(t) {
    return new Promise((n) => {
      const { container: i = document.body, ...o } = t, r = { show: !0, ...o };
      !r.type && r.url && (r.type = "ajax");
      const a = Kt.ensure(i, r), l = `${Kt.NAMESPACE}.open${u.guid++}`;
      a.on(`hidden${l}`, () => {
        a.off(l), n(a);
      }), a.show();
    });
  }
  static async alert(t) {
    typeof t == "string" && (t = { message: t });
    const { type: n, message: i, icon: o, iconClass: r = "icon-lg muted", actions: a = "confirm", onClickAction: l, custom: h, key: d = "__alert", ...c } = t, f = (typeof h == "function" ? h() : h) || {};
    let p = typeof i == "object" && i.html ? /* @__PURE__ */ g("div", { dangerouslySetInnerHTML: { __html: i.html } }) : /* @__PURE__ */ g("div", { children: i });
    o ? p = /* @__PURE__ */ g("div", { className: M("modal-body row gap-4 items-center", f.bodyClass), children: [
      /* @__PURE__ */ g("div", { className: `icon ${o} ${r}` }),
      p
    ] }) : p = /* @__PURE__ */ g("div", { className: M("modal-body", f.bodyClass), children: p });
    const m = [];
    (Array.isArray(a) ? a : [a]).forEach((v) => {
      v = {
        ...typeof v == "string" ? { key: v } : v
      }, typeof v.key == "string" && (v.text || (v.text = nt.getLang(v.key, v.key)), v.btnType || (v.btnType = `btn-wide ${v.key === "confirm" ? "primary" : "btn-default"}`)), v && m.push(v);
    }, []);
    let b;
    const _ = m.length ? {
      gap: 4,
      items: m,
      onClickItem: ({ item: v, event: x }) => {
        const k = Kt.query(x.target, d);
        b = v.key, (l == null ? void 0 : l(v, k)) !== !1 && k && k.hide();
      }
    } : void 0;
    return await Kt.open({
      key: d,
      type: "custom",
      size: 400,
      className: "modal-alert",
      content: p,
      backdrop: "static",
      custom: { footerActions: _, ...f },
      ...c
    }), b;
  }
  static async confirm(t) {
    typeof t == "string" && (t = { message: t });
    const { onClickAction: n, onResult: i, ...o } = t;
    return await Kt.alert({
      actions: ["confirm", "cancel"],
      onClickAction: (a, l) => {
        i == null || i(a.key === "confirm", l), n == null || n(a, l);
      },
      ...o
    }) === "confirm";
  }
};
_t = new WeakMap(), Qe = new WeakMap(), xt = new WeakMap(), ss = new WeakSet(), vr = function(t) {
  return new Promise((n) => {
    if (Array.isArray(t))
      return u(this.modalElement).html(t[0]), this.layout(), this._observeResize(), n();
    const { afterRender: i, ...o } = t;
    t = {
      afterRender: (r) => {
        this.layout(), i == null || i(r), this._observeResize(), n();
      },
      ...o
    }, Rn(
      /* @__PURE__ */ g(wr, { ...t }),
      this.modalElement
    );
  });
}, is = new WeakSet(), _r = function(t) {
  if (t)
    return j(this, ss, vr).call(this, {
      body: /* @__PURE__ */ g("div", { className: "modal-load-failed", children: t })
    });
}, Kt.DEFAULT = {
  ...Vt.DEFAULT,
  loadTimeout: 1e4,
  destoryOnHide: !0
};
let Bt = Kt;
const tf = '[data-toggle="modal"]', pa = class pa extends ot {
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
    } = this.options, n = t, i = this.$element.attr("href") || "";
    return n.type || (n.target || i[0] === "#" ? n.type = "static" : n.type = n.type || (n.url || i ? "ajax" : "custom")), !n.url && (n.type === "iframe" || n.type === "ajax") && i[0] !== "#" && (n.url = i), !n.key && n.id && (n.key = n.id), n;
  }
  _initModal() {
    const e = this._getBuilderOptions();
    let t = this._modal;
    if (t)
      return t.setOptions(e), t;
    if (e.type === "static") {
      const n = this._getStaticModalElement();
      if (!n)
        return;
      t = Vt.ensure(n, e);
    } else
      t = Bt.ensure(this.container, e);
    return this._modal = t, t.on("destroyed", () => {
      this._modal = void 0;
    }), t;
  }
  _getStaticModalElement() {
    let e = this.options.target;
    if (!e) {
      const { $element: t } = this;
      if (t.is("a")) {
        const n = t.attr("href");
        n != null && n.startsWith("#") && (e = n);
      }
    }
    return this.container.querySelector(e || ".modal");
  }
};
pa.NAME = "ModalTrigger";
let ui = pa;
u(document).on(`click${ui.NAMESPACE}`, tf, (s) => {
  const e = s.currentTarget;
  if (e) {
    const t = ui.ensure(e);
    t && (t.show(), s.preventDefault());
  }
});
var os;
let ef = (os = class extends Oo {
  beforeRender() {
    const e = super.beforeRender();
    return e.className = M(e.className, e.type ? `nav-${e.type}` : "", {
      "nav-stacked": e.stacked
    }), e;
  }
}, os.NAME = "nav", os);
const Hi = class Hi extends V {
};
Hi.NAME = "Nav", Hi.Component = ef;
let il = Hi;
function Ln(s, e) {
  const t = s.pageTotal || Math.ceil(s.recTotal / s.recPerPage);
  return typeof e == "string" && (e === "first" ? e = 1 : e === "last" ? e = t : e === "prev" ? e = s.page - 1 : e === "next" ? e = s.page + 1 : e === "current" ? e = s.page : e = Number.parseInt(e, 10)), e = e !== void 0 ? Math.max(1, Math.min(e < 0 ? t + e : e, t)) : s.page, {
    ...s,
    pageTotal: t,
    page: e
  };
}
function nf({
  key: s,
  type: e,
  btnType: t,
  page: n,
  format: i,
  pagerInfo: o,
  linkCreator: r,
  ...a
}) {
  const l = Ln(o, n);
  return a.text === void 0 && !a.icon && i && (a.text = typeof i == "function" ? i(l) : U(i, l)), a.url === void 0 && r && (a.url = typeof r == "function" ? r(l) : U(r, l)), a.disabled === void 0 && (a.disabled = n !== void 0 && l.page === o.page), /* @__PURE__ */ g(tt, { type: t, ...a });
}
function sf({
  key: s,
  type: e,
  page: t,
  text: n = "",
  pagerInfo: i,
  children: o,
  ...r
}) {
  const a = Ln(i, t);
  return n = typeof n == "function" ? n(a) : U(n, a), /* @__PURE__ */ g(Tc, { ...r, children: [
    o,
    n
  ] });
}
function of({
  key: s,
  type: e,
  btnType: t,
  count: n = 12,
  pagerInfo: i,
  onClick: o,
  linkCreator: r,
  ...a
}) {
  if (!i.pageTotal)
    return;
  const l = { ...a, square: !0 }, h = () => (l.text = "", l.icon = "icon-ellipsis-h", l.disabled = !0, /* @__PURE__ */ g(tt, { type: t, ...l })), d = (f, p) => {
    const m = [];
    for (let b = f; b <= p; b++) {
      l.text = b, delete l.icon, l.disabled = !1;
      const _ = Ln(i, b);
      r && (l.url = typeof r == "function" ? r(_) : U(r, _)), m.push(/* @__PURE__ */ g(tt, { type: t, ...l, onClick: o }));
    }
    return m;
  };
  let c = [];
  return c = [...d(1, 1)], i.pageTotal <= 1 || (i.pageTotal <= n ? c = [...c, ...d(2, i.pageTotal)] : i.page < n - 2 ? c = [...c, ...d(2, n - 2), h(), ...d(i.pageTotal, i.pageTotal)] : i.page > i.pageTotal - n + 3 ? c = [...c, h(), ...d(i.pageTotal - n + 3, i.pageTotal)] : c = [...c, h(), ...d(i.page - Math.ceil((n - 4) / 2), i.page + Math.floor((n - 4) / 2)), h(), ...d(i.pageTotal, i.pageTotal)]), c;
}
function rf({
  type: s,
  pagerInfo: e,
  linkCreator: t,
  items: n = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1e3, 2e3],
  dropdown: i = {},
  itemProps: o,
  ...r
}) {
  var l;
  i.items = i.items || n.map((h) => {
    const d = { ...e, recPerPage: h };
    return {
      ...o,
      text: `${h}`,
      active: h === e.recPerPage,
      url: typeof t == "function" ? t(d) : U(t, d)
    };
  });
  const { text: a = "" } = r;
  return r.text = typeof a == "function" ? a(e) : U(a, e), i.menu = { ...i.menu, className: M((l = i.menu) == null ? void 0 : l.className, "pager-size-menu") }, /* @__PURE__ */ g(Ac, { type: "dropdown", dropdown: i, ...r });
}
function af({
  key: s,
  page: e,
  type: t,
  btnType: n,
  pagerInfo: i,
  size: o,
  onClick: r,
  onChange: a,
  linkCreator: l,
  ...h
}) {
  const d = { ...h };
  let c;
  const f = (b) => {
    var _;
    c = Number((_ = b.target) == null ? void 0 : _.value) || 1, c = c > i.pageTotal ? i.pageTotal : c;
  }, p = (b) => {
    if (!(b != null && b.target))
      return;
    c = c <= i.pageTotal ? c : i.pageTotal;
    const _ = Ln(i, c);
    a && !a({ info: _, event: b }) || (b.target.href = d.url = typeof l == "function" ? l(_) : U(l, _));
  }, m = Ln(i, e || 0);
  return d.url = typeof l == "function" ? l(m) : U(l, m), /* @__PURE__ */ g("div", { className: M("input-group", "pager-goto-group", o ? `size-${o}` : ""), children: [
    /* @__PURE__ */ g("input", { type: "number", class: "form-control", max: i.pageTotal, min: "1", onInput: f }),
    /* @__PURE__ */ g(tt, { type: n, ...d, onClick: p })
  ] });
}
var Me;
let Jc = (Me = class extends Mt {
  get pagerInfo() {
    const { page: e = 1, recTotal: t = 0, recPerPage: n = 10 } = this.props;
    return { page: +e, recTotal: +t, recPerPage: +n, pageTotal: n ? Math.ceil(t / n) : 0 };
  }
  isBtnItem(e) {
    return e === "link" || e === "nav" || e === "size-menu" || e === "goto" || super.isBtnItem(e);
  }
  getItemRenderProps(e, t, n) {
    const i = super.getItemRenderProps(e, t, n), o = t.type || "item", { pagerInfo: r } = this;
    return o === "info" ? Object.assign(i, { pagerInfo: r }) : (o === "link" || o === "size-menu" || o === "nav" || o === "goto") && Object.assign(i, { pagerInfo: r, linkCreator: e.linkCreator }), i;
  }
}, Me.NAME = "pager", Me.defaultProps = {
  btnProps: {
    btnType: "ghost",
    size: "sm"
  }
}, Me.ItemComponents = {
  ...Mt.ItemComponents,
  link: nf,
  info: sf,
  nav: of,
  "size-menu": rf,
  goto: af
}, Me);
const zi = class zi extends V {
};
zi.NAME = "Pager", zi.Component = Jc;
let ol = zi;
const Bi = class Bi extends V {
};
Bi.NAME = "Pick", Bi.Component = ht;
let rl = Bi;
var tn, rs, as, Fi;
class Zc extends F {
  constructor(t) {
    super(t);
    C(this, tn, G());
    C(this, rs, G());
    C(this, as, (t) => {
      var i, o;
      const n = t.target.value;
      (o = (i = this.props).onSearch) == null || o.call(i, n), this.setState({ search: n }), t.stopPropagation();
    });
    C(this, Fi, (t) => {
      var n, i;
      t.stopPropagation(), (i = (n = this.props).onClear) == null || i.call(n), this.setState({ search: "" }, () => this.focus());
    });
    this.state = { search: t.defaultSearch ?? "" };
  }
  focus() {
    var t;
    (t = y(this, tn).current) == null || t.focus();
  }
  componentDidMount() {
    this.focus();
  }
  componentDidUpdate() {
    const { inline: t } = this.props;
    if (t) {
      const { current: n } = y(this, rs), { current: i } = y(this, tn);
      if (n && i) {
        const o = u(i).parent();
        o.width(Math.ceil(Math.min(n.clientWidth, o.closest(".picker").outerWidth() - 32)));
      }
    }
  }
  render(t, n) {
    const { placeholder: i, inline: o } = t, { search: r } = n, a = r.trim().length > 0;
    let l;
    return o ? l = /* @__PURE__ */ g("div", { className: "picker-search-measure", ref: y(this, rs), children: r }) : a ? l = /* @__PURE__ */ g("button", { type: "button", className: "btn picker-search-clear square size-sm ghost", onClick: y(this, Fi), children: /* @__PURE__ */ g("span", { className: "close" }) }) : l = /* @__PURE__ */ g("span", { className: "magnifier" }), /* @__PURE__ */ g("div", { className: `picker-search${o ? " is-inline" : ""}`, children: [
      /* @__PURE__ */ g(
        "input",
        {
          className: "form-control picker-search-input",
          type: "text",
          placeholder: i,
          value: r,
          onChange: y(this, as),
          onInput: y(this, as),
          ref: y(this, tn)
        }
      ),
      l
    ] });
  }
}
tn = new WeakMap(), rs = new WeakMap(), as = new WeakMap(), Fi = new WeakMap();
var en, ls, cs, hs;
class lf extends Vo {
  constructor() {
    super(...arguments);
    C(this, en, void 0);
    C(this, ls, void 0);
    C(this, cs, void 0);
    C(this, hs, void 0);
    $(this, en, G()), $(this, ls, (t) => {
      const { onDeselect: n, state: { selections: i } } = this.props, o = u(t.target).closest(".picker-deselect-btn").attr("data-value");
      n && i.length && typeof o == "string" && n(o), t.stopPropagation();
    }), $(this, cs, (t) => {
      this.props.changeState({ search: t });
    }), $(this, hs, () => {
      this.props.togglePop(!0, { search: "" });
    }), this._renderSelection = (t) => /* @__PURE__ */ g("div", { className: "picker-multi-selection", children: [
      /* @__PURE__ */ g("span", { className: "text", children: t.text }),
      this.props.disabled ? null : /* @__PURE__ */ g("div", { className: "picker-deselect-btn btn size-xs ghost", onClick: y(this, ls), "data-value": t.value, children: /* @__PURE__ */ g("span", { className: "close" }) })
    ] }, t.value);
  }
  _handleClick(t) {
    var n;
    super._handleClick(t), (n = y(this, en).current) == null || n.focus();
  }
  _getClass(t) {
    return M(
      super._getClass(t),
      "picker-select picker-select-multi form-control",
      t.disabled ? "disabled" : ""
    );
  }
  _renderSearch(t) {
    const { state: { search: n }, searchHint: i } = t;
    return /* @__PURE__ */ g(
      Zc,
      {
        inline: !0,
        ref: y(this, en),
        defaultSearch: n,
        onSearch: y(this, cs),
        onClear: y(this, hs),
        placeholder: i
      }
    );
  }
  _renderTrigger(t) {
    const { state: { selections: n = [], open: i }, search: o, placeholder: r, children: a } = this.props, l = i && o;
    return !l && !n.length ? /* @__PURE__ */ g("span", { className: "picker-select-placeholder", children: r }, "selections") : [
      /* @__PURE__ */ g("div", { className: "picker-multi-selections", children: [
        n.map(this._renderSelection),
        l ? this._renderSearch(t) : null
      ] }, "selections"),
      a,
      /* @__PURE__ */ g("span", { class: "caret" }, "caret")
    ];
  }
  _renderValue(t) {
    const { name: n, state: { value: i = "" }, id: o, valueList: r, emptyValue: a } = t, l = r.length ? r : [a];
    if (n)
      if (this.hasInput)
        u(`#${o}`).val(i);
      else
        return /* @__PURE__ */ g("select", { id: o, multiple: !0, className: "pick-value", name: n, style: { display: "none" }, children: l.map((h) => /* @__PURE__ */ g("option", { value: h, children: h }, h)) });
    return null;
  }
  componentDidMount() {
    super.componentDidMount();
    const { id: t, valueList: n, emptyValue: i } = this.props;
    u(`#${t}`).val(n.length ? n : [i]);
  }
  componentDidUpdate(t) {
    const { id: n, state: i, name: o, valueList: r, emptyValue: a } = this.props;
    o && t.state.value !== i.value && u(`#${n}`).val(r.length ? r : [a]).trigger("change", yr);
  }
}
en = new WeakMap(), ls = new WeakMap(), cs = new WeakMap(), hs = new WeakMap();
var ds, Wi, Vi, Ui, qi, Qc;
class cf extends Vo {
  constructor() {
    super(...arguments);
    C(this, qi);
    C(this, ds, G());
    C(this, Wi, (t) => {
      this.props.disabled || (this.props.onClear(), t.stopPropagation());
    });
    C(this, Vi, (t) => {
      this.props.changeState({ search: t });
    });
    C(this, Ui, () => {
      this.props.togglePop(!0, { search: "" });
    });
  }
  _handleClick(t) {
    var n;
    super._handleClick(t), (n = y(this, ds).current) == null || n.focus();
  }
  _getClass(t) {
    return M(
      super._getClass(t),
      "picker-select picker-select-single form-control",
      t.disabled ? "disabled" : ""
    );
  }
  _renderSearch(t) {
    const { state: { search: n } } = t;
    return /* @__PURE__ */ g(
      Zc,
      {
        ref: y(this, ds),
        defaultSearch: n,
        onSearch: y(this, Vi),
        onClear: y(this, Ui),
        placeholder: j(this, qi, Qc).call(this)
      }
    );
  }
  _renderTrigger(t) {
    const { children: n, state: { selections: i = [], open: o }, placeholder: r, search: a, disabled: l, clearable: h } = t, [d] = i, c = o && a;
    let f;
    c ? f = this._renderSearch(t) : d ? f = /* @__PURE__ */ g("span", { className: "picker-single-selection", children: d.text }, "main") : f = /* @__PURE__ */ g("span", { className: "picker-select-placeholder", children: r }, "main");
    const p = h && !c ? /* @__PURE__ */ g("button", { type: "button", className: "btn picker-deselect-btn size-sm square ghost", disabled: l, onClick: y(this, Wi), children: /* @__PURE__ */ g("span", { className: "close" }) }, "deselect") : null;
    return [
      f,
      n,
      p,
      c ? null : /* @__PURE__ */ g("span", { className: "caret" }, "caret")
    ];
  }
}
ds = new WeakMap(), Wi = new WeakMap(), Vi = new WeakMap(), Ui = new WeakMap(), qi = new WeakSet(), Qc = function() {
  const { searchHint: t, state: { value: n, selections: i } } = this.props;
  let o = t;
  if (o === void 0) {
    const r = i.find((a) => a.value === n);
    r && typeof r.text == "string" && (o = r.text);
  }
  return o;
};
const th = (s, e, t = "is-match") => s.reduce((n, i) => [...n].reduce((o, r) => {
  if (typeof r != "string")
    return o.push(r), o;
  const a = r.toLowerCase().split(i);
  if (a.length === 1)
    return o.push(r), o;
  let l = 0;
  return a.forEach((h, d) => {
    d && (o.push(/* @__PURE__ */ g("span", { class: t, children: r.substring(l, l + i.length) })), l += i.length), o.push(r.substring(l, l + h.length)), l += h.length;
  }), o;
}, []), e);
var Gi, Ki, eh, Yi, nh, Xi;
class hf extends ra {
  constructor() {
    super(...arguments);
    C(this, Ki);
    C(this, Yi);
    C(this, Gi, G());
    C(this, Xi, ({ item: t }) => {
      const n = t.key, { multiple: i, onToggleValue: o, onSelect: r, togglePop: a } = this.props;
      i ? o(n) : (r(n), a(!1, { search: "" }));
    });
  }
  componentDidMount() {
    super.componentDidMount();
    const t = this.element;
    t && u(t).on("mouseenter.picker.zui", ".menu-item", (n) => {
      const i = u(n.currentTarget);
      this.setHoverItem(i.children("a").attr("data-value") ?? "");
    });
  }
  componentWillUnmount() {
    super.componentDidMount();
    const t = this.element;
    t && u(t).off(".picker.zui");
  }
  setHoverItem(t) {
    this.props.changeState({ hoverItem: t }, () => {
      const n = j(this, Ki, eh).call(this);
      n != null && n.length && n.scrollIntoView({ block: "nearest", behavior: "smooth" });
    });
  }
  _getClass(t) {
    return M(
      super._getClass(t),
      "picker-menu"
    );
  }
  _renderPop(t) {
    const { menu: n } = t;
    return /* @__PURE__ */ g(
      Ie,
      {
        ref: y(this, Gi),
        className: "picker-menu-list",
        items: j(this, Yi, nh).call(this),
        onClickItem: y(this, Xi),
        ...n
      }
    );
  }
}
Gi = new WeakMap(), Ki = new WeakSet(), eh = function() {
  const t = this.element;
  if (t)
    return u(t).find(".menu-item>a.hover");
}, Yi = new WeakSet(), nh = function() {
  const { selections: t, items: n, hoverItem: i, search: o } = this.props.state, r = new Set(t.map((d) => d.value));
  let a = !1;
  const l = u.unique(o.toLowerCase().split(" ").filter((d) => d.length)), h = n.reduce((d, c) => {
    const {
      value: f = "",
      keys: p,
      text: m,
      className: b,
      ..._
    } = c;
    f === i && (a = !0);
    const v = m ?? f;
    return v && d.push({
      key: f,
      active: r.has(f),
      text: typeof v == "string" ? th(l, [v]) : /* @__PURE__ */ g(Cn, { content: v }),
      className: M(b, { hover: f === i }),
      "data-value": f,
      ..._
    }), d;
  }, []);
  return !a && h.length && (h[0].className = M(h[0].className, "hover")), h;
}, Xi = new WeakMap();
var nn, Ct, It, sn, wn;
let fi = (wn = class extends ht {
  constructor(t) {
    super(t);
    C(this, nn, void 0);
    C(this, Ct, void 0);
    C(this, It, void 0);
    C(this, sn, void 0);
    $(this, It, 0), this.isEmptyValue = (r) => y(this, sn).has(r), this.toggleValue = (r, a) => {
      if (!this.props.multiple)
        return a || r !== this.value ? this.setValue(r) : this.setValue();
      const { valueList: l } = this, h = l.indexOf(r);
      if (a !== h >= 0)
        return h > -1 ? l.splice(h, 1) : l.push(r), this.setValue(l);
    }, this.deselect = (r) => {
      const { valueList: a } = this, l = new Set(this.formatValueList(r)), h = a.filter((d) => !l.has(d));
      this.setValue(h);
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
    const { valueSplitter: n = ",", emptyValue: i = "" } = this.props;
    $(this, sn, new Set(i.split(n)));
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
    return y(this, sn).values().next().value;
  }
  async load() {
    let t = y(this, Ct);
    t && t.abort(), t = new AbortController(), $(this, Ct, t);
    const { items: n, searchDelay: i } = this.props, { search: o } = this.state;
    let r = [];
    if (typeof n == "function") {
      if (await ti(i || 500), y(this, Ct) !== t || (r = await n(o, { signal: t.signal }), y(this, Ct) !== t))
        return r;
    } else if (o.length) {
      const a = u.unique(o.toLowerCase().split(" ").filter((l) => l.length));
      a.length ? r = n.reduce((l, h) => {
        const {
          value: d,
          keys: c = "",
          text: f
        } = h;
        return a.every((p) => d.toLowerCase().includes(p) || c.toLowerCase().includes(p) || typeof f == "string" && f.toLowerCase().includes(p)) && l.push(h), l;
      }, []) : r = n;
    } else
      r = n;
    return $(this, Ct, void 0), r;
  }
  async update(t) {
    const { state: n, props: i } = this, o = y(this, nn) || {}, r = {};
    if ($(this, nn, o), (t || o.search !== n.search || i.items !== o.items) && (r.items = (await this.load()).filter((l) => (l.value = String(l.value), !this.isEmptyValue(l.value))), r.loading = !1, o.items = i.items, o.search = n.search), t || o.value !== n.value) {
      const l = r.items || n.items, h = new Map(l.map((d) => [d.value, d]));
      r.selections = this.valueList.reduce((d, c) => (this.isEmptyValue(c) || d.push(h.get(c) || { value: c }), d), []), o.value = n.value;
    }
    const a = r.items;
    i.required && !i.multiple && this.isEmptyValue(this.state.value) && Array.isArray(a) && a.length && (r.value = a[0].value), Object.keys(r).length && await this.changeState(r);
  }
  async tryUpdate() {
    y(this, It) && clearTimeout(y(this, It)), $(this, It, window.setTimeout(() => {
      $(this, It, 0), this.update();
    }, 50));
  }
  componentDidUpdate(t, n) {
    super.componentDidUpdate(t, n), this.tryUpdate();
  }
  componentDidMount() {
    super.componentDidMount(), this.tryUpdate();
  }
  componentWillUnmount() {
    var t;
    (t = y(this, Ct)) == null || t.abort(), $(this, Ct, void 0), $(this, nn, void 0), clearTimeout(y(this, It)), super.componentWillUnmount();
  }
  _getTriggerProps(t, n) {
    return {
      ...super._getTriggerProps(t, n),
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
  _getPopProps(t, n) {
    return {
      ...super._getPopProps(t, n),
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
    return t.Trigger || (t.multiple ? lf : cf);
  }
  formatValueList(t) {
    let n = [];
    return typeof t == "string" && t.length ? n = u.unique(t.split(this.props.valueSplitter ?? ",")) : Array.isArray(t) && (n = u.unique(t)), n.filter((i) => !this.isEmptyValue(i));
  }
  formatValue(t) {
    const n = this.formatValueList(t);
    return n.length ? n.join(this.props.valueSplitter ?? ",") : this.firstEmptyValue;
  }
  setValue(t = []) {
    if (this.props.disabled)
      return;
    !Array.isArray(t) && typeof t != "string" && (t = t !== null ? String(t) : this.firstEmptyValue);
    let n = this.formatValueList(t);
    if (!n.length)
      return this.changeState({ value: this.firstEmptyValue });
    const { items: i, limitValueInList: o } = this.props;
    if (o) {
      const a = new Set((Array.isArray(i) ? i : this.state.items).map((l) => String(l.value)));
      n = n.filter((l) => a.has(l));
    }
    const r = this.formatValue(n);
    return this.changeState({ value: r });
  }
}, nn = new WeakMap(), Ct = new WeakMap(), It = new WeakMap(), sn = new WeakMap(), wn.defaultProps = {
  ...ht.defaultProps,
  className: "picker",
  valueSplitter: ",",
  limitValueInList: !0,
  search: !0,
  emptyValue: ""
}, wn.Pop = hf, wn);
const Ji = class Ji extends V {
};
Ji.NAME = "Picker", Ji.Component = fi;
let al = Ji;
let df = class extends F {
  render(e) {
    const {
      id: t,
      popup: n,
      title: i,
      content: o,
      style: r,
      className: a,
      closeBtn: l,
      arrow: h,
      headingClass: d,
      titleClass: c,
      contentClass: f,
      arrowStyle: p,
      onlyInner: m
    } = e;
    let b = /* @__PURE__ */ g(Cn, { content: o }, "content");
    (f || i) && (b = /* @__PURE__ */ g("div", { className: f, children: b }, "content"));
    const _ = [], v = l ? /* @__PURE__ */ g("button", { className: "btn ghost square size-sm btn-close", "data-dismiss": "popover", children: /* @__PURE__ */ g("span", { className: "close" }) }) : null;
    return i ? _.push(/* @__PURE__ */ g("div", { className: d, children: [
      i ? /* @__PURE__ */ g("div", { className: c, children: i }) : null,
      v
    ] }, "heading")) : _.push(v), _.push(b), h && _.push(/* @__PURE__ */ g("div", { className: typeof h == "string" ? h : "arrow", style: p }, "arrow")), m ? _ : /* @__PURE__ */ g("div", { id: t, className: M("popover", a, { popup: n }), style: r, children: _ });
  }
};
const Zi = class Zi extends V {
};
Zi.NAME = "PopoverPanel", Zi.Component = df;
let xr = Zi;
const uf = '[data-toggle="popover"]', ll = "show", cl = "in", ze = class ze extends ot {
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
    const { trigger: e, id: t, triggerEvent: n } = this.options;
    this._triggerEvent = n, this._id = t || `popover_${this.gid}`;
    const i = this.getTriggerElement();
    if (i instanceof HTMLElement) {
      const r = u(i), { namespace: a } = this;
      e === "hover" ? r.on(`mouseenter${a}`, (l) => {
        this.show({ delay: !0, event: l });
      }).on(`mouseleave${a}`, () => {
        this.hide();
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
    const { delay: t, event: n } = e || {};
    if (n && (this._triggerEvent = n), t)
      return this._setTimer(() => {
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
    const o = u(i), { animation: r, mask: a, onShow: l, onShown: h } = this.options;
    o.addClass(ll), r && o.addClass(r === !0 ? "fade" : r), this._shown = !0, this.render(), l == null || l.call(this), this.emit("show"), this._virtual || u(this._triggerElement).addClass("with-popover-show"), this._setTimer(() => {
      o.addClass(cl), this._setTimer(() => {
        h == null || h.call(this), this.emit("shown");
      }, 200), a && u(document).on(`click${this.namespace}`, this._onClickDoc);
    }, 50);
  }
  hide() {
    (!this._shown || !this._targetElement) && this._setTimer();
    const { destroyOnHide: e, animation: t, onHide: n, onHidden: i } = this.options, o = u(this._targetElement);
    this._shown = !1, n == null || n.call(this), this.emit("hide"), o.removeClass(cl), this._virtual || u(this._triggerElement).removeClass("with-popover-show").removeAttr("data-popover-placement"), u(document).off(this.namespace), this._setTimer(() => {
      i == null || i.call(this), this.emit("hidden"), o.removeClass(ll), e && this._setTimer(() => {
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
    this._setTimer(), this._destoryTarget();
  }
  layout() {
    const e = this._triggerElement, t = this._targetElement, n = this._layoutWatcher;
    if (!t || !e || !this._shown) {
      n && (n(), this._layoutWatcher = void 0);
      return;
    }
    n || (this._layoutWatcher = sa(e, t, () => {
      const { width: i, animation: o, name: r = "popover" } = this.options;
      i === "100%" && !this._virtual && u(t).css({ width: u(e).width() }), Fo(...this._getLayoutOptions()).then(({ x: a, y: l, middlewareData: h, placement: d }) => {
        const c = u(t).css({
          left: a,
          top: l
        }), f = d.split("-")[0], p = {
          top: "bottom",
          right: "left",
          bottom: "top",
          left: "right"
        }[f], m = h.arrow;
        m && c.find(".arrow").css({
          left: m.x,
          top: m.y
        }).attr("class", `arrow ${r}-arrow arrow-${p}`), o === !0 && c.attr("class", `${c.attr("class").split(" ").filter((b) => b !== "fade" && !b.startsWith("fade-from")).join(" ")} fade-from-${p}`), this._virtual || u(this._triggerElement).attr("data-popover-placement", f);
      });
    }));
  }
  render(e) {
    super.render(e);
    const t = this._targetElement;
    if (!t)
      return;
    const n = this._getRenderOptions(), i = u(t);
    if (i.toggleClass("popup", n.popup).css(n.style), n.className && i.setClass(n.className), this._dynamic) {
      let o = this._panel;
      o && o.element !== t && (o.destroy(), o = void 0), o ? o.render(n) : (o = new xr(t, n), o.on("inited", () => this.layout())), this._panel = o;
    } else
      n.arrow && (i.find(".arrow").length || i.append(u('<div class="arrow"></div>').css(n.arrowStyle))), this.layout();
  }
  _getLayoutOptions() {
    const e = this._triggerElement, t = this._targetElement, { placement: n, flip: i, shift: o, offset: r, arrow: a } = this.options, l = a ? t.querySelector(".arrow") : null, h = l ? typeof a == "number" ? a : 5 : 0;
    return [e, t, {
      placement: n,
      middleware: [
        i ? jo() : null,
        o ? In(typeof o == "object" ? o : void 0) : null,
        r || h ? Ho((r || 0) + h) : null,
        a ? hr({ element: l }) : null
      ].filter(Boolean)
    }];
  }
  _getRenderOptions() {
    const { name: e = "popover" } = this.options, {
      popup: t,
      title: n,
      content: i,
      headingClass: o = `${e}-heading`,
      titleClass: r = `${e}-title`,
      contentClass: a = `${e}-content`,
      style: l,
      className: h = e,
      closeBtn: d,
      arrow: c
    } = this.options;
    return {
      popup: t,
      title: n,
      titleClass: r,
      headingClass: o,
      contentClass: a,
      content: i,
      style: { zIndex: this.constructor.Z_INDEX++, ...l },
      className: h,
      closeBtn: d,
      arrow: c ? `arrow ${e}-arrow` : !1,
      arrowStyle: { "--arrow-size": `${typeof c == "number" ? c : 5}px` },
      onlyInner: !0
    };
  }
  _destoryTarget() {
    var e, t, n;
    (e = this._layoutWatcher) == null || e.call(this), this._layoutWatcher = void 0, this._dynamic && ((t = this._panel) == null || t.destroy(), (n = this._targetElement) == null || n.remove(), this._panel = void 0, this._targetElement = void 0);
  }
  _setTimer(e, t = 0) {
    this._timer && clearTimeout(this._timer), e && (this._timer = window.setTimeout(() => {
      this._timer = 0, e();
    }, t));
  }
  _createTarget() {
    const { container: e = "body" } = this.options, t = u(e);
    let n = t.find(`#${this._id}`);
    return n.length || (n = u("<div />").attr({ id: this._id, class: "popover" }).appendTo(t)), n[0];
  }
  static show(e) {
    const { element: t, event: n, ...i } = e, o = t || (n == null ? void 0 : n.currentTarget);
    return this.ensure(o instanceof HTMLElement ? o : document.body, { element: o, show: !0, destroyOnHide: !0, triggerEvent: n, ...i });
  }
};
ze.NAME = "Popover", ze.Z_INDEX = 1700, ze.MULTI_INSTANCE = !0, ze.DEFAULT = {
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
let Nt = ze;
u(document).on(`click${Nt.NAMESPACE} mouseenter${Nt.NAMESPACE}`, uf, (s) => {
  const e = u(s.currentTarget);
  if (e.length && !e.data(Nt.KEY)) {
    const t = e.data("trigger") || "click";
    if ((s.type === "mouseover" ? "hover" : "click") !== t)
      return;
    Nt.ensure(e, { show: !0, triggerEvent: s }), s.preventDefault();
  }
});
const Qi = class Qi extends ot {
  init() {
    const { trigger: e } = this.options;
    this.initTarget(), this.initMask(), this.initArrow(), this.createPopper(), this.toggle = () => {
      if (this.$target.hasClass("hidden")) {
        this.show();
        return;
      }
      this.hide();
    }, this.$element.addClass("z-50").on(e, this.toggle);
  }
  destroy() {
    this.cleanup(), this.$element.off(this.options.trigger, this.toggle), this.$target.remove();
  }
  computePositionConfig() {
    const { placement: e, strategy: t } = this.options, n = {
      placement: e,
      strategy: t,
      middleware: []
    }, { flip: i, shift: o, arrow: r, offset: a } = this.options;
    return i && n.middleware.push(jo()), o && n.middleware.push(o === !0 ? In() : In(o)), r && n.middleware.push(hr({ element: this.$arrow[0] })), a && n.middleware.push(Ho(a)), n;
  }
  createPopper() {
    const e = this.element, t = this.$target[0];
    this.cleanup = sa(e, t, () => {
      Fo(e, t, this.computePositionConfig()).then(({ x: n, y: i, placement: o, middlewareData: r }) => {
        if (Object.assign(t.style, {
          left: `${n}px`,
          top: `${i}px`
        }), !hr || !r.arrow)
          return;
        const { x: a, y: l } = r.arrow, h = {
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
          [h]: "-4px"
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
    const { strategy: n } = this.options;
    t.addClass(n), t.addClass("hidden"), t.addClass("z-50"), t.on("click", (i) => {
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
Qi.NAME = "Popovers", Qi.DEFAULT = {
  placement: "bottom",
  strategy: "fixed",
  flip: !0,
  shift: { padding: 5 },
  arrow: !1,
  offset: 1,
  trigger: "click",
  mask: !0
};
let Cr = Qi;
var us, fs, Qt, to, ps, ms, gs, $r, ys;
let sh = (ys = class extends F {
  constructor(t) {
    super(t);
    C(this, gs);
    C(this, us, void 0);
    C(this, fs, G());
    C(this, Qt, 0);
    C(this, to, (t) => {
      const n = this.state.value;
      t.stopPropagation(), this.setState({ value: "" }, () => {
        const { onChange: i, onClear: o } = this.props;
        o == null || o(t), this.focus(), n.trim() !== "" && (i == null || i("", t));
      });
    });
    C(this, ps, (t) => {
      const n = this.state.value, i = t.target.value, { onChange: o } = this.props;
      this.setState({ value: i }, () => {
        !o || n === i || (j(this, gs, $r).call(this), $(this, Qt, window.setTimeout(() => {
          o(i, t), $(this, Qt, 0);
        }, this.props.delay || 0)));
      });
    });
    C(this, ms, (t) => {
      const n = t.type === "focus";
      this.setState({ focus: n }, () => {
        const i = n ? this.props.onFocus : this.props.onBlur;
        i == null || i(t);
      });
    });
    this.state = { focus: !1, value: t.defaultValue || "" }, $(this, us, t.id || `search-box-${u.guid++}`);
  }
  get id() {
    return y(this, us);
  }
  get input() {
    return y(this, fs).current;
  }
  focus() {
    var t;
    (t = this.input) == null || t.focus();
  }
  componentWillUnmount() {
    j(this, gs, $r).call(this);
  }
  render(t, n) {
    const { style: i, className: o, rootClass: r, rootStyle: a, readonly: l, disabled: h, circle: d, placeholder: c, mergeIcon: f, searchIcon: p, clearIcon: m } = t, { focus: b, value: _ } = n, { id: v } = this, x = typeof _ != "string" || !_.trim().length;
    let k, N, S;
    return p && (S = p === !0 ? /* @__PURE__ */ g("span", { class: "magnifier" }) : /* @__PURE__ */ g(Z, { icon: p })), !f && p && (k = /* @__PURE__ */ g("label", { for: v, class: "input-control-prefix", children: S }, "prefix")), m && !x ? N = /* @__PURE__ */ g(
      "button",
      {
        type: "button",
        class: "btn ghost size-sm square rounded-full",
        onClick: y(this, to),
        children: m === !0 ? /* @__PURE__ */ g("span", { class: "close" }) : /* @__PURE__ */ g(Z, { icon: m })
      }
    ) : f && p && (N = S), N && (N = /* @__PURE__ */ g("label", { for: v, class: "input-control-suffix", children: N }, "suffix")), /* @__PURE__ */ g("div", { class: M("search-box input-control", r, { focus: b, empty: x, "has-prefix-icon": k, "has-suffix-icon": N }), style: a, children: [
      k,
      /* @__PURE__ */ g(
        "input",
        {
          ref: y(this, fs),
          id: v,
          type: "text",
          class: M("form-control", o, { "rounded-full": d }),
          style: i,
          placeholder: c,
          disabled: h,
          readonly: l,
          value: _,
          onInput: y(this, ps),
          onChange: y(this, ps),
          onFocus: y(this, ms),
          onBlur: y(this, ms)
        }
      ),
      N
    ] });
  }
}, us = new WeakMap(), fs = new WeakMap(), Qt = new WeakMap(), to = new WeakMap(), ps = new WeakMap(), ms = new WeakMap(), gs = new WeakSet(), $r = function() {
  y(this, Qt) && clearTimeout(y(this, Qt)), $(this, Qt, 0);
}, ys.defaultProps = {
  clearIcon: !0,
  searchIcon: !0,
  delay: 500
}, ys);
var vn;
let Fp = (vn = class extends V {
}, vn.NAME = "SearchBox", vn.Component = sh, vn);
const eo = class eo extends V {
};
eo.NAME = "Toolbar", eo.Component = Mt;
let hl = eo;
const ff = '[data-toggle="tooltip"]', no = class no extends Nt {
  _getRenderOptions() {
    const { type: e, className: t, title: n, content: i } = this.options;
    let o = n, r = i;
    return r === void 0 && (r = o, o = void 0), {
      ...super._getRenderOptions(),
      title: o,
      content: r,
      className: M("tooltip", e, t, o ? "tooltip-has-title" : ""),
      contentClass: o ? "tooltip-content" : ""
    };
  }
};
no.NAME = "Tooltip", no.DEFAULT = {
  ...Nt.DEFAULT,
  trigger: "hover",
  delay: 500,
  closeBtn: !1,
  popup: !1,
  name: "tooltip",
  animation: "fade",
  destroyOnHide: 5e3
};
let Ot = no;
u(document).on(`click${Ot.NAMESPACE} mouseenter${Ot.NAMESPACE}`, ff, (s) => {
  const e = u(s.currentTarget);
  if (e.length && !e.data(Ot.KEY)) {
    const t = e.data("trigger") || "hover";
    if ((s.type === "mouseover" ? "hover" : "click") !== t)
      return;
    Ot.ensure(e, { show: Ot.DEFAULT.delay || !0 }), s.preventDefault();
  }
});
function pf({
  type: s,
  component: e,
  className: t,
  children: n,
  content: i,
  style: o,
  attrs: r,
  url: a,
  disabled: l,
  active: h,
  icon: d,
  text: c,
  target: f,
  trailingIcon: p,
  hint: m,
  checked: b,
  actions: _,
  show: v,
  level: x = 0,
  items: k,
  ...N
}) {
  const S = Array.isArray(_) ? { items: _ } : _;
  return S && (S.btnProps || (S.btnProps = { size: "sm" }), S.className = M("tree-actions not-nested-toggle", S.className)), /* @__PURE__ */ g(
    "div",
    {
      className: M("tree-item-content", t, { disabled: l, active: h }),
      title: m,
      "data-target": f,
      style: Object.assign({ paddingLeft: `calc(${x} * var(--tree-indent, 20px))` }, o),
      "data-level": x,
      ...r,
      ...N,
      children: [
        /* @__PURE__ */ g("span", { class: `tree-toggle-icon${k ? " state" : ""}`, children: k ? /* @__PURE__ */ g("span", { class: `caret-${v ? "down" : "right"}` }) : null }),
        typeof b == "boolean" ? /* @__PURE__ */ g("div", { class: `tree-checkbox checkbox-primary${b ? " checked" : ""}`, children: /* @__PURE__ */ g("label", {}) }) : null,
        /* @__PURE__ */ g(Z, { icon: d, className: "tree-icon" }),
        a ? /* @__PURE__ */ g("a", { className: "text tree-link not-nested-toggle", href: a, children: c }) : /* @__PURE__ */ g("span", { class: "text", children: c }),
        /* @__PURE__ */ g(Cn, { content: i }),
        n,
        S ? /* @__PURE__ */ g(Mt, { ...S }) : null,
        /* @__PURE__ */ g(Z, { icon: p, className: "tree-trailing-icon" })
      ]
    }
  );
}
var _n;
let ih = (_n = class extends Qr {
  get nestedTrigger() {
    return this.props.nestedTrigger || "click";
  }
  get menuName() {
    return "tree";
  }
  getNestedMenuProps(e) {
    const t = super.getNestedMenuProps(e), { collapsedIcon: n, expandedIcon: i, normalIcon: o, itemActions: r } = this.props;
    return {
      collapsedIcon: n,
      expandedIcon: i,
      normalIcon: o,
      itemActions: r,
      ...t
    };
  }
  getItemRenderProps(e, t, n) {
    const i = super.getItemRenderProps(e, t, n), { collapsedIcon: o, expandedIcon: r, normalIcon: a, itemActions: l } = e;
    return i.icon === void 0 && (i.icon = i.items ? i.show ? r : o : a), i.actions === void 0 && l && (i.actions = typeof l == "function" ? l(t) : l), i;
  }
  renderToggleIcon() {
    return null;
  }
  beforeRender() {
    const e = super.beforeRender(), { hover: t } = this.props;
    return t && (e.className = M(e.className, "tree-hover")), e;
  }
}, _n.ItemComponents = {
  item: pf
}, _n.NAME = "tree", _n);
const so = class so extends V {
};
so.NAME = "Tree", so.Component = ih;
let dl = so;
const io = class io extends ot {
  init() {
    const { multiple: e, defaultFileList: t, limitSize: n } = this.options;
    this.fileMap = /* @__PURE__ */ new Map(), this.renameMap = /* @__PURE__ */ new Map(), this.itemMap = /* @__PURE__ */ new Map(), this.dataTransfer = new DataTransfer(), this.limitBytes = n ? Od(n) : Number.MAX_VALUE, this.currentBytes = 0, e || (this.options.limitCount = 1), this.$element.addClass("upload"), this.initFileInputCash(), this.initUploadCash(), t && this.addFileItem(t);
  }
  initUploadCash() {
    const { name: e, uploadText: t, uploadIcon: n, listPosition: i, btnClass: o, tip: r, draggable: a } = this.options;
    this.$list = u('<ul class="file-list py-1"></ul>');
    const l = u(`<span class="upload-tip">${r}</span>`);
    if (!a) {
      if (this.$label = u(`<label class="btn ${o}" for="${e}">${t}</label>`), n) {
        const f = u(`<i class="icon icon-${n}"></i>`);
        this.$label.prepend(f);
      }
      const c = i === "bottom" ? [this.$label, l, this.$list] : [this.$list, this.$label, l];
      this.$element.append(this.$input, ...c);
      return;
    }
    const h = u(`<span class="text-primary">${t}</span>`);
    if (n) {
      const c = u(`<i class="icon icon-${n} mr-1"></i>`);
      h.prepend(c);
    }
    this.$label = u(`<label class="draggable-area col justify-center items-center cursor-pointer block w-full h-16 border border-dashed border-gray" for="${e}"></label>`).append(h).append(l), this.bindDragEvent();
    const d = i === "bottom" ? [this.$label, this.$list] : [this.$list, this.$label];
    this.$element.append(this.$input, ...d);
  }
  bindDragEvent() {
    this.$label.on("dragover", (e) => {
      e.preventDefault(), console.log("dragover"), this.$label.hasClass("border-primary") || (this.$label.removeClass("border-gray"), this.$label.addClass("border-primary"));
    }).on("dragleave", (e) => {
      e.preventDefault(), this.$label.removeClass("border-primary"), this.$label.addClass("border-gray");
    }).on("drop", (e) => {
      var n;
      e.preventDefault(), this.$label.removeClass("border-primary"), this.$label.addClass("border-gray");
      const t = Array.from(((n = e.dataTransfer) == null ? void 0 : n.files) ?? []);
      console.log(e.dataTransfer.files), this.addFileItem(t);
    });
  }
  initFileInputCash() {
    const { name: e, multiple: t, accept: n } = this.options;
    this.$input = u("<input />").addClass("hidden").prop("type", "file").prop("name", e).prop("id", e).prop("multiple", t).on("change", (i) => {
      const o = i.target.files;
      if (!o)
        return;
      const r = [...o];
      this.addFileItem(r);
    }), n && this.$input.prop("accept", n);
  }
  addFile(e) {
    const { multiple: t, onSizeChange: n } = this.options;
    t || (this.renameMap.clear(), this.fileMap.clear(), this.dataTransfer.items.clear(), this.currentBytes = e.size), this.renameMap.set(e.name, e.name), this.fileMap.set(e.name, e), this.dataTransfer.items.add(e), this.$input.prop("files", this.dataTransfer.files), this.currentBytes += e.size, n == null || n(this.currentBytes);
  }
  renameDuplicatedFile(e) {
    if (!this.fileMap.has(e.name))
      return e;
    const t = e.name.lastIndexOf(".");
    if (t === -1)
      return this.renameDuplicatedFile(new File([e], `${e.name}(1)`));
    const n = e.name.substring(0, t), i = e.name.substring(t);
    return this.renameDuplicatedFile(new File([e], `${n}(1)${i}`));
  }
  filterFiles(e) {
    const { accept: t } = this.options;
    if (!t)
      return e;
    const n = t.replace(/\s/g, "").split(","), i = [], o = [], r = [];
    return n.forEach((a) => {
      a.endsWith("/*") ? o.push(a.substring(0, a.length - 1)) : a.includes("/") ? i.push(a) : a.startsWith(".") && r.push(a);
    }), e.filter((a) => i.includes(a.type) || o.some((l) => a.type.startsWith(l)) || r.some((l) => a.name.endsWith(l)));
  }
  addFileItem(e) {
    e = this.filterFiles(e);
    const { multiple: t, limitCount: n, exceededSizeHint: i, exceededCountHint: o, onAdd: r } = this.options;
    if (t) {
      const h = [];
      for (let d of e) {
        if (n && this.fileMap.size >= n)
          return r == null || r(h), alert(o);
        if (this.currentBytes + d.size > this.limitBytes)
          return r == null || r(h), alert(i);
        d = this.renameDuplicatedFile(d);
        const c = this.createFileItem(d);
        this.itemMap.set(d.name, c), this.$list.append(c), h.push(d);
      }
      r == null || r(h);
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
    const n = this.fileMap.get(t);
    if (!n)
      return;
    const { onDelete: i, onSizeChange: o } = this.options, r = this.itemMap.get(n.name);
    this.itemMap.delete(n.name), r == null || r.addClass("hidden");
    const a = (l = r == null ? void 0 : r.find(".file-delete")) == null ? void 0 : l.data("tooltip");
    a && (a.destroy(), a.tooltip.remove()), setTimeout(() => r == null ? void 0 : r.remove(), 3e3), i == null || i(n), this.fileMap.delete(n.name), this.currentBytes -= n.size, o == null || o(this.currentBytes), this.dataTransfer = new DataTransfer(), this.fileMap.forEach((h) => this.dataTransfer.items.add(h)), this.$input.prop("files", this.dataTransfer.files);
  }
  renameFileItem(e, t) {
    var o, r;
    const n = this.renameMap.get(e.name);
    this.renameMap.set(e.name, t), n && (e = this.fileMap.get(n) ?? e);
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
    const { useIconBtn: e, renameText: t, renameIcon: n, renameClass: i } = this.options;
    if (e) {
      const o = u(`<button class="btn btn-link h-5 w-5 p-0 ${i}"><i class="icon icon-${n}"></i></button>`).prop("type", "button").addClass("file-action file-rename");
      return new Ot(o, { title: t }), o;
    }
    return u("<button />").prop("type", "button").addClass(`btn size-sm rounded-sm text-primary canvas file-action file-rename ${i}`).html(t);
  }
  fileDeleteBtn() {
    const { useIconBtn: e, deleteText: t, deleteIcon: n, deleteClass: i } = this.options;
    if (e) {
      const o = u(`<button class="btn btn-link h-5 w-5 p-0 ${i}"><i class="icon icon-${n}"></i></button>`).prop("type", "button").addClass("file-action file-delete");
      return o.data("tooltip", new Ot(o, { title: t })), o;
    }
    return u("<button />").html(t).prop("type", "button").addClass(`btn size-sm rounded-sm text-primary canvas file-action file-delete ${i}`);
  }
  fileName(e) {
    return u(`<span class="file-name">${e}</span>`);
  }
  fileSize(e) {
    return u(`<span class="file-size text-gray">${Vs(e)}</span>`);
  }
  createFileInfo(e) {
    const { renameBtn: t, deleteBtn: n, showSize: i } = this.options, o = u('<div class="file-info flex items-center gap-2"></div>');
    return o.append(this.fileName(e.name)), i && o.append(this.fileSize(e.size)), t && o.append(
      this.fileRenameBtn().on("click", (r) => {
        o.addClass("hidden").closest(".file-item").find(".input-rename-container.hidden").removeClass("hidden");
        const a = u(r.target).closest("li").find("input")[0];
        a.focus(), a.value.lastIndexOf(".") !== -1 && a.setSelectionRange(0, a.value.lastIndexOf("."));
      })
    ), n && o.append(
      this.fileDeleteBtn().on("click", () => this.deleteFileItem(e.name))
    ), o;
  }
  createRenameContainer(e) {
    const { confirmText: t, cancelText: n, duplicatedHint: i } = this.options, o = u('<div class="input-group input-rename-container hidden"></div>'), r = u("<input />").addClass("form-control").prop("type", "text").prop("autofocus", !0).prop("defaultValue", e.name).on("keydown", (d) => {
      if (d.key === "Enter") {
        const c = o.closest(".file-item"), f = c.find(".file-name");
        if (f.html() === r.val()) {
          o.addClass("hidden"), c.find(".file-info.hidden").removeClass("hidden");
          return;
        }
        if (this.fileMap.has(r.val()))
          return alert(i);
        this.renameFileItem(e, r.val()), o.addClass("hidden"), c.find(".file-info.hidden").removeClass("hidden"), f.html(r.val());
      } else
        d.key === "Escape" && (r.val(e.name), o.addClass("hidden").closest(".file-item").find(".file-info.hidden").removeClass("hidden"));
    }), a = u("<button />").addClass("btn primary rename-confirm-btn").prop("type", "button").html(t).on("click", () => {
      const d = o.closest(".file-item"), c = d.find(".file-name");
      if (c.html() === r.val()) {
        o.addClass("hidden"), d.find(".file-info.hidden").removeClass("hidden");
        return;
      }
      if (this.fileMap.has(r.val()))
        return alert(i);
      this.renameFileItem(e, r.val()), o.addClass("hidden"), d.find(".file-info.hidden").removeClass("hidden"), c.html(r.val());
    }), l = u("<button />").prop("type", "button").addClass("btn rename-cancel-btn").html(n).on("click", () => {
      r.val(e.name), o.addClass("hidden").closest(".file-item").find(".file-info.hidden").removeClass("hidden");
    }), h = u('<div class="btn-group"></div').append(a).append(l);
    return o.append(r).append(h);
  }
};
io.NAME = "Upload", io.DEFAULT = {
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
let kr = io;
const oo = class oo extends kr {
  init() {
    this.initUploadButtonItemCash(), this.options.onSizeChange = () => {
      this.$uploadInfo.html(this.options.totalCountText.replace("%s", this.fileMap.size.toString()).replace("%s", this.fileMap.size.toString())), this.fileMap.size > 0 ? (this.$tip.remove(), this.$list.append(this.$uploadButtonItem)) : (this.$uploadButtonItem.remove(), this.$label.append(this.$tip));
    }, super.init(), this.$list.addClass("flex");
  }
  initUploadButtonItemCash() {
    this.$uploadButtonItem = u(`<label class="upload-button-item order-last" for="${this.options.name}" />`).addClass("flex justify-center items-center cursor-pointer").css({ width: 120, height: 120, background: "var(--color-slate-100)" }).append(u('<i class="icon icon-plus" />'));
  }
  initUploadCash() {
    const { name: e, tip: t, uploadText: n, uploadIcon: i, totalCountText: o } = this.options;
    this.$list = u('<ul class="file-list py-1 flex-wrap gap-x-4 gap-y-4"></ul>'), this.$label = u('<div class="draggable-area relative block w-full border border-dashed border-gray"></div>').css({ minHeight: 64 });
    const r = u(`<label for="${e}" class="text-primary cursor-pointer">${n}</label>`);
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
    const n = t.replace(/\s/g, "").replace(/\./g, "image/").split(",");
    return e.filter((i) => n.includes(i.type));
  }
  createFileItem(e) {
    const t = super.createFileItem(e).addClass("relative").removeClass("flex items-center gap-2 my-1");
    this.setImageUrl(e, t);
    const { deleteBtn: n, showSize: i } = this.options;
    return n && t.append(
      this.fileDeleteBtn().addClass("absolute right-0 top-0 text-white").css({ background: "var(--color-slate-500)" }).on("click", () => this.deleteFileItem(e.name))
    ), i && t.append(
      this.fileSize(e.size).addClass("file-size label text-white circle darker absolute px-1 hidden").removeClass("text-gray").css({ top: 96, left: 4 })
    ), t;
  }
  setImageUrl(e, t) {
    const n = new FileReader();
    n.onload = () => {
      u('<div class="img flex-none" />').addClass("rounded").css({ backgroundImage: `url(${n.result})` }).prependTo(t);
    }, n.readAsDataURL(e);
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
    const { duplicatedHint: t } = this.options, n = u("<input />").addClass("input-rename-container border-primary border hidden").prop("type", "text").prop("autofocus", !0).prop("defaultValue", e.name).css({ width: 120 }).on("keydown", (i) => {
      if (i.key === "Enter") {
        const o = n.closest(".file-item").find(".file-name");
        if (o.html() === n.val()) {
          n.addClass("hidden"), o.closest(".file-info").removeClass("hidden");
          return;
        }
        if (this.fileMap.has(n.val()))
          return alert(t);
        this.renameFileItem(e, n.val()), n.addClass("hidden"), o.html(n.val()).closest(".file-info").removeClass("hidden");
      } else
        i.key === "Escape" && n.val(e.name).addClass("hidden").closest(".file-item").find(".file-name").removeClass("hidden");
    }).on("blur", () => {
      const i = n.closest(".file-item").find(".file-name");
      if (i.html() === n.val()) {
        n.addClass("hidden"), i.closest(".file-info").removeClass("hidden");
        return;
      }
      if (this.fileMap.has(n.val()))
        return alert(t);
      this.renameFileItem(e, n.val()), n.addClass("hidden"), i.html(n.val()).closest(".file-info").removeClass("hidden");
    });
    return n;
  }
};
oo.NAME = "UploadImgs", oo.DEFAULT = {
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
let ul = oo;
var Dt, we, on, ve, bs, K, ro, oh, _e, Nn, ao, rh, lo, ah;
const co = class co extends ot {
  constructor() {
    super(...arguments);
    C(this, ro);
    C(this, _e);
    C(this, ao);
    C(this, lo);
    C(this, Dt, void 0);
    C(this, we, void 0);
    C(this, on, null);
    C(this, ve, []);
    C(this, bs, 0);
    C(this, K, []);
  }
  afterInit() {
    const t = u(this.element), n = t.find(".form-batch-table").addClass("borderless");
    let i = n.find("tbody");
    i.length || (i = u("<tbody></tbody>").appendTo(n)), $(this, Dt, i), $(this, we, t.find(".form-batch-template").get(0)), $(this, ve, []), n.find("thead>tr>.form-batch-head").each((o, r) => {
      const l = u(r).data();
      l && y(this, ve).push(l);
    }), t.on("click", (o) => {
      const r = u(o.target).closest(".form-batch-btn");
      if (!r.length)
        return;
      const a = r.data("type"), h = r.closest("tr").data("index");
      a === "add" ? this.addRow(h) : a === "delete" ? this.deleteRow(h) : a === "ditto" && this.toggleDitto(r);
    }).on("change", ".form-batch-input", (o) => {
      this.syncDitto(u(o.target));
    }), this.render();
  }
  destroy() {
    u(this.element).off("click change"), $(this, Dt, void 0), $(this, we, void 0), y(this, ve).length = 0, y(this, K).length = 0;
  }
  render(t) {
    super.render(t), y(this, K).length ? j(this, _e, Nn).call(this) : ($(this, on, null), j(this, ro, oh).call(this));
  }
  addRow(t) {
    const n = _a(this, bs)._++;
    typeof t == "number" && t >= 0 && t <= y(this, K).length ? y(this, K).splice(t + 1, 0, n) : (t = y(this, K).length, y(this, K).push(n)), j(this, _e, Nn).call(this, void 0, t);
  }
  deleteRow(t) {
    var i;
    if (y(this, K).length <= 1 || typeof t != "number" || t < 0 || t >= y(this, K).length)
      return !1;
    const n = y(this, K)[t];
    y(this, K).splice(t, 1), (i = y(this, Dt)) == null || i.children(`[data-gid="${n}"]`).remove(), j(this, _e, Nn).call(this, void 0, t);
  }
  deleteRowByGid(t) {
    return this.deleteRow(y(this, K).indexOf(t));
  }
  toggleDitto(t, n) {
    const i = t.closest("td");
    n = n ?? i.attr("data-ditto") !== "on", i.attr("data-ditto", n ? "on" : "off"), n && i.closest("tr").prev("tr").find(`td[data-name="${i.data("name")}"]`).find(".form-batch-input").each((a, l) => {
      const h = u(l), d = h.data("name"), c = h.val();
      this.syncDitto(i.find(`.form-batch-input[data-name="${d}"]`).val(c), !1);
    });
  }
  syncDitto(t, n = !0) {
    const i = t.closest("td");
    n && i.attr("data-ditto", "off");
    const o = i.data("name"), r = t.data("name"), a = `td[data-name="${o}"][data-ditto="on"]`, l = t.val();
    let h = t.closest("tr").next("tr"), d = h.find(a);
    for (; d.length; )
      d.find(`.form-batch-input[data-name="${r}"]`).val(l), h = h.next("tr"), d = h.find(a);
  }
};
Dt = new WeakMap(), we = new WeakMap(), on = new WeakMap(), ve = new WeakMap(), bs = new WeakMap(), K = new WeakMap(), ro = new WeakSet(), oh = function() {
  const t = y(this, we), n = y(this, Dt);
  if (!t || !(n != null && n.length))
    return;
  const { data: i = [], minRows: o, maxRows: r, mode: a } = this.options, h = a === "add" ? Math.min(Math.max(1, r ?? 100), Math.max(1, 10, o ?? 10, i.length)) : i.length;
  $(this, K, Array(h).fill(0).map((d, c) => c)), $(this, bs, y(this, K).length), j(this, _e, Nn).call(this, i);
}, _e = new WeakSet(), Nn = function(t = [], n = 0) {
  var o;
  const i = y(this, K).length;
  for (let r = n; r < i; r++)
    j(this, lo, ah).call(this, r, t[r]);
  (o = y(this, Dt)) == null || o.attr("data-count", `${i}`);
}, ao = new WeakSet(), rh = function(t) {
  let n = y(this, on);
  if (!n) {
    const { addRowIcon: i = "icon-plus", deleteRowIcon: o = "icon-trash" } = this.options;
    n = new DocumentFragment();
    const r = '<button type="button" data-type="{type}" class="form-batch-btn btn square ghost size-sm" title="{text}"><i class="icon {icon}"></i></button>';
    i !== !1 && n.append(u(U(r, { type: "add", icon: i, text: this.i18n("add") }))[0]), o !== !1 && n.append(u(U(r, { type: "delete", icon: o, text: this.i18n("delete") }))[0]), $(this, on, n);
  }
  t.empty().append(n.cloneNode(!0));
}, lo = new WeakSet(), ah = function(t, n) {
  const i = y(this, Dt), o = String(y(this, K)[t]);
  let r = i.children(`[data-gid="${o}"]`);
  if (r.length) {
    if (!n && r.data("index") === t)
      return;
  } else {
    const p = y(this, we), b = document.importNode(p.content, !0).querySelector("tr");
    r = u(b).attr("data-gid", o);
  }
  if (r.attr("data-index", `${t}`), t) {
    const p = y(this, K)[t - 1], m = i.children(`[data-gid="${p}"]`);
    m.length ? m.after(r) : r.appendTo(i);
  } else
    r.prependTo(i);
  const { idKey: a = "id", mode: l, onRenderRowCol: h, onRenderRow: d } = this.options, c = l === "add", f = String(c || !n ? t + 1 : n[a]);
  y(this, ve).forEach((p) => {
    let m = r.find(`td[data-name="${p.name}"]`);
    if (m.length || (m = u(`<td data-name="${p.name}"></td>`).appendTo(r)), p.index) {
      m.find(".form-control-static").text(f).attr("id", `${p.name}_${o}`), h == null || h.call(this, m, p, n, t);
      return;
    }
    if (!m.data("init") || n) {
      if (p.name === "ACTIONS") {
        if (m.addClass("form-batch-row-actions"), !c)
          return;
        j(this, ao, rh).call(this, m);
        return;
      }
      m.data("init", 1).find("[name],.form-control-static").each((b, _) => {
        const v = u(_);
        if (v.hasClass("form-control-static")) {
          const x = v.attr("data-name");
          v.attr("id", `${p.name}_${o}`), n && v.text(String(n[x] ?? ""));
        } else {
          const x = v.attr("name"), k = v.attr("id");
          v.attr({
            id: `${k}_${o}`,
            name: `${x}[${f}]`,
            "data-name": x
          }).addClass("form-batch-input"), m.find(`label[for="${k}"]`).each((N, S) => {
            u(S).attr("for", `${k}_${o}`);
          }), n && v.val(String(n[x] ?? ""));
        }
      });
    } else
      m.find("[name]").each((b, _) => {
        var k;
        const v = u(_), x = v.attr("data-name");
        (k = v.attr("name")) != null && k.startsWith(`${x}[`) && v.attr("name", `${x}[${f}]`);
      });
    if (p.ditto && !m.hasClass("form-batch-ditto"))
      if (m.addClass("form-batch-ditto"), t) {
        const b = u(`<div class="input-control-suffix form-batch-ditto-btn"><button type="button" class="btn ghost form-batch-btn" data-type="ditto">${this.i18n("ditto")}</button></div>`), _ = u('<div class="input-control input-control-ditto has-suffix"></div>').append(m.children()).append(b).appendTo(m);
        requestAnimationFrame(() => _.css("--input-control-suffix", `${b.find(".btn").outerWidth()}px`)), m.attr("data-ditto", p.defaultDitto ?? "on");
      } else {
        m.attr("data-ditto", "");
        const b = m.find(".input-control-ditto");
        b.length && (b.children().not(".form-batch-ditto-btn").appendTo(m), b.remove());
      }
    h == null || h.call(this, m, p, n, t);
  }), d == null || d.call(this, r, t, n);
}, co.NAME = "BatchForm", co.DEFAULT = {
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
let fl = co;
function mf(s) {
  return typeof s == "string" ? s.split(",").map((e) => {
    const t = parseFloat(e);
    return Number.isNaN(t) ? null : t;
  }) : s;
}
var rn, xe, an, ws, vs, _s;
let lh = (_s = class extends F {
  constructor() {
    super(...arguments);
    C(this, rn, G());
    C(this, xe, 0);
    C(this, an, void 0);
    C(this, ws, void 0);
    C(this, vs, !1);
  }
  componentDidMount() {
    var n;
    this.tryDraw = this.tryDraw.bind(this), this.tryDraw();
    const t = (n = y(this, rn).current) == null ? void 0 : n.parentElement;
    if (this.props.responsive !== !1) {
      if (t && typeof ResizeObserver < "u") {
        const i = new ResizeObserver(this.tryDraw);
        i.observe(t), $(this, an, i);
      }
      y(this, an) || window.addEventListener("resize", this.tryDraw);
    }
    if (t && typeof IntersectionObserver < "u") {
      const i = new IntersectionObserver((o) => {
        y(this, vs) && o.some((r) => r.isIntersecting) && this.tryDraw();
      });
      i.observe(t), $(this, ws, i);
    }
  }
  componentWillUnmount() {
    var t;
    (t = y(this, an)) == null || t.disconnect(), window.removeEventListener("resize", this.tryDraw);
  }
  tryDraw() {
    y(this, xe) && cancelAnimationFrame(y(this, xe)), $(this, xe, requestAnimationFrame(() => {
      this.draw(), $(this, xe, 0);
    }));
  }
  draw() {
    const t = y(this, rn).current;
    if (!t)
      return;
    const n = t.parentElement, { width: i, height: o, responsive: r = !0 } = this.props;
    let a = i || n.clientWidth, l = o || n.clientHeight;
    if (i && o && r && (a = n.clientWidth, l = Math.floor(o * a / i)), t.style.width = `${a}px`, t.style.height = `${l}px`, a = a * (window.devicePixelRatio || 1), l = l * (window.devicePixelRatio || 1), t.width = a, t.height = l, !u(n).isVisible() && y(this, ws)) {
      $(this, vs, !0);
      return;
    }
    const {
      lineSize: h = 1,
      scaleLine: d = !1,
      scaleLineSize: c,
      scaleLineGap: f = 1,
      scaleLineDash: p,
      referenceLine: m,
      referenceLineSize: b,
      referenceLineDash: _,
      color: v = "#2c78f1",
      fillColor: x = ["rgba(46, 127, 255, 0.3)", "rgba(46, 127, 255, 0.05)"],
      lineDash: k = [],
      bezier: N
    } = this.props, S = mf(this.props.data), R = Math.floor(a / (S.length - 1)), L = Math.max(...S.filter((O) => O !== null)), I = S.map((O, E) => {
      const T = typeof O != "number";
      return {
        x: E * R,
        y: T ? l : Math.round((1 - O / L) * (l - h)),
        empty: T
      };
    });
    let D = I[0];
    const A = t.getContext("2d");
    if (d) {
      const O = typeof d == "string" ? d : "rgba(100,100,100,.1)";
      A.strokeStyle = O, A.lineWidth = c || h, p && A.setLineDash(p);
      for (let E = 0; E < I.length; ++E) {
        if (E % f !== 0)
          continue;
        const T = I[E];
        A.moveTo(T.x, 0), A.lineTo(T.x, l);
      }
      A.stroke();
    }
    if (m && I.length > 1) {
      const O = typeof m == "string" ? m : "rgba(100,100,100,.2)", E = I[I.length - 1];
      A.moveTo(E.x, E.y), A.strokeStyle = O, A.lineWidth = b || h, A.lineTo(D.x, D.y), _ && A.setLineDash(_), A.stroke();
    }
    for (A.setLineDash(k); I.length && I[I.length - 1].empty; )
      I.pop();
    if (x) {
      const O = I[I.length - 1];
      if (A.beginPath(), A.moveTo(0, l), A.lineTo(D.x, D.y), N) {
        const E = Math.round(R / 2);
        for (let T = 1; T < I.length; ++T) {
          const z = I[T], W = Math.round((z.y - D.y) / 5);
          A.bezierCurveTo(D.x + E, D.y + W, z.x - E, z.y - W, z.x, z.y), D = z;
        }
      } else
        for (let E = 1; E < I.length; ++E)
          D = I[E], A.lineTo(D.x, D.y);
      if (A.lineTo(O.x, l), Array.isArray(x)) {
        const E = A.createLinearGradient(0, 0, 0, l);
        for (let T = 0; T < x.length; ++T)
          E.addColorStop(T / (x.length - 1), x[T]);
        A.fillStyle = E;
      } else
        A.fillStyle = x;
      A.fill();
    }
    if (D = I[0], A.beginPath(), A.moveTo(D.x, D.y), N) {
      const O = Math.round(R / 2);
      for (let E = 1; E < I.length; ++E) {
        const T = I[E], z = Math.round((T.y - D.y) / 5);
        A.bezierCurveTo(D.x + O, D.y + z, T.x - O, T.y - z, T.x, T.y), D = T;
      }
    } else
      for (let O = 1; O < I.length; ++O)
        D = I[O], A.lineTo(D.x, D.y);
    A.strokeStyle = v, A.lineWidth = h, A.stroke();
  }
  render() {
    const { style: t, className: n, canvasClass: i } = this.props;
    return /* @__PURE__ */ w("div", { class: "center burn-chart", className: n, style: t }, /* @__PURE__ */ w("canvas", { className: i, ref: y(this, rn) }));
  }
}, rn = new WeakMap(), xe = new WeakMap(), an = new WeakMap(), ws = new WeakMap(), vs = new WeakMap(), _s.defaultProps = {
  responsive: !0,
  lineSize: 1,
  scaleLine: !1,
  scaleLineSize: 1,
  bezier: !0
}, _s);
const ho = class ho extends V {
};
ho.NAME = "Burn", ho.Component = lh;
let pl = ho;
function gf(s) {
  const { link: e, itemProps: t = {}, typeIconMap: n = {}, labelMap: i = {}, searchKeys: o, active: r, itemType: a = "", checkIcon: l, hideDirIcon: h } = this, {
    id: d,
    keys: c,
    text: f,
    className: p,
    url: m,
    type: b = a,
    items: _,
    icon: v,
    label: x,
    ...k
  } = s;
  let N = _ ? x ?? (i[b] || b) : null, S;
  typeof N == "string" && N.length && (S = N, N = /* @__PURE__ */ w("span", { class: "label rounded-full lighter size-sm ml-1" }, N));
  let R = m;
  R === void 0 && e && (typeof e == "function" ? R = e(s) : typeof e == "object" ? (R = e[b], R && (R = U(R, s))) : R = U(e, s));
  let L = v;
  L === void 0 && (!h || R) && (L = n[b]), typeof L == "string" && (L = { icon: L, "data-toggle": "tooltip", "data-title": S || b });
  let I = f ?? (i[b] || b);
  o != null && o.length && (I = th(o, [I]));
  const D = r === d;
  return l && D && (N = [N, /* @__PURE__ */ w("div", { className: "dropmenu-item-check" }, /* @__PURE__ */ w(Z, { icon: "check" }))]), {
    type: "item",
    key: d,
    children: N,
    icon: L,
    items: _,
    "data-url": R,
    text: I,
    active: D,
    title: f,
    ...t,
    ...k,
    className: M("dropmenu-item rounded", p, t.className, _ ? "is-dir" : "is-item", R ? "is-link open-url" : "is-toggle")
  };
}
function ml(s) {
  const {
    className: e,
    items: t,
    tree: n,
    onClickItem: i
  } = s;
  return /* @__PURE__ */ w(
    ih,
    {
      items: t,
      itemRenderProps: gf.bind(s),
      className: M(e, "dropmenu-tree"),
      defaultNestedShow: !0,
      onClickItem: i,
      ...n
    }
  );
}
const yf = (s, e) => {
  const { keys: t = "", text: n } = s;
  return !e.length || e.every((i) => t.toLowerCase().includes(i) || typeof n == "string" && n.toLowerCase().includes(i));
}, ch = (s, e, t = 0) => (s = s.reduce((n, i) => {
  const { items: o } = i;
  if (o) {
    if (o.length) {
      const [r, a] = ch(o, e);
      r.length && (n.push({ ...i, items: r }), t += a);
    }
  } else
    yf(i, e) && (n.push(i), t++);
  return n;
}, []), [s, t]);
var ln, cn, xs, Cs, $s, hn;
class bf extends ra {
  constructor(t) {
    super(t);
    C(this, ln, void 0);
    C(this, cn, void 0);
    C(this, xs, void 0);
    C(this, Cs, void 0);
    C(this, $s, void 0);
    C(this, hn, void 0);
    $(this, ln, G()), $(this, cn, G()), $(this, xs, (n) => {
      this.setState({ search: n });
    }), $(this, Cs, () => {
      this.expand();
    }), $(this, $s, ({ item: n, event: i }) => {
      i.target.closest(".is-link") && (this.props.togglePop(!1, {
        text: n.text ?? `${n.type}:${n.id}`
      }), this.props.changeState({ text: n.text ?? `${n.type || ""}:${n.id}`, value: n.id }));
    }), $(this, hn, () => {
      requestAnimationFrame(() => {
        const n = y(this, cn).current;
        n && u(n).find(".dropmenu-item.active").scrollIntoView();
      });
    }), this.activeTab = (n) => {
      this.setState({ active: n });
    }, this.expand = (n) => {
      this.setState({ expanded: n ?? !this.state.expanded });
    }, this.state = { search: "", data: t.data, expanded: !1, loading: !!t.fetcher && !t.data };
  }
  async load() {
    var i, o;
    let { fetcher: t } = this.props;
    if (!t)
      return y(this, hn).call(this);
    if (typeof t == "string" && (t = { url: t }), typeof t == "object") {
      const { url: r, ...a } = t;
      t = async () => await (await fetch(r, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        ...a
      })).json();
    }
    this.setState({ loading: !0 });
    let n = await t();
    n.result && (n = n.data), n = { ...this.props.data, ...n }, this.setState({ data: n, loading: !1 }, y(this, hn)), (o = (i = this.props).onCacheData) == null || o.call(i, n);
  }
  componentDidMount() {
    var t;
    super.componentDidMount(), this.load(), (t = y(this, ln).current) == null || t.focus();
  }
  _getTreeProps(t) {
    const { data: { labelMap: n, link: i, typeIconMap: o, typeLabelMap: r, itemProps: a, tree: l, itemType: h, checkIcon: d, hideDirIcon: c = !0 } = {}, search: f } = this.state;
    return {
      items: t.items,
      labelMap: n,
      typeIconMap: {
        execution: "run",
        project: "project",
        product: "product",
        program: "cards-view",
        ...o
      },
      hideDirIcon: c,
      typeLabelMap: r,
      itemProps: a,
      itemType: h,
      checkIcon: d,
      onClickItem: y(this, $s),
      tree: l,
      link: i,
      active: this.props.state.value,
      searchKeys: u.unique(f.toLowerCase().split(" ").filter((p) => p.length))
    };
  }
  _getData() {
    const { data: t = {}, search: n, active: i } = this.state, { tabs: o = [{ name: "other" }], expandName: r } = t;
    let { data: a = [] } = t;
    Array.isArray(a) && (a = { other: a });
    const l = {}, h = u.unique(n.toLowerCase().split(" ").filter((p) => p.length));
    let d, c;
    Object.keys(a).forEach((p) => {
      const m = r === p, b = o.find((S) => S.name === p) || { name: p }, _ = a[p] || [], v = i === p && !m, [x, k] = ch(_, h), N = {
        name: p,
        items: x,
        text: b.text,
        active: v,
        count: k
      };
      m ? d = N : (l[p] = N, v && (c = N));
    });
    const f = o.reduce((p, { name: m }) => (m !== r && p.push(l[m] || { name: m, items: [] }), p), []);
    return !c && f.length && (c = f[0]), { tabs: f, expandData: d, activeTab: c };
  }
  _renderList(t, n, i) {
    const o = n.name, { expanded: r, data: a = {} } = this.state;
    let { hideSingleTab: l = !0 } = a;
    return l = t.length === 1 && l, /* @__PURE__ */ w(ae, null, l ? null : /* @__PURE__ */ w("div", { className: "dropmenu-nav" }, /* @__PURE__ */ w("ul", { key: "nav", className: "nav nav-secondary" }, t.map(({ name: h, text: d, count: c }) => /* @__PURE__ */ w("li", { className: "nav-item" }, /* @__PURE__ */ w("a", { className: `${o === h ? " active" : ""}`, onClick: this.activeTab.bind(this, h) }, d || h, c ? /* @__PURE__ */ w("span", { className: "label lighter rounded-full size-sm font-normal" }, c) : null))))), /* @__PURE__ */ w("div", { key: "tab", className: "flex-auto dropmenu-list scrollbar-hover scrollbar-thin" }, /* @__PURE__ */ w(
      ml,
      {
        ...this._getTreeProps(n)
      }
    )), i ? /* @__PURE__ */ w("div", { key: "foot", className: "dropmenu-foot flex-none toolbar justify-end border-t" }, /* @__PURE__ */ w(tt, { type: "ghost text-dark rounded gap-0.5 px-1.5", trailingIcon: `angle-${this.state.expanded ? "left" : "right"} opacity-60`, onClick: y(this, Cs) }, i.text || i.name, " ", i.count && r ? /* @__PURE__ */ w("span", { className: "ml-1 label lighter rounded-full size-sm font-normal" }, i.count) : null)) : null);
  }
  _renderExpand(t) {
    return /* @__PURE__ */ w("div", { className: "col w-1/2 dropmenu-list scrollbar-thin scrollbar-hover" }, /* @__PURE__ */ w(
      ml,
      {
        ...this._getTreeProps(t)
      }
    ));
  }
  _renderPop() {
    const { expanded: t, data: n = {} } = this.state, { searchHint: i, search: o = !0, title: r } = n, { expandData: a, tabs: l, activeTab: h } = this._getData(), d = t && a;
    return /* @__PURE__ */ w(ae, null, o ? /* @__PURE__ */ w("div", { key: "search", className: "p-3 flex-none" }, /* @__PURE__ */ w(
      sh,
      {
        ref: y(this, ln),
        className: "size-md",
        placeholder: i,
        onChange: y(this, xs)
      }
    )) : null, r ? /* @__PURE__ */ w("div", { className: "dropmenu-title" }, r) : null, /* @__PURE__ */ w("div", { class: "row flex-auto min-h-0", ref: y(this, cn) }, /* @__PURE__ */ w("div", { class: `col w-${d ? "1/2" : "full"}` }, this._renderList(l, h, a)), d ? /* @__PURE__ */ w("div", { class: "w-px bg-gray opacity-10" }) : null, t && a && this._renderExpand(a)));
  }
  _getClass(t) {
    const { expanded: n, loading: i, data: o = {} } = this.state;
    return M("dropmenu load-indicator col", super._getClass(t), n && "is-expanded", i && "loading", o.search !== !1 && "has-search", o.title ? "has-title" : "");
  }
  _getProps(t) {
    const { width: n = 248 } = t, { style: i, ...o } = super._getProps(t), { expanded: r } = this.state, a = this.trigger, l = a == null ? void 0 : a.getBoundingClientRect();
    return {
      ...o,
      style: {
        ...i,
        width: n * (r ? 2 : 1),
        maxHeight: l ? Math.max(l.top - 8, window.innerHeight - l.bottom - 8) : i.maxHeight
      }
    };
  }
}
ln = new WeakMap(), cn = new WeakMap(), xs = new WeakMap(), Cs = new WeakMap(), $s = new WeakMap(), hn = new WeakMap();
class wf extends Vo {
  _getProps(e) {
    const t = super._getProps(e);
    return {
      type: "button",
      "data-value": e.state.value,
      ...t
    };
  }
  _renderTrigger(e) {
    const { text: t, state: n, children: i, caret: o, leadingAngle: r } = e;
    return [
      r && /* @__PURE__ */ w(Z, { icon: "angle-right", className: "is-leading" }),
      /* @__PURE__ */ w("span", { key: "text", className: "text" }, n.text ?? t ?? i),
      o && /* @__PURE__ */ w("div", { key: "caret", class: "is-caret" }, /* @__PURE__ */ w("span", { className: "caret" }))
    ];
  }
}
var dn, At, uo, Pe;
let vf = (Pe = class extends ht {
  constructor() {
    super(...arguments);
    C(this, dn, void 0);
    C(this, At, void 0);
    C(this, uo, (t) => {
      const { cache: n } = this.props;
      n && ($(this, dn, t), typeof n == "number" && (y(this, At) && clearTimeout(y(this, At)), $(this, At, window.setTimeout(() => {
        $(this, dn, void 0), $(this, At, 0);
      }, n))));
    });
  }
  componentWillUnmount() {
    super.componentWillUnmount(), y(this, At) && clearTimeout(y(this, At));
  }
  _getTriggerProps(t, n) {
    const { className: i, ...o } = super._getTriggerProps(t, n), { value: r = "" } = n;
    return {
      ...o,
      className: M(i, { "has-value": r.length }),
      text: t.text,
      caret: t.caret,
      maxWidth: t.maxWidth,
      leadingAngle: t.leadingAngle
    };
  }
  _getPopProps(t, n) {
    return {
      ...super._getPopProps(t, n),
      data: y(this, dn) || t.data,
      fetcher: t.fetcher,
      cache: !!t.cache,
      onCacheData: y(this, uo)
    };
  }
}, dn = new WeakMap(), At = new WeakMap(), uo = new WeakMap(), Pe.defaultProps = {
  ...ht.defaultProps,
  popWidth: 248,
  maxWidth: 160,
  className: "dropmenu-btn btn primary",
  tagName: "button",
  clickType: "toggle",
  leadingAngle: !0,
  caret: !0,
  cache: 60 * 1e3 * 4
}, Pe.Pop = bf, Pe.Trigger = wf, Pe);
const fo = class fo extends V {
};
fo.NAME = "Dropmenu", fo.Component = vf;
let gl = fo;
const ma = class ma extends ot {
  init() {
    const { echarts: e } = window;
    if (!e) {
      console.warn("ZUI: ECharts is not loaded.");
      return;
    }
    const { responsive: t = !0, theme: n, ...i } = this.options, o = e.init(this.element, n);
    o.setOption(i), t && u(window).on(`resize.${this.gid}.ECharts.zt`, o.resize), this.chart = o;
  }
  destroy() {
    var n;
    const { echarts: e } = window;
    if (!e) {
      super.destroy();
      return;
    }
    const { responsive: t = !0 } = this.options;
    t && u(window).off(`resize.${this.gid}.ECharts.zt`), (n = this.chart) == null || n.dispose(), super.destroy();
  }
};
ma.NAME = "ECharts";
let yl = ma;
const _f = '[data-toggle="dropdown"]', po = class po extends Nt {
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
      content: w(fr, this._getMenuOptions())
    };
  }
};
po.NAME = "Dropdown", po.DEFAULT = {
  ...Nt.DEFAULT,
  name: "dropdown",
  placement: "bottom-start",
  arrow: !1,
  closeBtn: !1,
  animation: "fade"
};
let oe = po;
u(document).on(`click${oe.NAMESPACE} mouseenter${oe.NAMESPACE}`, _f, (s) => {
  const e = u(s.currentTarget);
  if (e.length && !e.data(oe.KEY)) {
    const t = e.data("trigger") || "click";
    if ((s.type === "mouseover" ? "hover" : "click") !== t)
      return;
    const i = {
      ...e.data(),
      show: !0,
      triggerEvent: s
    };
    !i.target && !i.items && !i.menu && (i.target = e.next(".dropdown-menu")), oe.ensure(e, i), s.preventDefault();
  }
});
class xf extends F {
  renderCommonSearch(e) {
    const { commonSearchText: t, commonSearchUrl: n, searchValue: i } = this.props;
    return /* @__PURE__ */ w("li", { key: "common", className: M("w-full rounded flex items-center my-0.5", e) }, /* @__PURE__ */ w("a", { className: "inline-block p-1 ellipsis", style: { color: "inherit" }, href: n.replace("{searchValue}", i) }, t, " ", i));
  }
  render() {
    const { searchValue: e, searchItems: t, selectedKey: n } = this.props;
    return e === "" ? null : /^\d+$/.test(e) ? /* @__PURE__ */ w("ul", { className: "global-search-panel flex flex-wrap p-1 rounded shadow bg-white w-full" }, this.renderCommonSearch(), t.map(({ key: i, text: o, url: r }) => /* @__PURE__ */ w("li", { key: i, "data-fk": i, className: M({
      rounded: !0,
      "my-0.5": !0,
      flex: !0,
      "justify-between": !0,
      "items-center": !0,
      "w-1/2": i !== n,
      "w-full": i === n,
      secondary: i === n,
      "order-first": i === n
    }) }, /* @__PURE__ */ w("a", { className: "inline-block p-1 ellipsis", style: { color: "inherit" }, href: r.replace("{searchValue}", e) }, o, " #", e), i === n && /* @__PURE__ */ w("i", { className: "icon icon-check mr-2" })))) : /* @__PURE__ */ w("ul", { className: "global-search-panel p-1 rounded shadow bg-white w-full" }, this.renderCommonSearch("secondary"));
  }
}
const ga = class ga extends ot {
  init() {
    const { panelID: e = "global-search-panel" } = this.options, t = u(`<div id="${e}" />`).css({ width: 270 });
    this.$element.after(t);
    const n = this.$element.find("input");
    n.data("target", `#${e}`).on("input", (i) => {
      Rn(
        /* @__PURE__ */ w(
          xf,
          {
            ...this.options,
            searchValue: i.target.value,
            selectedKey: n.data("selectedKey")
          }
        ),
        t[0]
      );
    }), new Cr(this.$element.find("input"), {
      placement: "top-start",
      trigger: "focus blur",
      offset: 4,
      mask: !1
    });
  }
};
ga.NAME = "GlobalSearch";
let bl = ga;
function hh({ pri: s = "", text: e, className: t }) {
  return /* @__PURE__ */ w("span", { className: M(`pri-${s}`, t) }, e ?? s);
}
var ks;
let pi = (ks = class extends ht {
  constructor(e) {
    if (super(e), this.state.value === void 0 && e.required) {
      const t = this._getItems();
      t && (this.state.value = t[0].value);
    }
  }
  _getTriggerProps(e, t) {
    const n = super._getTriggerProps(e, t);
    return e.disabled && (n.className = M(n.className, "disabled")), n;
  }
  _renderTrigger(e, t) {
    var o;
    const { value: n } = t, { placeholder: i } = e;
    return [
      n === void 0 ? /* @__PURE__ */ w("span", { class: "placeholder" }, i) : this._renderItem(t.value, (o = this._getItems().find(({ value: r }) => r === n)) == null ? void 0 : o.text, "pri"),
      /* @__PURE__ */ w("span", { key: "caret", class: "caret" })
    ];
  }
  _getItems() {
    let { items: e = ["", 1, 2, 3, 4] } = this.props;
    return Array.isArray(e) ? e = e.map((t) => typeof t == "object" ? t : { value: `${t}` }) : typeof e == "object" && (e = Object.keys(e).map((t) => ({ value: t, text: e[t] }))), e.sort((t, n) => +t.value - +n.value), e;
  }
  _renderItem(e, t, n) {
    return /* @__PURE__ */ w(hh, { key: n, pri: e, text: t });
  }
  _renderPop() {
    const { value: e } = this.state;
    return /* @__PURE__ */ w("div", { className: "pick-pri-list" }, this._getItems().map(({ value: t, text: n }) => /* @__PURE__ */ w("button", { key: t, type: "button", class: `btn w-full ${e === t ? "primary-pale" : "ghost"}`, "data-pick-value": t }, this._renderItem(t, n))));
  }
}, ks.defaultProps = {
  ...ht.defaultProps,
  items: ["", 1, 2, 3, 4],
  className: "pick-pri form-control",
  popClass: "pick-pri-pop popup",
  popWidth: "auto",
  popMinWidth: 56
}, ks);
function dh({ severity: s = "", text: e, className: t }) {
  const n = e ?? s, i = `${Number.parseInt(n)}` != `${s}`;
  return /* @__PURE__ */ w("span", { className: M(`severity${i ? " severity-label" : ""}`, t), "data-severity": s }, i ? n : "");
}
var Ts;
let uh = (Ts = class extends pi {
  _renderItem(e, t, n) {
    return /* @__PURE__ */ w(dh, { key: n, severity: e, text: t });
  }
}, Ts.defaultProps = {
  ...pi.defaultProps,
  items: [0, 1, 2, 3, 4]
}, Ts);
const mo = class mo extends V {
};
mo.NAME = "PriPicker", mo.Component = pi;
let wl = mo;
const go = class go extends V {
};
go.NAME = "SeverityPicker", go.Component = uh;
let vl = go;
function Tr(s) {
  const { id: e, name: t, options: n, defaultValue: i } = s, o = (r) => /* @__PURE__ */ w("option", { value: r.value }, r.title);
  return /* @__PURE__ */ w("select", { id: e, name: t, className: "form-control", value: i }, n.map(o));
}
var Sr, q, Qo, _l, Nr = 0, fh = [], Gs = [], xl = H.__b, Cl = H.__r, $l = H.diffed, kl = H.__c, Tl = H.unmount;
function Cf(s, e) {
  H.__h && H.__h(q, s, Nr || e), Nr = 0;
  var t = q.__H || (q.__H = { __: [], __h: [] });
  return s >= t.__.length && t.__.push({ __V: Gs }), t.__[s];
}
function Er(s) {
  return Nr = 1, $f(ph, s);
}
function $f(s, e, t) {
  var n = Cf(Sr++, 2);
  if (n.t = s, !n.__c && (n.__ = [t ? t(e) : ph(void 0, e), function(a) {
    var l = n.__N ? n.__N[0] : n.__[0], h = n.t(l, a);
    l !== h && (n.__N = [h, n.__[1]], n.__c.setState({}));
  }], n.__c = q, !q.u)) {
    var i = function(a, l, h) {
      if (!n.__c.__H)
        return !0;
      var d = n.__c.__H.__.filter(function(f) {
        return f.__c;
      });
      if (d.every(function(f) {
        return !f.__N;
      }))
        return !o || o.call(this, a, l, h);
      var c = !1;
      return d.forEach(function(f) {
        if (f.__N) {
          var p = f.__[0];
          f.__ = f.__N, f.__N = void 0, p !== f.__[0] && (c = !0);
        }
      }), !(!c && n.__c.props === a) && (!o || o.call(this, a, l, h));
    };
    q.u = !0;
    var o = q.shouldComponentUpdate, r = q.componentWillUpdate;
    q.componentWillUpdate = function(a, l, h) {
      if (this.__e) {
        var d = o;
        o = void 0, i(a, l, h), o = d;
      }
      r && r.call(this, a, l, h);
    }, q.shouldComponentUpdate = i;
  }
  return n.__N || n.__;
}
function kf() {
  for (var s; s = fh.shift(); )
    if (s.__P && s.__H)
      try {
        s.__H.__h.forEach(Ks), s.__H.__h.forEach(Mr), s.__H.__h = [];
      } catch (e) {
        s.__H.__h = [], H.__e(e, s.__v);
      }
}
H.__b = function(s) {
  q = null, xl && xl(s);
}, H.__r = function(s) {
  Cl && Cl(s), Sr = 0;
  var e = (q = s.__c).__H;
  e && (Qo === q ? (e.__h = [], q.__h = [], e.__.forEach(function(t) {
    t.__N && (t.__ = t.__N), t.__V = Gs, t.__N = t.i = void 0;
  })) : (e.__h.forEach(Ks), e.__h.forEach(Mr), e.__h = [], Sr = 0)), Qo = q;
}, H.diffed = function(s) {
  $l && $l(s);
  var e = s.__c;
  e && e.__H && (e.__H.__h.length && (fh.push(e) !== 1 && _l === H.requestAnimationFrame || ((_l = H.requestAnimationFrame) || Tf)(kf)), e.__H.__.forEach(function(t) {
    t.i && (t.__H = t.i), t.__V !== Gs && (t.__ = t.__V), t.i = void 0, t.__V = Gs;
  })), Qo = q = null;
}, H.__c = function(s, e) {
  e.some(function(t) {
    try {
      t.__h.forEach(Ks), t.__h = t.__h.filter(function(n) {
        return !n.__ || Mr(n);
      });
    } catch (n) {
      e.some(function(i) {
        i.__h && (i.__h = []);
      }), e = [], H.__e(n, t.__v);
    }
  }), kl && kl(s, e);
}, H.unmount = function(s) {
  Tl && Tl(s);
  var e, t = s.__c;
  t && t.__H && (t.__H.__.forEach(function(n) {
    try {
      Ks(n);
    } catch (i) {
      e = i;
    }
  }), t.__H = void 0, e && H.__e(e, t.__v));
};
var Sl = typeof requestAnimationFrame == "function";
function Tf(s) {
  var e, t = function() {
    clearTimeout(n), Sl && cancelAnimationFrame(e), setTimeout(s);
  }, n = setTimeout(t, 100);
  Sl && (e = requestAnimationFrame(t));
}
function Ks(s) {
  var e = q, t = s.__c;
  typeof t == "function" && (s.__c = void 0, t()), q = e;
}
function Mr(s) {
  var e = q;
  s.__c = s.__(), q = e;
}
function ph(s, e) {
  return typeof e == "function" ? e(s) : e;
}
function je(s) {
  const { index: e, fields: t, operators: n, andOr: i, groupName: o, defaultValue: r, show: a = !1 } = s, [l, h] = Er(r.field), d = t.find((f) => f.name === l), c = (f) => Array.from(Object.entries(f)).map(([p, m]) => ({ value: p, text: m }));
  return a ? /* @__PURE__ */ w("tr", null, /* @__PURE__ */ w("td", { className: "p-2 text-right", style: { width: 80 } }, e === 1 ? /* @__PURE__ */ w("span", null, /* @__PURE__ */ w("strong", null, o[0])) : e === 4 ? /* @__PURE__ */ w("span", null, /* @__PURE__ */ w("strong", null, o[1])) : /* @__PURE__ */ w(
    Tr,
    {
      id: `andOr${e}`,
      name: `andOr${e}`,
      options: i,
      defaultValue: r.andOr
    }
  )), /* @__PURE__ */ w("td", { className: "p-2", style: { width: 150 } }, /* @__PURE__ */ w(
    fi,
    {
      className: "w-full",
      id: `field${e}`,
      name: `field${e}`,
      items: t.map((f) => ({ text: f.label, value: f.name })),
      onChange: (f) => h(f),
      defaultValue: r.field
    }
  )), /* @__PURE__ */ w("td", { className: "p-2", style: { width: 90 } }, /* @__PURE__ */ w(
    Tr,
    {
      id: `operator${e}`,
      name: `operator${e}`,
      options: n,
      defaultValue: r.operator
    }
  )), /* @__PURE__ */ w("td", { className: "p-2" }, (d == null ? void 0 : d.control) === "input" || !d ? /* @__PURE__ */ w(
    "input",
    {
      id: `value${e}`,
      name: `value${e}`,
      type: "text",
      className: "form-control",
      placeholder: d == null ? void 0 : d.placeholder,
      defaultValue: r.value
    }
  ) : /* @__PURE__ */ w(
    fi,
    {
      id: `value${e}`,
      name: `value${e}`,
      items: c(d == null ? void 0 : d.values),
      defaultValue: r.value
    }
  ))) : null;
}
function Sf(s) {
  const { text: e, applyURL: t, deleteURL: n } = s, [i, o] = Er("lighter"), [r, a] = Er("lighter");
  return /* @__PURE__ */ w(
    "a",
    {
      className: `search-condition label rounded-full h-6 flex p-1 gap-2 items-center cursor-pointer ${i}`,
      href: t,
      style: { width: "fit-content", maxWidth: "100%" },
      onMouseOver: () => o("gray"),
      onMouseLeave: () => o("lighter")
    },
    /* @__PURE__ */ w("span", { className: "ellipsis" }, e),
    /* @__PURE__ */ w(
      "a",
      {
        href: n,
        className: `rounded-full h-5 w-5 center ${r} shrink-0 grow-0`,
        onMouseOver: () => a("danger"),
        onMouseLeave: () => a("lighter")
      },
      /* @__PURE__ */ w("i", { className: "icon icon-close" })
    )
  );
}
let Nf = class extends F {
  constructor(e) {
    super(e), this.state = {
      toggleMore: !1,
      toggleSide: !1,
      formKey: Date.now().toString()
    };
  }
  render(e, t) {
    const { fields: n, operators: i, andOr: o, groupName: r, formSession: a, saveSearch: l, savedQueryTitle: h, formConfig: d, searchConditions: c, className: f } = e, { toggleMore: p, toggleSide: m, formKey: b } = t;
    return /* @__PURE__ */ w("form", { className: `flex ${f}`, action: d.action, method: d.method }, /* @__PURE__ */ w("table", { className: "grow", key: b, style: { tableLayout: "fixed" } }, /* @__PURE__ */ w("tr", null, /* @__PURE__ */ w("td", null, /* @__PURE__ */ w("table", { className: "w-full", style: { tableLayout: "fixed" } }, /* @__PURE__ */ w(
      je,
      {
        show: !0,
        index: 1,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[0]
      }
    ), /* @__PURE__ */ w(
      je,
      {
        show: p,
        index: 2,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[1]
      }
    ), /* @__PURE__ */ w(
      je,
      {
        show: p,
        index: 3,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[2]
      }
    ))), /* @__PURE__ */ w("td", { className: "p-2", style: { width: 140 } }, /* @__PURE__ */ w(
      Tr,
      {
        id: "groupAndOr",
        name: "groupAndOr",
        options: e.andOr,
        defaultValue: a[6].groupAndOr
      }
    )), /* @__PURE__ */ w("td", null, /* @__PURE__ */ w("table", { className: "w-full", style: { tableLayout: "fixed" } }, /* @__PURE__ */ w(
      je,
      {
        show: !0,
        index: 4,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[3]
      }
    ), /* @__PURE__ */ w(
      je,
      {
        show: p,
        index: 5,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[4]
      }
    ), /* @__PURE__ */ w(
      je,
      {
        show: p,
        index: 6,
        fields: n,
        operators: i,
        andOr: o,
        groupName: r,
        defaultValue: a[5]
      }
    )))), /* @__PURE__ */ w("tr", null, /* @__PURE__ */ w("td", null), /* @__PURE__ */ w("td", { className: "text-center p-2" }, /* @__PURE__ */ w("div", { className: "w-full flex justify-center gap-2" }, /* @__PURE__ */ w("button", { className: "btn primary", type: "submit" }, "确定"), /* @__PURE__ */ w("button", { className: "btn", type: "button", onClick: () => this.setState({ formKey: Date.now().toString() }) }, "重置"))), /* @__PURE__ */ w("td", { className: "text-right" }, /* @__PURE__ */ w(
      "button",
      {
        className: "btn btn-link",
        disabled: !l.hasPriv,
        type: "button",
        "data-toggle": l.config["data-toggle"],
        "data-type": l.config["data-type"],
        "data-data-type": l.config["data-data-type"],
        "data-url": l.config["data-url"]
      },
      /* @__PURE__ */ w("i", { className: "icon icon-save" }),
      l.text
    ), /* @__PURE__ */ w("button", { className: "btn btn-link", type: "button", onClick: () => this.setState({ toggleMore: !p }) }, /* @__PURE__ */ w("i", { className: M({
      icon: !0,
      "icon-chevron-double-up": p,
      "icon-chevron-double-down": !p
    }) }))))), /* @__PURE__ */ w(
      "button",
      {
        type: "button",
        className: "secondary self-center rounded-lg",
        style: { height: "min-content" },
        onClick: () => this.setState({ toggleSide: !m })
      },
      /* @__PURE__ */ w("i", { className: M({
        icon: !0,
        "icon-angle-left": m,
        "icon-angle-right": !m
      }) })
    ), /* @__PURE__ */ w(
      "div",
      {
        style: { width: 160, height: p ? 180 : 84 },
        className: M({
          "border-l": !0,
          hidden: m,
          col: !0
        })
      },
      /* @__PURE__ */ w("strong", { className: "pl-2 py-2" }, h),
      /* @__PURE__ */ w("div", { className: "grow overflow-y-auto col gap-2 p-2" }, c.map((_) => /* @__PURE__ */ w(Sf, { ..._ })))
    ));
  }
};
const yo = class yo extends V {
};
yo.NAME = "SearchForm", yo.Component = Nf;
let Nl = yo;
const Ef = { 1: "error", 2: "warning", 4: "parse", 8: "notice", 16: "core-error", 32: "core-warning", 64: "compile-error", 128: "compile-warning", 256: "user-error", 512: "user-warning", 1024: "user-notice", 2048: "strict", 4096: "recoverable-error", 8192: "deprecated", 16384: "user-deprecated", 32767: "all" };
function mh(s) {
  return typeof s == "number" && (s = Ef[s]), s;
}
function gh(s) {
  return s = mh(s), s.includes("error") ? "error" : s.includes("warning") ? "warning" : "info";
}
function Mf({ errors: s, ...e }) {
  const t = s.reduce((n, i) => (n[gh(i.level)]++, n), { error: 0, warning: 0, info: 0 });
  return /* @__PURE__ */ w("div", { class: "row items-stretch text-sm", "data-hint": "PHP errors", ...e }, t.error ? /* @__PURE__ */ w("button", { type: "button", class: "state font-bold px-0.5 danger" }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "ERR"), t.error) : null, t.warning ? /* @__PURE__ */ w("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-90" }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "WAR"), t.warning) : null, t.info ? /* @__PURE__ */ w("button", { type: "button", class: "state font-bold px-0.5 danger bg-opacity-80" }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block text-opacity-70 text-canvas" }, "INF"), t.info) : null);
}
function ut(s, e, t, n) {
  console.groupCollapsed(`%c${s} %c${e}`, "color: #fff; background-color: #9333ea; padding: 0 0.1em 0 0.25em; border-radius: 0.25em 0 0 0.25em;", "color: #9333ea; background-color: #e9d5ff; padding: 0 0.5em; border-radius: 0 0.25em 0.25em 0;", n), console.table(t), console.groupEnd();
}
function Gt(s, e = 400, t = 100) {
  return s < t ? "success" : s < e ? "warning" : "danger";
}
function kn(s) {
  return s < 1e3 ? `${s.toFixed(0)}ms` : `${(s / 1e3).toFixed(2)}s`;
}
function El({ perf: s }) {
  var r;
  const e = s.id === "page" ? "PAGE" : s.id === "#dtable" ? "TABLE" : "PART", t = [], { trace: n, xhprof: i } = s, o = n == null ? void 0 : n.request;
  if (s.requestEnd) {
    const a = s.requestEnd - s.requestBegin;
    if (t.push(/* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(a, 1e3, 400)}`, "data-hint": "Total load time (G<400<=N<1000<=B)", onClick: ut.bind(null, "Trace", "Perf", s, s.id) }, /* @__PURE__ */ w("i", { class: "icon-history" }), " ", kn(a))), o) {
      const h = o.timeUsed;
      t.push(
        /* @__PURE__ */ w("div", { class: "muted" }, "/"),
        /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(h)}`, "data-hint": "Server time (G<100<=N<400<=B)", onClick: ut.bind(null, "Trace", "Request", o, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "S"), kn(h))
      );
    }
    if (s.dataSize) {
      if (o) {
        const h = a - o.timeUsed;
        t.push(
          /* @__PURE__ */ w("div", { class: "muted" }, "/"),
          /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(h, 600, 200)}`, "data-hint": "Network time (G<200<=N<600<=B)", onClick: ut.bind(null, "Trace", "Request", o, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "N"), kn(h))
        );
      }
      if (t.push(
        /* @__PURE__ */ w("div", { class: "px-0.5 state", "data-hint": "Loaded data size", onClick: ut.bind(null, "Trace", "Perf", s, s.id) }, /* @__PURE__ */ w("span", { class: "muted" }, /* @__PURE__ */ w("i", { class: "icon icon-cube muted" }), " ", Vs(s.dataSize, 1)))
      ), o) {
        const h = a - o.timeUsed, d = 1e3 * s.dataSize / h;
        t.push(
          /* @__PURE__ */ w("div", { class: "muted" }, "/"),
          /* @__PURE__ */ w("div", { class: `px-0.5 state text-${d < 102400 ? "danger" : d < 1024e3 ? "warning" : "success"}`, "data-hint": "Download speed(B<100KB<=N<1MB<=G)", onClick: ut.bind(null, "Trace", "Request", o, s.id) }, /* @__PURE__ */ w("i", { class: "icon icon-arrow-down" }), Vs(d, 1), "/s")
        );
      }
    }
    if (s.renderEnd && s.renderBegin) {
      const h = s.renderEnd - s.renderBegin, d = Gt(h, 200, 50);
      t.push(
        /* @__PURE__ */ w("div", { class: "muted" }, "/"),
        /* @__PURE__ */ w("div", { class: `px-0.5 state text-${d}`, "data-hint": "Client render time (G<50<=N<200<=B)", onClick: ut.bind(null, "Trace", "Perf", s, s.id) }, /* @__PURE__ */ w("i", { class: `icon-${d === "danger" ? "frown" : d === "warning" ? "meh" : "smile"}` }), kn(h))
      );
    }
    if (o) {
      const { memory: h, querys: d } = o;
      typeof h == "number" && t.push(
        /* @__PURE__ */ w("div", { class: "muted" }, "/"),
        /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(h, 1024e3, 102400)}`, "data-hint": "Server memory usage(G<10KB<=N<100KB<=B)", onClick: ut.bind(null, "Trace", "Request", o, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "M"), Vs(h))
      ), typeof d == "number" && t.push(
        /* @__PURE__ */ w("div", { class: "muted" }, "/"),
        /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(d, 30, 10)}`, "data-hint": "Server sql queries count (G<30<=N<10<=B)", onClick: ut.bind(null, "SQL Query", `${((r = n.sqlQuery) == null ? void 0 : r.length) ?? 0} queries`, n.sqlQuery, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "Q"), d)
      );
    }
    n != null && n.files && t.push(
      /* @__PURE__ */ w("div", { class: "muted" }, "/"),
      /* @__PURE__ */ w("div", { class: "px-0.5 state", "data-hint": "Server loaded php files count", onClick: ut.bind(null, "Trace", `${n.files.length} php files`, n.files, s.id) }, /* @__PURE__ */ w("span", { class: "muted" }, /* @__PURE__ */ w("i", { class: "icon-file icon-sm muted scale-75" }), n.files.length))
    );
    const l = n == null ? void 0 : n.profiles;
    if (l != null && l.length) {
      let h = 0, d = { Duration: 0 };
      if (l.forEach((c) => {
        c.Duration > 0.3 && h++, c.Duration > d.Duration && (d = c);
      }), t.push(
        /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(h, 3, 1)}`, "data-hint": "Server slow SQL queries count (G<3<=N<1<=B)", onClick: ut.bind(null, "SQL Query", `${l.length} SQL profiles`, l, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "LQ"), h)
      ), d.Duration) {
        const c = d.Duration * 1e3;
        t.push(
          /* @__PURE__ */ w("div", { class: `px-0.5 state text-${Gt(c, 600, 300)}`, "data-hint": "Server lowest SQL query duration (G<600<=N<300<=B)", onClick: ut.bind(null, "SQL Query", `Slowest SQL query: ${c}ms`, d, s.id) }, /* @__PURE__ */ w("span", { class: "scale-95 font-bold inline-block" }, "MLQ"), kn(c))
        );
      }
    }
  } else
    t.push(/* @__PURE__ */ w("div", { class: "muted px-0.5" }, "loading..."));
  return /* @__PURE__ */ w("div", { class: "zin-perf-btn-list row items-center bg-black text-sm" }, /* @__PURE__ */ w("div", { class: "px-1 bg-canvas bg-opacity-20 self-stretch flex items-center", "data-hint": `REQUEST: ${s.id} URL: ${s.url}` }, /* @__PURE__ */ w("span", { class: "muted" }, e)), t, i ? /* @__PURE__ */ w("a", { class: "state text-secondary px-0.5", href: i, target: "_blank", "data-hint": "Visit xhprof page" }, "XHP") : null);
}
function Pf(s) {
  ut("Trace", "Error", s, s.message), navigator.clipboard.writeText(`vim +${s.line} ${s.file}`);
}
function Rf({ errors: s = [], show: e, basePath: t }) {
  s.length || (e = !1);
  const n = s.map((i) => {
    const o = mh(i.level), r = gh(o), a = r === "error" ? "danger" : r === "info" ? "important" : "warning";
    return /* @__PURE__ */ w("div", { class: `zin-error-item state ${a}-pale text-fore px-2 py-1 ring ring-darker`, onClick: Pf.bind(null, i) }, /* @__PURE__ */ w("div", { class: "zin-error-msg font-bold text-base" }, /* @__PURE__ */ w("strong", { class: `text-${a}`, style: "text-transform: uppercase;" }, o), " ", i.message), /* @__PURE__ */ w("div", { class: "zin-error-info text-sm opacity-60 break-all" }, /* @__PURE__ */ w("strong", null, "vim +", i.line), " ", /* @__PURE__ */ w("span", { className: "underline" }, t ? i.file.substring(t.length) : i.file)));
  });
  return /* @__PURE__ */ w("div", { class: `zin-errors-panel absolute bottom-full left-0 mono shadow-xl ring rounded fade-from-bottom ${e ? "in" : "events-none"}` }, n);
}
let If = class extends F {
  constructor(e) {
    var t, n;
    super(e), this.state = {
      showPanel: e.defaultShow ?? !0,
      showZinbar: !!((n = (t = e.defaultData) == null ? void 0 : t.errors) != null && n.length || !pe.get("Zinbar:hidden")),
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
  update(e, t, n) {
    this.setState((i) => {
      const o = !(e != null && e.id) || e.id === "page", r = {};
      return o ? (e && (r.pagePerf = { ...i.pagePerf, ...e }), t && (r.errors = t)) : (e && (r.partPerf = { ...i.partPerf, ...e }), t && (r.errors = [...i.errors ?? [], ...t ?? []])), n && (r.basePath = n), r;
    });
  }
  togglePanel() {
    this.setState({ showPanel: !this.state.showPanel });
  }
  toggleZinbar() {
    const e = !this.state.showZinbar;
    this.setState({ showZinbar: e }, () => {
      pe.set("Zinbar:hidden", !e);
    });
  }
  render() {
    const { errors: e, pagePerf: t, partPerf: n, showZinbar: i, basePath: o } = this.state, { fixed: r } = this.props, a = e == null ? void 0 : e.length;
    return /* @__PURE__ */ w(
      "div",
      {
        className: M(
          "zinbar row h-5 items-stretch gap-px inverse bg-opacity-50",
          r ? "relative" : "fixed right-0 bottom-0",
          { collapse: !i }
        ),
        style: { zIndex: 9999 }
      },
      /* @__PURE__ */ w(
        "button",
        {
          type: "button",
          "data-hint": i ? "Collapse" : "Expand",
          className: `w-4 ${a && !i ? "danger" : "bg-dark"} flex items-center justify-center`,
          style: { marginLeft: -15 },
          onClick: this.toggleZinbar.bind(this)
        },
        /* @__PURE__ */ w("span", { class: i ? "caret-right" : "caret-left" })
      ),
      a ? /* @__PURE__ */ w(Mf, { errors: e, onClick: this.togglePanel }) : null,
      t ? /* @__PURE__ */ w(El, { perf: t }) : null,
      n ? /* @__PURE__ */ w(El, { perf: n }) : null,
      /* @__PURE__ */ w(Rf, { show: this.state.showPanel, basePath: o, errors: e })
    );
  }
};
const bo = class bo extends V {
};
bo.NAME = "Zinbar", bo.Component = If;
let Ml = bo;
const wo = class wo extends oe {
  _getLayoutOptions() {
    const e = super._getLayoutOptions();
    return this.options.element || (e[0] = {
      getBoundingClientRect: this._getClickBounding
    }), e;
  }
};
wo.NAME = "ContextMenu", wo.DEFAULT = {
  ...oe.DEFAULT,
  name: "contextmenu",
  trigger: "contextmenu"
};
let Pr = wo;
let Df = class extends F {
  constructor() {
    super(...arguments), this._onDragStart = (e) => {
      var i, o, r;
      const t = e.target.closest(".dashboard-block");
      if (!t)
        return;
      const n = t.getBoundingClientRect();
      if (e.clientY - n.top > 48) {
        e.preventDefault();
        return;
      }
      this.setState({ dragging: !0 }), (i = e.dataTransfer) == null || i.setData("application/id", this.props.id), (r = (o = this.props).onDragStart) == null || r.call(o, e);
    }, this._onDragEnd = (e) => {
      var t, n;
      this.setState({ dragging: !1 }), (n = (t = this.props).onDragEnd) == null || n.call(t, e);
    };
  }
  render() {
    const { left: e, top: t, id: n, onMenuBtnClick: i, title: o, width: r, height: a, content: l, loading: h } = this.props, { dragging: d } = this.state;
    return /* @__PURE__ */ g("div", { class: "dashboard-block-cell", style: { left: e, top: t, width: r, height: a }, children: /* @__PURE__ */ g(
      "div",
      {
        class: `dashboard-block load-indicator${h && !l ? " loading" : ""}${i ? " has-more-menu" : ""}${d ? " is-dragging" : ""}`,
        draggable: !0,
        onDragStart: this._onDragStart,
        onDragEnd: this._onDragEnd,
        "data-id": n,
        children: [
          /* @__PURE__ */ g("div", { class: "dashboard-block-header", children: [
            /* @__PURE__ */ g("div", { class: "dashboard-block-title", children: o }),
            i ? /* @__PURE__ */ g("div", { class: "dashboard-block-actions toolbar", children: /* @__PURE__ */ g("button", { class: "toolbar-item dashboard-block-action btn square ghost rounded size-sm", "data-type": "more", onClick: i, children: /* @__PURE__ */ g("div", { class: "more-vert" }) }) }) : null
          ] }),
          u.isPlainObject(l) && l.html ? /* @__PURE__ */ g(Ds, { className: "dashboard-block-body", executeScript: !0, ...l }) : /* @__PURE__ */ g("div", { class: "dashboard-block-body", children: l })
        ]
      }
    ) });
  }
};
const Pl = ([s, e, t, n], [i, o, r, a]) => !(s + t <= i || i + r <= s || e + n <= o || o + a <= e), Ws = "Dashboard:Block.cache:";
var Ss;
let Af = (Ss = class extends F {
  constructor(e) {
    super(e), this.map = /* @__PURE__ */ new Map(), this._handleDragStart = (t) => {
      var i;
      const n = (i = t.dataTransfer) == null ? void 0 : i.getData("application/id");
      n !== void 0 && (this.setState({ dragging: n }), console.log("handleBlockDragStart", t));
    }, this._handleDragEnd = (t) => {
      this.setState({ dragging: void 0 }), console.log("handleBlockDragEnd", t);
    }, this._handleMenuClick = (t) => {
      const n = t.target.closest(".dashboard-block");
      if (!n)
        return;
      const i = n.dataset.id;
      if (!i)
        return;
      const o = this.getBlock(i);
      if (!o || !o.menu)
        return;
      t.stopPropagation();
      const { menu: r } = o, { onClickMenu: a } = this.props;
      Pr.show({
        triggerEvent: t,
        element: t.target,
        placement: "bottom-end",
        menu: {
          onClickItem: (l) => {
            var h;
            ((h = l.item.data) == null ? void 0 : h.type) === "refresh" && this.load(i), a && a.call(this, l, o);
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
    const { id: n } = e, { blocks: i } = this.state, o = i.findIndex((a) => a.id === n);
    if (o < 0)
      return;
    const r = i[o];
    e.fetch && e.fetch !== r.fetch && r.needLoad && (e.needLoad = !1), i[o] = { ...r, ...e }, this.setState({ blocks: i }, t);
  }
  delete(e) {
    const { blocks: t } = this.state, n = t.findIndex((i) => i.id === e);
    n < 0 || (t.splice(n, 1), this.setState({ blocks: t }));
  }
  add(e) {
    e = Array.isArray(e) ? e : [e], this.setState({ blocks: [...this.state.blocks, ...this._initBlocks(e)] });
  }
  load(e, t) {
    const n = this.getBlock(e);
    if (!n || n.loading || (t = t || n.fetch, typeof t == "string" ? t = { url: t } : typeof t == "function" && (t = t(n.id, n)), !t || !t.url))
      return;
    const { url: i, ...o } = t;
    this.update({ id: e, loading: !0, needLoad: !1 }, async () => {
      const r = U(i, n);
      try {
        const a = await fetch(U(r, n), {
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
    for (const n of e) {
      if (n.loading)
        return;
      n.needLoad && (t = n.id);
    }
    t.length && requestAnimationFrame(() => this.load(t));
  }
  _setCache(e, t) {
    const { cache: n } = this.props;
    if (n)
      try {
        typeof n == "string" ? pe.set(`${Ws}${n}:${e}`, t) : pe.session.set(`${Ws}${e}`, t);
      } catch {
        console.warn("ZUI: Failed to cache block content.", { id: e, html: t });
      }
  }
  _getCache(e) {
    const { cache: t } = this.props;
    if (!t)
      return;
    const n = typeof t == "string" ? pe.get(`${Ws}${t}:${e}`) : pe.session.get(`${Ws}${e}`);
    if (n)
      return { html: n };
  }
  _initBlocks(e) {
    const { blockFetch: t, blockMenu: n } = this.props;
    return e.map((o) => {
      const {
        id: r,
        size: a,
        left: l = -1,
        top: h = -1,
        fetch: d = t,
        menu: c = n,
        content: f,
        ...p
      } = o, [m, b] = this._getBlockSize(a);
      return {
        id: `${r}`,
        width: m,
        height: b,
        left: l,
        top: h,
        fetch: d,
        menu: c,
        content: f ?? this._getCache(`${r}`),
        loading: !1,
        needLoad: !!d,
        ...p
      };
    });
  }
  _getBlockSize(e) {
    const { blockDefaultSize: t, blockSizeMap: n } = this.props;
    return e = e ?? t, typeof e == "string" && (e = n[e]), e = e || t, Array.isArray(e) || (e = [e.width, e.height]), e;
  }
  _layout() {
    this.map.clear();
    let e = 0;
    const { blocks: t } = this.state;
    return t.forEach((n) => {
      this._layoutBlock(n);
      const [, i, , o] = this.map.get(n.id);
      e = Math.max(e, i + o);
    }), { blocks: t, height: e };
  }
  _layoutBlock(e) {
    const t = this.map, { id: n, left: i, top: o, width: r, height: a } = e;
    if (i < 0 || o < 0) {
      const [l, h] = this._appendBlock(r, a, i, o);
      t.set(n, [l, h, r, a]);
    } else
      this._insertBlock(n, [i, o, r, a]);
  }
  _canPlace(e) {
    const { dragging: t } = this.state;
    for (const [n, i] of this.map.entries())
      if (n !== t && Pl(i, e))
        return !1;
    return !0;
  }
  _insertBlock(e, t) {
    this.map.set(e, t);
    for (const [n, i] of this.map.entries())
      n !== e && Pl(i, t) && (i[1] = t[1] + t[3], this._insertBlock(n, i));
  }
  _appendBlock(e, t, n, i) {
    if (n >= 0 && i >= 0) {
      if (this._canPlace([n, i, e, t]))
        return [n, i];
      i = -1;
    }
    let o = n < 0 ? 0 : n, r = i < 0 ? 0 : i, a = !1;
    const l = this.props.grid;
    for (; !a; ) {
      if (this._canPlace([o, r, e, t])) {
        a = !0;
        break;
      }
      n < 0 ? (o += 1, o + e > l && (o = 0, r += 1)) : r += 1;
    }
    return [o, r];
  }
  componentDidMount() {
    this.loadNext();
  }
  componentDidUpdate(e) {
    e.blocks !== this.props.blocks ? this.setState({ blocks: this._initBlocks(this.props.blocks) }) : this.loadNext();
  }
  render() {
    const { blocks: e, height: t } = this._layout(), { cellHeight: n, grid: i } = this.props, o = this.map;
    return /* @__PURE__ */ g("div", { class: "dashboard", children: /* @__PURE__ */ g("div", { class: "dashboard-blocks", style: { height: t * n }, children: e.map((r, a) => {
      const { id: l, menu: h, content: d, title: c } = r, [f, p, m, b] = o.get(l) || [0, 0, r.width, r.height];
      return /* @__PURE__ */ g(
        Df,
        {
          id: l,
          index: a,
          left: `${100 * f / i}%`,
          top: n * p,
          width: `${100 * m / i}%`,
          height: n * b,
          content: d,
          title: c,
          onDragStart: this._handleDragStart,
          onDragEnd: this._handleDragEnd,
          onMenuBtnClick: h ? this._handleMenuClick : void 0
        },
        r.id
      );
    }) }) });
  }
}, Ss.defaultProps = {
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
}, Ss);
const vo = class vo extends V {
};
vo.NAME = "Dashboard", vo.Component = Af;
let Rl = vo;
var te, ee;
class Il extends F {
  constructor(t) {
    super(t);
    C(this, te, void 0);
    C(this, ee, void 0);
    $(this, te, 0), $(this, ee, null), this._handleWheel = (n) => {
      const { wheelContainer: i } = this.props, o = n.target;
      if (!(!o || !i) && (typeof i == "string" && o.closest(i) || typeof i == "object")) {
        const r = (this.props.type === "horz" ? n.deltaX : n.deltaY) * (this.props.wheelSpeed ?? 1);
        this.scrollOffset(r) && n.preventDefault();
      }
    }, this._handleMouseMove = (n) => {
      const { dragStart: i } = this.state;
      i && (y(this, te) && cancelAnimationFrame(y(this, te)), $(this, te, requestAnimationFrame(() => {
        const o = this.props.type === "horz" ? n.clientX - i.x : n.clientY - i.y;
        this.scroll(i.offset + o * this.props.scrollSize / this.props.clientSize), $(this, te, 0);
      })), n.preventDefault());
    }, this._handleMouseUp = () => {
      this.state.dragStart && this.setState({
        dragStart: !1
      });
    }, this._handleMouseDown = (n) => {
      this.state.dragStart || this.setState({ dragStart: { x: n.clientX, y: n.clientY, offset: this.scrollPos } }), n.stopPropagation();
    }, this._handleClick = (n) => {
      const i = n.currentTarget;
      if (!i)
        return;
      const o = i.getBoundingClientRect(), { type: r, clientSize: a, scrollSize: l } = this.props, h = (r === "horz" ? n.clientX - o.left : n.clientY - o.top) - this.barSize / 2;
      this.scroll(h * l / a), n.preventDefault();
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
    const { scrollSize: t, clientSize: n } = this.props;
    return Math.max(0, t - n);
  }
  get barSize() {
    const { clientSize: t, scrollSize: n, size: i = 12, minBarSize: o = 3 * i } = this.props;
    return Math.max(Math.round(t * t / n), o);
  }
  componentDidMount() {
    document.addEventListener("mousemove", this._handleMouseMove), document.addEventListener("mouseup", this._handleMouseUp);
    const { wheelContainer: t } = this.props;
    t && ($(this, ee, typeof t == "string" ? document : t.current), y(this, ee).addEventListener("wheel", this._handleWheel, { passive: !1 }));
  }
  componentWillUnmount() {
    document.removeEventListener("mousemove", this._handleMouseMove), document.removeEventListener("mouseup", this._handleMouseUp), y(this, ee) && y(this, ee).removeEventListener("wheel", this._handleWheel);
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
    const { onScroll: n } = this.props;
    n && n(t, this.props.type ?? "vert");
  }
  render() {
    const {
      clientSize: t,
      type: n,
      size: i = 12,
      className: o,
      style: r,
      left: a,
      top: l,
      bottom: h,
      right: d
    } = this.props, { maxScrollPos: c, scrollPos: f } = this, { dragStart: p } = this.state, m = {
      left: a,
      top: l,
      bottom: h,
      right: d,
      ...r
    }, b = {};
    return n === "horz" ? (m.height = i, m.width = t, b.width = this.barSize, b.left = Math.round(Math.min(c, f) * (t - b.width) / c)) : (m.width = i, m.height = t, b.height = this.barSize, b.top = Math.round(Math.min(c, f) * (t - b.height) / c)), /* @__PURE__ */ g(
      "div",
      {
        className: M("scrollbar", o, {
          "is-vert": n === "vert",
          "is-horz": n === "horz",
          "is-dragging": p
        }),
        style: m,
        onMouseDown: this._handleClick,
        children: /* @__PURE__ */ g(
          "div",
          {
            className: "scrollbar-bar",
            style: b,
            onMouseDown: this._handleMouseDown
          }
        )
      }
    );
  }
}
te = new WeakMap(), ee = new WeakMap();
const mi = /* @__PURE__ */ new Map(), gi = [];
function yh(s, e) {
  const { name: t } = s;
  if (!(e != null && e.override) && mi.has(t))
    throw new Error(`DTable: Plugin with name ${t} already exists`);
  mi.set(t, s), e != null && e.buildIn && !gi.includes(t) && gi.push(t);
}
function rt(s, e) {
  yh(s, e);
  const t = (n) => {
    if (!n)
      return s;
    const { defaultOptions: i, ...o } = s;
    return {
      ...o,
      defaultOptions: { ...i, ...n }
    };
  };
  return t.plugin = s, t;
}
function bh(s) {
  return mi.delete(s);
}
function Lf(s) {
  if (typeof s == "string") {
    const e = mi.get(s);
    return e || console.warn(`DTable: Cannot found plugin "${s}"`), e;
  }
  if (typeof s == "function" && "plugin" in s)
    return s.plugin;
  if (typeof s == "object")
    return s;
  console.warn("DTable: Invalid plugin", s);
}
function wh(s, e, t) {
  return e.forEach((n) => {
    var o;
    if (!n)
      return;
    const i = Lf(n);
    i && (t.has(i.name) || ((o = i.plugins) != null && o.length && wh(s, i.plugins, t), s.push(i), t.add(i.name)));
  }), s;
}
function Of(s = [], e = !0) {
  return e && gi.length && s.unshift(...gi), s != null && s.length ? wh([], s, /* @__PURE__ */ new Set()) : [];
}
function Dl() {
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
function jf(s, e, t) {
  return s && (e && (s = Math.max(e, s)), t && (s = Math.min(t, s))), s;
}
function Al(s, e) {
  return typeof s == "string" && (s = s.endsWith("%") ? parseFloat(s) / 100 : parseFloat(s)), typeof e == "number" && (typeof s != "number" || isNaN(s)) && (s = e), s;
}
function tr(s, e = !1) {
  if (!s.list.length)
    return;
  if (s.widthSetting && s.width !== s.widthSetting) {
    s.width = s.widthSetting;
    const n = s.width - s.totalWidth;
    if (!e && n > 0 || e && n !== 0) {
      const i = s.flexList.length ? s.flexList : s.list, o = i.reduce((r, a) => r + (a.flex || 1), 0);
      i.forEach((r) => {
        const a = Math[n < 0 ? "max" : "min"](n, Math.ceil(n * ((r.flex || 1) / o)));
        r.realWidth = r.width + a;
      });
    }
  }
  let t = 0;
  s.list.forEach((n) => {
    n.realWidth || (n.realWidth = n.width), n.left = t, t += n.realWidth;
  });
}
function Hf(s, e, t, n) {
  const { defaultColWidth: i, minColWidth: o, maxColWidth: r, fixedLeftWidth: a = 0, fixedRightWidth: l = 0 } = e, h = (x) => (typeof x == "function" && (x = x.call(s)), x = Al(x, 0), x < 1 && (x = Math.round(x * n)), x), d = {
    width: 0,
    list: [],
    flexList: [],
    widthSetting: 0,
    totalWidth: 0
  }, c = {
    ...d,
    list: [],
    flexList: [],
    widthSetting: h(a)
  }, f = {
    ...d,
    list: [],
    flexList: [],
    widthSetting: h(l)
  }, p = [], m = {};
  let b = !1;
  const _ = [], v = {};
  if (t.forEach((x) => {
    const { colTypes: k, onAddCol: N } = x;
    k && Object.entries(k).forEach(([S, R]) => {
      v[S] || (v[S] = []), v[S].push(R);
    }), N && _.push(N);
  }), e.cols.forEach((x) => {
    if (x.hidden)
      return;
    const { type: k = "", name: N } = x, S = {
      fixed: !1,
      flex: !1,
      width: i,
      minWidth: o,
      maxWidth: r,
      ...x,
      type: k
    }, R = {
      name: N,
      type: k,
      setting: S,
      flex: 0,
      left: 0,
      width: 0,
      realWidth: 0,
      visible: !0,
      index: p.length
    }, L = v[k];
    L && L.forEach((z) => {
      const W = typeof z == "function" ? z.call(s, S) : z;
      W && Object.assign(S, W, x);
    });
    const { fixed: I, flex: D, minWidth: A = o, maxWidth: O = r } = S, E = Al(S.width || i, i);
    R.flex = D === !0 ? 1 : typeof D == "number" ? D : 0, R.width = jf(E < 1 ? Math.round(E * n) : E, A, O), _.forEach((z) => z.call(s, R)), p.push(R), m[R.name] = R;
    const T = I ? I === "left" ? c : f : d;
    T.list.push(R), T.totalWidth += R.width, T.width = T.totalWidth, R.flex && T.flexList.push(R), typeof S.order == "number" && (b = !0);
  }), b) {
    const x = (k, N) => (k.setting.order ?? 0) - (N.setting.order ?? 0);
    p.sort(x), c.list.sort(x), d.list.sort(x), f.list.sort(x);
  }
  return tr(c, !0), tr(f, !0), d.widthSetting = n - c.width - f.width, tr(d), {
    list: p,
    map: m,
    left: c,
    center: d,
    right: f
  };
}
function zf({ col: s, className: e, height: t, row: n, onRenderCell: i, style: o, outerStyle: r, children: a, outerClass: l, width: h, left: d, top: c, ...f }) {
  var E;
  const p = {
    left: d ?? s.left,
    top: c ?? n.top,
    width: h ?? s.realWidth,
    height: t,
    ...r
  }, { align: m, border: b } = s.setting, _ = {
    justifyContent: m ? m === "left" ? "start" : m === "right" ? "end" : m : void 0,
    ...s.setting.cellStyle,
    ...o
  }, v = ["dtable-cell", l, e, s.setting.className, {
    "has-border-left": b === !0 || b === "left",
    "has-border-right": b === !0 || b === "right"
  }], x = ["dtable-cell-content", s.setting.cellClass], k = (E = n.data) == null ? void 0 : E[s.name], N = [a ?? k ?? ""], S = i ? i(N, { row: n, col: s, value: k }, w) : N, R = [], L = [], I = {}, D = {};
  let A = "div";
  S == null || S.forEach((T) => {
    if (typeof T == "object" && T && !et(T) && ("html" in T || "className" in T || "style" in T || "attrs" in T || "children" in T || "tagName" in T)) {
      const z = T.outer ? R : L;
      T.html ? z.push(/* @__PURE__ */ g("div", { className: M("dtable-cell-html", T.className), style: T.style, dangerouslySetInnerHTML: { __html: T.html }, ...T.attrs ?? {} })) : (T.style && Object.assign(T.outer ? p : _, T.style), T.className && (T.outer ? v : x).push(T.className), T.children && z.push(T.children), T.attrs && Object.assign(T.outer ? I : D, T.attrs)), T.tagName && !T.outer && (A = T.tagName);
    } else
      L.push(T);
  });
  const O = A;
  return /* @__PURE__ */ g(
    "div",
    {
      className: M(v),
      style: p,
      "data-col": s.name,
      "data-row": n.id,
      "data-type": s.type || null,
      ...f,
      ...I,
      children: [
        L.length > 0 && /* @__PURE__ */ g(O, { className: M(x), style: _, ...D, children: L }),
        R
      ]
    }
  );
}
function er({
  rows: s = [],
  cols: e,
  rowHeight: t,
  scrollLeft: n = 0,
  scrollTop: i = 0,
  left: o = 0,
  top: r = 0,
  width: a,
  height: l = "100%",
  className: h,
  CellComponent: d = zf,
  onRenderCell: c
}) {
  var b;
  const f = Array.isArray(s) ? s : [s], p = ((b = f[0]) == null ? void 0 : b.top) ?? 0, m = f.length;
  return /* @__PURE__ */ g(
    "div",
    {
      className: M("dtable-cells", h),
      style: { top: r, left: o, width: a, height: l },
      children: /* @__PURE__ */ g("div", { className: "dtable-cells-container", style: { left: -n, top: -i + p }, children: f.reduce((_, v, x) => {
        const k = e.length;
        return e.forEach((N, S) => {
          _.push(
            /* @__PURE__ */ g(
              d,
              {
                className: M(
                  `is-${v.index % 2 ? "odd" : "even"}-row`,
                  S ? "" : "is-first-in-row",
                  S === k - 1 ? "is-last-in-row" : "",
                  x ? "" : "is-first-row",
                  x === m - 1 ? "is-last-row" : ""
                ),
                col: N,
                row: v,
                top: v.top - p,
                height: t,
                onRenderCell: c
              },
              `${v.index}:${N.name}`
            )
          );
        }), _;
      }, []) })
    }
  );
}
function Ll({
  top: s,
  height: e,
  rowHeight: t,
  rows: n,
  cols: { left: i, center: o, right: r },
  scrollLeft: a,
  scrollTop: l,
  className: h,
  style: d,
  onRenderCell: c
}) {
  let f = null;
  i.list.length && (f = /* @__PURE__ */ g(
    er,
    {
      className: "dtable-fixed-left",
      rows: n,
      scrollTop: l,
      cols: i.list,
      width: i.width,
      rowHeight: t,
      onRenderCell: c
    },
    "left"
  ));
  let p = null;
  o.list.length && (p = /* @__PURE__ */ g(
    er,
    {
      rows: n,
      className: "dtable-scroll-center",
      scrollLeft: a,
      scrollTop: l,
      cols: o.list,
      left: i.width,
      width: o.width,
      rowHeight: t,
      onRenderCell: c
    },
    "center"
  ));
  let m = null;
  return r.list.length && (m = /* @__PURE__ */ g(
    er,
    {
      className: "dtable-fixed-right",
      rows: n,
      scrollTop: l,
      cols: r.list,
      left: i.width + o.width,
      width: r.width,
      rowHeight: t,
      onRenderCell: c
    },
    "right"
  )), /* @__PURE__ */ g(
    "div",
    {
      className: M("dtable-block", h),
      style: { ...d, top: s, height: e },
      children: [
        f,
        p,
        m
      ]
    }
  );
}
var ne, un, Lt, $t, se, st, kt, ft, Ce, Ns, fn, $e, Tt, ke, Te, _o, vh, xo, _h, Co, xh, $o, Ch, Es, Rr, pn, mn, Ms, Ps, Rs, Is, gn, Ys, ko, $h, To, kh, So, Th, xn;
let Bf = (xn = class extends F {
  constructor(t) {
    super(t);
    C(this, _o);
    C(this, xo);
    C(this, Co);
    C(this, $o);
    C(this, Es);
    C(this, gn);
    C(this, ko);
    C(this, To);
    C(this, So);
    C(this, ne, void 0);
    C(this, un, void 0);
    C(this, Lt, void 0);
    C(this, $t, void 0);
    C(this, se, void 0);
    C(this, st, void 0);
    C(this, kt, void 0);
    C(this, ft, void 0);
    C(this, Ce, void 0);
    C(this, Ns, void 0);
    C(this, fn, void 0);
    C(this, $e, void 0);
    C(this, Tt, void 0);
    C(this, ke, void 0);
    C(this, Te, void 0);
    C(this, pn, void 0);
    C(this, mn, void 0);
    C(this, Ms, void 0);
    C(this, Ps, void 0);
    C(this, Rs, void 0);
    C(this, Is, void 0);
    this.ref = G(), $(this, ne, 0), $(this, Lt, !1), $(this, st, []), $(this, ft, /* @__PURE__ */ new Map()), $(this, Ce, {}), $(this, fn, []), $(this, $e, { in: !1 }), this.updateLayout = () => {
      y(this, ne) && cancelAnimationFrame(y(this, ne)), $(this, ne, requestAnimationFrame(() => {
        this.update({ dirtyType: "layout" }), $(this, ne, 0);
      }));
    }, $(this, Tt, (n, i) => {
      i = i || n.type;
      const o = y(this, ft).get(i);
      if (o != null && o.length) {
        for (const r of o)
          if (r.call(this, n) === !1) {
            n.stopPropagation(), n.preventDefault();
            break;
          }
      }
    }), $(this, ke, (n) => {
      y(this, Tt).call(this, n, `window_${n.type}`);
    }), $(this, Te, (n) => {
      y(this, Tt).call(this, n, `document_${n.type}`);
    }), $(this, pn, (n, i, o) => {
      const { row: r, col: a } = i;
      i.value = this.getCellValue(r, a), n[0] = i.value;
      const l = r.id === "HEADER" ? "onRenderHeaderCell" : "onRenderCell";
      return y(this, st).forEach((h) => {
        h[l] && (n = h[l].call(this, n, i, o));
      }), this.options[l] && (n = this.options[l].call(this, n, i, o)), a.setting[l] && (n = a.setting[l].call(this, n, i, o)), n;
    }), $(this, mn, (n, i) => {
      i === "horz" ? this.scroll({ scrollLeft: n }) : this.scroll({ scrollTop: n });
    }), $(this, Ms, (n) => {
      var l, h, d;
      const i = this.getPointerInfo(n);
      if (!i)
        return;
      const { rowID: o, colName: r, cellElement: a } = i;
      if (o === "HEADER")
        a && ((l = this.options.onHeaderCellClick) == null || l.call(this, n, { colName: r, element: a }), y(this, st).forEach((c) => {
          var f;
          (f = c.onHeaderCellClick) == null || f.call(this, n, { colName: r, element: a });
        }));
      else {
        const c = this.layout.visibleRows.find((f) => f.id === o);
        if (a) {
          if (((h = this.options.onCellClick) == null ? void 0 : h.call(this, n, { colName: r, rowID: o, rowInfo: c, element: a })) === !0)
            return;
          for (const f of y(this, st))
            if (((d = f.onCellClick) == null ? void 0 : d.call(this, n, { colName: r, rowID: o, rowInfo: c, element: a })) === !0)
              return;
        }
      }
    }), $(this, Ps, (n) => {
      const i = n.key.toLowerCase();
      if (["pageup", "pagedown", "home", "end"].includes(i))
        return !this.scroll({ to: i.replace("page", "") });
    }), $(this, Rs, (n) => {
      const i = u(n.target).closest(".dtable-cell");
      if (!i.length)
        return j(this, gn, Ys).call(this, !1);
      j(this, gn, Ys).call(this, [i.attr("data-row"), i.attr("data-col")]);
    }), $(this, Is, () => {
      j(this, gn, Ys).call(this, !1);
    }), $(this, un, t.id ?? `dtable-${Hc(10)}`), this.state = { scrollTop: 0, scrollLeft: 0, renderCount: 0 }, $(this, se, Object.freeze(Of(t.plugins))), y(this, se).forEach((n) => {
      var a;
      const { methods: i, data: o, state: r } = n;
      i && Object.entries(i).forEach(([l, h]) => {
        typeof h == "function" && Object.assign(this, { [l]: h.bind(this) });
      }), o && Object.assign(y(this, Ce), o.call(this)), r && Object.assign(this.state, r.call(this)), (a = n.onCreate) == null || a.call(this, n);
    });
  }
  get options() {
    var t;
    return ((t = y(this, kt)) == null ? void 0 : t.options) || y(this, $t) || Dl();
  }
  get plugins() {
    return y(this, st);
  }
  get layout() {
    return y(this, kt);
  }
  get id() {
    return y(this, un);
  }
  get data() {
    return y(this, Ce);
  }
  get element() {
    return this.ref.current;
  }
  get parent() {
    var t;
    return this.props.parent ?? ((t = this.element) == null ? void 0 : t.parentElement);
  }
  get hoverInfo() {
    return y(this, $e);
  }
  componentWillReceiveProps() {
    $(this, $t, void 0);
  }
  componentDidMount() {
    y(this, Lt) ? this.forceUpdate() : j(this, Es, Rr).call(this), y(this, st).forEach((n) => {
      let { events: i } = n;
      i && (typeof i == "function" && (i = i.call(this)), Object.entries(i).forEach(([o, r]) => {
        r && this.on(o, r);
      }));
    }), this.on("click", y(this, Ms)), this.on("keydown", y(this, Ps));
    const { options: t } = this;
    if ((t.rowHover || t.colHover) && (this.on("mouseover", y(this, Rs)), this.on("mouseleave", y(this, Is))), t.responsive)
      if (typeof ResizeObserver < "u") {
        const { parent: n } = this;
        if (n) {
          const i = new ResizeObserver(this.updateLayout);
          i.observe(n), $(this, Ns, i);
        }
      } else
        this.on("window_resize", this.updateLayout);
    y(this, st).forEach((n) => {
      var i;
      (i = n.onMounted) == null || i.call(this);
    });
  }
  componentDidUpdate() {
    y(this, Lt) ? j(this, Es, Rr).call(this) : y(this, st).forEach((t) => {
      var n;
      (n = t.onUpdated) == null || n.call(this);
    });
  }
  componentWillUnmount() {
    var n;
    (n = y(this, Ns)) == null || n.disconnect();
    const { element: t } = this;
    if (t)
      for (const i of y(this, ft).keys())
        i.startsWith("window_") ? window.removeEventListener(i.replace("window_", ""), y(this, ke)) : i.startsWith("document_") ? document.removeEventListener(i.replace("document_", ""), y(this, Te)) : t.removeEventListener(i, y(this, Tt));
    y(this, st).forEach((i) => {
      var o;
      (o = i.onUnmounted) == null || o.call(this);
    }), y(this, se).forEach((i) => {
      var o;
      (o = i.onDestory) == null || o.call(this);
    }), $(this, Ce, {}), y(this, ft).clear();
  }
  on(t, n, i) {
    var r;
    i && (t = `${i}_${t}`);
    const o = y(this, ft).get(t);
    o ? o.push(n) : (y(this, ft).set(t, [n]), t.startsWith("window_") ? window.addEventListener(t.replace("window_", ""), y(this, ke)) : t.startsWith("document_") ? document.addEventListener(t.replace("document_", ""), y(this, Te)) : (r = this.element) == null || r.addEventListener(t, y(this, Tt)));
  }
  off(t, n, i) {
    var a;
    i && (t = `${i}_${t}`);
    const o = y(this, ft).get(t);
    if (!o)
      return;
    const r = o.indexOf(n);
    r >= 0 && o.splice(r, 1), o.length || (y(this, ft).delete(t), t.startsWith("window_") ? window.removeEventListener(t.replace("window_", ""), y(this, ke)) : t.startsWith("document_") ? document.removeEventListener(t.replace("document_", ""), y(this, Te)) : (a = this.element) == null || a.removeEventListener(t, y(this, Tt)));
  }
  emitCustomEvent(t, n) {
    y(this, Tt).call(this, n instanceof Event ? n : new CustomEvent(t, { detail: n }), t);
  }
  scroll(t, n) {
    const { scrollLeft: i, scrollTop: o, rowsHeightTotal: r, rowsHeight: a, rowHeight: l, cols: { center: { totalWidth: h, width: d } } } = this.layout, { to: c } = t;
    let { scrollLeft: f, scrollTop: p } = t;
    if (c === "up" || c === "down")
      p = o + (c === "down" ? 1 : -1) * Math.floor(a / l) * l;
    else if (c === "left" || c === "right")
      f = i + (c === "right" ? 1 : -1) * d;
    else if (c === "top")
      p = 0;
    else if (c === "bottom")
      p = r - a;
    else if (c === "begin")
      f = 0;
    else if (c === "end")
      f = h - d;
    else {
      const { offsetLeft: b, offsetTop: _ } = t;
      typeof b == "number" && (f = i + b), typeof _ == "number" && (f = o + _);
    }
    const m = {};
    return typeof f == "number" && (f = Math.max(0, Math.min(f, h - d)), f !== i && (m.scrollLeft = f)), typeof p == "number" && (p = Math.max(0, Math.min(p, r - a)), p !== o && (m.scrollTop = p)), Object.keys(m).length ? (this.setState(m, () => {
      var b;
      (b = this.options.onScroll) == null || b.call(this, m), n == null || n.call(this, !0);
    }), !0) : (n == null || n.call(this, !1), !1);
  }
  getColInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    const { cols: n } = this.layout;
    return typeof t == "number" ? n.list[t] : n.map[t];
  }
  getRowInfo(t) {
    if (t === void 0)
      return;
    if (typeof t == "object")
      return t;
    if (t === -1 || t === "HEADER")
      return { id: "HEADER", index: -1, top: 0 };
    const { rows: n, rowsMap: i, allRows: o } = this.layout;
    return typeof t == "number" ? n[t] : i[t] || o.find((r) => r.id === t);
  }
  getCellValue(t, n) {
    var l;
    const i = typeof t == "object" ? t : this.getRowInfo(t);
    if (!i)
      return;
    const o = typeof n == "object" ? n : this.getColInfo(n);
    if (!o)
      return;
    let r = i.id === "HEADER" ? o.setting.title : (l = i.data) == null ? void 0 : l[o.name];
    const { cellValueGetter: a } = this.options;
    return a && (r = a.call(this, i, o, r)), r;
  }
  getRowInfoByIndex(t) {
    return this.layout.rows[t];
  }
  update(t = {}, n) {
    if (!y(this, $t))
      return;
    typeof t == "function" && (n = t, t = {});
    const { dirtyType: i, state: o } = t;
    if (i === "layout")
      $(this, kt, void 0);
    else if (i === "options") {
      if ($(this, $t, void 0), !y(this, kt))
        return;
      $(this, kt, void 0);
    }
    this.setState(o ?? ((r) => ({ renderCount: r.renderCount + 1 })), n);
  }
  getPointerInfo(t) {
    const n = t.target;
    if (!n || n.closest(".no-cell-event"))
      return;
    const i = u(n).closest(".dtable-cell");
    if (!i.length)
      return;
    const o = i.attr("data-row"), r = i.attr("data-col");
    if (!(typeof r != "string" || typeof o != "string"))
      return {
        cellElement: i[0],
        colName: r,
        rowID: o,
        target: n
      };
  }
  i18n(t, n, i) {
    return nt(y(this, fn), t, n, i, this.options.lang) ?? `{i18n:${t}}`;
  }
  getPlugin(t) {
    return this.plugins.find((n) => n.name === t);
  }
  render() {
    const t = j(this, So, Th).call(this), { className: n, rowHover: i, colHover: o, cellHover: r, bordered: a, striped: l, scrollbarHover: h } = this.options, d = {}, c = ["dtable", n, {
      "dtable-hover-row": i,
      "dtable-hover-col": o,
      "dtable-hover-cell": r,
      "dtable-bordered": a,
      "dtable-striped": l,
      "scrollbar-hover": h
    }], f = [];
    return t && (d.width = t.width, d.height = t.height, c.push({
      "dtable-scrolled-down": t.scrollTop > 0,
      "dtable-scrolled-bottom": t.scrollTop >= t.rowsHeightTotal - t.rowsHeight,
      "dtable-scrolled-right": t.scrollLeft > 0,
      "dtable-scrolled-end": t.scrollLeft >= t.cols.center.totalWidth - t.cols.center.width
    }), f.push(
      j(this, _o, vh).call(this, t),
      j(this, xo, _h).call(this, t),
      j(this, Co, xh).call(this, t),
      j(this, $o, Ch).call(this, t)
    ), y(this, st).forEach((p) => {
      var b;
      const m = (b = p.onRender) == null ? void 0 : b.call(this, t);
      m && (m.style && Object.assign(d, m.style), m.className && c.push(m.className), m.children && f.push(m.children));
    })), /* @__PURE__ */ g(
      "div",
      {
        id: y(this, un),
        className: M(c),
        style: d,
        ref: this.ref,
        tabIndex: -1,
        children: f
      }
    );
  }
}, ne = new WeakMap(), un = new WeakMap(), Lt = new WeakMap(), $t = new WeakMap(), se = new WeakMap(), st = new WeakMap(), kt = new WeakMap(), ft = new WeakMap(), Ce = new WeakMap(), Ns = new WeakMap(), fn = new WeakMap(), $e = new WeakMap(), Tt = new WeakMap(), ke = new WeakMap(), Te = new WeakMap(), _o = new WeakSet(), vh = function(t) {
  const { header: n, cols: i, headerHeight: o, scrollLeft: r } = t;
  if (!n)
    return null;
  if (n === !0)
    return /* @__PURE__ */ g(
      Ll,
      {
        className: "dtable-header",
        cols: i,
        height: o,
        scrollLeft: r,
        rowHeight: o,
        scrollTop: 0,
        rows: { id: "HEADER", index: -1, top: 0 },
        top: 0,
        onRenderCell: y(this, pn)
      },
      "header"
    );
  const a = Array.isArray(n) ? n : [n];
  return /* @__PURE__ */ g(
    ar,
    {
      className: "dtable-header",
      style: { height: o },
      renders: a,
      generateArgs: [t],
      generatorThis: this
    },
    "header"
  );
}, xo = new WeakSet(), _h = function(t) {
  const { headerHeight: n, rowsHeight: i, visibleRows: o, rowHeight: r, cols: a, scrollLeft: l, scrollTop: h } = t;
  return /* @__PURE__ */ g(
    Ll,
    {
      className: "dtable-body",
      top: n,
      height: i,
      rows: o,
      rowHeight: r,
      scrollLeft: l,
      scrollTop: h,
      cols: a,
      onRenderCell: y(this, pn)
    },
    "body"
  );
}, Co = new WeakSet(), xh = function(t) {
  let { footer: n } = t;
  if (typeof n == "function" && (n = n.call(this, t)), !n)
    return null;
  const i = Array.isArray(n) ? n : [n];
  return /* @__PURE__ */ g(
    ar,
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
}, $o = new WeakSet(), Ch = function(t) {
  const n = [], { scrollLeft: i, cols: { left: { width: o }, center: { width: r, totalWidth: a } }, scrollTop: l, rowsHeight: h, rowsHeightTotal: d, footerHeight: c, headerHeight: f } = t, { scrollbarSize: p = 12, horzScrollbarPos: m } = this.options;
  return a > r && n.push(
    /* @__PURE__ */ g(
      Il,
      {
        type: "horz",
        scrollPos: i,
        scrollSize: a,
        clientSize: r,
        onScroll: y(this, mn),
        left: o,
        bottom: (m === "inside" ? 0 : -p) + c,
        size: p,
        wheelContainer: this.ref
      },
      "horz"
    ),
    /* @__PURE__ */ g("div", { className: "dtable-scroll-shadow is-left", style: { left: o, height: f + h } }),
    /* @__PURE__ */ g("div", { className: "dtable-scroll-shadow is-right", style: { left: o + r, height: f + h } })
  ), d > h && n.push(
    /* @__PURE__ */ g(
      Il,
      {
        type: "vert",
        scrollPos: l,
        scrollSize: d,
        clientSize: h,
        onScroll: y(this, mn),
        right: 0,
        size: p,
        top: f,
        wheelContainer: this.ref
      },
      "vert"
    )
  ), n.length ? n : null;
}, Es = new WeakSet(), Rr = function() {
  var t;
  $(this, Lt, !1), (t = this.options.afterRender) == null || t.call(this), y(this, st).forEach((n) => {
    var i;
    return (i = n.afterRender) == null ? void 0 : i.call(this);
  });
}, pn = new WeakMap(), mn = new WeakMap(), Ms = new WeakMap(), Ps = new WeakMap(), Rs = new WeakMap(), Is = new WeakMap(), gn = new WeakSet(), Ys = function(t) {
  const { element: n, options: i } = this;
  if (!n)
    return;
  const o = u(n), r = t ? { in: !0, row: t[0], col: t[1] } : { in: !1 };
  i.colHover === "header" && r.row !== "HEADER" && (r.col = void 0);
  const a = y(this, $e);
  r.in !== a.in && o.toggleClass("dtable-hover", r.in), r.row !== a.row && (o.find(".is-hover-row").removeClass("is-hover-row"), r.row && o.find(`.dtable-cell[data-row="${r.row}"]`).addClass("is-hover-row")), r.col !== a.col && (o.find(".is-hover-col").removeClass("is-hover-col"), r.col && o.find(`.dtable-cell[data-col="${r.col}"]`).addClass("is-hover-col")), $(this, $e, r);
}, ko = new WeakSet(), $h = function() {
  if (y(this, $t))
    return !1;
  const n = { ...Dl(), ...y(this, se).reduce((i, o) => {
    const { defaultOptions: r } = o;
    return r && Object.assign(i, r), i;
  }, {}), ...this.props };
  return $(this, $t, n), $(this, st, y(this, se).reduce((i, o) => {
    const { when: r, options: a } = o;
    let l = n;
    return a && (l = Object.assign({ ...l }, typeof a == "function" ? a.call(this, n) : a)), (!r || r(l)) && (l !== n && Object.assign(n, l), i.push(o)), i;
  }, [])), $(this, fn, [this.options.i18n, ...this.plugins.map((i) => i.i18n)].filter(Boolean)), !0;
}, To = new WeakSet(), kh = function() {
  var A, O;
  const { plugins: t } = this;
  let n = y(this, $t);
  const i = {
    flex: /* @__PURE__ */ g("div", { style: "flex:auto" }),
    divider: /* @__PURE__ */ g("div", { style: "width:1px;margin:var(--space);background:var(--color-border);height:50%" })
  };
  t.forEach((E) => {
    var z;
    const T = (z = E.beforeLayout) == null ? void 0 : z.call(this, n);
    T && (n = { ...n, ...T }), Object.assign(i, E.footer);
  });
  let o = n.width, r = 0;
  if (typeof o == "function" && (o = o.call(this)), o === "100%") {
    const { parent: E } = this;
    if (E)
      r = E.clientWidth;
    else {
      $(this, Lt, !0);
      return;
    }
  }
  const a = Hf(this, n, t, r), { data: l, rowKey: h = "id", rowHeight: d } = n, c = [], f = (E, T, z) => {
    var Y, dt;
    const W = { data: z ?? { [h]: E }, id: E, index: c.length, top: 0 };
    if (z || (W.lazy = !0), c.push(W), ((Y = n.onAddRow) == null ? void 0 : Y.call(this, W, T)) !== !1) {
      for (const $n of t)
        if (((dt = $n.onAddRow) == null ? void 0 : dt.call(this, W, T)) === !1)
          return;
    }
  };
  if (typeof l == "number")
    for (let E = 0; E < l; E++)
      f(`${E}`, E);
  else
    Array.isArray(l) && l.forEach((E, T) => {
      typeof E == "object" ? f(`${E[h] ?? ""}`, T, E) : f(`${E ?? ""}`, T);
    });
  let p = c;
  const m = {};
  if (n.onAddRows) {
    const E = n.onAddRows.call(this, p);
    E && (p = E);
  }
  for (const E of t) {
    const T = (A = E.onAddRows) == null ? void 0 : A.call(this, p);
    T && (p = T);
  }
  p.forEach((E, T) => {
    m[E.id] = E, E.index = T, E.top = E.index * d;
  });
  const { header: b, footer: _ } = n, v = b ? n.headerHeight || d : 0, x = _ ? n.footerHeight || d : 0;
  let k = n.height, N = 0;
  const S = p.length * d, R = v + x + S;
  if (typeof k == "function" && (k = k.call(this, R)), k === "auto")
    N = R;
  else if (typeof k == "object")
    N = Math.min(k.max, Math.max(k.min, R));
  else if (k === "100%") {
    const { parent: E } = this;
    if (E)
      N = E.clientHeight;
    else {
      N = 0, $(this, Lt, !0);
      return;
    }
  } else
    N = k;
  const L = N - v - x, I = {
    options: n,
    allRows: c,
    width: r,
    height: N,
    rows: p,
    rowsMap: m,
    rowHeight: d,
    rowsHeight: L,
    rowsHeightTotal: S,
    header: b,
    footer: _,
    footerGenerators: i,
    headerHeight: v,
    footerHeight: x,
    cols: a
  }, D = (O = n.onLayout) == null ? void 0 : O.call(this, I);
  D && Object.assign(I, D), t.forEach((E) => {
    if (E.onLayout) {
      const T = E.onLayout.call(this, I);
      T && Object.assign(I, T);
    }
  }), $(this, kt, I);
}, So = new WeakSet(), Th = function() {
  (j(this, ko, $h).call(this) || !y(this, kt)) && j(this, To, kh).call(this);
  const { layout: t } = this;
  if (!t)
    return;
  const { cols: { center: n } } = t;
  let { scrollLeft: i } = this.state;
  i = Math.min(Math.max(0, n.totalWidth - n.width), i);
  let o = 0;
  n.list.forEach((_) => {
    _.left = o, o += _.realWidth, _.visible = _.left + _.realWidth >= i && _.left <= i + n.width;
  });
  const { rowsHeightTotal: r, rowsHeight: a, rows: l, rowHeight: h } = t, d = Math.min(Math.max(0, r - a), this.state.scrollTop), c = Math.floor(d / h), f = d + a, p = Math.min(l.length, Math.ceil(f / h)), m = [], { rowDataGetter: b } = this.options;
  for (let _ = c; _ < p; _++) {
    const v = l[_];
    v.lazy && b && (v.data = b([v.id])[0], v.lazy = !1), m.push(v);
  }
  return t.visibleRows = m, t.scrollTop = d, t.scrollLeft = i, t;
}, xn.addPlugin = yh, xn.removePlugin = bh, xn);
const Ff = {
  html: { component: Ds }
}, Wf = {
  name: "custom",
  onRenderCell(s, e) {
    const { col: t } = e;
    let { custom: n } = t.setting;
    if (typeof n == "function" && (n = n.call(this, e)), !n)
      return s;
    const i = Array.isArray(n) ? n : [n], { customMap: o } = this.options;
    return i.forEach((r) => {
      let a;
      typeof r == "string" ? a = r.startsWith("<") ? {
        component: Ds,
        props: { html: U(r, { value: e.value, ...e.row.data, $value: e.value }) }
      } : {
        component: r
      } : a = r;
      let { component: l } = a;
      const h = [a];
      typeof l == "string" && h.unshift(Ff[l], o == null ? void 0 : o[l]);
      const d = {};
      h.forEach((f) => {
        if (f) {
          const { props: p } = f;
          p && u.extend(d, typeof p == "function" ? p.call(this, e) : p), l = f.component || l;
        }
      }, { props: {} });
      const c = l;
      s[0] = { outer: !0, className: "dtable-custom-cell", children: /* @__PURE__ */ g(c, { ...d }) };
    }), s;
  }
}, Sh = rt(Wf);
function aa(s, e, t, n) {
  if (typeof s == "function" && (s = s(e)), typeof s == "string" && s.length && (s = { url: s }), !s)
    return t;
  const { url: i, ...o } = s, { setting: r } = e.col, a = {};
  return r && Object.keys(r).forEach((l) => {
    l.startsWith("data-") && (a[l] = r[l]);
  }), /* @__PURE__ */ g("a", { href: U(i, e.row.data), ...n, ...o, ...a, children: t });
}
function la(s, e, t) {
  var n;
  if (s != null)
    return t = t ?? ((n = e.row.data) == null ? void 0 : n[e.col.name]), typeof s == "function" ? s(t, e) : U(s, t);
}
function Nh(s, e, t, n) {
  var i;
  return t ? (t = t ?? ((i = e.row.data) == null ? void 0 : i[e.col.name]), s === !1 ? t : (s === !0 && (s = "[yyyy-]MM-dd hh:mm"), typeof s == "function" && (s = s(t, e)), mt(t, s, n ?? t))) : n ?? t;
}
function Eh(s, e) {
  const { link: t } = e.col.setting, n = aa(t, e, s[0]);
  return n && (s[0] = n), s;
}
function Mh(s, e) {
  const { format: t } = e.col.setting;
  return t && (s[0] = la(t, e, s[0])), s;
}
function Ph(s, e) {
  const { map: t } = e.col.setting;
  return typeof t == "function" ? s[0] = t(s[0], e) : typeof t == "object" && t && (s[0] = t[s[0]] ?? s[0]), s;
}
function Rh(s, e, t = "[yyyy-]MM-dd hh:mm") {
  const { formatDate: n = t, invalidDate: i } = e.col.setting;
  return s[0] = Nh(n, e, s[0], i), s;
}
function Ir(s, e, t = !1) {
  const { html: n = t } = e.col.setting;
  if (n === !1)
    return s;
  const i = s[0], o = n === !0 ? i : la(n, e, i);
  return s[0] = {
    html: o
  }, s;
}
const Vf = {
  name: "rich",
  colTypes: {
    html: {
      onRenderCell(s, e) {
        return Ir(s, e, !0);
      }
    },
    progress: {
      align: "center",
      onRenderCell(s, { col: e }) {
        const { circleSize: t = 24, circleBorderSize: n = 1, circleBgColor: i = "var(--color-border)", circleColor: o = "var(--color-success-500)" } = e.setting, r = (t - n) / 2, a = t / 2, l = s[0];
        return s[0] = /* @__PURE__ */ g("svg", { width: t, height: t, children: [
          /* @__PURE__ */ g("circle", { cx: a, cy: a, r, "stroke-width": n, stroke: i, fill: "transparent" }),
          /* @__PURE__ */ g("circle", { cx: a, cy: a, r, "stroke-width": n, stroke: o, fill: "transparent", "stroke-linecap": "round", "stroke-dasharray": Math.PI * r * 2, "stroke-dashoffset": Math.PI * r * 2 * (100 - l) / 100, style: { transformOrigin: "center", transform: "rotate(-90deg)" } }),
          /* @__PURE__ */ g("text", { x: a, y: a + n, "dominant-baseline": "middle", "text-anchor": "middle", style: { fontSize: `${r}px` }, children: Math.round(l) })
        ] }), s;
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
  onRenderCell(s, e) {
    const { formatDate: t, html: n, hint: i } = e.col.setting;
    if (t && (s = Rh(s, e, t)), s = Ph(s, e), s = Mh(s, e), n ? s = Ir(s, e) : s = Eh(s, e), i) {
      let o = s[0];
      typeof i == "function" ? o = i.call(this, e) : typeof i == "string" && (o = U(i, e.row.data)), s.push({ attrs: { title: o } });
    }
    return s;
  }
}, Uf = rt(Vf, { buildIn: !0 });
function qf(s, e, t = !1) {
  var a, l;
  typeof s == "boolean" && (e = s, s = void 0);
  const n = this.state.checkedRows, i = {}, { canRowCheckable: o } = this.options, r = (h, d) => {
    const c = o ? o.call(this, h) : !0;
    !c || t && c === "disabled" || !!n[h] === d || (d ? n[h] = !0 : delete n[h], i[h] = d);
  };
  if (s === void 0 ? (e === void 0 && (e = !Ih.call(this)), (a = this.layout) == null || a.allRows.forEach(({ id: h }) => {
    r(h, !!e);
  })) : (Array.isArray(s) || (s = [s]), s.forEach((h) => {
    r(h, e ?? !n[h]);
  })), Object.keys(i).length) {
    const h = (l = this.options.beforeCheckRows) == null ? void 0 : l.call(this, s, i, n);
    h && Object.keys(h).forEach((d) => {
      h[d] ? n[d] = !0 : delete n[d];
    }), this.setState({ checkedRows: { ...n } }, () => {
      var d;
      (d = this.options.onCheckChange) == null || d.call(this, i);
    });
  }
  return i;
}
function Gf(s) {
  return this.state.checkedRows[s] ?? !1;
}
function Ih() {
  var n, i;
  const s = (n = this.layout) == null ? void 0 : n.allRows.length;
  if (!s)
    return !1;
  const e = this.getChecks().length, { canRowCheckable: t } = this.options;
  return t ? e === ((i = this.layout) == null ? void 0 : i.allRows.reduce((o, r) => o + (t.call(this, r.id) ? 1 : 0), 0)) : e === s;
}
function Kf() {
  return Object.keys(this.state.checkedRows);
}
function Yf(s) {
  const { checkable: e } = this.options;
  s === void 0 && (s = !e), e !== s && this.setState({ forceCheckable: s });
}
function Ol(s, e, t = !1) {
  return /* @__PURE__ */ g("div", { class: `checkbox-primary dtable-checkbox${s ? " checked" : ""}${t ? " disabled" : ""}`, children: /* @__PURE__ */ g("label", {}) });
}
const jl = 'input[type="checkbox"],.dtable-checkbox', Xf = {
  name: "checkable",
  defaultOptions: {
    checkable: "auto",
    checkboxRender: Ol
  },
  when: (s) => !!s.checkable,
  options(s) {
    const { forceCheckable: e } = this.state;
    return e !== void 0 ? s.checkable = e : s.checkable === "auto" && (s.checkable = !!s.cols.some((t) => t.checkbox)), s;
  },
  state() {
    return { checkedRows: {} };
  },
  methods: {
    toggleCheckRows: qf,
    isRowChecked: Gf,
    isAllRowChecked: Ih,
    getChecks: Kf,
    toggleCheckable: Yf
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
      const s = this.isAllRowChecked();
      return [
        /* @__PURE__ */ g("div", { style: { paddingRight: "calc(3*var(--space))", display: "flex", alignItems: "center" }, onClick: () => this.toggleCheckRows(), children: Ol(s) })
      ];
    },
    checkedInfo(s, e) {
      const t = this.getChecks(), { checkInfo: n } = this.options;
      if (n)
        return [n.call(this, t)];
      const i = t.length, o = [];
      return i && o.push(this.i18n("checkedCountInfo", { selected: i })), o.push(this.i18n("totalCountInfo", { total: e.allRows.length })), [
        /* @__PURE__ */ g("div", { children: o.join(", ") })
      ];
    }
  },
  onRenderCell(s, { row: e, col: t }) {
    var h;
    const { id: n } = e, { canRowCheckable: i } = this.options, o = i ? i.call(this, n) : !0;
    if (!o)
      return s;
    const { checkbox: r } = t.setting, a = typeof r == "function" ? r.call(this, n) : r, l = this.isRowChecked(n);
    if (a) {
      const d = (h = this.options.checkboxRender) == null ? void 0 : h.call(this, l, n, o === "disabled");
      s.unshift(d), s.push({ outer: !0, className: "has-checkbox" });
    }
    return l && s.push({ outer: !0, className: "is-checked" }), s;
  },
  onRenderHeaderCell(s, { row: e, col: t }) {
    var r;
    const { id: n } = e, { checkbox: i } = t.setting;
    if (typeof i == "function" ? i.call(this, n) : i) {
      const a = this.isAllRowChecked(), l = (r = this.options.checkboxRender) == null ? void 0 : r.call(this, a, n);
      s.unshift(l), s.push({ outer: !0, className: "has-checkbox" });
    }
    return s;
  },
  onHeaderCellClick(s) {
    const e = s.target;
    if (!e)
      return;
    const t = e.closest(jl);
    t && (this.toggleCheckRows(t.checked), s.stopPropagation());
  },
  onCellClick(s, { rowID: e }) {
    const t = u(s.target);
    if (!t.length || t.closest("btn,a,button.not-checkable,.form-control,.btn").length)
      return;
    (t.closest(jl).not(".disabled").length || this.options.checkOnClickRow) && this.toggleCheckRows(e, void 0, !0);
  }
}, Jf = rt(Xf);
var Dh = /* @__PURE__ */ ((s) => (s.unknown = "", s.collapsed = "collapsed", s.expanded = "expanded", s.hidden = "hidden", s.normal = "normal", s))(Dh || {});
function yi(s) {
  const e = this.data.nestedMap.get(s);
  if (!e || e.state !== "")
    return e ?? { state: "normal", level: -1 };
  if (!e.parent && !e.children)
    return e.state = "normal", e;
  const t = this.state.collapsedRows, n = e.children && t && t[s];
  let i = !1, { parent: o } = e;
  for (; o; ) {
    const r = yi.call(this, o);
    if (r.state !== "expanded") {
      i = !0;
      break;
    }
    o = r.parent;
  }
  return e.state = i ? "hidden" : n ? "collapsed" : e.children ? "expanded" : "normal", e.level = e.parent ? yi.call(this, e.parent).level + 1 : 0, e;
}
function Zf(s) {
  return s !== void 0 ? yi.call(this, s) : this.data.nestedMap;
}
function Qf(s, e) {
  let t = this.state.collapsedRows ?? {};
  const { nestedMap: n } = this.data;
  if (s === "HEADER")
    if (e === void 0 && (e = !Ah.call(this)), e) {
      const i = n.entries();
      for (const [o, r] of i)
        r.state === "expanded" && (t[o] = !0);
    } else
      t = {};
  else {
    const i = Array.isArray(s) ? s : [s];
    e === void 0 && (e = !t[i[0]]), i.forEach((o) => {
      const r = n.get(o);
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
function Ah() {
  const s = this.data.nestedMap.values();
  for (const e of s)
    if (e.state === "expanded")
      return !1;
  return !0;
}
function Lh(s, e = 0, t, n = 0) {
  var i;
  t || (t = [...s.keys()]);
  for (const o of t) {
    const r = s.get(o);
    r && (r.level === n && (r.order = e++), (i = r.children) != null && i.length && (e = Lh(s, e, r.children, n + 1)));
  }
  return e;
}
function Oh(s, e, t, n) {
  const i = s.getNestedRowInfo(e);
  return !i || i.state === "" || !i.children || i.children.forEach((o) => {
    n[o] = t, Oh(s, o, t, n);
  }), i;
}
function jh(s, e, t, n, i) {
  var a;
  const o = s.getNestedRowInfo(e);
  if (!o || o.state === "")
    return;
  ((a = o.children) == null ? void 0 : a.every((l) => {
    const h = !!(n[l] !== void 0 ? n[l] : i[l]);
    return t === h;
  })) && (n[e] = t), o.parent && jh(s, o.parent, t, n, i);
}
const tp = {
  name: "nested",
  defaultOptions: {
    nested: "auto",
    nestedParentKey: "parent",
    asParentKey: "asParent",
    nestedIndent: 20,
    canSortTo(s, e) {
      const { nestedMap: t } = this.data, n = t.get(s.id), i = t.get(e.id);
      return (n == null ? void 0 : n.parent) === (i == null ? void 0 : i.parent);
    },
    beforeCheckRows(s, e, t) {
      if (!this.options.checkable || !(s != null && s.length))
        return;
      const n = {};
      return Object.entries(e).forEach(([i, o]) => {
        const r = Oh(this, i, o, n);
        r != null && r.parent && jh(this, r.parent, o, n, t);
      }), n;
    }
  },
  options(s) {
    return s.nested === "auto" && (s.nested = !!s.cols.some((e) => e.nestedToggle)), s;
  },
  when: (s) => !!s.nested,
  data() {
    return { nestedMap: /* @__PURE__ */ new Map() };
  },
  methods: {
    getNestedInfo: Zf,
    toggleRow: Qf,
    isAllCollapsed: Ah,
    getNestedRowInfo: yi
  },
  beforeLayout() {
    var s;
    (s = this.data.nestedMap) == null || s.clear();
  },
  onAddRow(s) {
    var i, o;
    const { nestedMap: e } = this.data, t = String((i = s.data) == null ? void 0 : i[this.options.nestedParentKey ?? "parent"]), n = e.get(s.id) ?? {
      state: "",
      level: 0
    };
    if (n.parent = t === "0" ? void 0 : t, (o = s.data) != null && o[this.options.asParentKey ?? "asParent"] && (n.children = []), e.set(s.id, n), t) {
      let r = e.get(t);
      r || (r = {
        state: "",
        level: 0
      }, e.set(t, r)), r.children || (r.children = []), r.children.push(s.id);
    }
  },
  onAddRows(s) {
    return s = s.filter(
      (e) => this.getNestedRowInfo(e.id).state !== "hidden"
      /* hidden */
    ), Lh(this.data.nestedMap), s.sort((e, t) => {
      const n = this.getNestedRowInfo(e.id), i = this.getNestedRowInfo(t.id), o = (n.order ?? 0) - (i.order ?? 0);
      return o === 0 ? e.index - t.index : o;
    }), s;
  },
  onRenderCell(s, { col: e, row: t }) {
    var a;
    const { id: n, data: i } = t, { nestedToggle: o } = e.setting, r = this.getNestedRowInfo(n);
    if (o && (r.children || r.parent) && s.unshift(
      ((a = this.options.onRenderNestedToggle) == null ? void 0 : a.call(this, r, n, e, i)) ?? /* @__PURE__ */ g("a", { className: `dtable-nested-toggle state${r.children ? "" : " is-no-child"}`, children: /* @__PURE__ */ g("span", { className: "toggle-icon" }) }),
      { outer: !0, className: `is-${r.state}` }
    ), r.level) {
      let { nestedIndent: l = o } = e.setting;
      l && (l === !0 && (l = this.options.nestedIndent ?? 12), s.unshift(/* @__PURE__ */ g("div", { className: "dtable-nested-indent", style: { width: l * r.level + "px" } })));
    }
    return s;
  },
  onRenderHeaderCell(s, { row: e, col: t }) {
    var i;
    const { id: n } = e;
    return t.setting.nestedToggle && s.unshift(
      ((i = this.options.onRenderNestedToggle) == null ? void 0 : i.call(this, void 0, n, t, void 0)) ?? /* @__PURE__ */ g("a", { className: "dtable-nested-toggle state", children: /* @__PURE__ */ g("span", { className: "toggle-icon" }) }),
      { outer: !0, className: `is-${this.isAllCollapsed() ? "collapsed" : "expanded"}` }
    ), s;
  },
  onHeaderCellClick(s) {
    const e = s.target;
    if (!(!e || !e.closest(".dtable-nested-toggle")))
      return this.toggleRow("HEADER"), !0;
  },
  onCellClick(s, { rowID: e }) {
    const t = s.target;
    if (!(!t || !this.getNestedRowInfo(e).children || !t.closest(".dtable-nested-toggle")))
      return this.toggleRow(e), !0;
  }
}, ep = rt(tp);
function nr(s, { row: e, col: t }) {
  const { data: n } = e, i = n ? n[t.name] : void 0;
  if (!(i != null && i.length))
    return s;
  const { avatarClass: o = "rounded-full", avatarKey: r = `${t.name}Avatar`, avatarProps: a, avatarCodeKey: l, avatarNameKey: h = `${t.name}Name` } = t.setting, d = (n ? n[h] : i) || s[0], c = {
    size: "xs",
    className: M(o, a == null ? void 0 : a.className, "flex-none"),
    src: n ? n[r] : void 0,
    text: d,
    code: l ? n ? n[l] : void 0 : i,
    ...a
  };
  if (s[0] = /* @__PURE__ */ g(Uc, { ...c }), t.type === "avatarBtn") {
    const { avatarBtnProps: f } = t.setting, p = typeof f == "function" ? f(t, e) : f;
    s[0] = /* @__PURE__ */ g("button", { type: "button", className: "btn btn-avatar", ...p, children: [
      s[0],
      /* @__PURE__ */ g("div", { children: d })
    ] });
  } else
    t.type === "avatarName" && (s[0] = /* @__PURE__ */ g("div", { className: "flex items-center gap-1", children: [
      s[0],
      /* @__PURE__ */ g("span", { children: d })
    ] }));
  return s;
}
const np = {
  name: "avatar",
  colTypes: {
    avatar: {
      onRenderCell: nr
    },
    avatarBtn: {
      onRenderCell: nr
    },
    avatarName: {
      onRenderCell: nr
    }
  }
}, sp = rt(np, { buildIn: !0 }), ip = {
  name: "sort-type",
  onRenderHeaderCell(s, e) {
    const { col: t } = e, { sortType: n } = t.setting;
    if (n) {
      const i = n === !0 ? "none" : n;
      s.push(
        /* @__PURE__ */ g("div", { className: `dtable-sort dtable-sort-${i}` }),
        { outer: !0, attrs: { "data-sort": i } }
      );
      let { sortLink: o = this.options.sortLink } = t.setting;
      if (o) {
        const r = i === "asc" ? "desc" : "asc";
        typeof o == "function" && (o = o.call(this, t, r, i)), typeof o == "string" && (o = { url: o });
        const { url: a, ...l } = o;
        s[0] = /* @__PURE__ */ g("a", { href: U(a, { ...t.setting, sortType: r }), ...l, children: s[0] });
      }
    }
    return s;
  }
}, op = rt(ip, { buildIn: !0 }), sr = (s) => {
  s.length !== 1 && s.forEach((e, t) => {
    !t || e.setting.border || e.setting.group === s[t - 1].setting.group || (e.setting.border = "left");
  });
}, rp = {
  name: "group",
  defaultOptions: {
    groupDivider: !0
  },
  when: (s) => !!s.groupDivider,
  onLayout(s) {
    if (!this.options.groupDivider)
      return;
    const { cols: e } = s;
    sr(e.left.list), sr(e.center.list), sr(e.right.list);
  }
}, ap = rt(rp), lp = {
  name: "cellspan",
  when: (s) => !!s.getCellSpan,
  data() {
    return { cellSpanMap: /* @__PURE__ */ new Map(), overlayedCellSet: /* @__PURE__ */ new Set() };
  },
  onLayout(s) {
    const { getCellSpan: e } = this.options;
    if (!e)
      return;
    const { cellSpanMap: t, overlayedCellSet: n } = this.data, { rows: i, cols: o, rowHeight: r } = s;
    t.clear(), n.clear();
    const a = (l, h, d) => {
      const { index: c } = h;
      l.forEach((f, p) => {
        const { index: m } = f, b = `C${m}R${c}`;
        if (n.has(b))
          return;
        const _ = e.call(this, { row: h, col: f });
        if (!_)
          return;
        const v = Math.min(_.colSpan || 1, l.length - p), x = Math.min(_.rowSpan || 1, i.length - d);
        if (v <= 1 && x <= 1)
          return;
        let k = 0;
        for (let N = 0; N < v; N++) {
          k += l[p + N].realWidth;
          for (let S = 0; S < x; S++) {
            const R = `C${m + N}R${c + S}`;
            R !== b && n.add(R);
          }
        }
        t.set(b, {
          colSpan: v,
          rowSpan: x,
          width: k,
          height: r * x
        });
      });
    };
    i.forEach((l, h) => {
      ["left", "center", "right"].forEach((d) => {
        a(o[d].list, l, h);
      });
    });
  },
  onRenderCell(s, { row: e, col: t }) {
    const n = `C${t.index}R${e.index}`;
    if (this.data.overlayedCellSet.has(n))
      s.push({ outer: !0, style: { display: "none", className: "cellspan-overlayed-cell" } });
    else {
      const i = this.data.cellSpanMap.get(n);
      i && s.push({
        outer: !0,
        style: {
          width: i.width,
          height: i.height
        }
      });
    }
    return s;
  }
}, cp = rt(lp), hp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  NestedRowState: Dh,
  avatar: sp,
  cellspan: cp,
  checkable: Jf,
  custom: Sh,
  group: ap,
  nested: ep,
  renderDatetime: Nh,
  renderDatetimeCell: Rh,
  renderFormat: la,
  renderFormatCell: Mh,
  renderHtmlCell: Ir,
  renderLink: aa,
  renderLinkCell: Eh,
  renderMapCell: Ph,
  rich: Uf,
  sortType: op
}, Symbol.toStringTag, { value: "Module" })), fe = class fe extends V {
};
fe.NAME = "DTable", fe.Component = Bf, fe.definePlugin = rt, fe.removePlugin = bh, fe.plugins = hp;
let Hl = fe;
const dp = "nav", Xs = '[data-toggle="tab"]', up = "active";
var Se;
const ya = class ya extends ot {
  constructor() {
    super(...arguments);
    C(this, Se, 0);
  }
  active(t) {
    const n = this.$element, i = n.find(Xs);
    let o = t ? u(t).closest(Xs) : i.filter(`.${up}`);
    if (!o.length && (o = n.find(Xs).first(), !o.length))
      return;
    i.removeClass("active"), o.addClass("active");
    const r = o.attr("href") || o.data("target"), a = o.data("name") || r, l = u(r);
    l.length && (l.parent().children(".tab-pane").removeClass("active in"), l.addClass("active").trigger("show", [a]), this.emit("show", a), y(this, Se) && clearTimeout(y(this, Se)), $(this, Se, setTimeout(() => {
      l.addClass("in").trigger("show", [a]), this.emit("shown", a), $(this, Se, 0);
    }, 10)));
  }
};
Se = new WeakMap(), ya.NAME = "Tabs";
let Dr = ya;
u(document).on("click.tabs.zui", Xs, (s) => {
  s.preventDefault();
  const e = u(s.target), t = e.closest(`.${dp}`);
  t.length && Dr.ensure(t).active(e);
});
const zl = (s) => s.replace("[", "\\[").replace("]", "\\]");
var No, Hh, Eo, zh, Mo, Bh, Po, Fh;
const ba = class ba extends ot {
  constructor() {
    super(...arguments);
    C(this, No);
    C(this, Eo);
    C(this, Mo);
    C(this, Po);
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
    const n = u(t.target).closest(".has-error");
    !n.length || !n.attr("name") || (n.removeClass("has-error"), n.closest(".form-group,.form-batch-control").find(`#${zl(n.attr("name"))}Tip`).remove());
  }
  onSubmit(t) {
    var a;
    t.preventDefault();
    const { element: n } = this, i = u.extend({}, this.options);
    this.emit("before", t, n, i);
    const o = () => {
      this.disable(), j(this, Eo, zh).call(this, j(this, No, Hh).call(this)).finally(() => {
        this.enable();
      });
    }, r = (a = i.beforeSubmit) == null ? void 0 : a.call(i, t, n, i);
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
No = new WeakSet(), Hh = function() {
  const { element: t, options: n } = this;
  let i = new FormData(t), { submitEmptySelectValue: o = "" } = n;
  o !== !1 && (typeof o != "boolean" && (o = ""), u(t).find("select").each((a, l) => {
    const d = u(l).attr("name");
    i.has(d) || i.append(d, typeof o == "object" ? o[d] : o);
  }));
  const { beforeSend: r } = n;
  if (r) {
    const a = r(i);
    a instanceof FormData && (i = a);
  }
  return this.emit("send", i), i;
}, Eo = new WeakSet(), zh = async function(t) {
  var h, d;
  const { element: n, options: i } = this;
  let o, r, a;
  const l = {
    method: u(n).attr("method") || "POST",
    body: t,
    credentials: "same-origin",
    headers: {
      "X-Requested-With": "XMLHttpRequest"
    }
  };
  try {
    const c = await fetch(i.url || u(n).attr("action"), l);
    r = await c.text(), c.ok ? (a = JSON.parse(r), (!a || typeof a != "object") && (o = new Error("Invalid json format"))) : o = new Error(c.statusText);
  } catch (c) {
    o = c, console.warn("ZUI: cannot send ajax form", c);
  }
  o ? (this.emit("error", o, r), (h = i.onError) == null || h.call(i, o, r)) : j(this, Po, Fh).call(this, a), this.emit("complete", a, o), (d = i.onComplete) == null || d.call(i, a, o);
}, Mo = new WeakSet(), Bh = function(t) {
  var i;
  let n;
  Object.entries(t).forEach(([o, r]) => {
    Array.isArray(r) && (r = r.join(""));
    const a = zl(o);
    let l = this.$element.find(`#${a}`);
    if (l.length || (l = u(this.element).find(`[name="${a}"]`)), !l.length) {
      Bt.alert({ message: r });
      return;
    }
    l.addClass("has-error");
    const h = l.closest(".form-group,.form-batch-control");
    if (h.length) {
      let d = h.find(`#${a}Tip`);
      d.length || (d = u(`<div class="form-tip ajax-form-tip text-danger" id="${o}Tip"></div>`).appendTo(h)), d.empty().text(r);
    }
    n || (n = l);
  }), n && ((i = n[0]) == null || i.focus());
}, Po = new WeakSet(), Fh = function(t) {
  var l, h;
  const { options: n } = this, { message: i } = t;
  if (t.result === "success") {
    if (this.emit("success", t), ((l = n.onSuccess) == null ? void 0 : l.call(n, t)) === !1)
      return;
    typeof i == "string" && i.length && hi.show({ content: i, type: "success" });
  } else {
    if (this.emit("fail", t), ((h = n.onFail) == null ? void 0 : h.call(n, t)) === !1)
      return;
    i && (typeof i == "string" && i.length ? Bt.alert({ message: i }) : typeof i == "object" && j(this, Mo, Bh).call(this, i));
  }
  const o = t.closeModal || n.closeModal;
  o && Bt.hide(typeof o == "string" ? o : void 0);
  const r = t.callback || n.callback;
  if (r) {
    const d = [["options", n], ["result", t]];
    if (typeof r == "string") {
      const c = u.runJS(r, ...d);
      typeof c == "function" && !r.endsWith(";") && c(t);
    } else if (typeof r == "object") {
      const c = u.runJS(r.name, ...d);
      typeof c == "function" && c.apply(this, Array.isArray(r.params) ? r.params : [r.params]);
    }
  }
  const a = t.load || n.load || t.locate;
  a && u(this.element).trigger("locate.zt", a);
}, ba.NAME = "AjaxForm";
let Bl = ba;
function fp(s, e) {
  var l, h;
  const { message: t } = s, n = typeof t == "string" && t.length, i = s.result === "success";
  if (i) {
    if (((l = e.onSuccess) == null ? void 0 : l.call(e, s)) === !1)
      return;
  } else if (((h = e.onFail) == null ? void 0 : h.call(e, s)) === !1)
    return;
  n && (e.onMessage ? e.onMessage(t, s) : i ? hi.show({ content: t, type: "success" }) : Bt.alert({ message: t }));
  const o = s.closeModal || e.closeModal;
  o && Bt.hide(typeof o == "string" ? o : void 0);
  const r = s.callback || e.callback;
  if (r) {
    const d = [["options", e], ["result", s]];
    if (typeof r == "string") {
      const c = u.runJS(r, ...d);
      typeof c == "function" && !r.endsWith(";") && c(s);
    } else if (typeof r == "object") {
      const c = u.runJS(r.name, ...d);
      typeof c == "function" && c.apply(null, Array.isArray(r.params) ? r.params : [r.params]);
    }
  }
  const a = s.load || e.load || s.locate;
  a && u(e.element || document).trigger("locate.zt", a);
}
async function ca(s) {
  var h, d;
  if (s.confirm)
    return await Bt.confirm(s.confirm) ? ca({ ...s, confirm: void 0 }) : [void 0, new Error("canceled")];
  if (s.beforeSubmit && await s.beforeSubmit(s) === !1)
    return [void 0, new Error("canceled")];
  const { loadingClass: e, element: t } = s;
  t && e && u(t).addClass(e);
  const { data: n } = s;
  let i;
  if (n instanceof FormData)
    i = n;
  else if (n) {
    i = new FormData();
    for (const [c, f] of Object.entries(n))
      if (Array.isArray(f)) {
        for (const p of f)
          i.append(c, p);
        continue;
      } else
        i.append(c, f);
  }
  const { beforeSend: o } = s;
  if (o) {
    const c = o(i);
    c instanceof FormData && (i = c);
  }
  let r, a, l;
  try {
    const c = await fetch(s.url, {
      method: s.method || "POST",
      body: i,
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        ...s.headers
      }
    });
    a = await c.text(), c.ok ? (l = JSON.parse(a), (!l || typeof l != "object") && (r = new Error("Invalid json format"))) : r = new Error(c.statusText);
  } catch (c) {
    r = c;
  }
  return r ? (h = s.onError) == null || h.call(s, r, a) : fp(l, s), (d = s.onComplete) == null || d.call(s, l, r), t && e && u(t).removeClass(e), [l, r];
}
u.extend(u, { ajaxSubmit: ca });
u(document).on("click.ajaxSubmit.zui", ".ajax-submit", function(s) {
  s.preventDefault();
  const e = u(this), t = e.data();
  !t.url && e.is("a") && (t.url = e.attr("href") || ""), t.url && (t.element = this[0], ca(t));
});
function pp(s) {
  const [e, t] = s.split(":"), n = e[0] === "-" ? { name: e.substring(1), disabled: !0 } : { name: e };
  return t != null && t.length && (n.type = "dropdown", n.items = t.split(",").reduce((i, o) => (o = o.trim(), o.length && i.push(o[0] === "-" ? { name: o.substring(1), disabled: !0 } : { name: o }), i), [])), n;
}
const mp = (s, e) => {
  var t;
  return s.url && (s.url = U(s.url, e.row.data)), (t = s.dropdown) != null && t.items && (s.dropdown.items = s.dropdown.items.map((n) => (n.url && (n.url = U(n.url, e.row.data)), n))), s;
}, Fl = (s) => s ? (typeof s == "string" && (s = s.split("|")), s.map((e) => typeof e == "string" ? pp(e) : e).filter(Boolean)) : [], gp = {
  name: "actions",
  colTypes: {
    actions: {
      onRenderCell(s, e) {
        var d;
        const { row: t, col: n } = e, i = Fl(((d = t.data) == null ? void 0 : d[n.name]) || n.setting.actions);
        if (!i.length)
          return s;
        const { actionsSetting: o, actionsMap: r, actionsCreator: a = this.options.actionsCreator, actionItemCreator: l = this.options.actionItemCreator || mp } = n.setting, h = {
          items: (a == null ? void 0 : a(e)) ?? i.map((c) => {
            const { name: f, items: p, ...m } = c;
            if (r && f) {
              Object.assign(m, r[f], { ...m });
              const { buildProps: b } = m;
              typeof b == "function" && (delete m.buildProps, Object.assign(m, b(s, e)));
            }
            if (m.disabled && (delete m.url, delete m["data-toggle"]), p && m.type === "dropdown") {
              const { dropdown: b = { placement: "bottom-end" } } = m;
              b.menu = {
                className: "menu-dtable-actions",
                items: p.reduce((_, v) => {
                  const x = typeof v == "string" ? { name: v } : { ...v };
                  return x != null && x.name && (r && "name" in x && Object.assign(x, r[x.name], { ...x }), _.push(x)), x.disabled ? (delete x.url, delete x["data-toggle"]) : x.url && (x.url = U(x.url, t.data)), _;
                }, [])
              }, m.dropdown = b;
            }
            return l ? l(m, e) : m;
          }),
          btnProps: { size: "sm", className: "text-primary" },
          ...o
        };
        return s[0] = /* @__PURE__ */ g(Mt, { ...h }), s;
      }
    }
  },
  beforeLayout(s) {
    !Array.isArray(s.data) || !s.data.length || s.cols.forEach((e, t) => {
      if (e.type !== "actions" || e.width)
        return;
      const { actionsMap: n = {} } = e, o = Fl(s.data[0][e.name]).reduce((r, a) => {
        const l = a.name ? n[a.name] : null;
        return l && l.type === "dropdown" && l.caret && !l.text ? r + 16 : r + 24;
      }, 24);
      s.cols[t] = {
        ...e,
        width: o
      };
    });
  }
}, yp = rt(gp), bp = {
  name: "toolbar",
  footer: {
    toolbar() {
      const { footToolbar: s, showToolbarOnChecked: e } = this.options;
      return e && !this.getChecks().length ? [] : [s ? /* @__PURE__ */ g(Mt, { gap: 2, ...s }) : null];
    }
  }
}, wp = rt(bp), vp = {
  name: "pager",
  footer: {
    pager() {
      const { footPager: s } = this.options;
      return [s ? /* @__PURE__ */ g(Jc, { ...s }) : null];
    }
  }
}, _p = rt(vp);
function Wl(s, e, t) {
  if (t) {
    if (typeof t == "object") {
      const n = t[s];
      return typeof n == "string" ? n : typeof n == "object" && n ? n.realname : "";
    }
    if (typeof t == "function")
      return t(s, e);
  }
}
const xp = {
  name: "zentao",
  plugins: ["group", "checkable", "nested", yp, wp, _p],
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
    height(s) {
      return Math.min(s, window.innerHeight - 1 - (u("#header").outerHeight() || 0) - (u("#mainMenu").outerHeight() || 0) - (u("#mainNavbar").outerHeight() || 0));
    }
  },
  options(s) {
    const { checkable: e, footToolbar: t, footPager: n, footer: i, sortLink: o } = s;
    if (i === void 0) {
      const r = [];
      e && r.push("checkbox"), t && (r.push("toolbar"), t.btnProps = Object.assign({
        type: "primary",
        size: "sm"
      }, t.btnProps)), e && r.push("checkedInfo"), n && r.push("flex", "pager"), r.length && (s.footer = r);
    }
    return typeof o == "string" && (s.sortLink = { url: o, "data-load": "table" }), s;
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
      onRenderCell(s, { col: e, row: t }) {
        var r, a;
        const n = (r = t.data) == null ? void 0 : r[e.name];
        let i, o;
        return typeof n == "string" ? (i = n, o = (a = e.setting.statusMap) == null ? void 0 : a[n]) : typeof n == "object" && n && ({ name: i, label: o } = n), s[0] = /* @__PURE__ */ w("span", { class: `${e.setting.statusClassPrefix ?? "status-"}${i}` }, o ?? i), s;
      }
    },
    user: {
      width: 80,
      // 默认宽度
      align: "center",
      // 居中对齐
      sortType: !0,
      // 启用排序
      onRenderCell(s, { col: e, row: t, value: n }) {
        const { userMap: i = this.options.userMap } = e.setting, o = Wl(n, t, i);
        return o !== void 0 && (s[0] = o), s;
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
      onRenderCell(s, e) {
        const { col: t, row: n, value: i } = e, { userMap: o = this.options.userMap, currentUser: r, assignLink: a, unassignedText: l = this.i18n("unassigned") } = t.setting, h = !i, d = h ? l : Wl(i, n, o) ?? i;
        return s[0] = aa(a, e, [
          /* @__PURE__ */ w("i", { className: "icon icon-hand-right" }),
          /* @__PURE__ */ w("span", null, d)
        ], {
          "data-toggle": "modal",
          className: `dtable-assign-btn${r === i ? " is-me" : ""}${h ? " is-unassigned" : ""}`
        }), s;
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
      onRenderCell(s, { row: e, col: t, value: n }) {
        const { iconRender: i } = t.setting;
        let o = {};
        if (typeof i == "function") {
          const r = i(n, e);
          typeof r == "string" ? n = r : typeof r == "object" && r && ({ icon: n, ...o } = r);
        }
        return typeof n == "string" ? o.className = M(n, o.className) : typeof n == "object" && n && Object.assign(o, n), s[0] = /* @__PURE__ */ w("i", { ...o }), s;
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
      onRenderCell(s, { value: e }) {
        return s[0] = /* @__PURE__ */ w(hh, { pri: e }), s;
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
      onRenderCell(s) {
        const e = s[0];
        return s[0] = /* @__PURE__ */ w(dh, { severity: e }), s;
      }
    },
    burn: {
      width: 88,
      // 默认宽度
      align: "center",
      // 居中对齐
      onRenderCell(s, { col: e }) {
        const t = s[0];
        if (!t)
          return s;
        const { burn: n } = e.setting, i = {
          data: t,
          className: "border-b",
          width: 64,
          height: 24,
          responsive: !1,
          ...n
        };
        return s[0] = /* @__PURE__ */ w(lh, { ...i }), s;
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
      format(s) {
        return typeof s == "string" && s.endsWith("%") ? s : `${s}%`;
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
  onRenderCell(s, { row: e, col: t, value: n }) {
    const { iconRender: i } = t.setting;
    if (typeof i == "function" && t.type !== "icon") {
      const o = i(n, e);
      o && s.unshift(typeof o == "object" ? /* @__PURE__ */ w("i", { ...o }) : /* @__PURE__ */ w("i", { className: o }));
    }
    return s;
  },
  onRender() {
    const { customCols: s } = this.options;
    if (!s)
      return;
    const { custom: e, setGlobal: t, reset: n, resetGlobal: i } = s;
    return {
      children: /* @__PURE__ */ w("div", { className: "absolute gap-3 m-1.5 top-0 right-0 z-20 row" }, /* @__PURE__ */ w("div", { className: "w-px border-l my-1" }), /* @__PURE__ */ w(
        "a",
        {
          className: "btn ghost square size-sm rounded",
          "data-toggle": "dropdown",
          "data-placement": "bottom-end"
        },
        /* @__PURE__ */ w("i", { class: "icon icon-cog-outline" })
      ), /* @__PURE__ */ w("menu", { class: "dropdown-menu menu" }, e && /* @__PURE__ */ w("li", { class: "menu-item" }, /* @__PURE__ */ w("a", { href: e.url, "data-toggle": "modal" }, e.text)), t && /* @__PURE__ */ w("li", { class: "menu-item" }, /* @__PURE__ */ w("a", { href: t.url }, t.text)), n && /* @__PURE__ */ w("li", { class: "menu-item" }, /* @__PURE__ */ w("a", { href: n.url }, n.text)), i && /* @__PURE__ */ w("li", { class: "menu-item" }, /* @__PURE__ */ w("a", { href: i.url }, i.text))))
    };
  }
}, qp = rt(xp, { buildIn: !0 });
const En = (s, e, t, n = "value") => {
  const i = `${e.col.name}[${e.row.id}]`, o = s.getFormData(i) ?? e.value;
  return {
    [n]: o,
    className: "form-control",
    onChange: (r) => s.setFormData(i, r instanceof Event ? r.currentTarget.value : r),
    ...t
  };
};
function ir(s) {
  return En(this, s, null, "defaultValue");
}
const Cp = {
  input: {
    component: "input",
    props(s) {
      return En(this, s, { type: "text" });
    }
  },
  select: {
    component: function(s) {
      const { items: e, defaultValue: t, ...n } = s;
      return /* @__PURE__ */ w("select", { ...n }, e.map((i) => /* @__PURE__ */ w("option", { key: i.value, value: i.value }, i.text)));
    },
    props(s) {
      return En(this, s);
    }
  },
  picker: {
    component: fi,
    props: ir
  },
  datePicker: {
    component: Xc,
    props(s) {
      return En(this, s, { className: "flex-auto", icon: "calendar" }, "defaultValue");
    }
  },
  timePicker: {
    component: Gc,
    props(s) {
      return En(this, s, { className: "flex-auto", icon: "time" }, "defaultValue");
    }
  },
  priPicker: {
    component: pi,
    props: ir
  },
  severityPicker: {
    component: uh,
    props: ir
  }
}, $p = {
  name: "form",
  plugins: [Sh],
  data() {
    return { formData: {} };
  },
  colTypes: {
    control: {
      custom(s) {
        let { control: e } = s.col.setting;
        if (typeof e == "function" && (e = e.call(this, s)), !e)
          return;
        typeof e == "string" && (e = { type: e });
        const { controlMap: t } = this.options, { type: n } = e, i = { name: `${s.col.name}[${s.row.id}]` };
        let o;
        if ([Cp[n], t == null ? void 0 : t[n], e].forEach((r) => {
          if (r) {
            const { props: a } = r;
            a && u.extend(i, typeof a == "function" ? a.call(this, s) : a), o = r.component || o;
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
    setFormData(s, e) {
      var n;
      const { formData: t } = this.data;
      t[s] = e, (n = this.options.onFormChange) == null || n.call(this, s, e, t);
    },
    getFormData(s) {
      return s ? this.data.formData[s] : this.data.formData;
    }
  }
}, Gp = rt($p);
u(() => {
  u(".disabled, [disabled]").on("click", (s) => {
    s.preventDefault(), s.stopImmediatePropagation();
  });
});
function Wh(s) {
  s = s || location.search, s[0] === "?" && (s = s.substring(1));
  try {
    const e = new URLSearchParams(s), t = {};
    for (const n of e.keys())
      t[n] = e.getAll(n).join(",");
    return t;
  } catch {
    return {};
  }
}
function kp(s) {
  if (!s)
    return { url: s };
  const { config: e } = window;
  if (/^https?:\/\//.test(s)) {
    const l = window.location.origin;
    if (!s.includes(l))
      return { external: !0, url: s };
    s = s.substring((l + e.webRoot).length);
  }
  const t = s.split("#"), n = t[0].split("?"), i = n[1], o = i ? Wh(i) : {};
  let r = n[0];
  const a = {
    url: s,
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
    const h = r.lastIndexOf(".");
    h >= 0 ? (a.viewType = r.substring(h + 1), r = r.substring(0, h)) : a.viewType = e.defaultView;
    const d = r.split(e.requestFix);
    if (a.moduleName = d[0] || "index", a.methodName = d[1] || "index", d.length > 2)
      for (let c = 2; c < d.length; c++)
        a.vars.push(["", d[c]]), o["$" + (c - 1)] = d[c];
  }
  return a;
}
function Vh(s, e, t, n, i, o) {
  if (typeof s == "object")
    return Vh(s.moduleName, s.methodName, s.vars, s.viewType, s.hash, s.params);
  const r = window.config;
  if (n || (n = r.defaultView), t) {
    typeof t == "string" && (t = t.split("&"));
    for (let h = 0; h < t.length; h++) {
      const d = t[h];
      if (typeof d == "string") {
        const c = d.split("=");
        t[h] = [c.shift(), c.join("=")];
      }
    }
  }
  const a = [], l = r.requestType === "GET";
  if (l) {
    if (a.push(r.router, "?", r.moduleVar, "=", s, "&", r.methodVar, "=", e), t)
      for (let h = 0; h < t.length; h++)
        a.push("&", t[h][0], "=", t[h][1]);
    a.push("&", r.viewVar, "=", n);
  } else {
    if (r.requestType == "PATH_INFO" && a.push(r.webRoot, s, r.requestFix, e), r.requestType == "PATH_INFO2" && a.push(r.webRoot, "index.php/", s, r.requestFix, e), t)
      for (let h = 0; h < t.length; h++)
        a.push(r.requestFix + t[h][1]);
    a.push(".", n);
  }
  return o && Object.keys(o).forEach((h) => {
    const d = o[h];
    h[0] !== "$" && a.push(!l && !a.includes("?") ? "?" : "&", h, "=", d);
  }), typeof i == "string" && a.push(i.startsWith("#") ? "" : "#", i), a.join("");
}
const Tp = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  createLink: Vh,
  parseLink: kp,
  parseUrlParams: Wh
}, Symbol.toStringTag, { value: "Module" }));
function Sp(s) {
  const e = u(this), t = e.dataset();
  if (!(t.on || "click").split(" ").includes(s.type) || t.selector && !u(s.target).closest(t.selector).length)
    return;
  const n = (a) => a === "" ? !0 : a, i = (a) => {
    if (typeof a == "string")
      try {
        a = JSON.parse(a);
      } catch {
      }
    return a;
  };
  if (n(t.once)) {
    if (t.onceCalled)
      return;
    e.dataset("once-called", !0);
  }
  if (n(t.prevent) && s.preventDefault(), n(t.stop) && s.stopPropagation(), n(t.self) && s.currentTarget !== s.target)
    return;
  const o = [["$element", e], ["event", s], ["options", t]];
  if (t.if && !u.runJS(t.if, ...o))
    return;
  const r = t.call;
  if (r) {
    let a = window[r];
    const l = /^[$A-Z_][0-9A-Z_$.]*$/i.test(r);
    if (a || (a = u.runJS(r, ...o)), !l || !u.isFunction(a))
      return;
    const h = [], d = t.params;
    t.params = h, typeof d == "string" && d.length && (d[0] === "[" ? h.push(...i(d)) : h.push(...d.split(", ").map((c) => (c = c.trim(), c === "$element" ? e : c === "event" ? s : c === "options" ? t : c.startsWith("$element.") || c.startsWith("$event.") || c.startsWith("$options.") ? u.runJS(c, ...o) : i(c))))), a(...h);
  }
  t.do && u.runJS(t.do, ...o);
}
u(document).on("click.helpers.zt change.helpers.zt", "[data-on]", Sp);
window.$ && Object.assign(window.$, Tp);
export {
  u as $,
  La as ActionMenu,
  Oa as ActionMenuNested,
  Bl as AjaxForm,
  Ya as Avatar,
  fl as BatchForm,
  Xa as BtnGroup,
  pl as Burn,
  Ja as ColorPicker,
  ot as Component,
  V as ComponentFromReact,
  Pr as ContextMenu,
  Cn as CustomContent,
  ar as CustomRender,
  Hl as DTable,
  Rl as Dashboard,
  nl as DatePicker,
  oe as Dropdown,
  gl as Dropmenu,
  yl as ECharts,
  ia as EventBus,
  bl as GlobalSearch,
  Zr as HElement,
  Ds as HtmlContent,
  Z as Icon,
  ja as Menu,
  hi as Messager,
  Bt as Modal,
  Vt as ModalBase,
  ui as ModalTrigger,
  il as Nav,
  ol as Pager,
  rl as Pick,
  al as Picker,
  Nt as Popover,
  xr as PopoverPanel,
  Cr as Popovers,
  wl as PriPicker,
  Va as ProgressCircle,
  F as ReactComponent,
  Fp as SearchBox,
  Nl as SearchForm,
  vl as SeverityPicker,
  An as TIME_DAY,
  Dr as Tabs,
  el as TimePicker,
  hl as Toolbar,
  Ot as Tooltip,
  dl as Tree,
  kr as Upload,
  ul as UploadImgs,
  Ml as Zinbar,
  Wu as addDate,
  Ip as ajax,
  ca as ajaxSubmit,
  Dp as bus,
  u as cash,
  M as classes,
  Sn as componentsMap,
  Od as convertBytes,
  Lu as cookie,
  Bd as create,
  Q as createDate,
  Zd as createPortal,
  G as createRef,
  Ld as deepGet,
  Ad as deepGetPath,
  Ep as defineFn,
  ti as delay,
  Mp as dom,
  Gp as form,
  Vs as formatBytes,
  mt as formatDate,
  zp as formatDateSpan,
  U as formatString,
  cc as getClassList,
  ei as getComponent,
  w as h,
  Pp as hh,
  Gd as htm,
  nt as i18n,
  js as isSameDay,
  qc as isSameMonth,
  Lp as isSameWeek,
  br as isSameYear,
  Op as isToday,
  Hp as isTomorrow,
  et as isValidElement,
  jp as isYesterday,
  Ua as nativeEvents,
  Rn as render,
  $c as renderCustomContent,
  Yd as renderCustomResult,
  pe as store,
  hc as storeData,
  dc as takeData,
  qp as zentao,
  xp as zentaoPlugin
};
//# sourceMappingURL=zui.zentao.js.map
