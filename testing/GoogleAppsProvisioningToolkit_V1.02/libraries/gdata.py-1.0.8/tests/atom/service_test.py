#!/usr/bin/python
#
# Copyright (C) 2006 Google Inc.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

__author__ = 'jscudder@google.com (Jeff Scudder)'

import unittest
import atom.service

class AtomServiceUnitTest(unittest.TestCase):
  
  def setUp(self):
    pass

  def tearDown(self):
    pass

  def testBuildUriWithNoParams(self):
    x = atom.service.BuildUri('/base/feeds/snippets')
    self.assert_(x == '/base/feeds/snippets')

  def testBuildUriWithParams(self):
    # Add parameters to a URI
    x = atom.service.BuildUri('/base/feeds/snippets', url_params={'foo': 'bar', 
                                                     'bq': 'digital camera'})
    self.assert_(x == '/base/feeds/snippets?foo=bar&bq=digital+camera')
    self.assert_(x.startswith('/base/feeds/snippets'))
    self.assert_(x.count('?') == 1)
    self.assert_(x.count('&') == 1)
    self.assert_(x.index('?') < x.index('&'))
    self.assert_(x.index('bq=digital+camera') != -1)

    # Add parameters to a URI that already has parameters
    x = atom.service.BuildUri('/base/feeds/snippets?bq=digital+camera', 
                             url_params={'foo': 'bar', 'max-results': '250'})
    self.assert_(x.startswith('/base/feeds/snippets?bq=digital+camera'))
    self.assert_(x.count('?') == 1)
    self.assert_(x.count('&') == 2)
    self.assert_(x.index('?') < x.index('&'))
    self.assert_(x.index('max-results=250') != -1)
    self.assert_(x.index('foo=bar') != -1)


  def testBuildUriWithoutParameterEscaping(self):
    x = atom.service.BuildUri('/base/feeds/snippets', 
            url_params={'foo': ' bar', 'bq': 'digital camera'}, 
            escape_params=False)
    self.assert_(x.index('foo= bar') != -1)
    self.assert_(x.index('bq=digital camera') != -1)

  def testParseHttpUrl(self):
    as = atom.service.AtomService('code.google.com')
    self.assertEquals(as.server, 'code.google.com')
    (host, port, ssl, path) =  as._ProcessUrl(
        'http://www.google.com/service/subservice?name=value')

    self.assertEquals(ssl, False)
    self.assertEquals(host, 'www.google.com')
    self.assertEquals(port, 80)
    self.assertEquals(path, 'http://www.google.com:80/service/subservice?name=value')

  def testParseHttpUrlWithPort(self):
    as = atom.service.AtomService('code.google.com')
    self.assertEquals(as.server, 'code.google.com')
    (host, port, ssl, path) =  as._ProcessUrl(
        'http://www.google.com:12/service/subservice?name=value&newname=newvalue')

    self.assertEquals(ssl, False)
    self.assertEquals(host, 'www.google.com')
    self.assertEquals(port, 12)
    #self.assertEquals(path, '/service/subservice?name=value&newname=newvalue')
    self.assertEquals(path, 'http://www.google.com:12/service/subservice?name=value&newname=newvalue')

  def testParseHttpsUrl(self):
    as = atom.service.AtomService('code.google.com')
    self.assertEquals(as.server, 'code.google.com')
    (host, port, ssl, path) =  as._ProcessUrl(
        'https://www.google.com/service/subservice?name=value&newname=newvalue')

    self.assertEquals(ssl, True)
    self.assertEquals(host, 'www.google.com')
    self.assertEquals(port, 443)
    self.assertEquals(path, 'https://www.google.com:443/service/subservice?name=value&newname=newvalue')

  def testParseHttpsUrlWithPort(self):
    as = atom.service.AtomService('code.google.com')
    self.assertEquals(as.server, 'code.google.com')
    (host, port, ssl, path) =  as._ProcessUrl(
        'https://www.google.com:13981/service/subservice?name=value&newname=newvalue')

    self.assertEquals(ssl, True)
    self.assertEquals(host, 'www.google.com')
    self.assertEquals(port, 13981)
    self.assertEquals(path, 'https://www.google.com:13981/service/subservice?name=value&newname=newvalue')

  def testParseUrlWithFullProxyURL(self):
    as = atom.service.AtomService('code.google.com')
    as.proxy_url = 'https://proxy.example.com:8080'
    self.assert_(as.server == 'code.google.com')
    self.assert_(as.proxy_url == 'https://proxy.example.com:8080')
    (host, port, ssl, path) =  as._ProcessUrl(
        'http://www.google.com:13981/service/subservice?name=value&newname=newvalue')
    self.assertEquals(ssl, True)
    self.assertEquals(host, 'proxy.example.com')
    self.assertEquals(port, 8080)
    self.assertEquals(path, 'http://www.google.com:13981/service/subservice?name=value&newname=newvalue')

  def testParseUrlWithHostOnlyProxyURL(self):
    as = atom.service.AtomService('code.google.com')
    as.proxy_url = 'http://proxy.example.com'
    self.assert_(as.server == 'code.google.com')
    self.assert_(as.proxy_url == 'http://proxy.example.com:80')
    (host, port, ssl, path) =  as._ProcessUrl(
        'https://www.google.com/service/subservice')
    self.assertEquals(ssl, False)
    self.assertEquals(host, 'proxy.example.com')
    self.assertEquals(port, 80)
    self.assertEquals(path, 'https://www.google.com:443/service/subservice')

  def testSetProxyUrlToIllegalValues(self):
    as = atom.service.AtomService('code.google.com')
    try:
      as.proxy_url = 'proxy.example.com'
      self.fail('A URL with no protocol should trigger InvalidProxyUrl')
    except atom.service.InvalidProxyUrl:
      pass
    try:
      as.proxy_url = 'http://:80'
      self.fail('A URL with no server should trigger InvalidProxyUrl')
    except atom.service.InvalidProxyUrl:
      pass
    try:
      as.proxy_url = 'http://'
      self.fail('A URL with only the protocol should trigger InvalidProxyUrl')
    except atom.service.InvalidProxyUrl:
      pass
   
  def testSetBasicAuth(self):
    client = atom.service.AtomService()
    client.UseBasicAuth('foo', 'bar')
    self.assertEquals(client.additional_headers['Authorization'], 
        'Basic Zm9vOmJhcg==')
    client.UseBasicAuth('','')
    self.assertEquals(client.additional_headers['Authorization'],
        'Basic Og==')


if __name__ == '__main__':
  unittest.main()
