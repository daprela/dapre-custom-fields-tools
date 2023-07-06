/* eslint-disable react/jsx-filename-extension */
import React from 'react';

const { __ } = wp.i18n;

const PostsMetaHeaders = ({ className }) => (
  <div className={className}>
    <div>
      Post ID
    </div>
    <div>
      Field Name
    </div>
    <div>
      {__('Actions', 'dapre-cft')}
    </div>
    <div>
      {__('Value to Add', 'dapre-cft')}
    </div>
    <div>
      {__('Current Value', 'dapre-cft')}
    </div>
    <div>
      {__('Current Value Options', 'dapre-cft')}
    </div>
    <div>
      {__('Previous Value', 'dapre-cft')}
    </div>
  </div>
);

export default PostsMetaHeaders;
