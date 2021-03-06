#!/usr/bin/python
#
# Copyright (C) 2007 Google Inc.
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

"""SpreadsheetsService extends the GDataService to streamline Google
Spreadsheets operations.

  GBaseService: Provides methods to query feeds and manipulate items. Extends
                GDataService.

  DictionaryToParamList: Function which converts a dictionary into a list of
                         URL arguments (represented as strings). This is a
                         utility function used in CRUD operations.
"""

__author__ = 'api.laurabeth@gmail.com (Laura Beth Lincoln)'

import urllib
try:
  from xml.etree import cElementTree as ElementTree
except ImportError:
  try:
    import cElementTree as ElementTree
  except ImportError:
    from elementtree import ElementTree
import gdata
import atom.service
import gdata.service
import gdata.spreadsheet
import atom


class Error(Exception):
  pass


class RequestError(Error):
  pass


class SpreadsheetsService(gdata.service.GDataService):
  """Client for the Google Spreadsheets service."""

  def __init__(self, email=None, password=None, source=None,
               server='spreadsheets.google.com',
               additional_headers=None):
    gdata.service.GDataService.__init__(self, email=email, password=password,
                                        service='wise', source=source,
                                        server=server,
                                        additional_headers=additional_headers)
                                        
  def GetSpreadsheetsFeed(self, key=None, query=None, visibility='private', 
      projection='full'):
    """Gets a spreadsheets feed or a specific entry if a key is defined
    Args:
      key: string (optional) The spreadsheet key defined in /ccc?key=
      query: DocumentQuery (optional) Query parameters
      
    Returns:
      If there is no key, then a SpreadsheetsSpreadsheetsFeed.
      If there is a key, then a SpreadsheetsSpreadsheet.
    """
    
    uri = ('http://%s/feeds/spreadsheets/%s/%s' 
           % (self.server, visibility, projection))
    
    if key is not None:
      uri = '%s/%s' % (uri, key)
      
    if query != None:
      query.feed = uri
      uri = query.ToUri()

    if key:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsSpreadsheetFromString)
    else:
      return self.Get(uri,
          converter=gdata.spreadsheet.SpreadsheetsSpreadsheetsFeedFromString)
  
  def GetWorksheetsFeed(self, key, wksht_id=None, query=None, 
      visibility='private', projection='full'):
    """Gets a worksheets feed or a specific entry if a wksht is defined
    Args:
      key: string The spreadsheet key defined in /ccc?key=
      wksht_id: string (optional) The id for a specific worksheet entry
      query: DocumentQuery (optional) Query parameters
      
    Returns:
      If there is no wksht_id, then a SpreadsheetsWorksheetsFeed.
      If there is a wksht_id, then a SpreadsheetsWorksheet.
    """
    
    uri = ('http://%s/feeds/worksheets/%s/%s/%s' 
           % (self.server, key, visibility, projection))
    
    if wksht_id != None:
      uri = '%s/%s' % (uri, wksht_id)
      
    if query != None:
      query.feed = uri
      uri = query.ToUri()

    if wksht_id:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsWorksheetFromString)
    else:
      return self.Get(uri,
          converter=gdata.spreadsheet.SpreadsheetsWorksheetsFeedFromString)
  
  def GetCellsFeed(self, key, wksht_id='default', cell=None, query=None, 
      visibility='private', projection='full'):
    """Gets a cells feed or a specific entry if a cell is defined
    Args:
      key: string The spreadsheet key defined in /ccc?key=
      wksht_id: string The id for a specific worksheet entry
      cell: string (optional) The R1C1 address of the cell
      query: DocumentQuery (optional) Query parameters
      
    Returns:
      If there is no cell, then a SpreadsheetsCellsFeed.
      If there is a cell, then a SpreadsheetsCell.
    """
    
    uri = ('http://%s/feeds/cells/%s/%s/%s/%s' 
           % (self.server, key, wksht_id, visibility, projection))
    
    if cell != None:
      uri = '%s/%s' % (uri, cell)
      
    if query != None:
      query.feed = uri
      uri = query.ToUri()

    if cell:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsCellFromString)
    else:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsCellsFeedFromString)
  
  def GetListFeed(self, key, wksht_id='default', row_id=None, query=None,
      visibility='private', projection='full'):
    """Gets a list feed or a specific entry if a row_id is defined
    Args:
      key: string The spreadsheet key defined in /ccc?key=
      wksht_id: string The id for a specific worksheet entry
      row_id: string (optional) The row_id of a row in the list
      query: DocumentQuery (optional) Query parameters
      
    Returns:
      If there is no row_id, then a SpreadsheetsListFeed.
      If there is a row_id, then a SpreadsheetsList.
    """
    
    uri = ('http://%s/feeds/list/%s/%s/%s/%s' 
           % (self.server, key, wksht_id, visibility, projection))

    if row_id is not None:
      uri = '%s/%s' % (uri, row_id)
      
    if query is not None:
      query.feed = uri
      uri = query.ToUri()

    if row_id:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsListFromString)
    else:
      return self.Get(uri, 
          converter=gdata.spreadsheet.SpreadsheetsListFeedFromString)
    
  def UpdateCell(self, row, col, inputValue, key, wksht_id='default'):
    """Updates an existing cell.
    
    Args:
      uri: string The uri of the cells feed containing the cell
      row: int The row the cell to be editted is in
      col: int The column the cell to be editted is in
      inputValue: string the new value of the cell
      
    Returns:
      The updated cell entry
    """
    # make the new cell
    new_cell = gdata.spreadsheet.Cell(row=row, col=col, inputValue=inputValue)
    # get the edit uri and PUT
    cell = 'R%sC%s' % (row, col)
    entry = self.GetCellsFeed(key, wksht_id, cell)
    for a_link in entry.link:
      if a_link.rel == 'edit':
        entry.cell = new_cell
        return self.Put(entry, a_link.href, 
            converter=gdata.spreadsheet.SpreadsheetsCellFromString)
    
  def InsertRow(self, row_data, key, wksht_id='default'):
    """Inserts a new row with the provided data
    
    Args:
      uri: string The post uri of the list feed
      row_data: dict A dictionary of column header to row data
    
    Returns:
      The inserted row
    """
    new_entry = gdata.spreadsheet.SpreadsheetsList()
    for k, v in row_data.iteritems():
      new_custom = gdata.spreadsheet.Custom()
      new_custom.column = k
      new_custom.text = v
      new_entry.custom[new_custom.column] = new_custom
    feed = self.GetListFeed(key, wksht_id)
    for a_link in feed.link:
      if a_link.rel == 'http://schemas.google.com/g/2005#post':
        return self.Post(new_entry, a_link.href, 
            converter=gdata.spreadsheet.SpreadsheetsListFromString)
    
  def UpdateRow(self, entry, new_row_data):
    """Updates a row with the provided data
    
    Args:
      entry: gdata.spreadsheet.SpreadsheetsList The entry to be updated
      new_row_data: dict A dictionary of column header to row data
      
    Returns:
      The updated row
    """
    entry.custom = {}
    for k, v in new_row_data.iteritems():
      new_custom = gdata.spreadsheet.Custom()
      new_custom.column = k
      new_custom.text = v
      entry.custom[k] = new_custom
    for a_link in entry.link:
      if a_link.rel == 'edit':
        return self.Put(entry, a_link.href, 
            converter=gdata.spreadsheet.SpreadsheetsListFromString)
        
  def DeleteRow(self, entry):
    """Deletes a row, the provided entry
    
    Args:
      entry: gdata.spreadsheet.SpreadsheetsList The row to be deleted
    
    Returns:
      The delete response
    """
    for a_link in entry.link:
      if a_link.rel == 'edit':
        return self.Delete(a_link.href)


class DocumentQuery(gdata.service.Query):

  def _GetTitleQuery(self):
    return self['title']

  def _SetTitleQuery(self, document_query):
    self['title'] = document_query
    
  title = property(_GetTitleQuery, _SetTitleQuery,
      doc="""The title query parameter""")

  def _GetTitleExactQuery(self):
    return self['title-exact']

  def _SetTitleExactQuery(self, document_query):
    self['title-exact'] = document_query
    
  title_exact = property(_GetTitleExactQuery, _SetTitleExactQuery,
      doc="""The title-exact query parameter""")
 
 
class CellQuery(gdata.service.Query):

  def _GetMinRowQuery(self):
    return self['min-row']

  def _SetMinRowQuery(self, cell_query):
    self['min-row'] = cell_query
    
  min_row = property(_GetMinRowQuery, _SetMinRowQuery,
      doc="""The min-row query parameter""")

  def _GetMaxRowQuery(self):
    return self['max-row']

  def _SetMaxRowQuery(self, cell_query):
    self['max-row'] = cell_query
    
  max_row = property(_GetMaxRowQuery, _SetMaxRowQuery,
      doc="""The max-row query parameter""")
      
  def _GetMinColQuery(self):
    return self['min-col']

  def _SetMinColQuery(self, cell_query):
    self['min-col'] = cell_query
    
  min_col = property(_GetMinColQuery, _SetMinColQuery,
      doc="""The min-col query parameter""")
      
  def _GetMaxColQuery(self):
    return self['max-col']

  def _SetMaxColQuery(self, cell_query):
    self['max-col'] = cell_query
    
  max_col = property(_GetMaxColQuery, _SetMaxColQuery,
      doc="""The max-col query parameter""")
      
  def _GetRangeQuery(self):
    return self['range']

  def _SetRangeQuery(self, cell_query):
    self['range'] = cell_query
    
  range = property(_GetRangeQuery, _SetRangeQuery,
      doc="""The range query parameter""")
      
  def _GetReturnEmptyQuery(self):
    return self['return-empty']

  def _SetReturnEmptyQuery(self, cell_query):
    self['return-empty'] = cell_query
    
  return_empty = property(_GetReturnEmptyQuery, _SetReturnEmptyQuery,
      doc="""The return-empty query parameter""")
 
 
class ListQuery(gdata.service.Query):

  def _GetSpreadsheetQuery(self):
    return self['sq']

  def _SetSpreadsheetQuery(self, list_query):
    self['sq'] = list_query
    
  sq = property(_GetSpreadsheetQuery, _SetSpreadsheetQuery,
      doc="""The sq query parameter""")
      
  def _GetOrderByQuery(self):
    return self['orderby']

  def _SetOrderByQuery(self, list_query):
    self['orderby'] = list_query
    
  orderby = property(_GetOrderByQuery, _SetOrderByQuery,
      doc="""The orderby query parameter""")
      
  def _GetReverseQuery(self):
    return self['reverse']

  def _SetReverseQuery(self, list_query):
    self['reverse'] = list_query
    
  reverse = property(_GetReverseQuery, _SetReverseQuery,
      doc="""The reverse query parameter""")
