/**
 * Adapted from https://github.com/vermiliondesign/gutenberg-post-selector
 */

const { Component, Fragment } = wp.element;
const { decodeEntities } = wp.htmlEntities;
const { UP, DOWN, ENTER } = wp.keycodes;
const { Spinner, Popover, IconButton } = wp.components;
const { withInstanceId } = wp.compose;
const { withSelect } = wp.data;
const { apiFetch } = wp;
const { addQueryArgs } = wp.url;

const stopEventPropagation = event => event.stopPropagation();

const panelImageStyle = {
	'width': '20%',
    'borderRadius': '100%',
    'marginRight': '5%'
}

function debounce(func, wait = 100) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      func.apply(this, args);
    }, wait);
  };
}

class PostSelector extends Component {
  /**
   * ===== Available Props =======
   *
   * posts <Array> of Post Objects, must include ID and title.
   * data <Array> array of post properties to return (top level only right now)
   * postType = <String> singular name of post type to restrict results to.
   * onPostSelect <Function> callback for when a new post is selected.
   * onChange <Function> callback for when posts are deleted or rearranged.
   * limit <Number> limit selection to posts to X number of posts.
   *
   */
  constructor() {
    super(...arguments);
    this.onChange = this.onChange.bind(this);
    this.onKeyDown = this.onKeyDown.bind(this);
    this.bindListNode = this.bindListNode.bind(this);
    this.updateSuggestions = debounce(this.updateSuggestions.bind(this), 200);
    this.limit = this.props.limit ? parseInt(this.props.limit) : false;

    this.suggestionNodes = [];

	this.postTypes = null;
	
	console.log('PostSelector constructor', this.props);

    this.state = {
	  posts: [],
      showSuggestions: false,
      selectedSuggestion: null,
      input: ''
	};
  }

  componentWillUnmount() {
    delete this.suggestionsRequest;
  }

  bindListNode(ref) {
    this.listNode = ref;
  }

  bindSuggestionNode(index) {
    return ref => {
      this.suggestionNodes[index] = ref;
    };
  }

  updateSuggestions(value) {
    // Show the suggestions after typing at least 2 characters
	// and also for URLs
    if (value.length < 2 || /^https?:/.test(value)) {
      this.setState({
        showSuggestions: false,
        selectedSuggestion: null,
        loading: false
      });

      return;
	}

    this.setState({
      showSuggestions: true,
      selectedSuggestion: null,
      loading: true
	});

	// get the base of the URL for the post API request
	const restBase = this.props.postType;
	
    const request = apiFetch({
      path: addQueryArgs(`/wp/v2/${restBase}`, {
        search: value,
        per_page: 20,
        type: 'post',
        'sub-type': this.props.postType ? this.props.postType : undefined
      })
    });

    request
      .then(posts => {
        // A fetch Promise doesn't have an abort option. It's mimicked by
        // comparing the request reference in on the instance, which is
        // reset or deleted on subsequent requests or unmounting.
        if (this.suggestionsRequest !== request) {
          return;
        }

        this.setState({
          posts,
		  loading: false,
		  selectedSuggestion: 0
        });
      })
      .catch(() => {
        if (this.suggestionsRequest === request) {
          this.setState({
            loading: false
          });
        }
      });

    this.suggestionsRequest = request;
  }

  onChange(event) {
	const inputValue = event.target.value;
    this.setState({ input: inputValue });
    this.updateSuggestions(inputValue);
  }

  onKeyDown(event) {
    const { showSuggestions, selectedSuggestion, posts, loading } = this.state;
    // If the suggestions are not shown or loading, we shouldn't handle the arrow keys
    // We shouldn't preventDefault to allow block arrow keys navigation
    if (!showSuggestions || !posts.length || loading) {
      return;
    }

    switch (event.keyCode) {
      case UP: {
        event.stopPropagation();
        event.preventDefault();
        const previousIndex = !selectedSuggestion ? posts.length - 1 : selectedSuggestion - 1;
        this.setState({
          selectedSuggestion: previousIndex
        });
        break;
      }
      case DOWN: {
        event.stopPropagation();
        event.preventDefault();
        const nextIndex = selectedSuggestion === null || selectedSuggestion === posts.length - 1 ? 0 : selectedSuggestion + 1;
        this.setState({
          selectedSuggestion: nextIndex
        });
        break;
      }
      case ENTER: {
        if (this.state.selectedSuggestion !== null) {
          event.stopPropagation();
		  const post = this.state.posts[this.state.selectedSuggestion];
		  console.log( "ENTER pushed", post, this.state.posts );
          this.selectLink(post);
        }
      }
    }
  }

  selectLink(post) {
	  console.log('selectLink');
    // get the "full" post data if a post was selected. this may be something to add as a prop in the future for custom use cases.
    if (this.props.data) {
      // if data already exists in the post object, there's no need to make an API call.
      let reachOutToApi = false;
      const returnData = {};
      for (const prop of this.props.data) {
        if (!post.hasOwnProperty(prop)) {
          reachOutToApi = true;
          return;
        }
        returnData[prop] = post[prop];
      }

      if (!reachOutToApi) {
        this.props.onPostSelect(returnData);
        this.setState({
          input: '',
          selectedSuggestion: null,
          showSuggestions: false
        });
        return;
      }
    }

    // get the base of the URL for the post API request
	// const restBase = this.getPostTypeData(post.subtype).restBase;
	const restBase = this.props.postType;

    apiFetch({
      path: `/wp/v2/${restBase}/${post.id}?_embed`
    }).then(response => {
      const fullpost = {
        title: decodeEntities(response.title.rendered),
        id: response.id,
        excerpt: decodeEntities(response.excerpt.rendered),
        url: response.link,
        date: response.date,
        type: response.type,
		status: response.status,
		media: response._embedded['wp:featuredmedia'][0]
      };
      // send data to the block;
	  this.props.onPostSelect(fullpost);
	});

	this.setState({
      input: '',
      selectedSuggestion: null,
      showSuggestions: false
    });
  }

  renderPostImage( post, size='thumbnail' ) {
	const imageObject = post.media;
	  if ( ! imageObject )
		  return '';
	return (
		<img
		src={imageObject.media_details.sizes[size].source_url}
		width={imageObject.media_details.sizes[size].width}
		height={imageObject.media_details.sizes[size].height}
		alt={imageObject.media_details.image_meta.caption ? imageObject.media_details.image_meta.caption : post.title}
		style={panelImageStyle}
		/>
	)
  }

  renderPostTitle( post ) {
	  return (
		  <h3
		  	style={{
				flexGrow: '1',
				alignSelf: 'center',
				margin: '0',
			}}
		  >
		  	{post.title}
		  </h3>
	  )
  }

  renderPostItem( post, i ) {
	  console.log( 'renderPostItem', post, i );

	  if ( ! post.id ) {
		
		return;
	  }

	  return(
		<li style={{ display: 'flex', justifyContent: 'flex-start', alignItems: 'center', flexWrap: 'nowrap' }} key={post.id}>

            {
			  this.renderPostImage( post )
			}
			
			{
			  this.renderPostTitle( post )
			}

            <span>
              {i !== 0 ? (
                <IconButton
                  style={{ display: 'inline-flex', padding: '8px 2px', textAlign: 'center' }}
                  icon="arrow-up-alt2"
                  onClick={() => {
                    this.props.posts.splice(i - 1, 0, this.props.posts.splice(i, 1)[0]);
                    this.props.onChange(this.props.posts);
                    this.setState({ state: this.state });
                  }}
                />
              ) : null}

              {i !== this.props.posts.length - 1 ? (
                <IconButton
                  style={{ display: 'inline-flex', padding: '8px 2px', textAlign: 'center' }}
                  icon="arrow-down-alt2"
                  onClick={() => {
                    this.props.posts.splice(i + 1, 0, this.props.posts.splice(i, 1)[0]);
                    this.props.onChange(this.props.posts);
                    this.setState({ state: this.state });
                  }}
                />
              ) : null}

              <IconButton
                style={{ display: 'inline-flex', textAlign: 'center' }}
                icon="no"
                onClick={() => {
				  console.log( 'delete click, before props.posts splice', this.props.posts );
				  this.props.posts.splice(i, 1);
				  console.log( 'delete click, after props.posts splice', this.props.posts );
                  this.props.onChange(this.props.posts);
                  // force a re-render.
                  this.setState({ state: this.state });
                }}
              />
            </span>
          </li>
	  );
  }

  renderSelectedPosts() {
	// console.log( 'renderSelectedPosts props', this.props.posts, this.state.posts );
	// show each post in the list.
    return (
      <ul>
        {this.props.posts.map((post, i) => {
			console.log( 'should be mapping over posts', i, post );
			return this.renderPostItem(post,i);
		})}
      </ul>
    );
  }

  renderInput() {
	const { autoFocus = true, instanceId, limit } = this.props;
	const inputDisabled = !!limit && this.props.posts && this.props.posts.length >= limit;
	const inputPlaceholder = this.props.inputPlaceholder ? this.props.inputPlaceholder : 'Type page or post name';
	const inputPlaceholderLimit = this.props.inputPlaceholderLimit ? this.props.inputPlaceholderLimit : `Limited to ${this.props.limit} items.`;
	const { showSuggestions, selectedSuggestion, input } = this.state;

	if ( inputDisabled && this.props.hideInputOnLimit )
		return '';

	return (
		<input
		autoFocus={autoFocus}
		type="text"
		aria-label={'URL'}
		required
		value={input}
		onChange={this.onChange}
		onInput={stopEventPropagation}
		placeholder={inputDisabled ? inputPlaceholderLimit : inputPlaceholder}
		onKeyDown={this.onKeyDown}
		role="combobox"
		aria-expanded={showSuggestions}
		aria-autocomplete="list"
		aria-owns={`block-editor-url-input-suggestions-${instanceId}`}
		aria-activedescendant={selectedSuggestion !== null ? `block-editor-url-input-suggestion-${instanceId}-${selectedSuggestion}` : undefined}
		style={{ width: '100%' }}
		disabled={inputDisabled}
		/>
	)
  }

  resolvePostTypes(sourcePostTypes) {
    // check if the post types have already been resolved
    if (this.postTypes !== null) {
      return;
    }

    // check if we have the source post types from the API
    if (sourcePostTypes == null) {
      return;
    }

    // transform the source post types from the API
    // into the data we need and put it in a map
    const arr = sourcePostTypes.map((p) => {
      return [p.slug, {
        slug: p.slug,
        displayName: p.labels.singular_name,
        restBase: p.rest_base
      }]
    })

    this.postTypes = new Map(arr);
  }

  // get the post type data
  getPostTypeData(slug) {
    if (!this.hasPostTypeData()) { return {} }
    return this.postTypes.get(slug);
  }

  hasPostTypeData() {
    return this.postTypes !== null;
  }

  render() {
    this.resolvePostTypes(this.props.sourcePostTypes);
    const { autoFocus = true, instanceId, limit } = this.props;
	const { showSuggestions, posts, selectedSuggestion, loading, input } = this.state;

	console.log( 'PostSelector render method', this.props );
	
    /* eslint-disable jsx-a11y/no-autofocus */
    return (
      <Fragment>
        {this.renderSelectedPosts()}
        <div className="block-editor-url-input">
          {this.renderInput()}
          {loading && <Spinner />}
        </div>
        {showSuggestions &&
          !!posts.length && (
            <Popover position="bottom" noArrow focusOnMount={false}>
              <div className="block-editor-url-input__suggestions" id={`block-editor-url-input-suggestions-${instanceId}`} ref={this.bindListNode} role="listbox">
                {posts.map((post, index) => (
                  <button
                    key={post.id}
                    role="option"
                    tabIndex="-1"
                    id={`block-editor-url-input-suggestion-${instanceId}-${index}`}
                    ref={this.bindSuggestionNode(index)}
                    className={`block-editor-url-input__suggestion ${index === selectedSuggestion ? 'is-selected' : ''}`}
                    onClick={() => {
						console.log(post);
						this.selectLink(post)
					}}
                    aria-selected={index === selectedSuggestion}
                  >
                    <div style={{ display: 'flex', alignItems: 'center' }}>
                      <div>{decodeEntities(post.title.rendered) || '(no title)'}</div>
                    </div>

                  </button>
                ))}
              </div>
            </Popover>
          )}
      </Fragment>
    );
    /* eslint-enable jsx-a11y/no-autofocus */
  }
}

export default withSelect((select) => {
  const { getPostTypes } = select('core');
  return {
    sourcePostTypes: getPostTypes()
  }
})(withInstanceId(PostSelector));