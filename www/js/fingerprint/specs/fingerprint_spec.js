describe("Fingerprint", function(){
  beforeEach(function() {
    this.addMatchers({
      toBeInstanceOf : function(expected) {
        return this.actual instanceof expected && this.actual.length > 0;
      },
      toBeA: function(expected) {
        return typeof this.actual === expected;
      }
    });
  });
  describe("new Fingerprint", function(){
    it("Creates a new instance of Fingerprint", function(){
      expect(new Fingerprint()).not.toBeNull();
    });

    it("Accepts a custom hashing function as argument", function(){
      var hasher = function(){return 31;};
      expect(new Fingerprint(hasher)).not.toBeNull();
    });

    it("Accepts a custom hasing function as options argument", function(){
      var hasher = function(){return 31;};
      expect(new Fingerprint({hasher: hasher})).not.toBeNull();
    });
  });

  describe("#get", function(){
    it("Calculates fingerprint with built-in hashing if no custom hashing is given", function(){
      var fingerprint = new Fingerprint();
      spyOn(fingerprint, 'murmurhash3_32_gc');
      fingerprint.get();
      expect(fingerprint.murmurhash3_32_gc).toHaveBeenCalled();
    });

    it("Calculates fingerprint with custom hashing if it is given as an argument", function(){
      var hasher = function(){return 'abcdef';};
      var fingerprint = new Fingerprint(hasher);
      expect(fingerprint.get()).toEqual('abcdef');
    });

    it("Calculates fingerprint with custom hashing if it is given as an options argument", function(){
      var hasher = function(){return 'abcdef';};
      var fingerprint = new Fingerprint({hasher: hasher});
      expect(fingerprint.get()).toEqual('abcdef');
    });

    it('Calculates fingerprint with canvas fingerprinting if it is said to do so', function(){
      var fp = new Fingerprint({canvas: true});
      spyOn(fp, 'getCanvasFingerprint');
      fp.get();
      expect(fp.getCanvasFingerprint).toHaveBeenCalled();
    });


    it('Does not try to use canvas fingerprinting when not told to(version 1)', function(){
      var fp = new Fingerprint({canvas: false});
      spyOn(fp, 'getCanvasFingerprint');
      fp.get();
      expect(fp.getCanvasFingerprint).not.toHaveBeenCalled();
    });

    it('Does not try to use canvas fingerprinting when not told to(version 2)', function(){
      var fp = new Fingerprint();
      spyOn(fp, 'getCanvasFingerprint');
      fp.get();
      expect(fp.getCanvasFingerprint).not.toHaveBeenCalled();
    });

    it('Calculates fingerprint with ActiveX fingerprinting if it is said to do so', function(){
      var fp = new Fingerprint({ie_activex: true});
      spyOn(fp, 'isIE').andReturn(true);
      spyOn(fp, 'getIEPluginsString');
      fp.get();
      expect(fp.getIEPluginsString).toHaveBeenCalled();
    });


    it('Does not try to use ActiveX fingerprinting when not told to(version 1)', function(){
      var fp = new Fingerprint({ie_activex: false});
      spyOn(fp, 'getIEPluginsString');
      fp.get();
      expect(fp.getIEPluginsString).not.toHaveBeenCalled();
    });

    it('Does not try to use ActiveX fingerprinting when not told to(version 2)', function(){
      var fp = new Fingerprint();
      spyOn(fp, 'getIEPluginsString');
      fp.get();
      expect(fp.getIEPluginsString).not.toHaveBeenCalled();
    });

    it('Calculates fingerprint accessing screen resolution if it is said to do so', function(){
      var fp = new Fingerprint({screen_resolution: true});
      spyOn(fp, 'getScreenResolution');
      fp.get();
      expect(fp.getScreenResolution).toHaveBeenCalled();
    });

    it('Does not try to use screen resolution when not told to', function(){
      var fp = new Fingerprint();
      spyOn(fp, 'getScreenResolution');
      fp.get();
      expect(fp.getScreenResolution).not.toHaveBeenCalled();
    });

    it("Returns a number as a fingerprint value when used with a built-in hashing function", function(){
      var fingerprint = new Fingerprint();
      expect(fingerprint.get()).toBeA('number');
    });

    it('Returns a string from getCanvasFingerprint function', function(){
      var fp = new Fingerprint({canvas: true});
      expect(fp.getCanvasFingerprint()).toBeA('string');
    });

    it('Does not fail when document.body is null', function(){
      var body = document.body.parentNode.removeChild(document.body);
      var fingerprint = new Fingerprint();
      expect(fingerprint.get()).toBeA('number');
      document.body = body;
    });
  });
});